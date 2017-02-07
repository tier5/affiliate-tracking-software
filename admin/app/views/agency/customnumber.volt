{{ content() }}

<!-- Upgrade plan pop up -->
<div class="modal fade" id="upgradePlanModal" tabindex="-1" role="dialog" aria-labelledby="upgradePlanModalLabel">
	<div class="change-plan modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="growth-bar">
					<div class="caption">
						<span>Upgrade subscription</span>
					</div>
				</div>
			</div>
			<div class="modal-body center-block">
			    <div class="row">
                <div class="col-xs-12 text-center">
                        <span class="sub-section-header"><h4 class="bold">One Time Offer</h4></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <span class="sub-section-header"><h5 class="bold">Double The Amount Of Accounts & Get 20% Off For Life!</h5></span>
                    </div>
                </div>

                <div class="row" style="padding-bottom: 11px;">
                    <div class="col-xs-12 text-center">
                        <span class="sub-section-header"><h6 class="blue slight-bold">Upgrade To 20 Accounts For $160 Per Month & All New Additional Accounts Will Be $8 For Life.</h6></span>
                    </div>
                </div>
				<div class="row">
					<div class="col-xs-12">
						<button id="submit-agency-change-plan-btn" type="button" class="btn btn-warning btn-lg center-block">Upgrade to 20 accounts for $160</button>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12" style="margin-top: 10px;">
						<button onclick="DismissUpgrade();" type="button" class="btn btn-link btn-lg center-block">Dismiss message</button>
					</div>
				</div>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>

<div id="locationlist">
    {{ content() }}

    <ul class="pager">
        <li class="pull-right">
            <a href="/agency/create/2" class="btn default btn-lg apple-backgound subscription-btn">Create Business</a>
        </li>
    </ul>
    <?php
if ($tBusinesses) {
?>
    <div class="row">
        <div class="col-xs-12">
            <div class="portlet box red">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-globe"></i> Custom Number Assign
                    </div>
                    <div class="tools"></div>
                </div>
                <div class="portlet-body">
                    <table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Phone Number</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
foreach($tBusinesses as $objBusiness) {

$get_explode=explode("?",$generate_array[$objBusiness->id]);
$user_id=$get_explode[0];
$status=$get_explode[1];
$phone=$get_explode[2];
$friendly_number=trim($get_explode[3]);
$action=$get_explode[4];
?>
                        <!--Business Name, Email Address, Date Created, Plan Name, Account Type (Free/Paid), Status (can turn on and off from here - Active/Inactive), Action -->
                        <tr>
                            <td><?=$objBusiness->name?></td>
                            <td>{{status}}</td>
                            <td>{{phone}}</td>
                            <td>
                            <?php if($action==1){ ?>
                            <a  href="/twilio/agencyreleseThisnumber/<?php echo base64_encode($phone);?>||<?php echo base64_encode($friendly_number);?>||"><input id="gather_info" class="btnLink btnPrimary" value="Release This Number" style="height: 42px; line-height: 14px; padding: 15px 36px; text-align: left;" type="button"></a>
                            <?php } else { ?>
                            <a href="assignnumber/<?php echo base64_encode($user_id);?>"><input id="gather_info" class="btnLink btnPrimary" value="Assign Number" style="height: 42px; line-height: 14px; padding: 15px 36px; text-align: left;" type="button"></a>
                            <?php }?>
                            </td>
                            
                            
                        </tr>
                        <?php
}
?>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>

    <?php } else { ?>
    No businesses

    <?php }  ?>

</div>
