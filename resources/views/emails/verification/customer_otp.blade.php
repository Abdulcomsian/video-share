<!DOCTYPE html>
<html>
<head>
    <title>OTP Verifcation Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F9F9F9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgb(34, 34, 34);
            overflow: hidden;
        }
        .header {
            background-color: #A694DD;
            color: #FFFFFF;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
        }
        .button {
            display: inline-block;
            background-color: #A694DD;
            color: #FFFFFF;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777;
            background-color: #F1F1F1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Otp Verification</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user['first_name'].' '.$user['last_name'] }}!</p>
            <p>Thank you for registering. Please use the OTP below to verify your email address:</p>
            <p style="font-size: 24px; font-weight: bold; color: #A694DD;">{{ $otp }}</p>
        </div>
        <div class="footer">
            <p>If you have any questions, contact us at {{ config('variables.supportEmail') }}.</p>
        </div>
    </div>
</body>
</html>
