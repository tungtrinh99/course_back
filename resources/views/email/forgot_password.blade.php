@component('mail::message')
    # Xin chào, {{ $user->name }}!

    Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình.

    @component('mail::button', ['url' => $resetUrl])
        Đặt lại mật khẩu
    @endcomponent

    Liên kết này sẽ hết hạn sau {{ config('auth.passwords.users.expire') }} phút.

    Nếu bạn không yêu cầu đặt lại mật khẩu, bạn có thể bỏ qua email này.

    Trân trọng,<br>
    Đội ngũ hỗ trợ
@endcomponent
