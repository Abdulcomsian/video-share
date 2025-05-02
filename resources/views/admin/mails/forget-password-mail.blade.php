<!DOCTYPE html>
<html>

<head>
    <title>Forget Password Verifcation OTP</title>
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
            <h1>Forget Password Verification</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->first_name . ' ' . $user->last_name }}!</p>
            <p>You have requested to reset your password. Please click the link below to proceed with resetting your
                password:</p>
            <p>
                <a href="{{ $link }}"
                    style="display: inline-block; background-color: #A694DD; color: white; padding: 10px 20px; text-decoration: none; font-weight: bold; border-radius: 5px;">
                    Reset Password
                </a>
            </p>
        </div>
        <div class="footer">
            <p>If you did not request a password reset, please ignore this email or contact us at
                {{ config('variables.supportEmail') }}</p>
        </div>
    </div>
</body>

</html>
