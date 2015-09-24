<?php
	

	// Redirect if filter is posted to use URL-parameters instead.
	// This will allow co-workers the possibility to send URL-links and get the same view
	if (isset($_POST['inputDateFrom'])) {
		header('Location: ?page=invoices&filter=true&dueDateFrom='.$_POST['inputDateFrom'].'&dueDateTo='.$_POST['inputDateTo']);
		exit();
	}

	// Get URL filter parameters
	elseif (isset($_GET['filter'])) {
		$p['due_date_from'] = $_GET['dueDateFrom'];
		$p['due_date_to'] = $_GET['dueDateTo'];
	}

	// Set default filter-parameters if not set
	else {

		$timeFrom = strtotime("-30 day", time());
		$timeTo = strtotime("+30 day", time());

		$p['due_date_from'] = date('Y-m-d', $timeFrom);
		$p['due_date_to'] = date('Y-m-d', $timeTo);
	}




	$getInvoices = $obj->getInvoices($p);
	$nInvoices = count($getInvoices);


?>



<h1><?php echo _('Invoices'); ?></h1>


<div class="well">
	<form action="?page=invoices&filter=true" method="POST">
		<div class="row">

			<div class="col-md-2">
				<div class="form-group">
					<label for="inputDateFrom"><?php echo _('Due date from'); ?></label>
					<input type="text" class="form-control datepicker" name="inputDateFrom" id="inputDateFrom" placeholder="<?php echo _('yyyy-mm-dd'); ?>" value="<?php echo $p['due_date_from']; ?>">
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label for="inputDateTo"><?php echo _('Due date to'); ?></label>
					<input type="text" class="form-control datepicker" name="inputDateTo" id="inputDateTo" placeholder="<?php echo _('yyyy-mm-dd'); ?>" value="<?php echo $p['due_date_to']; ?>">
				</div>
			</div>

			<div class="col-md-4">
				<div style="margin-top:30px;">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-filter"></i> <?php echo _('Filter'); ?>
					</button>

					<?php if (isset($_GET['filter'])): ?>
						<a class="btn btn-default" style="margin-left:15px;" href="?page=invoices">
							<i class="fa fa-close"></i> <?php echo _('Reset filter'); ?>
						</a>
					<?php endif; ?>

				</div>
			</div>

		</div>
	</form>
</div>


<?php if ($nInvoices): ?>
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th><?php echo _('Number'); ?></th>
				<th><?php echo _('Status'); ?></th>
				<th><?php echo _('Time sent'); ?></th>
				<th><?php echo _('Due date'); ?></th>
				<th><?php echo _('Receiver'); ?></th>
				<!-- <th>Send to</th> -->
				<th class="number"><?php echo _('SUM'); ?><br /><?php echo _('incl.'); ?> <?php echo _('VAT'); ?></th>
				<th style="width:130px;"></th>
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
						<?php echo $iID . ' / #' . $iData['data']['invoice_number']; ?>
					</td>

					<td>
						<?php
							if ($iData['data']['type'] == 'invoice') {
								echo '<span style="color:green;" data-toggle="tooltip" title="Invoice is sent"><i class="fa fa-paper-plane-o"></i></span>';
							}

							if ($iData['data']['type'] == 'reminder') {
								echo '<span style="color:red;" data-toggle="tooltip" title="Reminder sent"><i class="fa fa-bell-o"></i></span>';
							}

							if ($iData['data']['type'] == 'draft') {
								echo '<span style="color:blue;" data-toggle="tooltip" title="Draft! Not sent"><i class="fa fa-edit"></i></span>';
							}

							if ($iData['data']['type'] == 'credit') {
								echo '<span style="color:orange;" data-toggle="tooltip" title="Creditnote"><i class="fa fa-money"></i></span>';
							}

							if ($iData['data']['type'] == 'dept') {
								echo '<span style="color:red;" data-toggle="tooltip" title="Sent to dept collection"><i class="fa fa-warning"></i></span>';
							}

							if ($iData['data']['type'] == 'paid') {
								echo '<span style="color:green;" data-toggle="tooltip" title="Invoice paid"><i class="fa fa-check"></i></span>';
							}
						?>
					</td>

					<td>
						<?php
							if ($iData['data']['time']['sent'] != '0000-00-00 00:00:00') {
								$timeSent = strtotime($iData['data']['time']['sent']);
								echo date('Y-m-d', $timeSent);
							}
						?>
					</td>

					<td>
						<?php
							if (date('Y-m-d') > $iData['data']['time']['due_date']) {
								echo '<span data-toggle="tooltip" title="Due date passed" style="color:red;">'.$iData['data']['time']['due_date'].' <i class="fa fa-warning"></i></span>';
							} else {
								echo $iData['data']['time']['due_date'];
							}
						?>
					</td>

					<td>
						<?php echo $iData['receiver']['name']; ?>
					</td>

					<!-- <td>
						<?php echo $iData['receiver']['mail']; ?>
					</td> -->

					<td class="number">
						<?php echo round($iData['data']['sum']['total_incl_vat'],2); ?>
					</td>

					<td style="text-align:right;">


						<?php if ($iData['data']['type'] == 'draft'): ?>
							<a href="../<?php echo $base_url; ?>&action=deleteInvoice&id=<?php echo $iID; ?>" data-toggle="tooltip" data-placement="top" title="Delete this invoice">
								<i class="fa fa-trash"></i>
							</a>
							&nbsp;
						<?php endif ?>
					
						<a href="?page=invoice&id=<?php echo $iID; ?>" data-toggle="tooltip" data-placement="top" title="Preview in browser">
							<i class="fa fa-file-text-o"></i>
						</a>
						&nbsp; 

						<a href="#" data-toggle="tooltip" data-placement="top" title="Open PDF">
							<i class="fa fa-file-pdf-o"></i>
						</a>
						&nbsp; 

						<a href="<?php echo $base_url; ?>&action=sendInvoice&id=<?php echo $iID; ?>" data-toggle="tooltip" data-placement="top" title="Generate invoice number and kid, and send to receiver to <?php echo $iData['receiver']['mail']; ?>">
							<i class="fa fa-envelope"></i>
						</a>
						&nbsp;


					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>