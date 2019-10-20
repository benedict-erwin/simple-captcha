<?php
require_once '../src/Captcha.php';

$captcha_str = new Captcha;
$captcha_str->imageSize(150, 50)
    ->setChar(2)
    ->setCaptchaLen(6)
    ->dificulty(3)
    ->render();
