<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <style>
        body { font-family: Tahoma, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: #fff; padding: 25px; border-radius: 8px; max-width: 600px; margin: auto; box-shadow: 0 0 10px #ccc; }
        .header { color: #972f6a; text-align: center; margin-bottom: 20px; }
        .message-box { background: #f9f9f9; padding: 20px; border-left: 4px solid #972f6a; margin: 25px 0; }
        .footer { font-size: 12px; color: #999; text-align: center; margin-top: 25px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Hello {{ $name }} 👋</h2>
    </div>

    <p>Thank you for reaching out to us and sharing your feedback! We truly appreciate you taking the time to let us know about your experience.</p>

    <p>Here is our response to your concern:</p>

    <div class="message-box">
        "{{ $message }}"
    </div>

    <p>Your satisfaction is extremely important to us 🌟, and we're grateful for the opportunity to address this matter.</p>

    <p>If you have any further questions or need additional assistance, please don't hesitate to contact us again.</p>

    <p>Warm regards,<br>
        <strong>The Customer Support Team</strong> 🤝</p>

    <div class="footer">
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
</div>
</body>
</html>
