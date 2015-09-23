<!-- <a class="btn btn-default" href="<?php echo $base_url; ?>&action=addInvoice">
	<i class="fa fa-fw fa-plus"></i> Add random invoice
</a>
 -->



<form action="<?php echo $base_url; ?>&action=addInvoice" method="POST">

	<h2 style="margin-top:50px;">New invoice</h2>
	<div style="border:1px solid #eaeaea; padding:20px; margin-bottom:30px;">


		<!-- Hidden fields -->
		<input type="hidden" class="form-control" name="user_id" id="inputUserID" value="1">
		<input type="hidden" class="form-control" name="customer_id" id="inputCustomerID" value="43">


		<div class="row">

			<div class="col-md-6">

			<div style="text-align:; margin-top:25px;">
					<img src="http://fosen-utvikling.no/home/wp-content/uploads/2015/04/blue_long-300x73.png">
				</div>


				<h3 style="margin-top:143px;">Receiver</h3>
				<div>
					<div class="form-group">
						<label for="inputReceiverName">Organization number</label>
						<input type="text" class="form-control" name="receiver_orgnumber" id="inputReceiverName" placeholder="" value="123456789">
					</div>

					<div class="form-group">
						<label for="inputReceiverName">Receiver name</label>
						<input type="text" class="form-control" name="receiver_name" id="inputReceiverName" placeholder="Receiver name" value="A Company Inc">
					</div>

					<div class="form-group">
						<label for="inputReceiverAddress">Address</label>
						<input type="text" class="form-control" name="receiver_address" id="inputReceiverAddress" placeholder="" value="TheUltimateStreet 1337">
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="inputReceiverZip">Zip</label>
								<input type="text" class="form-control" name="receiver_zip" id="inputReceiverZip" placeholder="" value="1337">
							</div>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<label for="inputReceiverLocation">Postal / Location</label>
								<input type="text" class="form-control" name="receiver_postal" id="inputReceiverLocation" placeholder="" value="FunPlace">
							</div>
						</div>
					</div>


					<div class="form-group">
						<label for="inputReceiverRef">Ref</label>
						<input type="text" class="form-control" name="receiver_ref" id="inputReceiverRef" placeholder="" value="">
					</div>

					<div class="form-group">
						<label for="inputReceiverMail">Mail</label>
						<input type="text" class="form-control" name="receiver_mail" id="inputReceiverMail" placeholder="" value="jonas.kirkemyr@fosen-utvikling.no">
					</div>
				</div>
			</div>



			<div class="col-md-6">

				<h3>Invoicedata</h3>
				<div>
					<div class="form-group">
						<label for="inputDueDate">Due date</label>
						<?php
							// Find date for +14 days in future
							$duedate = strtotime("+14 day", time());
							$duedate = date('Y-m-d', $duedate);
						?>
						<input type="text" class="form-control datepicker" name="due_date" id="inputDueDate" placeholder="yyyy-dd-mm" value="<?php echo $duedate; ?>">
					</div>

					<div class="form-group">
						<label for="inputSenderRef">Bank account number</label>
						<input type="text" class="form-control" name="bank_account_number" id="inputBankAccountNumber" placeholder="" value="1234.55.67890">
					</div>
				</div>



				<h3 style="margin-top:53px;">Sender</h3>
				<div>
					<div class="form-group">
						<label for="inputSenderOrgNumber">Organization number</label>
						<input type="text" class="form-control" name="sender_orgnumber" id="inputSenderOrgNumber" placeholder="" value="123456789">
					</div>

					<div class="form-group">
						<label for="inputSenderName">Sender name</label>
						<input type="text" class="form-control" name="sender_name" id="inputSenderName" placeholder="Sender name" value="Fosen Utvikling AS">
					</div>

					<div class="form-group">
						<label for="inputSenderAddress">Address</label>
						<input type="text" class="form-control" name="sender_address" id="inputSenderAddress" placeholder="" value="Rådhusveien 18">
					</div>

					<div class="row">
						<div class="col-md-3">
							<div class="form-group">
								<label for="inputSenderZip">Zip</label>
								<input type="text" class="form-control" name="sender_zip" id="inputSenderZip" placeholder="" value="7100">
							</div>
						</div>
						<div class="col-md-9">
							<div class="form-group">
								<label for="inputSenderLocation">Postal / Location</label>
								<input type="text" class="form-control" name="sender_postal" id="inputSenderLocation" placeholder="" value="Rissa">
							</div>
						</div>
					</div>
					

					<div class="form-group">
						<label for="inputSenderRef">Ref</label>
						<input type="text" class="form-control" name="sender_ref" id="inputSenderRef" placeholder="" value="5REF">
					</div>

					<div class="form-group">
						<label for="inputSenderMail">Mail</label>
						<input type="text" class="form-control" name="sender_mail" id="inputSenderMail" placeholder="" value="post@fosen-utvikling.no">
					</div>

					<div class="form-group">
						<label for="inputSenderWebpage">Website</label>
						<input type="text" class="form-control" name="sender_webpage" id="inputSenderWebpage" placeholder="" value="http://www.fosen-utvikling.no">
					</div>
				</div>
			</div>

		</div>



		<div class="form-group" style="margin:20px 0px;">
			<label for="textareaDescription">Description</label>
			<textarea class="form-control" style="height:80px;" name="description" id="textareaDescription">This is a description field...</textarea>
		</div>



		<h3>Lines</h3>

		<div class="row">
			<div class="col-md-1">
				<label for="inputLine-prodid">Product ID</label>
			</div>

			<div class="col-md-6">
				<label for="inputLine-desc">Description</label>
			</div>

			<div class="col-md-1">
				<label for="inputLine-price">Price</label>
			</div>

			<div class="col-md-1">
				<label for="inputLine-qty">Qty</label>
			</div>

			<div class="col-md-1">
				<label for="inputLine-vat">VAT</label>
			</div>

			<div class="col-md-1">
				<label for="inputLine-vat">Discount</label>
			</div>

			<div class="col-md-1">
				<label for="inputLine-total">Total</label>
			</div>
		</div>


		<?php for ($i=0; $i < 5; $i++): ?>

			<input type="hidden" name="lines[<?php echo $i; ?>][account_number]" id="inputLine-account_number-<?php echo $i; ?>">

			<div class="row" style="margin-bottom:2px;">

				<div class="col-md-1 col-xs-1">
					<!-- <label for="inputLine-prodid-<?php echo $i; ?>">Product ID</label> -->
					<input type="text" class="form-control" name="lines[<?php echo $i; ?>][product_id]" id="inputLine-prodid-<?php echo $i; ?>" placeholder="" value="<?php echo rand(11,99); ?>">
				</div>

				<div class="col-md-6 col-xs-6">
					<!-- <label for="inputLine-desc-<?php echo $i; ?>">Description</label> -->
					<input type="text" class="form-control" name="lines[<?php echo $i; ?>][description]" id="inputLine-desc-<?php echo $i; ?>" placeholder="" value="<?php echo 'Product ' . rand(11,99); ?>">
				</div>

				<div class="col-md-1 col-xs-1">
					<!-- <label for="inputLine-price-<?php echo $i; ?>">Price</label> -->
					<input type="text" class="form-control" name="lines[<?php echo $i; ?>][price]" id="inputLine-price-<?php echo $i; ?>" placeholder="" value="<?php echo rand(11,999); ?>">
				</div>

				<div class="col-md-1 col-xs-1">
					<!-- <label for="inputLine-qty-<?php echo $i; ?>">Qty</label> -->
					<input type="text" class="form-control" name="lines[<?php echo $i; ?>][qty]" id="inputLine-qty-<?php echo $i; ?>" placeholder="" value="<?php echo rand(1,20); ?>">
				</div>

				<div class="col-md-1 col-xs-1">
					<!-- <label for="inputLine-vat-<?php echo $i; ?>">VAT</label> -->
					<input type="text" class="form-control" name="lines[<?php echo $i; ?>][vat]" id="inputLine-vat-<?php echo $i; ?>" placeholder="" value="25">
				</div>

				<div class="col-md-1 col-xs-1">
					<!-- <label for="inputLine-vat-<?php echo $i; ?>">VAT</label> -->
					<input type="text" class="form-control" name="lines[<?php echo $i; ?>][discount]" id="inputLine-discount-<?php echo $i; ?>" placeholder="" value="0">
				</div>

				<div class="col-md-1 col-xs-1">
					<!-- <label for="inputLine-total-<?php echo $i; ?>">Total</label> -->
					<input type="text" class="form-control" name="lines[<?php echo $i; ?>][total]" id="inputLine-total-<?php echo $i; ?>" placeholder="">
				</div>

			</div>
		<?php endfor; ?>	


	</div>

	<div style="margin-bottom:45px; text-align:right;">
		<button type="submit" class="btn btn-primary btn-lg">Save invoice</button>
	</div>


</form>