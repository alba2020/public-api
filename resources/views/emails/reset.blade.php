<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Reset Your Password</h2>

<div>
    Please follow the link below to reset your password
{{--    {{ URL::to('api/confirm/' . $confirmation_code) }}.<br/>--}}
    {{--/api/set_password--}}
        {{ $reset_code }}
</div>

</body>
</html>
