# simple-captcha
Simple Captcha Helper

## installation
* Clone this repository
```git clone https://github.com/benedict-erwin/simple-captcha.git```

* Load into your php file (look at example folder)
```php
    require_once 'src/Captcha.php';
    $captcha_str = new Captcha('nama_captcha');
    $captcha_str->imageSize(150, 50)
        ->setChar(2)
        ->setCaptchaLen(3)
        ->dificulty(0)
        ->render();
```
* Method description
```
    # Set Image Size
    ->imageSize(150, 50); // width, height

    # Set Captcha font Size
    ->setCaptchaSize();

    # Set character list
    ->setChar(0);
        // 0: numeric
        // 1: alpha
        // 2: alphanumeric

    # Set Captcha length (char length)
    ->setCaptchaLen(3);

    # Set captcha dificulty
    ->dificulty(0);
        // 0: easy
        // 1: medium
        // 2: hard
        // 3: random
```
