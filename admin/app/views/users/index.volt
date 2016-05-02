{{ content() }}

<ul class="pager">
    <li class="pull-right">
      <a class="btn red btn-outline" href="/admin/users/<?=($profilesId==3?'':'admin')?>create">Create <?=($profilesId==3?'Employee':'Admin User')?></a>
    </li>
</ul>
{% for user in page.items %}
{% if loop.first %}
<div class="portlet box red">
  <div class="portlet-title">
      <div class="caption">
          <i class="fa fa-user"></i> <?=($profilesId==3?'Employee':'Admin User')?> List </div>
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
    <th>Type</th>
    <th>Location</th>
    <th></th>
    <th></th>
  </tr>
  </thead>
  <tfoot>
  <tr>
    <th>Name</th>
    <th>Email</th>
    <th>Type</th>
    <th>Location</th>
    <th></th>
    <th></th>
  </tr>
  </tfoot>
  <tbody>
{% endif %}
    <tr>
      <td>{{ user.name }}</td>
      <td>{{ user.email }}</td>
      {% if user.profilesId == 1 or user.profilesId == 4 %}
      <td>Admin</td>
      {% else %}
      <td>Employee</td>
      {% endif %}
      <td>
      {% if user.profilesId == 1 or user.profilesId == 4 %}
        <div><b>All</b></div>
      {% else %}
        {% for location in user.locations %}
        <div><b>{{ location.name }}</b><br />{{ location.address }}<br />{{ location.locality }}, {{ location.state_province }}, {{ location.postal_code }}</div>
        {% endfor %}
      {% endif %}</td>
      <td><a class="btn" href="/admin/users/<?=($profilesId==3?'':'admin')?>edit/{{user.id}}"><i class="icon-pencil"></i></a></td>
      <td><a href="/admin/users/<?=($profilesId==3?'':'admin')?>delete/{{user.id}}" onclick="return confirm('Are you sure you want to delete this item?');" class="btn" style="color: Red;"><i class="icon-close"></i></a></td>
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
    No <?=($profilesId==3?'Employees':'Admin Users')?>
{% endfor %}
