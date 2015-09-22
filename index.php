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
	</head>



	<body>
		<div class="container">
			<h1>fuInvoice</h1>
			<b>a invoice backend for your application</b>
			<br /><br />

			<h2>API testing</h2>

			<?php
				$getInvoices = $obj->getInvoices();
				$nInvoices = count($getInvoices);
			?>
			

			<a href="<?php echo $base_url; ?>&action=getInvoices">getInvoices</a><br />
			<a href="<?php echo $base_url; ?>&action=addInvoice">addInvoice</a><br />


			<br /><br /><br />

			<h2>Invoices</h2>
			<?php if ($nInvoices): ?>
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Number</th>
						<th>Type</th>
						<th>Receiver</th>
						<th></th>
					</tr>
				</thead>

				<tbody>	
					<?php foreach ($getInvoices as $iID => $iData): ?>
						<tr>
							<td>
								<?php echo $iData['data']['invoice_number']; ?>
							</td>

							<td>
								<?php echo $iData['data']['type']; ?>
							</td>

							<td>
								<?php echo $iData['receiver']['receiver_name']; ?>
							</td>

							<td>
								<a href="<?php echo $base_url; ?>&action=deleteInvoice&id=<?php echo $iID; ?>">
									<i class="fa fa-trash"></i>
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