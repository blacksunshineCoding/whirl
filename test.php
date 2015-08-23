<?php
include 'library/debug.php';
include 'library/whirl.class.php';

// demo

$options = array();
$options['term'] = 'zitrone';
$options['quantity'] = 4;
$whirl = new Whirl($options);

// $new = rgbToHsv($_GET['r'], $_GET['g'], $_GET['b']);
$new = $whirl->rgbToHsl(100, 150, 100);
print_r($new);
echo '<br>';
$new = $whirl->hslToRgb(0, 1, 0.29411764705882);
print_r($new);
echo '<br>';
$new = $whirl->rgbToHsv(100, 150, 100);
print_r($new);
echo '<br>';
$new = $whirl->hsvToRgb(0, 1, 0.58823529411765);
print_r($new);
echo '<br>';