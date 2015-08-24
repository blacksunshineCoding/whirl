<?php
include 'library/debug.php';
include 'library/whirl.class.php';

// demo

// $options = array();
// $options['term'] = 'zitrone';
// $options['quantity'] = 4;
// $whirl = new Whirl($options);

// test2
// base:	200
// blend:	50
// result:	115

de('base: 200');
de('bland: 50');
de('result: 115');

$baseColor['red'] = 200;
$topColor['red'] = 50;

$baseColor['green'] = 200;
$topColor['green'] = 50;

$baseColor['blue'] = 200;
$topColor['blue'] = 50;

$destColor = array(
		'red' => intval(round((($topColor['red'] / 255) <= 0.5) ? ((1 - (1 - ($baseColor['red'] / 255.0)) / (2 * ($topColor['red'] / 255.0))) * 255.0) : ((($baseColor['red'] / 255.0) / (2 * (1 - ($topColor['red'] / 255.0)))) * 255.0))),
		'green' => intval(round((($topColor['green'] / 255) <= 0.5) ? ((1 - (1 - ($baseColor['green'] / 255.0)) / (2 * ($topColor['green'] / 255.0))) * 255.0) : ((($baseColor['green'] / 255.0) / (2 * (1 - ($topColor['green'] / 255.0)))) * 255.0))),
		'blue' => intval(round((($topColor['blue'] / 255) <= 0.5) ? ((1 - (1 - ($baseColor['blue'] / 255.0)) / (2 * ($topColor['blue'] / 255.0))) * 255.0) : ((($baseColor['blue'] / 255.0) / (2 * (1 - ($topColor['blue'] / 255.0)))) * 255.0))),
		'alpha' => intval($topColor['alpha'])
);

de($destColor);