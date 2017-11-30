<html>
<head>
    <title>Paralideres : Activation Token</title>
</head>
<body>
<p>
    To activate your account please click on the below link or copy and paste the url to your
    browser and activate your account
</p>
<br><br>
<p><a href="{{env('APP_URL')}}/account/activation/{{$token}}">{{env('APP_URL')}}/account/activation/{{$token}}</a></p>

</body>
</html>