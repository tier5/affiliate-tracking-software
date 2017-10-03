@extends('layouts.admin')
@section('main')
@parent
    <!-- Main content -->
    <section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Cancellable Accounts</h3>

          <div class="box-tools">
            <div class="input-group input-group-sm" style="width: 150px;">
              <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

              <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tbody><tr>
              <th>Name</th>
           	  <th>Email</th>
              <th>Type (Agency/Business)</th>
              <th>Stripe In DB</th>
              <th>Active Subscription</th>
              <th>Created At</th>
              <th>Updated At</th>
              <th>Last Billing Cycle</th>
            </tr>
            @foreach ($subscriptions as $subscription)
            <tr>
              <td>{{ $subscription->name }}</td>
              <td>{{ $subscription->email }}</td>
              <td><span class="label label-primary">{{ $subscription->type }}</span></td>
              <td>{{ $subscription->stripe_exists_in_db }}</td>
              <td>{{ $subscription->stripe_subscription_exists }}</td>
              <td>{{ $subscription->created_at }}</td>
            </tr>
            @endforeach
          </tbody></table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>

    </section>
    <!-- /.content -->
@endsection