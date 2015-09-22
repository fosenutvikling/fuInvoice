<?php
	/**
		Project:		fuInvoice
		Description:	Invoice backend for web-applications

		License:		Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
						http://creativecommons.org/licenses/by-nc-sa/4.0/

		File:			api.php
		File purpose:	Class-handler. Get parameters (GET/POST) and get/set data in backend.

		Creator:		Fosen Utvikling AS
		Contact:		post at fosen-utvikling dot as

		Developers:		Jonas Kirkemyr
						Robert Andresen
	*/

	header('Content-Type: application/json');

	// Sanitize POST/GET inputs
	$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
	$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


	// Get required files
	require_once('config.php');
	require_once('Invoice.class.php');


	// Get API key
	if (isset($_GET['apikey'])) {
		define('API_KEY', $_GET['apikey']);
	} else {
		die('ERROR: API key missing');
	}



	// Get action/function to target
	if (isset($_GET['action'])) {
		$action = $_GET['action'];
	} else {
		die('ERROR: No function target');
	}

	

	// Create instance for API
	// Auth of API key is checked when creating the object.
	$obj = new fuInvoice\Invoice(API_KEY, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	








	// Get invoices
	if ($action == 'getInvoices') {



		$result = $obj->getInvoices($_GET);
		echo json_encode($result);
	}



	// Get invoices
	if ($action == 'addInvoice') {

		$duedate = strtotime("+7 day", time());
		$duedate = date('Y-m-d', $duedate);

		$p = array (
			'user_id' => '1',
			'customer_id' => '43',
			'due_date' => $duedate,
			'sender_orgnumber' => '998871212',
			'sender_name' => 'Fosen Utvikling AS',
			'sender_address' => 'Rådhusveien 18',
			'sender_zip' => '7100',
			'sender_location' => 'Rissa',
			'sender_ref' => 'Robert Andresen',
			'invoice_ref' => '',
			'receiver_orgnumber' => '123456789',
			'receiver_name' => 'Testkunde',
			'receiver_address' => 'Bortiveien 44',
			'receiver_zip' => '7105',
			'receiver_location' => 'Stadsbygd',
			'receiver_ref' => '5REF',
			'receiver_mail' => 'ra@stadsbygd.net',
		);

		$result = $obj->addInvoice($p);
		echo json_encode($result);
	}




	// Get invoices
	if ($action == 'deleteInvoice') {
		$result = $obj->deleteInvoice($_GET['id']);
		echo json_encode($result);
	}


?>