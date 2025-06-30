<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ecba16;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 600px;
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 20px;
        }
        .header {
            background-color: #ecba16;
            color: #FFFFFF;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            font-size: 24px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
            font-size: 18px;
        }
        .success-message {
            background-color: #ecba16;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Success</div>
        <div class="content">
            <p>Hello {{ $user->full_name ?? '' }}!</p>

            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
