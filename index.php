<?php
	// Get required files
	require_once('config.php');
	require_once('Invoice.class.php');

	$apikey = 'bRAuvH268cp6g2pzSH3000oAqZLiZdMcas613fd5o6eiIS6175fmIahj67d7';
	$base_url = 'api.php?apikey='.$apikey;
	
	$obj = new fuInvoice\Invoice($apikey, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>

<!DOCTYPE html>
<html lang="en">


	<head>
		<title>fuInvoice</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">


		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

		<link rel="stylesheet" href="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/css/bootstrap.css">
		<script src="https://cdn.rawgit.com/twbs/bootstrap/v4-dev/dist/js/bootstrap.js"></script>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	
		<script type="text/javascript">
			$(document).ready(function() {
				$('[data-toggle="tooltip"]').tooltip(); // Not working ATM: Bootstrap v4 bug

				/*$('.toolTip').tooltip({
					'placement': 'top',
				});*/
			});
		</script>

	</head>



	<body>
		<div class="container" style="padding-top:4%">
			<h1>fuInvoice</h1>
			<b>a invoice backend for your application</b>
			<br /><br />

			<h2>API testing</h2>

			<?php
				$getInvoices = $obj->getInvoices();
				$nInvoices = count($getInvoices);
			?>
			

			<a href="<?php echo $base_url; ?>&action=getInvoices">
				<i class="fa fa-fw fa-file-text-o"></i> getInvoices
			</a><br />
			
			<a href="<?php echo $base_url; ?>&action=addInvoice">
				<i class="fa fa-fw fa-plus"></i> addInvoice (With random lines)
			</a><br />


			<br /><br /><br />

			<h2>Invoices</h2>
			<?php if ($nInvoices): ?>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Number</th>
						<th>Type</th>
						<th>Due date</th>
						<th>Receiver</th>
						<th>Send to</th>
						<th>SUM</th>
						<th></th>
					</tr>
				</thead>

				<tbody>	
					<?php foreach ($getInvoices as $iID => $iData): ?>

						<?php
							/*echo '<pre>';
								print_r($iData);
							echo '</pre>';*/
						?>

						<tr>
							<td>
								<?php echo $iData['data']['invoice_number']; ?>
							</td>

							<td>
								<?php echo $iData['data']['type']; ?>
							</td>

							<td>
								<?php echo $iData['data']['time']['due_date']; ?>
							</td>

							<td>
								<?php echo $iData['receiver']['name']; ?>
							</td>

							<td>
								<?php echo $iData['receiver']['mail']; ?>
							</td>

							<td>
								<?php echo $iData['data']['sum']['total']; ?>
							</td>

							<td>
								<a href="invoice.php?id=<?php echo $iID; ?>" data-toggle="tooltip" data-placement="top" title="Preview in browser">
									<i class="fa fa-file-text-o"></i>
								</a>

								&nbsp; 

								<a href="#" data-toggle="tooltip" data-placement="top" title="Open PDF">
									<i class="fa fa-file-pdf-o"></i>
								</a>

								&nbsp; 

								<a href="<?php echo $base_url; ?>&action=deleteInvoice&id=<?php echo $iID; ?>" data-toggle="tooltip" data-placement="top" title="Delete this invoice">
									<i class="fa fa-trash"></i>
								</a>

								&nbsp;

								<a href="<?php echo $base_url; ?>&action=sendInvoice&id=<?php echo $iID; ?>" data-toggle="tooltip" data-placement="top" title="Generate invoice number and kid, and send to receiver mail">
									<i class="fa fa-envelope"></i>
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
			<?php endif ?>

		</div>
	</body>


</html>