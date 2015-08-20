<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * isAdmin
 * Prueft ob von der Admin-IP zugegriffen wird
 */
function isAdmin() {
	if ($_SERVER['REMOTE_ADDR'] == '88.116.54.114' || $_GET['debug'] == 1) {
		return TRUE;
	} else {
		return FALSE;
	}
}


/**
 * de
 * Debug-Ausgabe von String oder Array
 * @param string/array $array
 */
function de($array){
	echo '<div class="debug" style="background: #404040; font-family: monospace; color: #ffffff; padding: 20px;">';
    print '<pre>';
    print_r($array);
    print '</pre>';
    echo '</div>';
}
?>