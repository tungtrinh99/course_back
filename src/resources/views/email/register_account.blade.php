<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản thành công</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            margin: 0 auto;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #4CAF50;
            padding: 10px;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }
        .content {
            margin-top: 20px;
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Chào mừng bạn!</h1>
    </div>
    <div class="content">
        <p>Xin chào {{ $user->name }},</p>
        <p>Chúng tôi rất vui khi thông báo rằng bạn đã đăng ký tài khoản thành công tại hệ thống {{ config('app.name') }}của chúng tôi.</p>
        <p>Dưới đây là thông tin tài khoản của bạn:</p>
        <ul>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Ngày đăng ký:</strong> {{ $user->created_at->format('d/m/Y') }}</li>
        </ul>
        <p>Để bắt đầu sử dụng, vui lòng đăng nhập vào hệ thống bằng email và mật khẩu mà bạn đã đăng ký.</p>
        <p>Nếu bạn có bất kỳ câu hỏi nào, xin vui lòng liên hệ với chúng tôi qua <a href="mailto:support@example.com">support@example.com</a>.</p>
        <p>Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi!</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Hệ thống của chúng tôi. Mọi quyền được bảo lưu.</p>
    </div>
</div>
</body>
</html>
