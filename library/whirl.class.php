<?php
class Whirl {
	var $term = 'babycat';
	var $quantity = 40;
	var $cacheDir = 'cache';
	var $finalImageWidth = 400;
	var $finalImageHeight = null;
	var $finalImageSizing = 'default';
	var $finalImageAlignH = 'center';
	var $finalImageAlignV = 'center';
	var $backgroundColor = '255,255,255';
	var $blendOpacity = 'default';
	var $alphaBlendMode = 'normal';
	var $realBlendMode = false;
	var $realMultiply = false;
	var $effectList = false;
	var $effects = false;
	var $effectBrightnessLevel = 100;
	var $effectContrastLevel = 100;
	var $effectColorizeRgba = '255,0,0,1';
	var $effectSmoothLevel = 100;
	var $effectPixelSize = 10;
	var $effectGammaCorrect = '1,1';
	var $effectAdvancedPixelation = false;
	
	var $allResults;
	
	public function __construct($options) {
		if (isset($options['term'])) {
			$this->term = $options['term'];
		}
		
		if (isset($options['quantity'])) {
			$this->quantity = $options['quantity'];
		}
		
		if (isset($options['cacheDir'])) {
			$this->cacheDir = $options['cacheDir'];
		}
		
		if (isset($options['finalImageWidth'])) {
			$this->finalImageWidth = $options['finalImageWidth'];
		}
		
		if (isset($options['finalImageHeight'])) {
			$this->finalImageHeight = $options['finalImageHeight'];
		}
		
		if (isset($options['finalImageSizing'])) {
			$this->finalImageSizing = $options['finalImageSizing'];
		}
		
		if (isset($options['finalImageAlignH'])) {
			$this->finalImageAlignH = $options['finalImageAlignH'];
		}
		
		if (isset($options['finalImageAlignV'])) {
			$this->finalImageAlignV = $options['finalImageAlignV'];
		}
		
		if (isset($options['backgroundColor'])) {
			$this->backgroundColor = $options['backgroundColor'];
		}
		
		if (strpos($this->backgroundColor, ',') !== false) {
			$backgroundColorExplode = explode(',', $this->backgroundColor);
			$this->backgroundColorRgb['red'] = $backgroundColorExplode[0];
			$this->backgroundColorRgb['green'] = $backgroundColorExplode[1];
			$this->backgroundColorRgb['blue'] = $backgroundColorExplode[2];
		}
		
		if (isset($options['effectGammaCorrect'])) {
			$this->effectGammaCorrect = $options['effectGammaCorrect'];
			
			if (strpos($this->effectGammaCorrect, ',') !== false) {
				$valueExplode = explode(',', $this->effectGammaCorrect);
				$this->gammaInput = reset($valueExplode);
				$this->gammaOutput = end($valueExplode);
			} else {
				$this->gammaInput = 1;
				$this->gammaOutput = 1;
			}
			
		}
		
		if (isset($options['blendOpacity'])) {
			$this->blendOpacity = $options['blendOpacity'];
		}
		
		if (isset($options['alphaBlendMode'])) {
			$this->alphaBlendMode = $options['alphaBlendMode'];
		}
		
		if (isset($options['realBlendMode'])) {
			$this->realBlendMode = $options['realBlendMode'];
		}
		
		if (isset($options['effectList'])) {
			$this->effectList = str_replace(' ', '', $options['effectList']);
			
			$effects = array();
			if (strpos($this->effectList, ',') !== false) {
				$effectsExplode = explode(',', $this->effectList);
				foreach ($effectsExplode as $effect) {
					$effects[] = $effect;
				}
			} else {
				$effects[] = $this->effectList;
			}
			$this->effects = $effects;
		}
		
		if (isset($options['effectBrightnessLevel'])) {
			if ($options['effectBrightnessLevel'] < -255) $options['effectBrightnessLevel'] = -255;
			if ($options['effectBrightnessLevel'] > 255) $options['effectBrightnessLevel'] = 255;
			
			$this->effectBrightnessLevel = $options['effectBrightnessLevel'];
		}
		
		if (isset($options['effectContrastLevel'])) {
			$options['effectContrastLevel'] = $options['effectContrastLevel'] * -1;
			if ($options['effectContrastLevel'] < -100) $options['effectContrastLevel'] = -100;
			if ($options['effectContrastLevel'] > 100) $options['effectContrastLevel'] = 100;
			
			$this->effectContrastLevel = $options['effectContrastLevel'];
		}
		
		if (isset($options['effectColorizeRgba'])) {
			$this->effectColorizeRgba = $options['effectColorizeRgba'];
			
			if (strpos($this->effectColorizeRgba, ',') !== false) {
				$valueExplode = explode(',', $this->effectColorizeRgba);
				if (count($valueExplode) == 4) {
					$this->colorize['red'] = $valueExplode[0];
					$this->colorize['green'] = $valueExplode[1];
					$this->colorize['blue'] = $valueExplode[2];
					$this->colorize['alpha'] = $valueExplode[3];
					
					if ($this->colorize['red'] > 255) $this->colorize['red'] = 255;
					if ($this->colorize['red'] < 0) $this->colorize['red'] = 0;
					
					if ($this->colorize['green'] > 255) $this->colorize['green'] = 255;
					if ($this->colorize['green'] < 0) $this->colorize['green'] = 0;
					
					if ($this->colorize['blue'] > 255) $this->colorize['blue'] = 255;
					if ($this->colorize['blue'] < 0) $this->colorize['blue'] = 0;
					
					if ($this->colorize['alpha'] > 1) $this->colorize['alpha'] = 1;
					if ($this->colorize['alpha'] < 0) $this->colorize['alpha'] = 0;
				}
			}
		}
		
		if (isset($options['effectSmoothLevel'])) {
			$this->effectSmoothLevel = $options['effectSmoothLevel'];
		}
		
		if (isset($options['effectPixelSize'])) {
			$this->effectPixelSize = $options['effectPixelSize'];
		}
		
		if (isset($options['effectAdvancedPixelation'])) {
			$this->effectAdvancedPixelation = $options['effectAdvancedPixelation'];
		}
		
		$this->createDir('src');
		$this->createDir('scaled');
		$this->createDir('blend');
	}
	
