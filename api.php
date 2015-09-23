<?php
	require __DIR__ . '/vendor/autoload.php';

	$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

	header('Content-Type: application/json');



	require_once('Invoice.class.php');


	if (isset($_GET['apikey'])) {
		define('API_KEY', $_GET['apikey']);
	} else {
		die('API key missing');
	}


	


	$obj = new fuInvoice\Invoice(API_KEY, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	/*echo '<pre>';
		print_r($obj);
	echo '</pre>';*/

	$getInvoices = $obj->getInvoices();

	echo json_encode($getInvoices);

	/*echo '<pre>';
		print_r($getInvoices);
	echo '</pre>';*/
?>