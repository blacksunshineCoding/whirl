<?php
include 'library/debug.php';
include 'library/whirl.class.php';

// demo

$options = array();
$options['term'] = 'zitrone';
$options['quantity'] = 4;
$whirl = new Whirl($options);

// $new = rgbToHsv($_GET['r'], $_GET['g'], $_GET['b']);
// $new = $whirl->rgbToHsl(100, 150, 100);
// print_r($new);
// echo '<br>';
// $new = $whirl->hslToRgb(0, 1, 0.29411764705882);
// print_r($new);
// echo '<br>';
// $new = $whirl->rgbToHsv(100, 150, 100);
// print_r($new);
// echo '<br>';
// $new = $whirl->hsvToRgb(0, 1, 0.58823529411765);
// print_r($new);
// echo '<br>';

// $baseImage = imagecreatefrompng('_test/dissolve-green.png');
// $topImage = imagecreatefrompng('_test/dissolve-yellow.png');

// $blendImage = $whirl->blend($baseImage, $topImage, 'dissolve');
// header('Content-Type: image/png');
// imagepng($blendImage);
// imagedestroy($blendImage);

// base 57
// top 99
// result 47

$baseColor['red'] = 100;
$topColor['red'] = 200;

$baseColor['green'] = 100;
$topColor['green'] = 200;

$baseColor['blue'] = 100;
$topColor['blue'] = 200;

$destColor = array(
	'red' => intval(round((($topColor['red'] / 255.0) < 0.5) ? ((2 * ($baseColor['red'] / 255.0) * ($topColor['red'] / 255.0) + (($baseColor['red'] / 255.0) * ($baseColor['red'] / 255.0)) * (1 - (2 * ($topColor['red'] / 255.0)))) * 255.0) : (((2 * ($baseColor['red'] / 255.0)) * (1-($topColor['red'] / 255.0)) + sqrt(($baseColor['red'] / 255.0)) * ((2 * ($topColor['red'] / 255.0)) - 1)) * 255.0))),
	'green' => intval(round((($topColor['green'] / 255.0) < 0.5) ? ((2 * ($baseColor['green'] / 255.0) * ($topColor['green'] / 255.0) + (($baseColor['green'] / 255.0) * ($baseColor['green'] / 255.0)) * (1 - (2 * ($topColor['green'] / 255.0)))) * 255.0) : (((2 * ($baseColor['green'] / 255.0)) * (1-($topColor['green'] / 255.0)) + sqrt(($baseColor['green'] / 255.0)) * ((2 * ($topColor['green'] / 255.0)) - 1)) * 255.0))),
	'blue' => intval(round((($topColor['blue'] / 255.0) < 0.5) ? ((2 * ($baseColor['blue'] / 255.0) * ($topColor['blue'] / 255.0) + (($baseColor['blue'] / 255.0) * ($baseColor['blue'] / 255.0)) * (1 - (2 * ($topColor['blue'] / 255.0)))) * 255.0) : (((2 * ($baseColor['blue'] / 255.0)) * (1-($topColor['blue'] / 255.0)) + sqrt(($baseColor['blue'] / 255.0)) * ((2 * ($topColor['blue'] / 255.0)) - 1)) * 255.0)))
);
de($destColor);