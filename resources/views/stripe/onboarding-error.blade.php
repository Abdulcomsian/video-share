<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
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
        .error-message {
            background-color: #FFFFFF;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 20px;
            display: inline-block;
        }
        .retry-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #ecba16;
            color: #FFFFFF;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }
        .retry-btn:hover {
            background-color: #d4a813;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Error</div>
        <div class="content">
            <p>Oops! Something went wrong.</p>

            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            @if(request()->query('account_id'))
                <a href="{{ route('stripe.refresh', ['account_id' => request()->query('account_id')]) }}" class="retry-btn">
                    Retry Onboarding
                </a>
            @endif
        </div>
    </div>
</body>
</html>
