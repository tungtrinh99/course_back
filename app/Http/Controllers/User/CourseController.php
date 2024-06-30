<?php

namespace App\Http\Controllers\User;

use App\Helpers\BaseResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\GetCoursesRequest;
use App\Http\Resources\User\CourseResource;
use Illuminate\Support\Facades\Log;
use App\Services\CourseService;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Get all courses.
     *
     * @param GetCoursesRequest $request
     * @return \Illuminate\Http\Response
     */

    public function index(GetCoursesRequest $request)
    {
        try {
            $data = $this->courseService->getAllCourses($request->all());

            return BaseResponse::success(CourseResource::collection($data));
        } catch (\Exception $e) {
            Log::error($e);

            return BaseResponse::error($e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @param Request $request
     * @param number $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = $this->courseService->findOneCourse($id);

            return BaseResponse::success(new CourseResource($data));
        } catch (\Exception $e) {
            Log::error($e);

            return BaseResponse::error($e->getMessage(), null, Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Get trending courses.
     *
     * @param GetCoursesRequest $request
     * @return \Illuminate\Http\Response
     */

    public function getTrendingCourses(GetCoursesRequest $request)
    {
        try {
            $data = $this->courseService->getAllCourses($request->all());

            return BaseResponse::success(CourseResource::collection($data));
        } catch (\Exception $e) {
            Log::error($e);

            return BaseResponse::error();
        }
    }
}
