<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Onboarding Error</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #FFFFFF;
            color: #1A1A2E;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }
        .logo {
            width: 120px;
            margin-bottom: 40px;
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #EF4444, #F87171);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
        }
        .icon-circle svg {
            width: 36px;
            height: 36px;
            fill: none;
            stroke: #FFFFFF;
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .title {
            font-size: 24px;
            font-weight: 700;
            color: #1A1A2E;
            margin-bottom: 12px;
            text-align: center;
        }
        .subtitle {
            font-size: 15px;
            color: #6B7280;
            text-align: center;
            line-height: 1.6;
            max-width: 320px;
            margin-bottom: 32px;
        }
        .info-card {
            width: 100%;
            max-width: 340px;
            background-color: #FEF2F2;
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 32px;
        }
        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 0;
        }
        .info-item:not(:last-child) {
            border-bottom: 1px solid #FEE2E2;
        }
        .info-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #EF4444;
            flex-shrink: 0;
            margin-top: 5px;
        }
        .info-text {
            font-size: 14px;
            color: #4B5563;
            line-height: 1.4;
        }
        .error-detail {
            width: 100%;
            max-width: 340px;
            background-color: #FEF2F2;
            border: 1px solid #FEE2E2;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 24px;
            text-align: center;
        }
        .error-detail p {
            font-size: 13px;
            color: #DC2626;
            line-height: 1.5;
        }
        .retry-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 340px;
            padding: 16px 24px;
            background: linear-gradient(135deg, #FD683D, #FF8A65);
            color: #FFFFFF;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 30px;
            text-decoration: none;
            cursor: pointer;
            transition: opacity 0.2s;
            gap: 8px;
        }
        .retry-btn:hover {
            opacity: 0.9;
        }
        .retry-btn:active {
            opacity: 0.8;
        }
        .retry-btn svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: #FFFFFF;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .help-text {
            margin-top: 20px;
            font-size: 13px;
            color: #9CA3AF;
            text-align: center;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <img src="/images/logo/logo-1.png" alt="OpenEdit" class="logo">

    {{-- <div class="icon-circle">
        <svg viewBox="0 0 24 24">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </div> --}}

    <h1 class="title">Setup Incomplete</h1>
    <p class="subtitle">We couldn't complete your account setup. This can happen if the session expired or some details were missing.</p>

    {{-- <div class="info-card">
        <div class="info-item">
            <span class="info-dot"></span>
            <span class="info-text">Your onboarding session may have expired</span>
        </div>
        <div class="info-item">
            <span class="info-dot"></span>
            <span class="info-text">Some required information might be missing</span>
        </div>
        <div class="info-item">
            <span class="info-dot"></span>
            <span class="info-text">Don't worry, you can try again below</span>
        </div>
    </div> --}}

    @if(session('error'))
        <div class="error-detail">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    @if(request()->query('account_id'))
        <a href="{{ route('stripe.refresh', ['account_id' => request()->query('account_id')]) }}" class="retry-btn">
            <svg viewBox="0 0 24 24">
                <polyline points="23 4 23 10 17 10"></polyline>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
            Retry Onboarding
        </a>
    @endif

    <p class="help-text">If the issue persists, please contact<br>our support team for assistance.</p>
</body>
</html>
