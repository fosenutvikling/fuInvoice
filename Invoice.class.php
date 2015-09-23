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

			$this->mail_reply_name 		= 'FU Invoice';
			$this->mail_reply_address 	= 'post@fosen-utvikling.no';
			$this->mail_sender_address 	= 'post@fosen-utvikling.no';
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

				$r[$key]['sender']['orgnumber'] 		= $d['sender_orgnumber'];
				$r[$key]['sender']['name'] 				= $d['sender_name'];
				$r[$key]['sender']['address'] 			= $d['sender_address'];
				$r[$key]['sender']['zip'] 				= $d['sender_zip'];
				$r[$key]['sender']['location'] 			= $d['sender_location'];
				$r[$key]['sender']['ref'] 				= $d['sender_ref'];

				$r[$key]['receiver']['orgnumber']	 	= $d['receiver_orgnumber'];
				$r[$key]['receiver']['name'] 			= $d['receiver_name'];
				$r[$key]['receiver']['address'] 		= $d['receiver_address'];
				$r[$key]['receiver']['zip'] 			= $d['receiver_zip'];
				$r[$key]['receiver']['location'] 		= $d['receiver_location'];
				$r[$key]['receiver']['ref'] 			= $d['receiver_ref'];
				$r[$key]['receiver']['mail'] 			= $d['receiver_mail'];



				// Invoice lines
				$invoiceLines = $this->getInvoiceLines($d['id']);

				$r[$key]['data']['sum']['total'] = 0;
				$r[$key]['data']['sum']['total_incl_vat'] = 0;

				// Loop lines to calculate sum
				if (count($invoiceLines) > 0) {
					foreach ($invoiceLines as $lID => $lData) {

						if ($lData['discount'] == 0) $lData['discount'] = 1;

						$r[$key]['data']['sum']['total'] 			+= ($lData['sum_price']);
						$r[$key]['data']['sum']['total_incl_vat'] 	+= ($lData['sum_price_incl_vat']);
					}
				}


				$r[$key]['lines'] = $invoiceLines;

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



				// Add invoice lines
				// Check if lines are included in parameter set
				if (isset($p['lines']) && count($p['lines']) > 0) {

					// Loop lines
					foreach ($p['lines'] as $key => $lData) {
						$lData['id_invoice'] = $r['invoice_draft_id']; // Set idInvoice

						// Add single line
						$result_add_line = $this->addInvoiceLine($lData);
						$r['status_add_line'][$key] = $result_add_line;
					}
				}

			} else {
				$r['status'] = 'error';
				$r['status'] = 'Could not write to database. Please check database and/or parameters.';
			}

			
			return $r;
		}


		/**
		 * Get invoice lines
		 * Return all lines for the invoice
		 *
		 * @param 		Int		$idInvoice 			The Incremental ID to invoice
		 * @return 		Array	$r 					Array with status/data
		*/
		public function getInvoiceLines($idInvoice)
		{
			$r = array();

			$query = "SELECT * 
					  FROM Invoice_line 
					  WHERE invoice_id='$idInvoice'";

			$result = $this->mysqli->query($query);
			$numRows = $result->num_rows;

			$sum_total = 0;
			$sum_total_incl_discount = 0;

			while ($d = $result->fetch_array()) {
				$key = $d['line_id'];

				$r[$key]['app']['product_id'] 		= $d['app_product_id'];
				$r[$key]['app']['account_number'] 	= $d['app_account_number'];
				$r[$key]['description'] 			= $d['description'];
				$r[$key]['quantity'] 				= $d['quantity'];
				$r[$key]['price'] 					= $d['price'];
				$r[$key]['discount'] 				= $d['discount'];
				$r[$key]['vat'] 					= $d['mva'];


				$setDiscount = ($d['discount'] / 100);
				if ($setDiscount == 0) $setDiscount = 1;

				$setVAT = ($d['mva'] / 100);
				if ($setVAT == 0) $setVAT = 1;

				$sum = ($d['price'] * $d['quantity']) * $setDiscount;
				$sumVAT = $sum * $setVAT;

				$r[$key]['sum_price']					= $sum;
				$r[$key]['sum_price_incl_vat']			= ($sum + $sumVAT);
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
		public function addInvoiceLine($p)
		{
			$r = array();

			// Change any commas
			$p['price'] 	= str_replace(',', '.', $p['price']);
			$p['discount'] 	= str_replace(',', '.', $p['discount']);
			$p['vat'] 		= str_replace(',', '.', $p['vat']);

			$query = "INSERT INTO Invoice_line SET 
						invoice_id='{$p['id_invoice']}', 
						app_product_id='{$p['product_id']}', 
						description='{$p['description']}', 
						quantity='{$p['qty']}', 
						price='{$p['price']}', 
						discount='{$p['discount']}', 
						mva='{$p['vat']}', 
						app_account_number='{$p['account_number']}'";
			$result = $this->mysqli->query($query);

			if ($result) {
				$r['status'] = 'success';
				$r['line_id'] = $this->mysqli->insert_id;
			} else {
				$r['status'] = 'error';
				$r['status'] = 'Could not write to database. Please check database and/or parameters.';
			}

			
			return $r;
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
				$r['message'] = 'ID missing';
				return $r;
			}


			// Check invoice type/status
			$p = array('invoice_id'=>$id);
			$getInvoice = $this->getInvoices($p);

			if (count($getInvoice) == 0) {
				$r['status'] = 'error';
				$r['message'] = 'Invoice does not exist';
				return $r;
			}

			if ($getInvoice[$id]['data']['type'] != 'draft') {
				$r['status'] = 'error';
				$r['message'] = 'Invoice is sent and cannot be deleted';
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
				$r['message'] = 'DB Error. Please check database and/or parameters.';
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
		public function sendInvoice($idInvoice, $type='invoice', $mail=true, $postal=false, $ehf=false)
		{
			// Get next invoice ID $this->getInvoiceNextID();
			// Generate KID
			// Send

			$r = array();

			// Check if ID exists
			if (empty($idInvoice)) {
				$r['status'] = 'error';
				$r['status'] = 'ID missing';
				return $r;
			}


			// Check invoice type/status
			$p = array('invoice_id'=>$idInvoice);
			$getInvoice = $this->getInvoices($p);



			// Generate ID and KID - if draft
			if ($getInvoice[$idInvoice]['data']['type'] == 'draft') {
				// Get next invoice number
				$invoiceID = $this->getInvoiceNextID();

				// Generate KID
				$KID = $this->generateKID($invoiceID, $type='MOD10');


				// Update invoice status
				$query = "UPDATE Invoice SET 
							invoice_id='$invoiceID', 
							kid='$KID', 
							time_sent = '".date('Y-m-d H:i:s')."',
							invoice_type='$type' 
							WHERE id='$idInvoice'";
				$result = $this->mysqli->query($query);
			}


			// Re-send
			else {
				$result = true;
				$invoiceID = $getInvoice[$idInvoice]['data']['invoice_number'];
				$KID = $getInvoice[$idInvoice]['data']['kid'];
			}



			// Send invoice and return status to client
			if ($result) {
				$r['status'] = 'success';
				$r['invoice_number'] = $invoiceID;
				$r['invoice_kid'] = $KID;

				if ($mail && !empty($getInvoice[$idInvoice]['receiver']['mail'])) {

					$subject = "Invoice #$invoiceID";
					$message = "Hi {$getInvoice[$idInvoice]['receiver']['name']}!<br /><br />";
					$message .= "This is a new invoice.<br /><br />";
					$message .= "<b>Invoice ID:</b> #$invoiceID<br />";
					$message .= "<b>KID:</b> $KID<br />";
					$message .= "<b>Due date:</b> {$getInvoice[$idInvoice]['data']['time']['due_date']}<br />";
					$message .= "<b>SUM:</b> Not supported yet<br />";
					$message .= "<br /><b>Best regards,</b><br />{$getInvoice[$idInvoice]['sender']['name']}";

					$mailResult = $this->sendMail($getInvoice[$idInvoice]['receiver']['mail'], $subject, $message);
					$r['status_mail'] = $mailResult;
				}
			} else {
				$r['status'] = 'error';
				$r['status'] = 'DB Error. Please check database and/or parameters.';
			}

			return $r;
		}


		/**
		 * Get next invoice ID
		 *
		 * @return 		Int		$invoiceID 		Next customer invoice ID
		*/
		private function getInvoiceNextID()
		{
			$query = "SELECT invoice_id 
					  FROM Invoice 
					  WHERE app_id='{$this->App['app_id']}'
					  ORDER BY invoice_id DESC";

			$result = $this->mysqli->query($query);
			$numRows = $result->num_rows;

			if ($numRows == 0) {
				return 1;
			}

			else {
				$d = $result->fetch_array();
				return ($d['invoice_id'] + 1);
			}
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


		/**
		 * Generate KID
		 * Supports only MOD10 at this moment
		 *
		 * @param 		Int		$invoicenumber 		The invoice number/ID
		 * @return 		String	$type 				Default MOD10
		*/
		private function generateKID($invoicenumber, $type='MOD10')
		{
			if ($type == 'MOD10') {
				$siffer = str_split(strrev($invoicenumber));
				$sum = 0;

				for($i=0; $i<count($siffer); ++$i) $sum += $this->checksum(( $i & 1 ) ? $siffer[$i] * 1 : $siffer[$i] * 2);
				
				$checksumInt = ($sum==0) ? 0 : substr(10 - substr($sum, -1),-1);

				return $invoicenumber . $checksumInt;
			}

			else {
				$r = array();
				$r['status'] = 'error';
				$r['message'] = 'No type provided to generate new KID';
				return $r;
			}
		}



		/**
		 * Return checksum for KID generation
		 *
		 * @param 		Int		$int 		Input INT to return checksum for
		 * @return 		Int 				The checksum
		*/
		private function checksum($int)
		{
			return array_sum(str_split($int));
		}


		/**
		 * Send Mail
		 *
		 * @param 		String		$to 		Receiver mailaddress
		 * @param 		String		$subject 	Mail-subject
		 * @param 		String		$message 	Mail-message
		 * @return 		Boolean				 	True/false for if mail is sent
		*/
		private function sendMail($to, $subject, $message) {

			// UTF-8 decoode to support characters like norwegian ÆØÅ
			$subject = utf8_decode($subject);
			$message = utf8_decode($message);

			$message = "<span style='font-family:Sans-serif; font-size:12px;'>" . $message . "</span>";

			$headers = "Content-type: text/html; charset=iso-8859-1" . "\r\n";
			$headers .= "From: {$this->mail_reply_name} <{$this->mail_sender_address}>" . "\r\n";
			$headers .= "Return-Path: {$this->mail_reply_name} <{$this->mail_reply_address}>" . "\r\n";
			$headers .= "Reply-To: {$this->mail_reply_address}" . "\r\n";
			$headers .= "X-Mailer: PHP/" . phpversion();

			$result = mail($to, $subject, $message, $headers);

			return $result;
		}


	}

?>