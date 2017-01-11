<?php if(!empty($mobile)){ ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="portlet box red">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-globe"></i> Mobile Numbers
					</div>
					<div class="tools"></div>
				</div>
				<div class="portlet-body">
					<table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
							<th>Number</th>
							<th>Friendly Number</th>
							<th>Country Code</th>
							<th>Voice</th>
							<th>SMS</th>
							<th>MMS</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($mobile as $key => $mobile_number) { ?>
							<tr>
								<td><?php echo $mobile_number->phone_number; ?></td>
								<td><?php echo $mobile_number->friendly_name; ?></td>
								<td><?php echo $mobile_number->iso_country; ?></td>
								<td><?php if($mobile_number->capabilities->voice==1){ echo "Yes"; }else{ echo "No"; } ?></td>
								<td><?php if($mobile_number->capabilities->SMS==1){ echo "Yes"; }else{ echo "No"; } ?></td>
								<td><?php if($mobile_number->capabilities->MMS==1){ echo "Yes"; }else{ echo "No"; } ?></td>
								<td><?php if($mobile_number->capabilities->SMS==1){ ?> <a href="booknumber/<?php echo base64_encode($mobile_number->phone_number);?>||<?php echo base64_encode($mobile_number->friendly_name);?>||">Book This Number</a> <?php } ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php if(!empty($local)){ ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="portlet box red">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-globe"></i> Local Number
					</div>
					<div class="tools"></div>
				</div>
				<div class="portlet-body">
					<table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
							<th>Number</th>
							<th>Friendly Number</th>
							<th>Country Code</th>
							<th>Voice</th>
							<th>SMS</th>
							<th>MMS</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($local as $key => $local_number) { ?>
							<tr>
								<td><?php echo $local_number->phone_number; ?></td>
								<td><?php echo $local_number->friendly_name; ?></td>
								<td><?php echo $local_number->iso_country; ?></td>
								<td><?php if($local_number->capabilities->voice==1){ echo "Yes"; }else{ echo "No"; } ?></td>
								<td><?php if($local_number->capabilities->SMS==1){ echo "Yes"; }else{ echo "No"; } ?></td>
								<td><?php if($local_number->capabilities->MMS==1){ echo "Yes"; }else{ echo "No"; } ?></td>
								<td><?php if($local_number->capabilities->SMS==1){ ?><a href="booknumber/<?php echo base64_encode($local_number->phone_number);?>||<?php echo base64_encode($local_number->friendly_name);?>">Book This Number</a><?php } ?></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php if(!empty($purchased)){ ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="portlet box red">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-globe"></i> Already Purchased Numbers
					</div>
					<div class="tools"></div>
				</div>
				<div class="portlet-body">
					<table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
							<th>Number</th>
							<th>Friendly Number</th>
							<th>Voice</th>
							<th>SMS</th>
							<th>MMS</th>
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($purchased as $key => $purchase_number) { ?>
							<tr>
								<td><?php echo $purchase_number['phone_number']; ?></td>
								<td><?php echo $purchase_number['friendly_name']; ?></td>
								<td><?php if( $purchase_number['capabilities']['voice']==1){ echo "Yes"; }else{ echo "No"; } ?></td>
								<td><?php if( $purchase_number['capabilities']['sms']==1){ echo "Yes"; }else{ echo "No"; } ?></td>
								<td><?php if( $purchase_number['capabilities']['mms']==1){ echo "Yes"; }else{ echo "No"; } ?></td>
								<td><a href="bookpurchased/<?php echo base64_encode($purchase_number['phone_number']);?>||<?php echo base64_encode($purchase_number['friendly_name']);?>">Book This Number</a></td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php if(empty($mobile) && empty($local) && empty($purchased)){ ?>
<div class="row">
		<div class="col-xs-12">
			<div class="portlet box red">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-globe"></i> Number List
					</div>
					<div class="tools"></div>
				</div>
				<div class="portlet-body">
					Sorry No Number Available !!!
				</div>
			</div>
		</div>
	</div>
<?php } ?>