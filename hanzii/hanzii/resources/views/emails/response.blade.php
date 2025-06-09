<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản hồi từ Trung tâm Tiếng Trung Hanzii</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        padding: 20px;
        max-width: 600px;
        margin: 0 auto;
    }

    .header {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
        border-left: 5px solid #4b6cb7;
    }

    .logo {
        text-align: center;
        margin-bottom: 15px;
    }

    .logo img {
        max-width: 150px;
    }

    h1 {
        color: #4b6cb7;
        font-size: 24px;
        margin-top: 0;
    }

    .content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        border: 1px solid #e9ecef;
    }

    .message {
        white-space: pre-line;
        margin-bottom: 20px;
    }

    .footer {
        text-align: center;
        margin-top: 30px;
        font-size: 14px;
        color: #6c757d;
        border-top: 1px solid #e9ecef;
        padding-top: 20px;
    }

    .contact-info {
        margin-top: 15px;
    }

    .contact-item {
        margin-bottom: 5px;
    }

    .social {
        margin-top: 15px;
    }

    .social a {
        margin: 0 10px;
        text-decoration: none;
    }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('assets/img/avt.jpg') }}" alt="Hanzii Logo">
        </div>
        <h1>Phản hồi liên hệ</h1>
    </div>

    <div class="content">
        <div class="message">
            {!! $noiDungPhanHoi !!}
        </div>

        <p>Nếu bạn có thêm câu hỏi hoặc cần hỗ trợ, vui lòng liên hệ với chúng tôi qua email hoặc hotline bên dưới.</p>

        <div class="contact-info">
            <div class="contact-item">📞 Hotline: (024) 7106 6866</div>
            <div class="contact-item">✉️ Email: info@hanzii.edu.vn</div>
            <div class="contact-item">🏠 Địa chỉ: Hai Bà Trưng, Hà Nội</div>
        </div>
    </div>

    <div class="footer">
        <p>© Trung tâm Tiếng Trung Hanzii. Tất cả các quyền được bảo lưu.</p>
    </div>
</body>

</html>