	public function whirl() {
		$this->clearCache();
		$this->getResults();
		$this->saveResults();
		$this->resizeResults();
		$this->multiplyResults();
		$finalImage = $this->finalImage();
		return $finalImage;
	}
	
	public function clearCache() {
		
		$srcDir = $this->cacheDir . '/src';
		$scaledDir = $this->cacheDir . '/scaled';
		$blendDir = $this->cacheDir . '/blend';
		
		$srcFiles = glob($srcDir . '/*');
		foreach ($srcFiles as $srcFile) {
			if (is_file($srcFile)) unlink($srcFile);
		}
		
		$scaledFiles = glob($scaledDir . '/*');
		foreach ($scaledFiles as $scaledFile) {
			if (is_file($scaledFile)) unlink($scaledFile);
		}
		
		$blendFiles = glob($blendDir . '/*');
		foreach ($blendFiles as $blendFile) {
			if (is_file($blendFile)) unlink($blendFile);
		}
		
		return true;
		
	}
	
	public function getResults() {
		$allResults = array();
		$count = 0;

		while ($count <= $this->quantity) {
			$json = $this->get_url_contents('http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=' . urlencode($this->term) . '&start=' . $count);
			$data = json_decode($json);
			foreach ($data->responseData->results as $result) {
				$allResults[] = $result->url;
			}
			$count = $count + 4;
		}
		
		$this->allResults = $allResults;
		return $this->allResults;
	}
	
	public function saveResults($allResults=false) {
		
		if ($allResults == false) {
			$allResults = $this->allResults;
		}
		
		foreach ($allResults as $resultId => $result) {
			$extension = strtolower(pathinfo($result, PATHINFO_EXTENSION));
			if (in_array($extension, array('jpg', 'png', 'jpeg', 'gif'))) {
				$saveFile = 'image_' . $resultId . '.' . $extension;
				$fileContent = @file_get_contents($result);
				if ($fileContent !== false) {
					file_put_contents($this->cacheDir . '/src/' . $saveFile, $fileContent);
				}
			}

		}
	}
	
	public function resizeResults() {
		
		$results = array_diff(scandir($this->cacheDir . '/src'), array('.', '..'));
		foreach ($results as $result) {
			$image = $this->resizePic($result, $this->finalImageWidth, $this->finalImageHeight, $this->cacheDir . '/src', $this->cacheDir . '/scaled');
// 			$image = $this->picCrop($result, 400, 400, 'center', 'center');
		}
	}
	
	public function cropResults() {
		$results = array_diff(scandir($this->cacheDir . '/scaled'), array('.', '..'));
		foreach ($results as $result) {
			$image = $this->picCrop($result, $this->finalImageWidth, $this->finalImageHeight, 'center', 'center');
		}	
	}
	
	private function blankImage() {
		
		$blankWidth = $this->finalImageWidth;
		$blankHeight = $this->finalImageHeight;
		
		if ($blankWidth == null) $blankWidth = $blankHeight;
		if ($blankHeight == null) $blankHeight = $blankWidth;

		$img = imagecreatetruecolor($blankWidth, $blankHeight);
		if ($this->backgroundColor != 'transparent') {
			$bg = imagecolorallocate($img, $this->backgroundColorRgb['red'], $this->backgroundColorRgb['green'], $this->backgroundColorRgb['blue']);
			imagefilledrectangle($img, 0, 0, $blankWidth, $blankHeight, $bg);
		}
		imagepng($img, 'blank.png');
		
		return 'blank.png';
	}
	
