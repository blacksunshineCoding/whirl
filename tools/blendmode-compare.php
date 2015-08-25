<?php
include '../library/debug.php';
include '../library/whirl.class.php';

$options['term'] = 'zitrone';
$options['quantity'] = 4;
$options['cacheDir'] = dirname(__FILE__) . '/../cache';

$whirl = new Whirl($options);

// insert the blend mode to proof here
$blendMode = 'softLight';

$baseImage = imagecreatefrompng('blendtests/blend-test1.png');
$topImage = imagecreatefrompng('blendtests/blend-test1-invert.png');

$proofImage = imagecreatefrompng('blendtests/png/blend-' . $blendMode . '.png');

$blendImage = $whirl->blend($baseImage, $topImage, $blendMode);

$baseWidth  = imagesx($baseImage);
$baseHeight = imagesy($baseImage);
$topWidth   = imagesx($topImage);
$topHeight  = imagesy($topImage);
$proofWidth   = imagesx($proofImage);
$proofHeight  = imagesy($proofImage);
$blendWidth   = imagesx($blendImage);
$blendHeight  = imagesy($blendImage);

$finalImageWidth = ($proofWidth + $blendWidth) / 2;
$finalImageHeight = $proofHeight + $blendHeight;

$destX = ($baseWidth - $topWidth) / 2;
$destY = ($baseHeight - $topHeight) / 2;

$finalImage = imagecreatetruecolor($finalImageWidth, $finalImageHeight);

imagecopy(	$finalImage,
			$proofImage,
			0,
			0,
			0,
			0,
			$proofWidth,
			$proofHeight
);

imagecopy(	$finalImage,
			$blendImage,
			0,
			$proofHeight,
			0,
			0,
			$blendWidth,
			$blendHeight
);

header('Content-Type: image/png');
imagepng($finalImage);
imagedestroy($finalImage);
imagedestroy($blendImage);
imagedestroy($proofImage);
imagedestroy($topImage);
imagedestroy($baseImage);