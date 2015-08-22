<?php
include 'library/debug.php';
include 'library/whirl.class.php';

// demo

$options = array();
$options['term'] = 'zitrone';
$options['quantity'] = 8;
$options['effectColorizeRgba'] = '0,255,0,1';
$options['effectList'] = 'colorize';
$options['cacheDir'] = dirname(__FILE__) . '/cache';
$whirl = new Whirl($options);
$whirl->whirl();
$finalImage = $whirl->finalImage();


echo '<img src="cache/blend/'.$finalImage.'">';