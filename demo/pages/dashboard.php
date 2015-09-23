<h2>API testing</h2>

<?php
	$getInvoices = $obj->getInvoices();
	$nInvoices = count($getInvoices);
?>


<a class="btn btn-default" href="<?php echo $base_url; ?>&action=getInvoices">
	<i class="fa fa-fw fa-file-text-o"></i> getInvoices()
</a>



<br />


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
						<a href="?page=invoice&id=<?php echo $iID; ?>" data-toggle="tooltip" data-placement="top" title="Preview in browser">
							<i class="fa fa-file-text-o"></i>
						</a>

						&nbsp; 

						<a href="#" data-toggle="tooltip" data-placement="top" title="Open PDF">
							<i class="fa fa-file-pdf-o"></i>
						</a>

						&nbsp; 

						<a href="../<?php echo $base_url; ?>&action=deleteInvoice&id=<?php echo $iID; ?>" data-toggle="tooltip" data-placement="top" title="Delete this invoice">
							<i class="fa fa-trash"></i>
						</a>

						&nbsp;

						<a href="../<?php echo $base_url; ?>&action=sendInvoice&id=<?php echo $iID; ?>" data-toggle="tooltip" data-placement="top" title="Generate invoice number and kid, and send to receiver mail">
							<i class="fa fa-envelope"></i>
						</a>
					</td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
<?php endif ?>