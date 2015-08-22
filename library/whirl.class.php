<?php
class Whirl {
	var $term = 'babycat';
	var $quantity = 40;
	var $cacheDir = 'cache';
	var $finalImageWidth = 400;
	var $finalImageHeight = 400;
	var $blendMode = 'normal';
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
		
		if (isset($options['blendMode'])) {
			$this->blendMode = $options['blendMode'];
		}
		
		if (isset($options['realMultiply'])) {
			$this->realMultiply = $options['realMultiply'];
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
			$image = $this->resizePic($result, 400, null, $this->cacheDir . '/src', $this->cacheDir . '/scaled');
// 			$image = $this->picCrop($result, 400, 400, 'center', 'center');
		}
	}
	
	public function cropResults() {
		$results = array_diff(scandir($this->cacheDir . '/scaled'), array('.', '..'));
		foreach ($results as $result) {
			$image = $this->picCrop($result, 400, 400, 'center', 'center');
		}	
	}
	
	private function blankImage() {
		$img = imagecreatetruecolor(400, 400);
		$bg = imagecolorallocate($img, 255, 255, 255);
		imagefilledrectangle($img, 0, 0, 400, 400, $bg);
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
		$opacity = round(100 / count($results));
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
			switch ($this->blendMode) {
				
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
		
		imagecopymerge($baseImage, $topImage, $destX, $destY, 0, 0, $topWidth, $topHeight, $opacity);
		
		imagepng($baseImage, $destFile);

		imagedestroy($baseImage);
		imagedestroy($topImage);
		return $destFile;
	}
	
	public function finalImage() {
		$blendDir = scandir($this->cacheDir . '/blend/');
		$lastFile = end($blendDir);
		return $lastFile;
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