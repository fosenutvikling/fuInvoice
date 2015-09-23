<?php
	// Get required files
	require_once('../config.php');
	require_once('../Invoice.class.php');

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

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

		<link rel="stylesheet" href="css/demo.css">
	
		<script type="text/javascript">
			$(document).ready(function() {
				$('[data-toggle="tooltip"]').tooltip(); // Not working ATM: Bootstrap v4 bug
			});
		</script>

	</head>



	<body>
		<div class="container" style="padding-top:4%">
			<h1>fuInvoice</h1>
			<b>a invoice backend for your application</b>
			<br /><br />

			<a class="btn btn-default" href="?page=dashboard">
				<i class="fa fa-file-text-o"></i> See all invoices
			</a>


			<a class="btn btn-default" href="?page=new_invoice">
				<i class="fa fa-plus"></i> New invoice
			</a>

			<?php
				if (isset($_GET['page'])) {
					include('pages/'.$_GET['page'].'.php');
				} else {
					include('pages/dashboard.php');
				}
			?>

		</div>
	</body>


</html>