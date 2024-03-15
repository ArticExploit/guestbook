<?php
session_start();

function random_string()
{
    $chars = str_split('ABCDGHIJKLMNSTUVWXYZabcdefghijklmnrstuvwxyz123456789');
    $random_str = "";

    for($i = 0; random_int(4, 6) > $i; $i++) {
        $random_str .= $chars[random_int(0, count($chars) - 1)];
    }

    return $random_str;
}

function hex_to_rgb($hex)
{
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return array($r, $g, $b);
}

function create_captcha($captcha_string,$width,$height,$bg,$fg,$lineColor)
{
    $im = imagecreate($width, $height);
    
    $bg_rgb = hex_to_rgb($bg);
    $fg_rgb = hex_to_rgb($fg);
    $line_rgb = hex_to_rgb($lineColor);

    $background_color = imagecolorallocate($im, $bg_rgb[0], $bg_rgb[1], $bg_rgb[2]);
    $text_color = imagecolorallocate($im, $fg_rgb[0], $fg_rgb[1], $fg_rgb[2]);
    $line_color = imagecolorallocate($im, $line_rgb[0], $line_rgb[1], $line_rgb[2]);
    
    $text_x = 0;
    for($i=0; strlen($captcha_string)>$i; $i++)
    {
        $text_y = random_int(0, $height- ($height*0.60));
        imagestring($im, 5, $text_x, $text_y, $captcha_string[$i], $text_color);
        $text_x += 10 + random_int(0, $width / 10 - 5);
    }
        
    for($i=0; random_int(8, 15)>$i; $i++)
        imageline($im, random_int(0,$width), random_int(0,$height), random_int(0,$width), random_int(0,$width), $line_color);

    imagepng($im);
    imagedestroy($im);
}

$captcha_string = random_string();

$_SESSION["captcha"] = $captcha_string;

header("Content-Type: image/png");

$width = 80;
$height = 40; 
$bg = "#002b36";
$fg = "#d33682";
$lineColor = "#2aa198";

create_captcha($captcha_string,$width,$height,$bg,$fg,$lineColor);
?>
