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
                        <i class="fa fa-globe"></i> Business List
                    </div>
                    <div class="tools"></div>
                </div>
                <div class="portlet-body">
                    <table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Date Created</th>
                            <th>Plan Name</th>
                            <th>Account Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
foreach($tBusinesses as $objBusiness) {
?>
                        <!--Business Name, Email Address, Date Created, Plan Name, Account Type (Free/Paid), Status (can turn on and off from here - Active/Inactive), Action -->
                        <tr>
                            <td><?=$objBusiness->name?></td>
                            <td><?=$objBusiness->email?></td>
                            <td><?=date("Y-m-d",strtotime($objBusiness->date_created))?></td>
                            <!--<td>{{ objBusiness.subscription_id ? objBusiness.subscription.name : 'Free' }}
                            </td>-->
                            <td><?=(isset($objBusiness->subscription_id) && $objBusiness->subscription_id > 0?objBusiness.subscription.name:($generate_array[$objBusiness->id]=='FR')?'Free':"Paid")?></td>

                            <td><?=(isset($objBusiness->subscription_id) && $objBusiness->subscription_id > 0?'Paid':($generate_array[$objBusiness->id]=='FR')?'Free':"Paid")?></td>
                            <td>
                                <a href="/admindashboard/status/2/{{ objBusiness.agency_id }}/{{ objBusiness.status ? 0 : 1 }}"><img src="/public/img/{{ objBusiness.status ? 'on' : 'off' }}.png" /></a>
                            </td>
                            <td style="text-align: right;">
                                <div class="actions">
                                    <div class="btn-group">
                                        <a data-toggle="dropdown" href="javascript:;" class="btn btn-sm green dropdown-toggle" aria-expanded="false">
                                            Actions <i class="fa fa-angle-down"></i></a>
                                        <ul class="dropdown-menu pull-right">
                                            <!--<li>
                                                <a href="/agency/view/2/{{ objBusiness.agency_id }}" class=""><i class="icon-eye"></i>
                                                    View</a></li>-->
                                            <li>
                                                <a href="/admindashboard/edit/{{ objBusiness.agency_id }}" class=""><i class="icon-pencil"></i>
                                                    Edit</a></li>
                                            <!--<li>
                                                <a href="/agency/view/2/{{ objBusiness.agency_id }}" class=""><i class="icon-user"></i>
                                                    Password</a></li>-->
                                            <li>
                                                <a href="/admindashboard/delete/2/{{ objBusiness.agency_id }}" onclick="return confirm('Are you sure you want to delete this item?');" class=""><i class="fa fa-trash-o"></i>
                                                    Delete</a></li>
                                            <!--
                                            <li>
                                                <a href="/agency/view/2/{{ objBusiness.agency_id }}" class=""><i class="icon-envelope"></i>
                                                    Resend Credentials</a></li>-->
                                            <li>
                                                <a href="/agency/view/2/{{ objBusiness.agency_id }}" class=""><i class="icon-paper-plane"></i>
                                                    Manage</a></li>
                                        </ul>
                                    </div>
                                </div>
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

{% if showUpgrade %}
	<script type="text/javascript">
		$(document).ready(function() {
			$('#upgradePlanModal').modal('show');

			$('#submit-agency-change-plan-btn').click(function () {
				changePlan();
			});
		});
		function changePlan() {
			$.post('/agency/upgradePlan')
				.done(function (data) {
					//console.log(data);
					if (data.status === true) {
						$('#upgradePlanModal').modal('hide');
					} else {
						alert('Change plan failed - ' + data.error);
					}
				})
				.fail(function () {})
				.always(function () {});
		}

		function DismissUpgrade() {
			$.post('/agency/dismissUpgrade')
				.done(function (data) {
					console.log(data);
					if (data.status === true) {
						$('#upgradePlanModal').modal('hide');
					} else {
						alert('Could not dismiss message - ' + data.error);
					}
				})
				.fail(function () {})
				.always(function () {});
		}



	</script>
{% endif %}
