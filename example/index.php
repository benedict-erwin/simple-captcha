<?php
session_start();
require_once '../src/Captcha.php';
$captcha_str = new Captcha('nama_captcha');
if (isset($_GET['captcha_img']) && $_GET['captcha_img']) {
    $captcha_str->imageSize(150, 50)
        ->setChar(2)
        ->setCaptchaSize()
        ->setCaptchaLen(5)
        ->dificulty(3)
        ->render();
    exit();
}

if (isset($_GET['captcha_challenge']) && $captcha_str->verify($_GET['captcha_challenge'])) {
    echo '<a href="./" style="text-decoration:none;">';
    echo '<pre>';
    echo 'Captcha solved!';
    echo '</a>';
    exit();
}
?>
<html>

<head>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>
    <form class="form-inline">
        <div class="form-group mb-2">
            <label for="staticEmail2" class="sr-only">Email</label>
            <img style="padding-right:5px;" src="index.php?captcha_img=1" alt="CAPTCHA" class="form-group captcha-image"><i class="fa fa-refresh refresh-captcha"></i>
        </div>
        <div class="form-group mx-sm-3 mb-2">
            <label for="inputPassword2" class="sr-only">Password</label>
            <input type="text" class="form-control" id="captcha" name="captcha_challenge">
        </div>
        <button type="submit" class="btn btn-primary mb-2">Confirm</button>
    </form>
    <script>
        var refreshButton = document.querySelector(".refresh-captcha");
        refreshButton.onclick = function() {
            document.querySelector(".captcha-image").src = 'index.php?captcha_img=' + Date.now();
        }
    </script>
</body>

</html>
