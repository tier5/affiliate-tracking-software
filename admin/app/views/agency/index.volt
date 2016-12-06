{{ content() }}

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
                            <td>{{ objBusiness.subscription_id ? objBusiness.subscription.name : 'Free' }}
                            </td>
                            <td>{{ objBusiness.subscription_id ? 'Paid':'Free' }}
                            </td>
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
                                                <a href="/agency/delete/2/{{ objBusiness.agency_id }}" onclick="return confirm('Are you sure you want to delete this item?');" class=""><i class="fa fa-trash-o"></i>
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
