<?php

namespace App\Http\Controllers;

use App\Helpers\BaseResponse;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Http\Resources\User\UserResource;
use App\Mail\ForgotPassword;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;
use App\Services\AuthService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use App\Http\Requests\User\ChangePasswordRequest;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(
        AuthService $authService,
    )
    {
        $this->authService = $authService;
    }

    /**
     * Send verify email.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendVerificationEmail(User $user)
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', now()->addMinutes(60), ['id' => $user->id, 'hash' => Hash::make($user->email)]
        );

        Mail::to($user->email)->send(new VerifyEmail($user, $verificationUrl));
    }

    /**
     * Register user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $hashPassword = Hash::make($request->password);
            $user = $this->authService->createUser([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $hashPassword,
            ]);

            $this->sendVerificationEmail($user);
            DB::commit();

            return BaseResponse::success($user, 'Registration Successful', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return BaseResponse::error($e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = auth()->attempt($credentials);

        if (!$token) {
            return BaseResponse::error('Invalid credentials', null, Response::HTTP_UNAUTHORIZED);
        }

        return BaseResponse::success(
            $this->respondWithToken($token)->getData(),
            'Authentication successful',
            Response::HTTP_OK
        );
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return BaseResponse::success(
            new UserResource(auth()->user())
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return BaseResponse::success('Successfully logged out');
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return BaseResponse::success(
            $this->respondWithToken(auth()->refresh())->getData(),
            'Token refreshed',
            Response::HTTP_OK
        );
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'user' => new UserResource(auth()->user()),
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL') * 60
        ]);
    }

    /**
     * Change password.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function changePassword(ChangePasswordRequest $request)
    {
        try {
            $user = auth()->user();

            if (!Hash::check($request->current_password, $user->password)) {
                return BaseResponse::error('Current password is incorrect', null, Response::HTTP_UNAUTHORIZED);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return BaseResponse::success($user, 'Password changed successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return BaseResponse::error($e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return response()->json([
            'access_token' => $token,
            'user' => new UserResource(auth()->user()),
            'token_type' => 'bearer',
            'expires_in' => env('JWT_TTL') * 60
        ]);
    }

    /**
     * Verify email.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function verifyEmail(Request $request, $id, $hash)
    {
        try {
            if (!URL::hasValidSignature($request)) {
                abort(Response::HTTP_FORBIDDEN, 'Invalid or expired verification link.');
            }

            $user = $this->authService->findUserById($id);

            if (!Hash::check($user->email, $hash)) {
                abort(Response::HTTP_FORBIDDEN, 'Invalid or expired verification link.');
            }

            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            return BaseResponse::success(
                new UserResource($user),
                'Email verified successfully',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return BaseResponse::error($e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Send verify email.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendMailForgotPassword(User $user)
    {
        $frontendResetUrl = config('app.client_url')."/reset-password?user_id={$user->id}&hash=" . urlencode(sha1($user->email)) . "&expires=" . now()->addMinutes(60)->timestamp;

        Mail::to($user->email)->send(new ForgotPassword($user, $frontendResetUrl));
    }

    /**
     * Forgot password.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $user = $this->authService->findUserByEmail($request->email);

            if (is_null($user)) {
                return BaseResponse::error('User not found', null, Response::HTTP_NOT_FOUND);
            }

            $this->sendMailForgotPassword($user);

            return BaseResponse::error('Password reset email sent', null, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return BaseResponse::error($e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Reset password.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function resetPassword(ResetPasswordRequest $request, $id, $hash)
    {
        try {
            $user = $this->authService->findUserById($id);

            if (!Hash::check($user->email, $hash)) {
                return BaseResponse::error('Operation failed', null, Response::HTTP_NOT_FOUND);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return BaseResponse::success($user, 'Updated password successfully');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return BaseResponse::error($e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
