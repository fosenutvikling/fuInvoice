<?php
	// Get required files
	require_once('../config.php');
	require_once('../Invoice.class.php');
	require_once('functions.php');

	$apikey = 'bRAuvH268cp6g2pzSH3000oAqZLiZdMcas613fd5o6eiIS6175fmIahj67d7';
	$base_url = '../api.php?apikey='.$apikey;
	
	$obj = new fuInvoice\Invoice($apikey, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
?>

<!DOCTYPE html>
<html lang="en">


<head>
	<title>fuInvoice</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- 
		Jquery
		Source: https://jquery.com/
	-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>


	<!-- 
		Bootstrap
		Source: http://getbootstrap.com/
		Theme: https://bootswatch.com/paper/bootstrap.min.css

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	-->
	<link rel="stylesheet" href="packages/bootstrap-paper-theme/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


	<!-- 
		Fontawsome
		Source: http://fortawesome.github.io/Font-Awesome/icons/
	-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">


	<!-- 
		Bootstrap datepicker
		Source: https://github.com/eternicode/bootstrap-datepicker
	-->
	<link rel="stylesheet" href="packages/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
	<script src="packages/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>


	<!-- 
		Theme demo
	-->
	<link rel="stylesheet" href="css/demo.css">





	<script type="text/javascript">
		$(document).ready(function() {

			// Boostrap tooltip
			$('[data-toggle="tooltip"]').tooltip();


			// Bootstrap datepicker
			$('.datepicker').datepicker({
				format: 'yyyy-mm-dd',
				autoclose: true,
				weekStart: 1
			});

		});
	</script>

</head>






<body style="padding-top:2%">
	<div class="container">


		<!-- Static navbar -->
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.php">
						<i class="fa fa-folder-open-o"></i> fuInvoice
					</a>
				</div>

				<div id="navbar" class="navbar-collapse collapse">

					<ul class="nav navbar-nav navbar-right">
						<li>
							<a href="?page=dashboard">
								<i class="fa fa-home"></i> <?php echo _('Home'); ?>
							</a>
						</li>

						<li>
							<a href="?page=invoices">
								<i class="fa fa-file-text-o"></i> <?php echo _('Invoices'); ?>
							</a>
						</li>

						<li>
							<a href="?page=new_invoice">
								<i class="fa fa-plus"></i> <?php echo _('New invoice'); ?>
							</a>
						</li>
					</ul>
				</div><!--/.nav-collapse -->
			</div><!--/.container-fluid -->
		</nav>
	</div>

		
		
	<div class="container" style="padding:0px 30px;">
		<?php
			if (isset($_GET['page'])) {
				include('pages/'.$_GET['page'].'.php');
			} else {
				include('pages/dashboard.php');
			}
		?>
	</div>


	<div class="container">
		<div style="border-top:1px solid #eaeaea; margin-top:60px; padding:15px 8px;">
			<div class="row">
				<div class="col-md-8">
					2015 &copy; Fosen Utvikling AS<br />
					License: <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">
								Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
							</a>
				</div>

				<div class="col-md-3 col-md-offset-1">
					<a href="?page=developer_api">
						<i class="fa fa-heart-o"></i> API testing
					</a>
				</div>
			</div>
		</div>

	</div>
</body>


</html>