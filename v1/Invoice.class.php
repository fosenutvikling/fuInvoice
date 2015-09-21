<?php
	
	/**
	* Invoice class
	*/
	class Invoice
	{
		
		function __construct($apiID)
		{
			$this->AppID = $this->auth($apiID);
		}



		/**
		 * Check if api key exist and return appID
		 *
		 * @param 	String	  $apiID 	API ID
		 * @return 	Boolean	  			True/false. True if access OK.
		*/
		private function auth($apiID)
		{

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
		public function addWhitelist($appID)
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
		 * @param 		Int		$p['status'] 			Invoice status
		 * @param 		Int		$p['customer_id'] 		app_c_id / Application customer ID
		 * @return 		Array	$r 						Array with invoice-data
		*/
		public function getInvoices($p)
		{
			// Use auth to fetch appID
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

				Add lines to the invoice here?
			*/
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