<!DOCTYPE html>
<html>
<head>
    <title>OTP for Password Reset</title>
</head>
<body>
    <p>Dear {{ $user->name }},</p>
    <p>Your OTP for password reset is: <strong>{{ $otp }}</strong></p>
    <p>This OTP will expire in 10 minutes.</p>
    <p>Thank you!</p>
</body>
</html>
