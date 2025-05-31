<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto;">
        <h2>Thông Tin Tài Khoản Hệ Thống CUSC</h2>
        <p>Xin chào {{ $full_name }},</p>
        <p>Tài khoản của bạn đã được tạo thành công:</p>
        <ul>
            <li>Tên đăng nhập: {{ $username }}</li>
            <li>Mật khẩu: {{ $password }}</li>
        </ul>
        <p><strong>Lưu ý:</strong> Vui lòng đổi mật khẩu sau khi đăng nhập lần đầu.</p>
        <p>Trân trọng,<br>Phòng Quản Lý Đào Tạo CUSC</p>
    </div>
</body>

</html>
