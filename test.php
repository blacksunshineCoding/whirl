<?php
include 'library/debug.php';
include 'library/whirl.class.php';

// demo

// $options = array();
// $options['term'] = 'zitrone';
// $options['quantity'] = 4;
// $whirl = new Whirl($options);
// test1
// base:	100
// blend:	150
// result:	100

// test2
// base:	200
// blend:	50
// result:	100

// test3
// base:	80
// blend:	20
// result:	40

de('base: 80');
de('bland: 20');
de('result: 40');

$baseColor['red'] = 80;
$topColor['red'] = 20;

$baseColor['green'] = 80;
$topColor['green'] = 20;

$baseColor['blue'] = 80;
$topColor['blue'] = 20;

						$destColor = array(
							'red' => intval(round((($baseColor['red'] / 255.0) < ((2 * ($topColor['red'] / 255.0)) - 1) ) ? (((2 * ($topColor['red'] / 255.0)) - 1) * 255.0) : ( (2 * ($topColor['red'] / 255.0) - 1 < ($baseColor['red'] / 255.0)) && (($baseColor['red'] / 255.0) < 2 * ($topColor['red'] / 255.0))) ? ($baseColor['red']) : ((2 * ($topColor['red'] / 255.0)) * 255.0))),
							'green' => intval(round((($baseColor['green'] / 255.0) < ((2 * ($topColor['green'] / 255.0)) - 1) ) ? (((2 * ($topColor['green'] / 255.0)) - 1) * 255.0) : ( (2 * ($topColor['green'] / 255.0) - 1 < ($baseColor['green'] / 255.0)) && (($baseColor['green'] / 255.0) < 2 * ($topColor['green'] / 255.0))) ? ($baseColor['green']) : ((2 * ($topColor['green'] / 255.0)) * 255.0))),
							'blue' => intval(round((($baseColor['blue'] / 255.0) < ((2 * ($topColor['blue'] / 255.0)) - 1) ) ? (((2 * ($topColor['blue'] / 255.0)) - 1) * 255.0) : ( (2 * ($topColor['blue'] / 255.0) - 1 < ($baseColor['blue'] / 255.0)) && (($baseColor['blue'] / 255.0) < 2 * ($topColor['blue'] / 255.0))) ? ($baseColor['blue']) : ((2 * ($topColor['blue'] / 255.0)) * 255.0))),
							'alpha' => intval($topColor['alpha'])
						);

de($destColor);
