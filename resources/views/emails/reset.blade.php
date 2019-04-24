<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Reset Your Password</h2>

<div>
    Please follow the link below to reset your password
    <a href="{{ env('APP_URL') . '/reset/' . $reset_code }}">
        {{ env('APP_URL') . '/reset/' . $reset_code }}
    </a>.<br/>
</div>

</body>
</html>
