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
    require __DIR__ . '/vendor/autoload.php';

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

		if (isset($_POST['lines']) && count($_POST['lines']) > 0) {
			$p['lines'] = $_POST['lines'];
		}


		$p = array (
			'user_id' => $_POST['user_id'],
			'customer_id' => $_POST['customer_id'],
			'due_date' => $_POST['due_date'],
			'bank_account_number' => $_POST['bank_account_number'],
			'description' => $_POST['description'],
			'sender_orgnumber' => $_POST['sender_orgnumber'],
			'sender_name' => $_POST['sender_name'],
			'sender_address' =>  $_POST['sender_address'],
			'sender_zip' =>  $_POST['sender_zip'],
			'sender_location' =>  $_POST['sender_postal'],
			'sender_ref' => $_POST['sender_ref'],
			'sender_mail' => $_POST['sender_mail'],
			'sender_webpage' => $_POST['sender_webpage'],
			'invoice_ref' => '',
			'receiver_orgnumber' => $_POST['receiver_orgnumber'],
			'receiver_name' => $_POST['receiver_name'],
			'receiver_address' => $_POST['receiver_address'],
			'receiver_zip' => $_POST['receiver_zip'],
			'receiver_location' => $_POST['receiver_postal'],
			'receiver_ref' => $_POST['receiver_ref'],
			'receiver_mail' => $_POST['receiver_mail'],
			'lines' => $p['lines'],
		);

		$result = $obj->addInvoice($p);
		echo json_encode($result);
	}




	// Get invoices
	if ($action == 'deleteInvoice') {
		$result = $obj->deleteInvoice($_GET['id']);
		echo json_encode($result);
	}


	// Send invoices
	if ($action == 'sendInvoice') {
		$result = $obj->sendInvoice($_GET['id']);
		echo json_encode($result);
	}


?>