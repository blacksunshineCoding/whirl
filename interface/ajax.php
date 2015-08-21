<?php
include '../library/debug.php';
include '../library/whirl.class.php';

if (isset($_GET['do'])) {

	$options['term'] = urldecode($_GET['term']);
	$options['quantity'] = $_GET['quantity'];
	$options['cacheDir'] = dirname(__FILE__) . '/../cache';
	
	switch($_GET['do']) {
	
		case 'clear':
			$whirl = new Whirl($options);
			$whirl->clearCache();
			break;
			
		case 'fetch':
			$whirl = new Whirl($options);
			$results = $whirl->getResults();
			if (!file_exists($whirl->cacheDir . '/fetched-results.json')) {
				touch($whirl->cacheDir . '/fetched-results.json');
			}
			file_put_contents($whirl->cacheDir . '/fetched-results.json', json_encode($results));
			break;
			
		case 'save':
			$whirl = new Whirl($options);
			$json = file_get_contents($whirl->cacheDir . '/fetched-results.json');
			$fetchedResults = json_decode($json);
			$whirl->saveResults($fetchedResults);
			break;
			
		case 'resize':
			$whirl = new Whirl($options);
			$whirl->resizeResults();
			break;
			
		case 'multiply':
			$whirl = new Whirl($options);
			$result = $whirl->multiplyResults();
			break;
			
		case 'final':
			$whirl = new Whirl($options);
			$result = $whirl->finalImage();
			echo $result;
			break;
	}
}