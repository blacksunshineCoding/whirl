<?php
include 'library/debug.php';
include 'library/whirl.class.php';

// demo

$options = array();
$options['term'] = 'zitrone';
$options['quantity'] = 10;
// $options['blendMode'] = 'multiply';
// $options['blendOpacity'] = '100';
// $options['alphaBlendMode'] = 'normal';
$options['finalImageWidth'] = 500;
$options['finalImageHeight'] = null;
$options['finalImageSizing'] = 'default';
// $options['backgroundColor'] = 'transparent';
// $options['effectColorizeRgba'] = '0,255,0,1';
// $options['effectList'] = 'colorize';
$options['cacheDir'] = dirname(__FILE__) . '/cache';
$whirl = new Whirl($options);
// $results = $whirl->getResults();
// de($results);
$whirl->whirl();
$finalImage = $whirl->finalImage();

echo '<img src="cache/blend/'.$finalImage.'">';