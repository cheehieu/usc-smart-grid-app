<?php
	$total = array();
	if(!isset($_GET['id'])){
		$total['error'] = "No id specified!";
	} else {
		$total['result'] = $_GET['id'] * 2;
	}

	header('Vary: Accept');
	if (isset($_SERVER['HTTP_ACCEPT']) &&
		(strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
		header('Content-type: application/json');
	} else {
		header('Content-type: text/plain');
	}	
	
	echo json_encode($total);
?>