	private function createDir($dirname) {
		$dir = $this->cacheDir . '/' . $dirname;
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
			return true;
		} else {
			return false;
		}
	}
	
	public function multiplyResults() {
		$results = array_diff(scandir($this->cacheDir . '/scaled'), array('.', '..'));
		$count = 0;
		
		if ($this->blendOpacity == 'default') {
			$opacity = round(100 / count($results));
		} else {
			$opacity = $this->blendOpacity;
		}
		
		foreach ($results as $result) {
			$lastImage = $this->blankImage();
			$destFile = $this->cacheDir . '/blend/blend' . $count . '.png';
			$thisImage = $this->cacheDir . '/scaled/' . $result;
			if (isset($lastImage)) {
				if (!isset($blendImage)) {
					$blendImage = $this->multiplyRun($lastImage, $thisImage, $destFile, $opacity);
				} else {
					$blendImage = $this->multiplyRun($blendImage, $thisImage, $destFile, $opacity);
				}
			}
			
			$lastImage = $this->cacheDir . '/scaled/' . $result;
			$count++;
		}
		
		$finalImageName = $this->finalImage();
		$finalImage = $this->cacheDir . '/blend/' . $finalImageName;
		
		$finalImageNameExplode = explode('.', $finalImageName);
		$finalImageEnding = end($finalImageNameExplode);
		$finalImageNewName = $this->term . '.' . $finalImageEnding;
		$finalimageNew = $this->cacheDir . '/blend/' . $finalImageNewName;
		copy($finalImage, $finalimageNew);
		
	}
	
	public function multiplyRun($image1, $image2, $destFile, $opacity=50) {
		
		$baseImageType = pathinfo($image1, PATHINFO_EXTENSION);
		
		if ($baseImageType == 'png') {
			$baseImage = imagecreatefrompng($image1);
		} else if ($baseImageType == 'gif') {
			$baseImage = imagecreatefromgif($image1);
		} else {
			$baseImage = imagecreatefromjpeg($image1);
		}
		
		$baseIsTrueColor = imageistruecolor($baseImage);
		
		$topImageType = pathinfo($image2, PATHINFO_EXTENSION);
		
		if ($topImageType == 'png') {
			$topImage = imagecreatefrompng($image2);
		} else if ($topImageType == 'gif') {
			$topImage = imagecreatefromgif($image2);
		} else {
			$topImage = imagecreatefromjpeg($image2);
		}
		
		
		$topIsTrueColor = imageistruecolor($topImage);
		
		$baseWidth = imagesx($baseImage);
		$baseHeight = imagesy($baseImage);
		$topWidth = imagesx($topImage);
		$topHeight = imagesy($topImage);
		
		$destX = ($baseWidth - $topWidth) / 2;
		$destY = ($baseHeight - $topHeight) / 2;
		
		if (function_exists('imagelayereffect')) {
			switch ($this->alphaBlendMode) {
				
				default:
				case 'normal':
					imagelayereffect($topImage, IMG_EFFECT_NORMAL);
					break;
					
				case 'replace':
					imagelayereffect($topImage, IMG_EFFECT_REPLACE);
					break;
				
				case 'overlay':
					imagelayereffect($topImage, IMG_EFFECT_OVERLAY);
					break;
			}
		}
		
		if ($this->effects != false) {
			// negate,grayscale,brightness,contrast,colorize,edges,emboss,gaussBlur,selectiveBlur,meanRemoval,smooth,pixelate
			foreach ($this->effects as $effect) {
				switch ($effect) {
					case 'gammaCorrect':
						if (function_exists('imagegammacorrect') && isset($this->gammaCorrect) && !empty($this->gammaCorrect)) {
							imagegammacorrect($topImage, $this->gammaInput, $this->gammaOutput);
						}
						break;
						
					case 'negate':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_NEGATE);
						}
						break;
						
					case 'grayscale':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_GRAYSCALE);
						}
						break;
						
					case 'brightness':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_BRIGHTNESS, $this->effectBrightnessLevel);
						}
						break;
						
					case 'contrast':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_CONTRAST, $this->effectContrastLevel);
						}
						break;
					
					case 'colorize':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_COLORIZE, $this->colorize['red'], $this->colorize['green'], $this->colorize['blue'], $this->colorize['alpha']);
						}
						break;
						
					case 'edges':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_EDGEDETECT);
						}
						break;
					
					case 'emboss':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_EMBOSS);
						}
						break;
						
					case 'gaussBlur':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_GAUSSIAN_BLUR);
						}
						break;
					
					case 'selectiveBlur':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_SELECTIVE_BLUR);
						}
						break;
						
					case 'meanRemoval':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_MEAN_REMOVAL);
						}
						break;
					
					case 'smooth':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_SMOOTH, $this->effectSmoothLevel);
						}
						break;
						
					case 'pixelate':
						if (function_exists('imagefilter')) {
							imagefilter($topImage, IMG_FILTER_PIXELATE, $this->effectPixelSize, $this->effectAdvancedPixelation);
						}
						break;
				}
			}
		}
		
		if ($this->realBlendMode != false) {
			$baseImage = $this->realBlend($baseImage, $topImage, $this->realBlendMode);
		} else {
			imagecopymerge($baseImage, $topImage, $destX, $destY, 0, 0, $topWidth, $topHeight, $opacity);
		}
		
		imagepng($baseImage, $destFile);

		imagedestroy($baseImage);
		imagedestroy($topImage);
		return $destFile;
	}
	
	public function realBlend($baseImage, $topImage, $mode) {
		$baseIsTrueColor = imageistruecolor($baseImage);
		$topIsTrueColor = imageistruecolor($topImage);
		
		$baseWidth  = imagesx($baseImage);
		$baseHeight = imagesy($baseImage);
		$topWidth   = imagesx($topImage);
		$topHeight  = imagesy($topImage);
		
		$destX = ($baseWidth - $topWidth) / 2;
		$destY = ($baseHeight - $topHeight) / 2;

		for ($x = 0; $x < $topWidth; ++$x) {
			for ($y = 0; $y < $topHeight; ++$y) {
			
				$color = imagecolorat($baseImage, $x + $destX, $y + $destY);
				
				if ($baseIsTrueColor) {
					$baseColor = array(
						'red' => ($color >> 16) & 0xFF,
						'green' => ($color >> 8) & 0xFF,
						'blue' => $color & 0xFF,
						'alpha' => ($color & 0x7F000000) >> 24,
					);
				} else {
					$baseColor = imagecolorsforindex($baseImage, $color);
				}
			
				$color = imagecolorat($topImage, $x, $y);
				
				if ($topIsTrueColor) {
					$topColor = array(
						'red' => ($color >> 16) & 0xFF,
						'green' => ($color >> 8) & 0xFF,
						'blue' => $color & 0xFF,
						'alpha' => ($color & 0x7F000000) >> 24,
					);
				} else {
					$topColor = imagecolorsforindex($topImage, $color);
				}
				
				switch ($mode) {
					case 'dissolve':
						// unknown formula
						break;
						
					case 'darkerColor':
						// the same?
					case 'darken':
						// formula min(target,blend)
						$destColor = array(
							'red' => intval(min($baseColor['red'], $topColor['red'])),
							'green' => intval(min($baseColor['green'], $topColor['green'])),
							'blue' => intval(min($baseColor['blue'], $topColor['blue'])),
							'alpha' => 	intval(min($baseColor['alpha'], $topColor['alpha']))
						);
						break;
						
					default:
					case 'multiply':
						// target * blend / maxValue
						$destColor = array(
							'red' => intval($baseColor['red'] * ($topColor['red'] / 255.0)),
							'green' => intval($baseColor['green'] * ($topColor['green'] / 255.0)),
							'blue' => intval($baseColor['blue'] * ($topColor['blue'] / 255.0)),
							'alpha' => intval($baseColor['alpha'] * ($topColor['alpha'] / 127.0))
						);
						break;
						
					case 'colorBurn':
						// formula maxValue - (maxValue-target) / blend
						$destColor = array(
							'red' => intval($baseColor['red'] * ($topColor['red'] / 255.0)),
							'green' => intval($baseColor['green'] * ($topColor['green'] / 255.0)),
							'blue' => intval($baseColor['blue'] * ($topColor['blue'] / 255.0)),
							'alpha' => intval($baseColor['alpha'] * ($topColor['alpha'] / 127.0))
						);
						break;
						
					case 'linearBurn':
						// formula target + blend - maxValue
						$destColor = array(
							'red' => intval($baseColor['red'] + $topColor['red'] - 255.0),
							'green' => intval($baseColor['green'] + $topColor['green'] - 255.0),
							'blue' => intval($baseColor['blue'] + $topColor['blue'] - 255.0),
							'alpha' => intval($baseColor['alpha'] + $topColor['alpha'] - 127.0)
						);
						break;
						
					case 'lighterColor':
						// the same?
					case 'lighten':
						// formula max(target,blend)
						$destColor = array(
							'red' => intval(max($baseColor['red'], $topColor['red'])),
							'green' => intval(max($baseColor['green'], $topColor['green'])),
							'blue' => intval(max($baseColor['blue'], $topColor['blue'])),
							'alpha' => intval(max($baseColor['alpha'], $topColor['alpha']))
						);
						break;
						
					case 'screen':
						// formula target + ((maxValue - target) / 100) * ((blend / 255) * 100)
						$destColor = array(
							'red' => intval(round($baseColor['red'] + ((255.0 - $baseColor['red']) / 100) * (($topColor['red'] / 255.0) * 100))),
							'green' => intval(round($baseColor['green'] + ((255.0 - $baseColor['green']) / 100) * (($topColor['green'] / 255.0) * 100))),
							'blue' => intval(round($baseColor['blue'] + ((255.0 - $baseColor['blue']) / 100) * (($topColor['blue'] / 255.0) * 100))),
							'alpha' => intval(round($baseColor['alpha'] + ((127.0 - $baseColor['alpha']) / 100) * (($topColor['alpha'] / 127.0) * 100)))
						);
						break;
						
					case 'colorDodge':
						// formula  if (base+target > maxValue) maxValue else base
						$destColor = array(
							'red' => intval(($baseColor['red'] + $topColor['red'] > 255.0 ? 255.0 : $baseColor['red'])),
							'green' => intval(($baseColor['green'] + $topColor['green'] > 255.0 ? 255.0 : $baseColor['green'])),
							'blue' => intval(($baseColor['blue'] + $topColor['blue'] > 255.0 ? 255.0 : $baseColor['blue'])),
							'alpha' => intval(($baseColor['alpha'] + $topColor['alpha'] > 127.0 ? 127.0 : $baseColor['alpha']))
						);
						break;
					
					case 'linearDodge':
						// formula  if (target + blend > maxValue) maxValue else (target + base)
						$destColor = array(
							'red' => intval(($baseColor['red'] + $topColor['red']) > 255.0 ? 255.0 : ($baseColor['red'] + $topColor['red'])),
							'green' => intval(($baseColor['green'] + $topColor['green']) > 255.0 ? 255.0 : ($baseColor['green'] + $topColor['red'])),
							'blue' => intval(($baseColor['blue'] + $topColor['blue']) > 255.0 ? 255.0 : ($baseColor['blue'] + $topColor['blue'])),
							'alpha' => intval(($baseColor['alpha'] + $topColor['alpha']) > 127.0 ? 127.0 : ($baseColor['alpha'] + $topColor['alpha']))
						);
						break;
						
					case 'overlay':
						/*
						formula: 
						
						if ($baseColor['red'] > 127.5) {
							(Upper Layer Value * ((255-Lower Layer Value)/127.5)) + (Lower Layer Value - (255-Lower Layer Value))
							(($topColor['red'] * ((255.0 - $baseColor['red']) / 127.5)) + ($baseColor['red'] - (255.0 - $baseColor['red'])))
						} else {
							Upper Layer Value * (Lower Layer Value/127.5)
							($topColor['red'] * ($baseColor['red'] / 127.5))
						}
						*/
						
						$destColor = array(
							'red' => intval($baseColor['red'] > 127.5 ? (($topColor['red'] * ((255.0 - $baseColor['red']) / 127.5)) + ($baseColor['red'] - (255.0 - $baseColor['red']))) : ($topColor['red'] * ($baseColor['red'] / 127.5))),
							'green' => intval($baseColor['green'] > 127.5 ? (($topColor['green'] * ((255.0 - $baseColor['green']) / 127.5)) + ($baseColor['green'] - (255.0 - $baseColor['green']))) : ($topColor['green'] * ($baseColor['green'] / 127.5))),
							'blue' => intval($baseColor['blue'] > 127.5 ? (($topColor['blue'] * ((255.0 - $baseColor['blue']) / 127.5)) + ($baseColor['blue'] - (255.0 - $baseColor['blue']))) : ($topColor['blue'] * ($baseColor['blue'] / 127.5))),
							'alpha' => intval($baseColor['alpha'] > 63.5 ? (($topColor['alpha'] * ((127.0 - $baseColor['alpha']) / 63.5)) + ($baseColor['alpha'] - (127.0 - $baseColor['alpha']))) : ($topColor['alpha'] * ($baseColor['alpha'] / 63.5)))
						);
						break;
						
					case 'softLight':
						// nearly same as overlay, unknown formula
						break;
						
					case 'hardLight':
						// nearly same as overlay, unknow formula
						break;
						
					case 'vividLight':
						// unknow formula
						break;
						
					case 'linearLight':
						// unknow formula
						break;
						
					case 'pinLight':
						// unknow formula
						break;
						
					case 'hardMix':
						// formula Base + Blend >= 255.0 ? 255.0 : 0.0
						$destColor = array(
							'red' => intval(($baseColor['red'] + $topColor['red']) >= 255.0 ? 255.0 : 0.0),
							'green' => intval(($baseColor['green'] + $topColor['green']) >= 255.0 ? 255.0 : 0.0),
							'blue' => intval(($baseColor['blue'] + $topColor['blue']) >= 255.0 ? 255.0 : 0.0),
							'alpha' => intval(($baseColor['alpha'] + $topColor['alpha']) >= 127.0 ? 127.0 : 0.0)
						);
						break;
						
					case 'difference':
						// formmula (Blend-Base) < 0 ? ((Blend-Base) * -1) : (Blend-Base)
						$destColor = array(
							'red' => intval(($topColor['red'] - $baseColor['red']) < 0 ? (($topColor['red'] - $baseColor['red']) * -1) : ($topColor['red'] - $baseColor['red'])),
							'green' => intval(($topColor['green'] - $baseColor['green']) < 0 ? (($topColor['green'] - $baseColor['green']) * -1) : ($topColor['green'] - $baseColor['green'])),
							'blue' => intval(($topColor['blue'] - $baseColor['blue']) < 0 ? (($topColor['blue'] - $baseColor['blue']) * -1) : ($topColor['blue'] - $baseColor['blue'])),
							'alpha' => intval(($topColor['alpha'] - $baseColor['alpha']) < 0 ? (($topColor['alpha'] - $baseColor['alpha']) * -1) : ($topColor['alpha'] - $baseColor['alpha']))
						);
						break;
					
					case 'exclusion':
						//formula ((0.5 - 2*((Target/255)-0.5)*((Blend/255)-0.5)) * 255)
						$destColor = array(
							'red' => intval(round(((0.5 - 2.0 * (($baseColor['red'] / 255.0) - 0.5) * (($topColor['red'] / 255.0) - 0.5)) * 255.0))),
							'green' => intval(round(((0.5 - 2.0 * (($baseColor['green'] / 255.0) - 0.5) * (($topColor['green'] / 255.0) - 0.5)) * 255.0))),
							'blue' => intval(round(((0.5 - 2.0 * (($baseColor['blue'] / 255.0) - 0.5) * (($topColor['blue'] / 255.0) - 0.5)) * 255.0))),
							'alpha' => intval(round(((0.5 - 2.0 * (($baseColor['alpha'] / 127.0) - 0.5) * (($topColor['alpha'] / 127.0) - 0.5)) * 127.0)))
						);
						break;
						
					case 'subtract':
						// formula (Base - Blend) < 0.0 ? 0.0 : (Base - Blend)
						$destColor = array(
							'red' => intval(($baseColor['red'] - $topColor['red']) < 0.0 ? 0.0 : ($baseColor['red'] - $topColor['red'])),
							'green' => intval(($baseColor['green'] - $topColor['green']) < 0.0 ? 0.0 : ($baseColor['green'] - $topColor['green'])),
							'blue' => intval(($baseColor['blue'] - $topColor['blue']) < 0.0 ? 0.0 : ($baseColor['blue'] - $topColor['blue'])),
							'alpha' => intval(($baseColor['alpha'] - $topColor['alpha']) < 0.0 ? 0.0 : ($baseColor['alpha'] - $topColor['alpha']))
						);
						break;
					
					case 'divide':
						// formula intval(((base / blend) * 255) > 255.0 ? 255.0 : ((base / blend) * 255))
						$destColor = array(
							'red' => intval((($baseColor['red'] / $topColor['red']) * 255.0) > 255.0 ? 255.0 : (($baseColor['red'] / $topColor['red']) * 255.0)),
							'green' => intval((($baseColor['green'] / $topColor['green']) * 255.0) > 255.0 ? 255.0 : (($baseColor['green'] / $topColor['green']) * 255.0)),
							'blue' => intval((($baseColor['blue'] / $topColor['blue']) * 255.0) > 255.0 ? 255.0 : (($baseColor['blue'] / $topColor['blue']) * 255.0)),
							'alpha' => intval((($baseColor['alpha'] / $topColor['alpha']) * 255.0) > 255.0 ? 255.0 : (($baseColor['alpha'] / $topColor['alpha']) * 255.0))
						);
						break;
						
					case 'hue':
						// formula The Hue blend mode preserves the luma and chroma of the bottom layer, while adopting the hue of the top layer (wikipedia)
						// unsure about handling of alpha channel (opacity)
						$baseColorHsl = $this->rgbToHsl($baseColor['red'], $baseColor['green'], $baseColor['green']);
						$topColorHsl = $this->rgbToHsl($topColor['red'], $topColor['green'], $topColor['blue']);
						$destColorRgb = $this->hslToRgb($topColorHsl['hue'], $baseColorHsl['saturation'], $baseColorHsl['lightness']);
						
						$destColor = array(
							'red' => $destColorRgb['red'],
							'green' => $destColorRgb['green'],
							'blue' => $destColorRgb['blue'],
							'alpha' => $topColor['alpha']
						);
						break;
					
					case 'saturation':
						// formula The Saturation blend mode preserves the luma and hue of the bottom layer, while adopting the chroma of the top layer. (wikipedia)
						// unsure about handling of alpha channel (opacity)
						$baseColorHsl = $this->rgbToHsl($baseColor['red'], $baseColor['green'], $baseColor['green']);
						$topColorHsl = $this->rgbToHsl($topColor['red'], $topColor['green'], $topColor['blue']);
						$destColorRgb = $this->hslToRgb($baseColorHsl['hue'], $topColorHsl['saturation'], $baseColorHsl['lightness']);
						
						$destColor = array(
							'red' => $destColorRgb['red'],
							'green' => $destColorRgb['green'],
							'blue' => $destColorRgb['blue'],
							'alpha' => $topColor['alpha']
						);
						break;
						
					case 'color':
						// formula The Color blend mode preserves the luma of the bottom layer, while adopting the hue and chroma of the top layer. (wikipedia)
						// unsure about handling of alpha channel (opacity)
						$baseColorHsl = $this->rgbToHsl($baseColor['red'], $baseColor['green'], $baseColor['green']);
						$topColorHsl = $this->rgbToHsl($topColor['red'], $topColor['green'], $topColor['blue']);
						$destColorRgb = $this->hslToRgb($topColorHsl['hue'], $topColorHsl['saturation'], $baseColorHsl['lightness']);
						
						$destColor = array(
							'red' => $destColorRgb['red'],
							'green' => $destColorRgb['green'],
							'blue' => $destColorRgb['blue'],
							'alpha' => $topColor['alpha']
						);
						break;
							
					case 'luminosity':
						// The Luminosity blend mode preserves the hue and chroma of the bottom layer, while adopting the luma of the top layer. (wikipedia)
						// unsure about handling of alpha channel (opacity)
						$baseColorHsl = $this->rgbToHsl($baseColor['red'], $baseColor['green'], $baseColor['green']);
						$topColorHsl = $this->rgbToHsl($topColor['red'], $topColor['green'], $topColor['blue']);
						$destColorRgb = $this->hslToRgb($baseColorHsl['hue'], $baseColorHsl['saturation'], $topColorHsl['lightness']);
						
						$destColor = array(
							'red' => $destColorRgb['red'],
							'green' => $destColorRgb['green'],
							'blue' => $destColorRgb['blue'],
							'alpha' => $topColor['alpha']
						);
						break;
						
				}
			
				$colorIndex = imagecolorallocatealpha($baseImage, $destColor['red'], $destColor['green'], $destColor['blue'], $destColor['alpha']);
				
				if ($colorIndex === false) {
					$colorIndex = imagecolorclosestalpha($baseImage, $destColor['red'], $destColor['green'], $destColor['blue'], $destColor['alpha']);
				}
				
				imagesetpixel($baseImage, $x + $destX, $y + $destY, $colorIndex);
			}
		}
		
		return $baseImage;
	}
	
	public function finalImage() {
		$blendDir = scandir($this->cacheDir . '/blend/');
		$lastFile = end($blendDir);
		return $lastFile;
	}
	
	private function topLimit($int, $limit) {
		if (intval($int) > intval($limit)) {
			return intval($limit);
		} else {
			return intval($int);
		}
	}
	
	private function bottomLimit($int, $limit) {
		if (intval($int) < intval($limit)) {
			return intval($limit);
		} else {
			return intval($int);
		}
	}
	
	private function get_url_contents($url) {
		$crl = curl_init();
		curl_setopt($crl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
		curl_setopt($crl, CURLOPT_URL, $url);
		curl_setopt($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($crl, CURLOPT_CONNECTTIMEOUT, 5);
		$ret = curl_exec($crl);
		curl_close($crl);
		return $ret;
	}
	
	public function rgbToHsv ($red, $green, $blue) {
		$hsl = array();
		
		$varRed = ($red / 255);
		$varGreen = ($green / 255);
		$varBlue = ($blue / 255);
		
		$varMin = min($varRed, $varGreen, $varBlue);
		$varMax = max($varRed, $varGreen, $varBlue);
		$delMax = $varMax - $varMin;
		
		$v = $varMax;
		
		if ($delMax == 0) {
			$h = 0;
			$s = 0;
		} else {
			$s = $delMax / $varMax;
		
			$delRed = ((($varMax - $varRed) / 6) + ($delMax / 2)) / $delMax;
			$delGreen = ((($varMax - $varGreen) / 6) + ($delMax / 2)) / $delMax;
			$delBlue = ((($varMax - $varBlue) / 6) + ($delMax / 2)) / $delMax;
		
			if ($varRed == $varMax) {
				$h = $delBlue - $delGreen;
			} else if ($varGreen == $varMax) {
				$h = ( 1 / 3 ) + $delRed - $delBlue;
			} else if ($varBlue == $varMax) {
				 $h = ( 2 / 3 ) + $delGreen - $delRed;
			}
		
			if ($h < 0) $h++;
			if ($h > 1) $h--;
		}
		
		$hsv['hue'] = $h;
		$hsv['saturation'] = $s;
		$hsv['value'] = $v;
		
		return $hsv;
	}
	
	public function hsvToRgb($hue, $saturation, $value) {
		$hue *= 6;
		$i = floor($hue);
		$f = $hue - $i;
		$m = $value * (1 - $saturation);
		$n = $value * (1 - $saturation * $f);
		$k = $value * (1 - $saturation * (1 - $f));
		
		switch ($i) {
			case 0:
				list($red, $green, $blue) = array($value, $k, $m);
				break;
				
			case 1:
				list($red, $green, $blue) = array($n, $value, $m);
				break;
				
			case 2:
				list($red, $green, $blue) = array($m, $value, $k);
				break;
				
			case 3:
				list($red, $green, $blue) = array($m, $n, $value);
				break;
				
			case 4:
				list($red, $green, $blue) = array($k, $m, $value);
				break;
				
			case 5:
			case 6:
				list($red, $green, $blue) = array($value, $m, $n);
				break;
		}
		
		$rgb['red'] = $red * 255;
		$rgb['green'] = $green * 255;
		$rgb['blue'] = $blue * 255;
		
		return $rgb;
	}
	
	public function rgbToHsl($red, $green, $blue) {
		$oldRed = $red;
		$oldGreen = $green;
		$oldBlue = $blue;
		
		$red /= 255;
		$green /= 255;
		$blue /= 255;
		
		$max = max($red, $green, $blue);
		$min = min($red, $green, $blue);
		
		$hue;
		$saturation;
		$lightness = ($max + $min) / 2;
		$d = $max - $min;
		
		if ($d == 0){
			$hue = $saturation = 0;
			
		} else {
			$saturation = $d / (1 - abs(2 * $lightness - 1));
			
			switch ($max) {
				case $red:
					$hue = 60 * fmod((($green - $blue) / $d), 6);
					if ($blue > $green) {
						$hue += 360;
					}
					break;
					
				case $green:
					$hue = 60 * (($blue - $red) / $d + 2);
					break;
					
				case $blue:
					$hue = 60 * (($red - $green) / $d + 4);
					break;
			}
		}
		
		$hsl['hue'] = $hue;
		$hsl['saturation'] = $saturation;
		$hsl['lightness'] = $lightness;
		
		return $hsl;
	}
	
	public function hslToRgb($hue, $saturation, $lightness){
		$c = (1 - abs(2 * $lightness - 1)) * $saturation;
		$x = $c * (1 - abs(fmod(($hue / 60), 2) - 1));
		$m = $lightness - ($c / 2);
		
		if ($hue < 60) {
			$red = $c;
			$green = $x;
			$blue = 0;
			
		} else if ($hue < 120) {
			$red = $x;
			$green = $c;
			$blue = 0;
			
		} else if ($hue < 180) {
			$red = 0;
			$green = $c;
			$blue = $x;
			
		} else if ($hue < 240) {
			$red = 0;
			$green = $x;
			$blue = $c;
			
		} else if ($hue < 300) {
			$red = $x;
			$green = 0;
			$blue = $c;
			
		} else {
			$red = $c;
			$green = 0;
			$blue = $x;
		}
		
		$red = ($red + $m) * 255;
		$green = ($green + $m) * 255;
		$blue = ($blue + $m) * 255;
		
		$rgb['red'] = floor($red);
		$rgb['green'] = floor($green);
		$rgb['blue'] = floor($blue);
		
		return $rgb;
	}
	
	private function resizePic($img, $width, $height, $src, $dest) {
		list ($srcWidth, $srcHeight, $srcTyp) = getimagesize($src . '/' . $img);
		$srcRatio = $srcWidth / $srcHeight;
		$srcNewRatio = $srcWidth / $width;
	
		if ($height == null) {
			$srcNewRatio = $srcWidth / $width;
			$newImgWidth = $width;
			$newImgHeight = $srcHeight / $srcNewRatio;
		} elseif ($width == null) {
			$srcNewRatio = $srcHeight / $height;
			$newImgHeight = $height;
			$newImgWidth = $srcWidth / $srcNewRatio;
		} else {
			if ($srcWidth >= $srcHeight) {
				$newImgWidth = $width;
				$newImgHeight = $height * $width / $width;
			}
	
			if ($srcWidth < $srcHeight) {
				$newImgHeight = $width;
				$newImgWidth = $width * $height / $height;
			}
		}
		
		if ($srcTyp == 1) {
			$image = imagecreatefromgif ($src . '/' . $img);
			$newImage = imagecreate ($newImgWidth, $newImgHeight);
			imagecopyresampled ($newImage, $image, 0, 0, 0, 0, $newImgWidth, $newImgHeight, $srcWidth, $srcHeight);
			imagegif ($newImage, $dest . '/' . $img, 100);
			imagedestroy ($image);
			imagedestroy ($newImage);
			return $dest . '/' . $img;
	
		} elseif ($srcTyp == 2) {
			$image = imagecreatefromjpeg ($src . '/' . $img);
			$newImage = imagecreatetruecolor ($newImgWidth, $newImgHeight);
			imagecopyresampled ($newImage, $image, 0, 0, 0, 0, $newImgWidth, $newImgHeight, $srcWidth, $srcHeight);
			imagejpeg ($newImage, $dest . '/' . $img, 100);
			imagedestroy ($image);
			imagedestroy ($newImage);
			return $dest . '/' . $img;
	
		} elseif ($srcTyp == 3) {
			$image = imagecreatefrompng ($src . '/' . $img);
			$newImage = imagecreatetruecolor ($newImgWidth, $newImgHeight);
			imagecopyresampled ($newImage, $image, 0, 0, 0, 0, $newImgWidth, $newImgHeight, $srcWidth, $srcHeight);
			imagepng ($newImage, $dest . '/' . $img);
			imagedestroy ($image);
			imagedestroy ($newImage);
			return $dest . '/' . $img;
	
		} else {
			return false;
		}
	}
	
	function picCrop($src, $width, $height, $vpos, $hpos, $opacity=false, $srcPath = 'src/', $destPath = 'scaled', $quality = 100) {
	
		list ($sourceWidth, $sourceHeight, $sourceTyp) = getimagesize($srcPath . $src);
	
		switch ($vpos) {
			case 'top':
				$yPosition = 0;
				$newHeight = $height;
				break;
			case 'center':
				$yPosition = ($sourceHeight - $height) / 2;
				$newHeight = $height;
				break;
			case 'bottom':
				$yPosition = $sourceHeight - $height;
				$newHeight = $height;
				break;
		}
	
		switch ($hpos) {
			case 'left':
				$xPosition = 0;
				$newWidth = $width;
				break;
			case 'center':
				$xPosition = ($sourceWidth - $width) / 2;
				$newWidth = $width;
				break;
			case 'right':
				$xPosition = $sourceWidth - $width;
				$newWidth = $width;
				break;
		}
	
		$tempImage = imagecreatetruecolor($width, $height);
	
		switch ($sourceTyp) {
			case 1:
				$resourceImage = imagecreatefromgif($srcPath . $src);
				break;
			case 2:
				$resourceImage = imagecreatefromjpeg($srcPath . $src);
				break;
			case 3:
				$resourceImage = imagecreatefrompng($srcPath . $src);
				if ($opacity == true) {
					imagesavealpha($tempImage, true);
					imagefill( $tempImage, 0, 0, imagecolorallocatealpha( $tempImage, 0, 0, 0, 127 ) );
	
				}
				break;
		}
	
		imagecopy($tempImage, $resourceImage, 0, 0, $xPosition, $yPosition, $width, $height);
	
		if (strpos($src, '/') !== false) {
			$srcFileNameExplode = explode('/', $src);
			$srcFileName = end($srcFileNameExplode);
		} else {
			$srcFileName = $src;
		}
	
		$finalImage = $destPath . '/' . $srcFileName;
	
		switch ($sourceTyp) {
			case 1:
				imagegif($tempImage, $finalImage);
				break;
			case 2:
				imagejpeg($tempImage, $finalImage);
				break;
			case 3:
				imagepng($tempImage, $finalImage);
				break;
		}
	
		imagedestroy($resourceImage);
		return $finalImage;
	}
}