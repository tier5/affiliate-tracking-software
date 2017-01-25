 {{ content() }}
<?php //print_r($twilio_details); ?>
<div class="row">
		<div class="col-xs-12">
			<div class="portlet box red">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-globe"></i> Booked Number
					</div>
					<div class="tools"></div>
				</div>
				<div class="portlet-body">
					<table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
							<th>Number</th>
							<th>Friendly Number</th>
							<th>Booking Date & Time</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($twilio_details as $key => $mobile_number) { ?>
							<tr>
								<td><?php echo $mobile_number['friendly_name']; ?></td>
								<td><?php echo $mobile_number['phone_number']; ?></td>
								<td><?php echo $mobile_number['created']; ?></td>
								
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>