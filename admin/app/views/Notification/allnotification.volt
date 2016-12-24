<div class="row">
        <div class="col-xs-12">
            <div class="portlet box red">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-globe"></i>Notification List </div>
                    <div class="tools"> </div>
                </div>
                <div class="portlet-body">
                    <table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            
                            <th>Name</th>
                            <th>Message</th>
                            <th>Date Created</th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($notification as $agency) {
                        ?>
							<tr>
								<td><?php echo $agency['from'];?></td>
								<td><?php echo $agency['message'];?></td>
								<td><?=date("Y-m-d",strtotime($agency['created']))?></td>
								
							</tr>
						<?php } ?>
                        </tbody>