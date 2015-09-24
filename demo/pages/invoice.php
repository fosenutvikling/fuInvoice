<?php
	// Get required files
	/*require_once('../config.php');
	require_once('../Invoice.class.php');

	$apikey = 'bRAuvH268cp6g2pzSH3000oAqZLiZdMcas613fd5o6eiIS6175fmIahj67d7';
	$base_url = 'api.php?apikey='.$apikey;
	
	$obj = new fuInvoice\Invoice($apikey, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);*/

	$iID = $_GET['id'];
	$thisInvoice = $obj->getInvoice($iID);

?>

<div style="border:1px solid #eaeaea; padding:20px;">


	<div class="row">
		<div class="col-sm-8">

			<div style="text-align:; margin-top:0px;">
				<img src="http://fosen-utvikling.no/home/wp-content/uploads/2015/04/blue_long-300x73.png">
			</div>

			<div style="padding-top:40px; padding-left:0px;">
				<div style="font-size:16px; font-weight:bold;"><?php echo $thisInvoice['receiver']['name']; ?></div>
				<div style="font-size:16px;"><?php echo $thisInvoice['receiver']['address']; ?></div>
				<div style="font-size:16px;"><?php echo $thisInvoice['receiver']['zip']; ?> <?php echo $thisInvoice['receiver']['location']; ?></div>
				<div style="font-size:16px; margin-top:5px;"><?php echo $thisInvoice['receiver']['ref']; ?></div>
			</div>
		</div>

		<div class="col-sm-4">


			<div style="text-align:; margin-top:0px;">
				<h1><?php echo _('Invoice'); ?></h1>
			</div>

			

			<div style="padding-top:20px;">
				<div style="font-size:16px; font-weight:bold;"><?php echo $thisInvoice['sender']['name']; ?></div>
				<div style="font-size:16px;"><?php echo $thisInvoice['sender']['address']; ?></div>
				<div style="font-size:16px;"><?php echo $thisInvoice['sender']['zip']; ?> <?php echo $thisInvoice['receiver']['location']; ?></div>
			</div>

			<div style="margin-top:20px;">
				<table style="width:100%;">
					<tr>
						<td style="font-weight:bold;">Invoice ID</td>
						<td><?php echo $thisInvoice['data']['invoice_number']; ?></td>
					</tr>

					<tr>
						<td style="font-weight:bold;">KID</td>
						<td><?php echo $thisInvoice['data']['kid']; ?></td>
					</tr>

					<tr>
						<td style="font-weight:bold;">Account ID</td>
						<td><?php echo $thisInvoice['data']['bank_account_number']; ?></td>
					</tr>

					<tr>
						<td style="font-weight:bold;">Ref</td>
						<td><?php echo $thisInvoice['data']['invoice_ref']; ?></td>
					</tr>

					<tr>
						<td style="font-weight:bold;">Created</td>
						<td><?php echo $thisInvoice['data']['time']['created']; ?></td>
					</tr>

					<tr>
						<td style="font-weight:bold;">Due date</td>
						<td><?php echo $thisInvoice['data']['time']['due_date']; ?></td>
					</tr>

					<tr>
						<td style="font-weight:bold;">SUM</td>
						<td><?php echo $thisInvoice['data']['sum']['total_incl_vat']; ?></td>
					</tr>
				</table>

				<br />
				<?php echo $thisInvoice['sender']['mail']; ?><br />
				<?php echo $thisInvoice['sender']['webpage']; ?>

			</div>

		</div>
	</div>



	<div style="margin-top:45px;">
		<h2>Invoice lines</h2>
		<?php if (count($thisInvoice['lines']) > 0): ?>

			<table class="table table-sriped table-hover">
				<thead>
					<tr>
						<th style="width:100px;">#</th>
						<th>Description</th>
						<th style="width:100px; text-align:right; padding-right:15px;">Price</th>
						<th style="width:100px; text-align:right; padding-right:15px;">Discount</th>
						<th style="width:100px; text-align:right; padding-right:15px;">VAT</th>
						<th style="width:100px; text-align:right; padding-right:15px;">Total (ex. VAT)</th>
					</tr>
				</thead>

				<tbody>
					<?php foreach ($thisInvoice['lines'] as $lID => $lData): ?>
						<tr>
							<td><?php echo $lData['app']['product_id']; ?></td>
							<td><?php echo $lData['description']; ?></td>
							<td style="text-align:right; padding-right:15px;"><?php echo $lData['price']; ?></td>
							<td style="text-align:right; padding-right:15px;"><?php echo $lData['discount']; ?></td>
							<td style="text-align:right; padding-right:15px;"><?php echo $lData['vat']; ?></td>
							<td style="text-align:right; padding-right:15px;"><?php echo $lData['sum_price']; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>

				<tr class="success">
					<td>SUM</td>
					<td colspan="4"></td>
					<td><?php echo $thisInvoice['data']['sum']['total']; ?></td>
				</tr>

			</table>
			
		<?php endif; ?>
	</div>






	<div class="row">
		<div class="col-md-4 col-md-offset-8">

			<div style="text-align:right;">
				<table style="width:100%;">
					<tr>
						<td style="font-weight:bold;">Sum VAT</td>
						<td><?php echo ($thisInvoice['data']['sum']['total_incl_vat'] - $thisInvoice['data']['sum']['total']); ?></td>
					</tr>
					<tr>
						<td style="font-weight:bold;">Sum TOTAL</td>
						<td><?php echo ($thisInvoice['data']['sum']['total_incl_vat']); ?></td>
					</tr>
				</table>
			</div>

		</div>
	</div>

</div>




<?php
	/*echo '<pre>';
		print_r($thisInvoice);
	echo '</pre>';*/
?>