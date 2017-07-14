  @extends('layouts.affiliate')


  @section('leadstats')
  @parent
  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{ $totalClicks }}</h3>

          <p>Clicks</p>
        </div>
        <div class="icon">
          <i class="fa fa-mouse-pointer"></i>
        </div>
        <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{ $totalEnrollments }}</h3>

          <p>Enrollments</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ $totalSales }}</h3>

          <p>Sales</p>
        </div>
        <div class="icon">
          <i class="fa fa-usd"></i>
        </div>
        <!--<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
      </div>
    </div>
  </div>
  <!-- /.row -->

  <!-- per plan stats -->
  <!-- plan name, clicks, enrollments, sales -->

  @endsection 

  @section('leadstable')
  @parent
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tbody><tr>
              <th></th>
              <th>Clicks</th>
              <th>Enrollments</th>
              <th>Sales</th>
            </tr>
            <tr>
              <th>Landing Page</th>
              <td>{{ $landingPageStats['clicks'] }}</td>
              <td>{{ $landingPageStats['enrollments'] }}</td>
              <td>{{ $landingPageStats['sales'] }}</td>
            </tr>
          </tbody></table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tbody><tr>
              <th>Plan</th>
              <th>Clicks</th>
              <th>Enrollments</th>
              <th>Sales</th>
            </tr>
            @foreach ($plans as $plan)
            <tr>
              <td>{{ $plan->name }}</td>
              <td>{{ $plan->clicks }}</td>
              <td>{{ $plan->enrollments }}</td>
              <td>{{ $plan->sales }}</td>
            </tr>
            @endforeach
          </tbody></table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>

  @if (count($leads) > 0)
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Recent Leads</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
          <table class="table table-hover">
            <tbody><tr>
              <th>Plan</th>
              <th>Stage</th>
              <th>Date</th>
            </tr>
            @foreach ($leads as $lead)
            <tr>
              <td>{{ $lead->name }}</td>
              <td><span class="label label-primary"> {{ $lead->stage }} </span></td>
              <td>{{ $lead->created_at }}</td>
            </tr>
            @endforeach
          </tbody></table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
  </div>
  @endif

  @endsection