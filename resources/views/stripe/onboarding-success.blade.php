<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Onboarding Complete</title>
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
            background: linear-gradient(135deg, #FD683D, #FF8A65);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 28px;
        }
        .icon-circle svg {
            width: 40px;
            height: 40px;
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
            margin-bottom: 16px;
        }
        .user-name {
            font-size: 16px;
            font-weight: 600;
            color: #FD683D;
            margin-bottom: 32px;
        }
        .info-card {
            width: 100%;
            max-width: 340px;
            background-color: #FFF7F5;
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 32px;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
        }
        .info-item:not(:last-child) {
            border-bottom: 1px solid #FFE8E0;
        }
        .info-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #FD683D;
            flex-shrink: 0;
        }
        .info-text {
            font-size: 14px;
            color: #4B5563;
            line-height: 1.4;
        }
        .success-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #ECFDF5;
            color: #059669;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
        }
        .success-badge svg {
            width: 16px;
            height: 16px;
            fill: none;
            stroke: #059669;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
    </style>
</head>
<body>
    <img src="/images/logo/logo-1.png" alt="OpenEdit" class="logo">

    {{-- <div class="icon-circle">
        <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
    </div> --}}

    <h1 class="title">You're All Set!</h1>
    <p class="subtitle">Your account has been verified and is ready to receive payments.</p>

    @if(isset($user) && $user->full_name)
        <p class="user-name">{{ $user->full_name }}</p>
    @endif

    {{-- <div class="info-card">
        <div class="info-item">
            <span class="info-dot"></span>
            <span class="info-text">Your payment account is now active</span>
        </div>
        <div class="info-item">
            <span class="info-dot"></span>
            <span class="info-text">You can now bid on jobs and receive payments</span>
        </div>
        <div class="info-item">
            <span class="info-dot"></span>
            <span class="info-text">Earnings will be deposited to your bank account</span>
        </div>
    </div> --}}

    @if(session('success'))
        <div class="success-badge">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
            {{ session('success') }}
        </div>
    @else
        <div class="success-badge">
            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
            Verification Complete
        </div>
    @endif
</body>
</html>
