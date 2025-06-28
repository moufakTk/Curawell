<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <style>
        body { font-family: Tahoma, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: #fff; padding: 25px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px #ccc; }
        .code { font-size: 30px; font-weight: bold; color: #972f6a; text-align: center; margin: 30px 0; }
        .footer { font-size: 12px; color: #999; text-align: center; margin-top: 25px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Hello {{ $user->first_name }} {{ $user->last_name }} 👋</h2>
    <p>Your reset password code is:</p>
    <div class="code">{{ $code->code }}</div>
    <p>Please use this code to complete your verification process.</p>
    <p>If you did not request a verification code, please ignore this message.</p>

    <div class="footer">
        © {{ date('Y') }} YourSite. All rights reserved.
    </div>
</div>
</body>
</html>
