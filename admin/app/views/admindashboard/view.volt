<ul class="pager">
  <li class="previous pull-left">
    <a href="/admin/admindashboard/list/<?=$agency_type_id?>" class="btn red btn-outline">&larr; Go Back</a>
  </li>
</ul>

{{ content() }}


<!-- BEGIN SAMPLE FORM PORTLET-->
<div class="portlet light bordered">
  <div class="portlet-title">
    <div class="caption font-red-user">
      <i class="icon-settings fa-user"></i>
      <span class="caption-subject bold uppercase"> View <?=($agency_type_id==1?'Agency':'Business')?> </span>
    </div>
  </div>
  <div class="portlet-body form">
    <div class="form-group" style="clear: both;">
      <label for="name" class="col-md-4 control-label">Name</label>
      <div class="col-md-8">
        <?=$agency->name?>
      </div>
    </div>
    <div class="form-group" style="clear: both;">
      <label for="subscription_interval_id" class="col-md-4 control-label">Subscription</label>
      <div class="col-md-8">
        <?=($agency->subscription_id>0?$agency->subscription->name:'Free')?>
      </div>
    </div>
    <div class="form-group" style="clear: both;">
      <label for="name" class="col-md-4 control-label">Email</label>
      <div class="col-md-8">
        <a href="mailto:<?=$agency->email?>"><?=$agency->email?></a>
      </div>
    </div>
    <div class="form-group" style="clear: both;">
      <label for="name" class="col-md-4 control-label">Phone</label>
      <div class="col-md-8">
        <?=$agency->phone?>
      </div>
    </div>
    <div class="form-group" style="clear: both;">
      <label for="name" class="col-md-4 control-label">Address</label>
      <div class="col-md-8">
        <?=$agency->address?><br />
        <?=$agency->locality?>, <?=$agency->state_province?> <?=$agency->postal_code?>
      </div>
    </div>
    <div style="clear: both;"></div>
  </div>
</div>


{% for user in users %}
{% if loop.first %}
<div class="portlet box red" style="clear: both;">
  <div class="portlet-title">
      <div class="caption">
          <i class="fa fa-user"></i> Employee List </div>
      <div class="tools"> </div>
  </div>
  <div class="portlet-body">
          <!-- Start .panel -->
          <div class="panel-default toggle panelMove panelClose panelRefresh">
            <div class="">
  <table id="basic-datatables" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Profile</th>
    <th>Status</th>
    <th>Location</th>
    <th>Actions</th>
  </tr>
  </thead>
  <tfoot>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Profile</th>
    <th>Status</th>
    <th>Location</th>
    <th>Actions</th>
  </tr>
  </tfoot>
  <tbody>
{% endif %}
    <tr>
      <td>{{ user.name }}</td>
      <td>{{ user.email }}</td>
      <td>{{ user.profile.name }}</td>
      <td>{{ user.active == 'Y' ? 'Active' : 'Archived' }}</td>
      <td style="width: 100% !important;">
      {% if user.profilesId == 1 or user.profilesId == 4 %}
        <div><b>All</b></div>
      {% else %}
        {% for location in user.locations %}
        <div><b>{{ location.name }}</b><br />{{ location.address }}<br />{{ location.locality }}, {{ location.state_province }}, {{ location.postal_code }}</div>
        {% endfor %}
      {% endif %}</td>
      <td style="text-align: right;">
      <div class="actions">
        <div class="btn-group">
          <a data-toggle="dropdown" href="javascript:;" class="btn btn-sm green dropdown-toggle" aria-expanded="false"> Actions <i class="fa fa-angle-down"></i></a>
          <ul class="dropdown-menu pull-right">
            <li><a class="" href="mailto:{{ user.email }}"><i class="icon-envelope-letter"></i> Email</a></li>
            <li><a href="/admin/admindashboard/forgotPassword/<?=$agency_type_id?>/<?=$agency->agency_id?>/{{ user.id }}" onclick="return confirm('Are you sure you want to reset this employee\'s password?');" class=""><i class="icon-user"></i> Password</a></li>
            <li><a href="/admin/admindashboard/confirmation/<?=$agency_type_id?>/<?=$agency->agency_id?>/{{ user.id }}" onclick="return confirm('Are you sure you want to resend this employee\'s confirmation email?');" class=""><i class="icon-envelope"></i> Resend Credentials</a></li>
            <li><a href="/admin/admindashboard/login/<?=$agency_type_id?>/<?=$agency->agency_id?>/{{ user.id }}" onclick="return confirm('Are you sure you want to log in as this employee?');" class=""><i class="icon-paper-plane"></i> Manage</a></li>
          </ul>
        </div>
      </div>
      </td>
    </tr>
{% if loop.last %}
  </tbody>
  </table>
            </div>
          </div>
          <!-- End .panel -->
  </div>
</div>
{% endif %}
{% else %}
    No employees
{% endfor %}