<?php
	$getInvoices = $obj->getInvoices();
	$nInvoices = count($getInvoices);
?>



<h1><?php echo _('Invoices'); ?></h1>


<div class="well">
	<form action="" method="POST">
		<div class="row">

			<div class="col-md-2">
				<div class="form-group">
					<label for="inputDateFrom"><?php echo _('Date from'); ?></label>
					<input type="text" class="form-control datepicker" name="inputDateFrom" id="inputDateFrom" placeholder="<?php echo _('yyyy-mm-dd'); ?>">
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
					<label for="inputDateTo"><?php echo _('Date to'); ?></label>
					<input type="text" class="form-control datepicker" name="inputDateTo" id="inputDateTo" placeholder="<?php echo _('yyyy-mm-dd'); ?>">
				</div>
			</div>

			<div class="col-md-4">
				<div style="margin-top:30px;">
					<button type="submit" class="btn btn-primary">
						<i class="fa fa-filter"></i> <?php echo _('Filter'); ?>
					</button>
				</div>
			</div>

		</div>
	</form>
</div>


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
				<th style="width:100px;"></th>
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

						<a href="../<?php echo $base_url; ?>&action=sendInvoice&id=<?php echo $iID; ?>" data-toggle="tooltip" data-placement="top" title="Generate invoice number and kid, and send to receiver mail">
							<i class="fa fa-envelope"></i>
						</a>
						&nbsp;


					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>