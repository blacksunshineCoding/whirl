<?php
class Whirl {
	
	public function full($term, $quantity) {
		$results = $this->getResults($term, $quantity);
		$this->saveResults($results);
		$this->resizeResults();
		$this->multiplyResults();
	}
	
	public function clearCache($cacheDir) {
		
		$srcDir = $cacheDir . '/src';
		$scaledDir = $cacheDir . '/scaled';
		$blendDir = $cacheDir . '/blend';
		
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
	
	public function getResults($term, $quantity) {
		$allResults = array();
		$count = 0;

		while ($count <= $quantity) {
			$json = $this->get_url_contents('http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=' . $term . '&start=' . $count);
			$data = json_decode($json);
			foreach ($data->responseData->results as $result) {
				$allResults[] = $result->url;
			}
			$count = $count + 4;
		}
		
		return $allResults;
	}
	
	public function saveResults($results) {
		foreach ($results as $resultId => $result) {
			$extension = strtolower(pathinfo($result, PATHINFO_EXTENSION));
			if (in_array($extension, array('jpg', 'png', 'jpeg', 'gif'))) {
				$saveFile = 'image_' . $resultId . '.' . $extension;
				$fileContent = @file_get_contents($result);
				if ($fileContent !== false) {
					file_put_contents('../cache/src/' . $saveFile, $fileContent);
				}
			}

		}
	}
	
	public function resizeResults() {
		$results = array_diff(scandir('../cache/src'), array('.', '..'));
		foreach ($results as $result) {
			$image = $this->resizePic($result, 400, null, '../cache/src', '../cache/scaled');
// 			$image = $this->picCrop($result, 400, 400, 'center', 'center');
		}
	}
	
	public function cropResults() {
		$results = array_diff(scandir('../cache/scaled'), array('.', '..'));
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
	
	public function multiplyResults() {
		$results = array_diff(scandir('../cache/scaled'), array('.', '..'));
		$count = 0;
		$opacity = round(100 / count($results));
		foreach ($results as $result) {
			$lastImage = $this->blankImage();
			$destFile = '../cache/blend/blend' . $count . '.png';
			$thisImage = '../cache/scaled/' . $result;
			if (isset($lastImage)) {
				if (!isset($blendImage)) {
					$blendImage = $this->multiplyRun($lastImage, $thisImage, $destFile, $opacity);
				} else {
					$blendImage = $this->multiplyRun($blendImage, $thisImage, $destFile, $opacity);
				}
			}
			
			$lastImage = '../cache/scaled/' . $result;
			$count++;
		}
		
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
		
		imagelayereffect($baseImage, IMG_EFFECT_NORMAL);
		imagecopymerge(
				$baseImage, // destination
				$topImage, // source
				// destination x and y
				$destX, $destY,
				// x, y, width, and height of the area of the source to copy
				0, 0, $topWidth, $topHeight, $opacity);
		
		imagepng($baseImage, $destFile);

		imagedestroy($baseImage);
		imagedestroy($topImage);
		return $destFile;
	}
	
	public function finalImage($cacheDir) {
		$blendDir = scandir($cacheDir . '/blend/');
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
	
	private function resizePic($img, $width, $height, $src = '../cache/src', $dest = '../cache/scaled') {
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
		
		if ($srcTyp == 1) { // GIF
			$image = imagecreatefromgif ($src . '/' . $img);
			$newImage = imagecreate ($newImgWidth, $newImgHeight);
			imagecopyresampled ($newImage, $image, 0, 0, 0, 0, $newImgWidth, $newImgHeight, $srcWidth, $srcHeight);
			imagegif ($newImage, $dest . '/' . $img, 100);
			imagedestroy ($image);
			imagedestroy ($newImage);
			return $dest . '/' . $img;
	
		} elseif ($srcTyp == 2) {// JPG
			$image = imagecreatefromjpeg ($src . '/' . $img);
			$newImage = imagecreatetruecolor ($newImgWidth, $newImgHeight);
			imagecopyresampled ($newImage, $image, 0, 0, 0, 0, $newImgWidth, $newImgHeight, $srcWidth, $srcHeight);
			imagejpeg ($newImage, $dest . '/' . $img, 100);
			imagedestroy ($image);
			imagedestroy ($newImage);
			return $dest . '/' . $img;
	
		} elseif ($srcTyp == 3) {// PNG
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