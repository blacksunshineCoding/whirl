<?php
include '../debug.php';
include '../library/whirl.class.php';

if (isset($_GET['do'])) switch($_GET['do']) {
	case 'clear':
		$whirl = new Whirl();
		$cacheDir = dirname(__FILE__) . '/../cache';
		$whirl->clearCache($cacheDir);
		break;
		
	case 'fetch':
		$whirl = new Whirl();
		$fetchedResults = $whirl->getResults(urldecode($_GET['term']), $_GET['quantity']);
		file_put_contents('../cache/fetched-results.json', json_encode($fetchedResults));
		echo json_encode($fetchedResults);
		break;
		
	case 'save':
		$whirl = new Whirl();
		$json = file_get_contents('../cache/fetched-results.json');
		$fetchedResults = json_decode($json);
		$whirl->saveResults($fetchedResults);
		break;
		
	case 'resize':
		$whirl = new Whirl();
		$whirl->resizeResults();
		break;
		
	case 'multiply':
		$whirl = new Whirl();
		$result = $whirl->multiplyResults();
		break;
		
	case 'final':
		$whirl = new Whirl();
		$result = $whirl->finalImage('../cache');
		echo $result;
		break;
}