<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function de($array){
	echo '<div class="debug" style="background: #404040; font-family: monospace; color: #ffffff; padding: 20px;">';
    print '<pre>';
    print_r($array);
    print '</pre>';
    echo '</div>';
}