<?php
	/**
		Project:		fuInvoice
		Description:	Invoice backend for web-applications

		License:		Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
						http://creativecommons.org/licenses/by-nc-sa/4.0/

		File:			Invoice.class.php
		File purpose:	The invoice backend to view and create fancy invoice stuff

		Creator:		Fosen Utvikling AS
		Contact:		post at fosen-utvikling dot as

		Developers:		Jonas Kirkemyr
						Robert Andresen
	*/


	namespace fuInvoice;


	/**
	* Invoice class
	*/
	class Invoice
	{
		
		function __construct($apiID, $host, $user, $pw, $dbname)
		{
			$this->mysqli 	= $this->dbConnect($host, $user, $pw, $dbname);
			$this->App 		= $this->auth($apiID);
		}




		private function dbConnect($host, $user, $pw, $dbname)
		{
			// Create DB-instance
			$mysqli = new \Mysqli($host, $user, $pw, $dbname);

			// Check for connection errors
			if ($mysqli->connect_errno) {
				die('Connect Error: ' . $mysqli->connect_errno);
			}
			
			// Set DB charset
			mysqli_set_charset($mysqli, "utf8");

			return $mysqli;
		}



		/**
		 * Check if api key exist and return appID
		 *
		 * @param 	String	  $apiID 	API ID
		 * @return 	Boolean	  			True/false. True if access OK.
		*/
		private function auth($apiID)
		{
			$r = array();

			$query = "SELECT app_id, api_key, reader_api_key, role FROM Application WHERE (api_key LIKE '$apiID' OR reader_api_key LIKE '$apiID')";
			$result = $this->mysqli->query($query);
			$numRows = $result->num_rows;

			if ($numRows > 0) {
				$d = $result->fetch_array();

				$r['app_id'] = $d['app_id'];

				if ($apiID === $d['api_key']) $r['level'] = 1;
				elseif ($apiID === $d['reader_api_key']) $r['level'] = 2;

				$r['access'] = true;
				$r['role'] = $d['role'];
			}


			else {
				$r['access'] = false;
				$r['status'] = 'error';
				$r['message'] = 'No access';
			}

			return $r;
		}


		/**
		 * Add new application customer
		 * 
		 * @return 	  String 	$r['api_key'] 		R/W Api key
		 * @return 	  String 	$r['api_key_ro'] 	R Api key
		*/
		public function addApplication()
		{
			// Return API keys
		}


		/**
		 * Generate new API key
		 *
		 * @return 	  String 	$apikey 	API key.
		*/
		private function generateAPIkey()
		{

		}



		/**
		 * Add IP to whitelist
		 * The complete list of IP's need to be sent each time,
		 * as all old IP's will be deleted when using this function.
		 *
		 * @param 	$appID 			Application ID 
		 * @param 	$ipAddresses 	Commalist of all IP's to whitelist
		 * @return 	$r 				Status array
		*/
		public function addWhitelist($appID, $ipAddresses)
		{

		}


		/**
		 * Get IP whitelist
		 * The complete list of whitelisted IP's
		 *
		 * @param 		Int		$appID 		Application ID 
		 * @return 		Array	$r 			Array with IPs 
		*/
		public function getWhitelist($appID)
		{

		}


		/**
		 * Get invoices
		 * Fetch appID from auth()
		 *
		 * @uses auth()
		 *
		 * @param 		Int		$p['invoice_id'] 		Invoice ID - This will only return 1 invoice
		 * @param 		Int		$p['kid'] 				KID - This will only return 1 invoice
		 * @param 		Date	$p['time_from'] 		Time created from
		 * @param 		Date	$p['time_to'] 			Time created to
		 * @param 		Int		$p['type'] 				Invoice type/status
		 * @param 		Int		$p['customer_id'] 		app_c_id / Application customer ID
		 * @return 		Array	$r 						Array with invoice-data
		*/
		public function getInvoices($p=array())
		{
			$r = array();
			$q = array();


			// Check parameters for query
			if (isset($p['invoice_id']) && !empty($p['invoice_id'])) {
				$q[] = "id='{$p['invoice_id']}'";
			}

			if (isset($p['kid']) && !empty($p['kid'])) {
				$q[] = "kid='{$p['kid']}'";
			}

			if (isset($p['time_from']) && !empty($p['time_from'])) {
				$q[] = "time_from > '{$p['time_from']}'";
				// (date_field BETWEEN '2010-01-30 14:15:55' AND '2010-09-29 10:15:55')
			}

			if (isset($p['time_to']) && !empty($p['time_to'])) {
				$q[] = "time_to < '{$p['time_to']}'";
			}


			if (isset($p['type']) && !empty($p['type'])) {
				$q[] = "invoice_type='{$p['type']}'";
			}

			if (isset($p['customer_id']) && !empty($p['customer_id'])) {
				$q[] = "app_receiver_id='{$p['customer_id']}'";
			}


			$nQ = count($q);
			$c = 0;
			$queryString = "";

			if ($nQ > 0) {
				$queryString = " AND (";
				foreach ($q as $key => $value) {
					$queryString .= $value;

					$c++;
					if ($c < $nQ) {
						$queryString .= " AND ";
					}
				}
				$queryString .= ")";
			}




			$query = "SELECT * 
					  FROM Invoice 
					  WHERE app_id='{$this->App['app_id']}' $queryString";

			$result = $this->mysqli->query($query);
			$numRows = $result->num_rows;

			while ($d = $result->fetch_array()) {
				$key = $d['id'];

				//$r[$key]['data']['id'] = $d['id'];

				//$r[$key]['app']['app_id'] 		= $d['app_id']; // End-user does not need to know this
				$r[$key]['app']['creator_user'] 	= $d['app_user_id'];
				$r[$key]['app']['customer_id'] 		= $d['app_receiver_id'];

				$r[$key]['data']['invoice_number'] 	= $d['invoice_id'];
				$r[$key]['data']['kid'] 			= $d['kid'];
				$r[$key]['data']['type'] 			= $d['invoice_type'];
				$r[$key]['data']['invoice_ref'] 	= $d['invoice_ref'];
				$r[$key]['data']['time']['created'] 	= $d['time_created'];
				$r[$key]['data']['time']['due_date'] 	= $d['time_due_date'];
				$r[$key]['data']['time']['sent']		= $d['time_sent'];

				$r[$key]['sender']['sender_orgnumber'] 			= $d['sender_orgnumber'];
				$r[$key]['sender']['sender_name'] 				= $d['sender_name'];
				$r[$key]['sender']['sender_address'] 			= $d['sender_address'];
				$r[$key]['sender']['sender_zip'] 				= $d['sender_zip'];
				$r[$key]['sender']['sender_location'] 			= $d['sender_location'];
				$r[$key]['sender']['sender_ref'] 				= $d['sender_ref'];

				$r[$key]['receiver']['receiver_orgnumber']	 	= $d['receiver_orgnumber'];
				$r[$key]['receiver']['receiver_name'] 			= $d['receiver_name'];
				$r[$key]['receiver']['receiver_address'] 		= $d['receiver_address'];
				$r[$key]['receiver']['receiver_zip'] 			= $d['receiver_zip'];
				$r[$key]['receiver']['receiver_location'] 		= $d['receiver_location'];
				$r[$key]['receiver']['receiver_ref'] 			= $d['receiver_ref'];
				$r[$key]['receiver']['receiver_mail'] 			= $d['receiver_mail'];
			}

			return $r;
		}


		/**
		 * Create a new invoice
		 *
		 * @uses auth()
		 *
		 * @param 		String 	$p['id']
		 * @param 		String 	$p['app_user_id']
		 * @param 		String 	$p['app_receiver_id']
		 * @param 		String 	$p['invoice_id']
		 * @param 		String 	$p['kid']
		 * @param 		String 	$p['time_created']
		 * @param 		String 	$p['time_sent']
		 * @param 		String 	$p['time_due_date']
		 * @param 		String 	$p['invoice_type']
		 * @param 		String 	$p['sender_orgnumber']
		 * @param 		String 	$p['sender_name']
		 * @param 		String 	$p['sender_address']
		 * @param 		String 	$p['sender_zip']
		 * @param 		String 	$p['sender_postal']
		 * @param 		String 	$p['sender_ref']
		 * @param 		String 	$p['invoice_ref']
		 * @param 		String 	$p['receiver_orgnumber']
		 * @param 		String 	$p['receiver_name']
		 * @param 		String 	$p['receiver_address']
		 * @param 		String 	$p['receiver_zip']
		 * @param 		String 	$p['receiver_postal']
		 * @param 		String 	$p['receiver_ref']
		 * @param 		String 	$p['receiver_mail']
		 * @param 		String 	$p['invoice_lines']			Array with invoice lines (see addInvoiceLine())
		 * @return 		Array	$r 							Array with status
		*/
		public function addInvoice($p)
		{
			/*
				$this->AppID
				If id is provided => Check if draft and update
				


				Check if config for summing invoices is set, if yes {
					Get draft invoice for $p['app_receiver_id']
					Add lines to that draft
				}
				
				else {
					Check if $p['id'] exists: If yes => add line to that invoice
				}

				else {
					Add lines to the invoice here?
				}

			*/


			$r = array();

			$query = "INSERT INTO Invoice SET 
						app_id='{$this->App['app_id']}', 
						app_user_id='{$p['user_id']}', 
						app_receiver_id='{$p['customer_id']}', 
						time_due_date='{$p['due_date']}', 
						invoice_type='draft', 
						sender_orgnumber='{$p['sender_orgnumber']}', 
						sender_name='{$p['sender_name']}', 
						sender_address='{$p['sender_address']}', 
						sender_zip='{$p['sender_zip']}', 
						sender_location='{$p['sender_location']}', 
						sender_ref='{$p['sender_ref']}', 
						invoice_ref='{$p['invoice_ref']}', 
						receiver_orgnumber='{$p['receiver_orgnumber']}', 
						receiver_name='{$p['receiver_name']}', 
						receiver_address='{$p['receiver_address']}', 
						receiver_zip='{$p['receiver_zip']}', 
						receiver_location='{$p['receiver_location']}', 
						receiver_ref='{$p['receiver_ref']}', 
						receiver_mail='{$p['receiver_mail']}'";
			$result = $this->mysqli->query($query);

			if ($result) {
				$r['status'] = 'success';
				$r['invoice_draft_id'] = $this->mysqli->insert_id;
			} else {
				$r['status'] = 'error';
				$r['status'] = 'Could not write to database. Please check database and/or parameters.';
			}

			
			return $r;
		}


		/**
		 * Add invoice lines to invoice
		 *
		 * @param 		Int		$p['id_invoice'] 		Incremental ID to invoice
		 * @param 		String	$p['app_product_id'] 	Application product ID
		 * @param 		String	$p['description'] 		Description text
		 * @param 		Int		$p['qty'] 				Quantity
		 * @param 		Double	$p['price'] 			Item price
		 * @param 		Double	$p['discount'] 			Productline discount
		 * @param 		Double	$p['vat'] 				Taxes
		 * @return 		Array	$r 						Array with status/data
		*/
		public function addInvoiceLine()
		{

		}


		/**
		 * Delete invoice
		 *
		 * @param 		Int		$p['id_invoice'] 		Incremental ID to invoice
		 * @return 		Array	$r 						Array with status/data
		*/
		public function deleteInvoice($id)
		{
			$r = array();

			// Check if ID exists
			if (empty($id)) {
				$r['status'] = 'error';
				$r['status'] = 'ID missing';
				return $r;
			}


			// Check invoice type/status
			$p = array('invoice_id'=>$id);
			$getInvoice = $this->getInvoices($p);

			if ($getInvoice[$id]['data']['type'] != 'draft') {
				$r['status'] = 'error';
				$r['status'] = 'Invoice is sent and cannot be deleted';
				return $r;
			}


			// Delete invoice
			$query = "DELETE FROM Invoice 
					  WHERE app_id='{$this->App['app_id']}' 
					  	AND invoice_type LIKE 'draft'
					  	AND id='$id'";
			$result = $this->mysqli->query($query);

			if ($result) {
				$r['status'] = 'success';
			} else {
				$r['status'] = 'error';
				$r['status'] = 'DB Error. Please check database and/or parameters.';
			}

			
			return $r;
		}


		/**
		 * Move invoice line
		 * Move to another invoice
		 * 
		 * @param 		Int		$lineID 			ID for line to be moved
		 * @param 		String	$invoiceID 			Target invoice to move to
		 * @return 		Array	$r 					Array with status/data
		*/
		public function moveInvoiceLine($lineID, $invoiceID)
		{
			// Check if target invoice is draft.
		}


		/**
		 * Send invoice to customer
		 *
		 * @uses getInvoiceNextID()
		 *
		 * @param 		Int			$idInvoice 		Incremental ID to invoice
		 * @param 		Boolean		$mail 			True = send to customer mail
		 * @param 		Boolean		$postal 		UNSUPPORTED (Default false) True = send by mail/postal
		 * @param 		Boolean		$ehf 			UNSUPPORTED (Default false) True = send by mail/postal
		 * @return 		Array		$r 				Array with status/data
		*/
		public function sendInvoice($idInvoice, $mail=true, $postal=false, $ehf=false)
		{
			// Get next invoice ID $this->getInvoiceNextID();
			// Generate KID
			// Send
		}


		/**
		 * Get next invoice ID
		 *
		 * @return 		Int		$invoiceID 		Next customer invoice ID
		*/
		private function getInvoiceNextID()
		{

		}


		/**
		 * Generate invoice KID
		 *
		 * @return 		Int		$kid 			KID number
		*/
		private function generateKID()
		{

		}


		/**
		 * Creditnote a invoice
		 *
		 * @param 		Int		$invoiceID 		Invoice number
		 * @param 		Array	$lines 			If empty => Creditnote all lines
		 * @return 		Int		$newInvoiceID 	New invoice ID for creditnote
		*/
		public function makeCreditNote($idInvoice, $lines=array())
		{
			// Generate invoice
			// Send
		}


		/**
		 * Send reminder to customer
		 *
		 * @param 		Double		$fee 			Add fee for reminer
		 * @return 		Int			$newInvoiceID 	New invoice ID for reminder
		*/
		public function makeReminder($fee)
		{

		}


		/**
		 * Send dept collection
		 *
		 * @param 		Double		$fee 			Add fee for collection
		 * @return 		Int			$newInvoiceID 	New invoice ID for collection
		*/
		public function makeDeptCollection($fee)
		{

		}


	}

?>