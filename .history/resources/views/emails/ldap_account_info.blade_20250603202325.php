<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Thông Tin Tài Khoản Hệ Thống CUSC</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f7f7f7; margin: 0; padding: 20px;">
    <div style="max-width: 600px; background-color: #fff; margin: auto; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #2c3e50;">Thông Tin Tài Khoản Hệ Thống CUSC</h2>

        <p>Xin chào <strong>{{ $full_name }}</strong>,</p>

        <p>Dưới đây là thông tin đăng nhập:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><strong>Tên đăng nhập:</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $username }}</td>
            </tr>
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd;"><strong>Mật khẩu:</strong></td>
                <td style="padding: 10px; border: 1px solid #ddd;">{{ $password }}</td>
            </tr>
        </table>

        <p style="color: #e74c3c; font-weight: bold;">
            Lưu ý: Vui lòng đổi mật khẩu ngay sau lần đăng nhập đầu tiên để bảo mật tài khoản.
        </p>

        <p>Nếu bạn gặp bất kỳ vấn đề nào hoặc cần hỗ trợ, vui lòng liên hệ:</p>
        <ul>
            <li>Email: support@cusc.ctu.vn</li>
            <li>Điện thoại: 0123 456 789</li>
        </ul>

        <p>Trân trọng,<br /><strong>Phòng Quản Lý Đào Tạo CUSC</strong></p>
    </div>
</body>

</html>
