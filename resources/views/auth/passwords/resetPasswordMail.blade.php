<!DOCTYPE html>
<html lang="en">
<head>
    <title>FIITaPIXEL reset password</title>
</head>
<body>
<h1>Dear {{ $mailData['name'] }}</h1>
<p>To reset your password click the link below:</p>
<a href="{{ $mailData['resetLink'] }}">{{ $mailData['resetLink'] }}</a>
<br>
<p>If you did not request a password reset from FIITaPIXEL, you can safely ignore this email.</p>
<br>
<p>Yours truly,</p>
<p>The FIITaPIXEL Team</p>
</body>
</html>
