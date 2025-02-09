<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Registration Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007BFF; /* Màu xanh dương */
            text-align: center;
        }
        p {
            font-size: 16px;
            color: #333;
        }
        .info {
            background-color: #e0f7fa; /* Màu nền xanh dương nhẹ */
            padding: 10px;
            border-left: 5px solid #007BFF; /* Màu xanh dương đậm */
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #333; /* Màu chữ ở footer */
            margin-top: 30px;
        }
        .footer a {
            color: #007BFF; /* Màu xanh dương */
            text-decoration: none;
        }
        .button {
            display: inline-block;
            background-color: #ffffff;
            color: white;
            padding: 10px 20px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            border: 2px solid #007BFF;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Welcome to Our Platform, {{ $tenantName }}!</h1>
        
        <p>Dear {{ $tenantName }},</p>
        
        <p>We are pleased to inform you that your tenant registration has been completed successfully. You now have access to your account and can manage your tenant details through our platform.</p>

        <div class="info">
            <h3>Your Account Information:</h3>
            <p><strong>Login URL:</strong> <a href="{{ config('app.url') }}/dashboard">{{ config('app.url') }}/dashboard</a></p>
            <p><strong>Email:</strong> {{ $tenantEmail }}</p>
            <p><strong>Password:</strong> {{ $tenantPassword }}</p>
        </div>
        
        <p>To get started, simply log in to your account using the credentials above.</p>

        <p>If you have any questions or require further assistance, please do not hesitate to reach out to us at <a href="mailto:pmo.assistant.2025@gmail.com">pmo.assistant.2025@gmail.com</a>.</p>

        <p>Thank you for choosing us!</p>

        <p class="footer">
            <a href="#" class="button">Visit Our Website</a>
        </p>
        
        <p class="footer">Best Regards, <br>Your Company Name</p>
    </div>

</body>
</html>
