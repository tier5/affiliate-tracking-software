
	<div class="row">
		<div class="col-xs-12">
			
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-globe"></i> Numbers
					</div>
					<div class="tools"></div>
				</div>
				<div class="portlet-body">
					<table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
							<th>Number</th>
							<th>Country Code</th>
							
							<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php $i=0;foreach ($number as $key => $allnumber) {
							
							if(count($allnumber)!=0){
							
								foreach ($allnumber as $keyx => $spfnumber) { if($spfnumber->capabilities->SMS==1){ ?>
									<tr>
								
								<td><?php echo $spfnumber->friendly_name; ?></td>
								<td><?php echo $spfnumber->iso_country; ?></td>
								
								<td><?php if($spfnumber->capabilities->SMS==1){ ?> <a  href="/twilio/booknumberagency/<?php echo base64_encode($spfnumber->phone_number);?>||<?php echo base64_encode($spfnumber->friendly_name);?>||/<?php echo base64_encode($user_id);?>"><input id="gather_info" class="btnLink btnPrimary" value="Choose This Number" style="height: 42px; line-height: 14px; padding: 15px 36px; text-align: left;" type="button"></a> <?php } ?></td>
							</tr>
						<?php $i++;}}}
						} if($i==0){?>
						<tr>
						<td colspan="4"> Sorry No Number Available</td>
						</tr>
						<?php } ?>	
						
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


