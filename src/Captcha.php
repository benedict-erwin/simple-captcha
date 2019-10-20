<?php

/**
 * Simple Captcha helper
 */
class Captcha
{
    private $numb;
    private $alpha;
    private $captcha_len;
    private $image;
    private $width;
    private $height;
    private $size;
    private $space;
    private $level;
    private $fonts;

    public function __construct()
    {
        $this->numb = '0123456789';
        $this->alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $this->captcha_len = 5;
        $this->width = 150;
        $this->height = 50;
        $this->size = 25;
        $this->space = 150;
        $this->level = 0;
        $this->fonts = [dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'Bebas-Regular.otf'];
    }

    public function setCaptchaLen(int $len = 0)
    {
        $this->captcha_len = ($len !== 0) ? $len : $this->captcha_len;
        return $this;
    }

    public function getCaptchaLen()
    {
        return $this->captcha_len;
    }

    public function setCaptchaSize(int $size = 25)
    {
        $this->size = $size;
        return $this;
    }

    public function getCaptchaSize()
    {
        return $this->size;
    }

    /**
     * Set Default charset
     *
     * @param level 0:numeric 1:alpha 2:alphanumeric
     *
     */
    public function setChar(int $level = 0)
    {
        $this->level = ($level > 0) ? $level : $this->level;
        return $this;
    }

    public function generateString()
    {
        $chars = ($this->level > 0) ? (($this->level > 1) ? $this->numb . $this->alpha : $this->alpha) : $this->numb;
        $chars_len = strlen($chars);
        $rand_str = '';
        for ($i = 0; $i < $this->captcha_len; $i++) {
            $rand_char = $chars[mt_rand(0, $chars_len - 1)];
            $rand_str .= $rand_char;
        }

        return $rand_str;
    }

    public function imageSize(int $width = 200, int $height = 50)
    {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    private function generateBackground()
    {
        $this->image = imagecreatetruecolor($this->width, $this->height);
        imageantialias($this->image, true);

        $colors = [];
        $r = rand(125, 175);
        $g = rand(125, 175);
        $b = rand(125, 175);

        for ($i = 0; $i < 5; $i++) {
            $colors[] = imagecolorallocate($this->image, $r - 20 * $i, $g - 20 * $i, $b - 20 * $i);
        }

        imagefill($this->image, 0, 0, $colors[0]);

        for ($i = 0; $i < 10; $i++) {
            imagesetthickness($this->image, rand(2, 10));
            $rect_color = $colors[rand(1, 4)];
            imagerectangle($this->image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $rect_color);
        }
    }

    /**
     * Set Captcha dificulty
     *
     * @param number 0:easy 1:medium 2:hard 3:random
     *
     */
    public function dificulty(int $number = 0)
    {
        $fonts = [];

        if ($number === 0) { //easy
            $fonts = [
                dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'Bebas-Regular.otf',
            ];
        } elseif ($number === 1) { //medium
            $fonts = [
                dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'acme.regular.ttf',
                dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'epyval.regular.ttf',
            ];
        } elseif ($number === 2) { //hard
            $fonts = [
                dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'captcha-code.otf',
            ];
        } elseif ($number === 3) { //random
            $fonts = [
                dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'Bebas-Regular.otf',
                dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'acme.regular.ttf',
                dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'epyval.regular.ttf',
                dirname(__FILE__) . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'captcha-code.otf',
            ];
        }

        $this->fonts = $fonts;
        return $this;
    }

    private function generateCaptcha()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->generateBackground();
        $black = imagecolorallocate($this->image, 0, 0, 0);
        $white = imagecolorallocate($this->image, 255, 255, 255);
        $textcolors = [$black, $white];
        $captcha_str = $this->generateString();
        $_SESSION['captcha_text'] = $captcha_str;

        for ($i = 0; $i < $this->getCaptchaLen(); $i++) {
            $letter_space = 135 / $this->getCaptchaLen();
            $initial = 15;
            imagettftext($this->image, $this->size, rand(-15, 15), $initial + $i * $letter_space, rand(35, 45), $textcolors[rand(0, 1)], $this->fonts[array_rand($this->fonts)], $captcha_str[$i]);
        }
    }

    public function render()
    {
        $this->generateCaptcha();
        header('Content-type: image/png');
        imagepng($this->image);
        imagedestroy($this->image);
    }
}
