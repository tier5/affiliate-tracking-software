@extends('layouts.main')

@section('title')
    Dashboard
@endsection

@section('content')
   @php($queryString= Request::getQueryString() ? Request::getQueryString() : 'campaign_id=0&affiliate_id=0'  )
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <form class="form info-box" method="get" id="form-filter">
                        <div class="info-box-content row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label class="col-md-4 col-sm-4 col-xs-4 col-md-offset-2 col-sm-offset-2 col-xs-offset-2">Filter
                                    by Campaign </label>
                                <div class="col-md-8">
                                    <select class="form-control filter" name="campaign_id">
                                        <option value="0"> Select Campaign</option>
                                        @forelse($campaignsDropdown as $campaign)
                                            <option value="{{ $campaign->id }}" {{ request()->has('campaign_id') ? (request()->input('campaign_id') == $campaign->id ? 'selected' : null) : null }}>{{ $campaign->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label class="col-md-4 col-sm-4 col-xs-4 col-md-offset-2 col-sm-offset-2 col-xs-offset-2">Filter
                                    by Affiliate </label>
                                <div class="col-md-8">
                                    <select class="form-control filter" name="affiliate_id">
                                        <option value="0"> Select Affiliate</option>
                                        @forelse($affiliatesDropdown as $affiliate)
                                            <option value="{{ $affiliate->user_id }}" {{ request()->has('affiliate_id') ? (request()->input('affiliate_id') == $affiliate->user_id ? 'selected' : null) : null }}>{{ $affiliate->user->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- /.info-box-content -->
                    </form>
                    <!-- /.info-box -->
                </div>
                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>
            </div>
            <!-- /.row -->

            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="box1">
                        <div class="row one-row">
                            <div class="col-md-3 ">
                                <div class="spec">
                                    <div class="blue-txt">
                                        @if($visitors->count() > 0)
                                            ${!! number_format(($grossCommission / $visitors->count()),2,'.','') !!}
                                        @else
                                            {{ number_format($grossCommission,2,'.','') }}
                                        @endif
                                    </div>
                                    <div class="normal-txt">EPC</div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row two-row">
                                    <div class="col-md-3 col-sm-3">

                                        <div class="blue-txt">
                                            {{--<a href="{{ route('view.link',['admin',Auth::user()->id,'visitor',$queryString]) }}">--}} {{ $visitors->count() }}{{--</a>--}}
                                        </div>
                                        <div class="normal-txt">Unique Clicks</div>

                                    </div>
                                    <div class="col-md-3 col-sm-3">

                                        <div class="blue-txt">
                                            {{--<a href="{{ route('view.link',['admin',Auth::user()->id,'leads',$queryString]) }}">--}}{{ $leads->count() }}{{--</a>--}}
                                        </div>
                                        <div class="normal-txt">Leads</div>

                                    </div>
                                    <div class="col-md-3 col-sm-3">

                                        <div class="blue-txt">
                                            {{--<a href="{{ route('view.link',['admin',Auth::user()->id,'sales',$queryString]) }}">--}}{{ $sales->count() }}{{--</a>--}}
                                        </div>
                                        <div class="normal-txt">Customers</div>

                                    </div>
                                    <div class="col-md-3 col-sm-3">

                                        <div class="blue-txt">
                                            @if($sales->count() > 0 && $visitors->count() > 0)
                                                {!! round($sales->count() /  $visitors->count() * 100,2) !!}%
                                            @else
                                                0%
                                            @endif
                                        </div>
                                        <div class="normal-txt">Conversion Rate</div>

                                    </div>
                                </div>
                                <div class="row two-row">
                                    {{--<div class="col-md-3 col-sm-3">

                                        <div class="blue-txt">
                                            ${{ round($grossCommission) }}
                                        </div>
                                        <div class="normal-txt">Gross Commission</div>

                                    </div>--}}
                                    <div class="col-md-3 col-sm-3">

                                        <div class="blue-txt">${{ round($grossCommission - $refundCommission) }}</div>
                                        <div class="normal-txt">Net Commission</div>

                                    </div>
                                    <div class="col-md-3 col-sm-3">

                                        <div class="blue-txt">
                                            {{ $newSalesCount }}
                                        </div>
                                        <div class="normal-txt">Sales</div>

                                    </div>
                                    <div class="col-md-3 col-sm-3">

                                        <div class="blue-txt">
                                            {{--<a href="{{ route('view.link',['admin',Auth::user()->id,'refund',$queryString]) }}">--}}{{ $refundCount }}{{--</a>--}}
                                        </div>
                                        <div class="normal-txt">Refunds</div>

                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <div class="blue-txt">${{ round($refundCommission) }}</div>
                                        <div class="normal-txt">Refund Amount</div>

                                    </div>
                                </div>
                                <div class="row two-row">
                                    <div class="col-md-3 col-sm-3">

                                        <div class="blue-txt">
                                            ${{ round($totalPaid) }}
                                        </div>
                                        <div class="normal-txt">Commission Paid</div>

                                    </div>
                                    <div class="col-md-3 col-sm-3">

                                        <div class="blue-txt">${{ round($grossCommission - $refundCommission - $totalPaid) }}</div>
                                        <div class="normal-txt">Commission Due</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Monthly Recap Report</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-box-tool dropdown-toggle"
                                            data-toggle="dropdown">
                                        <i class="fa fa-wrench"></i></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-center">
                                        <strong>Visitor log of last 6 months</strong>
                                    </p>

                                    <div class="chart">
                                        <!-- Sales Chart Canvas -->
                                        <canvas id="salesChart" style="height: 180px;"></canvas>
                                    </div>
                                    <!-- /.chart-responsive -->
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-8">
                    <!-- MAP & BOX PANE -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Visitors IP Report</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="pad">
                                        <!-- Map will be created here -->
                                        <div id="world-map-markers" style="height: 325px;"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- USERS LIST -->
                            <div class="box">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Latest Affiliates</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                    class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                    class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table id="user" class="table table-bordered table-hover">
                                        <thead>
                                        <td>Name</td>
                                        <td>Email</td>
                                        <td>Campaign</td>
                                        <td>Joining date</td>
                                        </thead>
                                        <tbody>
                                        @if(count($latestAffiliates) > 0)
                                            @foreach($latestAffiliates as $affiliateUser)
                                                <tr>
                                                    <td>{{ $affiliateUser->user->name }}</td>
                                                    <td>{{ $affiliateUser->user->email }}</td>
                                                    <td>{{ $affiliateUser->campaign->name }}</td>
                                                    <td>{{ date('d-m-Y',strtotime($affiliateUser->created_at)) }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <!-- /.users-list -->
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!--/.box -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- TABLE: LATEST ORDERS -->
                {{--<div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Latest Orders</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Item</th>
                                    <th>Status</th>
                                    <th>Popularity</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                    <td>Call of Duty IV</td>
                                    <td><span class="label label-success">Shipped</span></td>
                                    <td>
                                        <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR1848</a></td>
                                    <td>Samsung Smart TV</td>
                                    <td><span class="label label-warning">Pending</span></td>
                                    <td>
                                        <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                                    <td>iPhone 6 Plus</td>
                                    <td><span class="label label-danger">Delivered</span></td>
                                    <td>
                                        <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                                    <td>Samsung Smart TV</td>
                                    <td><span class="label label-info">Processing</span></td>
                                    <td>
                                        <div class="sparkbar" data-color="#00c0ef" data-height="20">90,80,-90,70,-61,83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR1848</a></td>
                                    <td>Samsung Smart TV</td>
                                    <td><span class="label label-warning">Pending</span></td>
                                    <td>
                                        <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                                    <td>iPhone 6 Plus</td>
                                    <td><span class="label label-danger">Delivered</span></td>
                                    <td>
                                        <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                                    <td>Call of Duty IV</td>
                                    <td><span class="label label-success">Shipped</span></td>
                                    <td>
                                        <div class="sparkbar" data-color="#00a65a" data-height="20">90,80,90,-70,61,-83,63</div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix">
                        <a href="javascript:void(0)" class="btn btn-sm btn-info btn-flat pull-left">Place New Order</a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
                    </div>
                    <!-- /.box-footer -->
                </div>--}}
                <!-- /.box -->
                </div>
                <!-- /.col -->

                <div class="col-md-4">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Browser Usage</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="chart-responsive">
                                        <canvas id="pieChart" height="150"></canvas>
                                    </div>
                                    <!-- ./chart-responsive -->
                                </div>
                                <!-- /.col -->
                                <div class="col-md-4">
                                    <ul class="chart-legend clearfix">
                                        <li><i class="fa fa-circle-o text-red"></i> Chrome</li>
                                        <li><i class="fa fa-circle-o text-green"></i> IE</li>
                                        <li><i class="fa fa-circle-o text-yellow"></i> FireFox</li>
                                        <li><i class="fa fa-circle-o text-aqua"></i> Safari</li>
                                        <li><i class="fa fa-circle-o text-light-blue"></i> Opera</li>
                                    </ul>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.footer -->
                    </div>
                    <!-- /.box -->

                    <!-- PRODUCT LIST -->
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Recently Added Products</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <ul class="products-list product-list-in-box">
                                @if(count($products) > 0)
                                    @foreach($products as $product)
                                        <li class="item">
                                            <div class="product-img">
                                                <img src="{{ url('/') }}/admin/dist/img/default-50x50.gif"
                                                     alt="Product Image">
                                            </div>
                                            <div class="product-info">
                                                <a href="javascript:void(0)" class="product-title">{{ $product->name }}
                                                    <span class="label label-warning pull-right">{{ ($product->method == 2?'$':'')}}{{  $product->commission }}{{ ($product->method == 1?'%':'')}}</span></a>
                                                <span class="product-description">{{ ($product->frequency == 1?'  One-time  ':'  Recurring  ')}}
                                                    @if($product->plan == 1)
                                                        ------  Daily
                                                    @elseif($product->plan == 2)
                                                        ------  Monthly
                                                    @elseif($product->plan == 3)
                                                        ------  Quaterly
                                                    @elseif($product->plan == 4)
                                                        ------  Yearly
                                                    @endif
                                                </span>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    <li class="item">
                                        <div class="product-img">
                                            <img src="{{ url('/') }}/admin/dist/img/default-50x50.gif"
                                                 alt="Product Image">
                                        </div>
                                        <div class="product-info">
                                            <span class="product-description">Product not available.</span>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        <!-- /.box-body -->
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
    </div>
@endsection

@section('script')
    <script src="{{ url('/') }}/admin/dist/js/pages/dashboard2.js"></script>
        
    <script type="text/javascript"
            src="https://maps.google.com/maps/api/js?key=AIzaSyC2mKnuJAEplo1wkuweW0MSnaAs-zSyD_Y"></script>
        
    <script type="text/javascript" src="{{ url('/') }}/js/ipmapper.js"></script>
        
    <script type="text/javascript">
        $(window).bind("load", function() {
            var id = '{{ \Auth::user()->id }}';
            IPMapper.initializeMap("world-map-markers");
            $.ajax({
                url: "{{ route('ip.information') }}",
                type: "POST",
                data: {
                    id: id,
                    campaign_id: "{{ request()->input('campaign_id') }}",
                    affiliate_id: "{{ request()->input('affiliate_id') }}",
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    if (data.success) {
                        var ipArray = data.ipAddress;
                        var ipArrayLength = ipArray.length;
                        var deduction = 10;
                        var deductedArray = ipArrayLength - deduction;
                        IPMapper.addIPArray(ipArray.splice(deductedArray));
                        plotFunction(ipArray, deduction, deductedArray);
                    }
                }
            });
        });

        function plotFunction(ipArray, deduction, deductedArray){
            console.log(deductedArray);
            var counter = 0;
            setInterval(function() {
                counter++;
                var newDeductedArray = deductedArray - (deduction*counter);
                //console.log(counter);
                console.log(newDeductedArray);
                if( newDeductedArray !=0 ) {
                    IPMapper.addIPArray(ipArray.splice(newDeductedArray));
                    //console.log("Message after every 5 seconds");
                }
            }, 5000);
        }

        $(function () {
            $('#user').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });
        $(function () {
            'use strict';
            try {
                // Month Chart
                var id = '{{ \Auth::user()->id }}';
                $.ajax({
                    url: "{{ route('data.sales') }}",
                    type: "POST",
                    data: {
                        id: id,
                        campaign_id: "{{ request()->input('campaign_id') }}",
                        affiliate_id: "{{ request()->input('affiliate_id') }}",
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        if (data.success) {
                            // Get context with jQuery - using jQuery's .get() method.
                            var salesChartCanvas = $("#salesChart").get(0).getContext("2d");
                            // This will get the first returned node in the jQuery collection.
                            var salesChart = new Chart(salesChartCanvas);
                            var salesChartData = {
                                labels: data.months,
                                datasets: [
                                    {
                                        label: "Visitors",
                                        fillColor: "rgba(191, 191, 191, 1)",
                                        strokeColor: "rgba(196, 194, 194, 1)",
                                        pointColor: "rgba(191, 191, 191, 1)",
                                        pointStrokeColor: "rgba(10,101,198,10)",
                                        pointHighlightFill: "#fff",
                                        pointHighlightStroke: "rgba(0,0,0,0.8)",
                                        data: data.visitors
                                    },
                                    {
                                        label: "Leads",
                                        fillColor: "rgba(60,141,188,0.4)",
                                        strokeColor: "rgba(196,194,194,1)",
                                        pointColor: "rgba(60,141,188,0.4)",
                                        pointStrokeColor: "rgba(1,141,188,1)",
                                        pointHighlightFill: "#fff",
                                        pointHighlightStroke: "rgba(60,141,188,1)",
                                        data: data.leads
                                    },
                                    /*{
                                        label: "Customers",
                                        fillColor: "rgb(210, 214, 222)",
                                        strokeColor: "rgb(210, 214, 222)",
                                        pointColor: "rgb(210, 214, 222)",
                                        pointStrokeColor: "#c1c7d1",
                                        pointHighlightFill: "#fff",
                                        pointHighlightStroke: "rgb(220,220,220)",
                                        data: data.sales
                                    },*/
                                    {
                                        label: "Sales",
                                        fillColor: "rgba(236, 240, 245, 0.6)",
                                        strokeColor: "rgba(236, 240, 245, 1)",
                                        pointColor: "rgba(236, 240, 245, 0.6)",
                                        pointStrokeColor: "rgba(10,101,198,10)",
                                        pointHighlightFill: "#fff",
                                        pointHighlightStroke: "rgba(50,111,138,0.8)",
                                        data: data.sales
                                    }
                                ]
                            };
                            var salesChartOptions = {
                                //Boolean - If we should show the scale at all
                                showScale: true,
                                //Boolean - Whether grid lines are shown across the chart
                                scaleShowGridLines: false,
                                //String - Colour of the grid lines
                                scaleGridLineColor: "rgba(0,0,0,.05)",
                                //Number - Width of the grid lines
                                scaleGridLineWidth: 1,
                                //Boolean - Whether to show horizontal lines (except X axis)
                                scaleShowHorizontalLines: true,
                                //Boolean - Whether to show vertical lines (except Y axis)
                                scaleShowVerticalLines: true,
                                //Boolean - Whether the line is curved between points
                                bezierCurve: true,
                                //Number - Tension of the bezier curve between points
                                bezierCurveTension: 0.3,
                                //Boolean - Whether to show a dot for each point
                                pointDot: false,
                                //Number - Radius of each point dot in pixels
                                pointDotRadius: 4,
                                //Number - Pixel width of point dot stroke
                                pointDotStrokeWidth: 1,
                                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                                pointHitDetectionRadius: 20,
                                //Boolean - Whether to show a stroke for datasets
                                datasetStroke: true,
                                //Number - Pixel width of dataset stroke
                                datasetStrokeWidth: 2,
                                //Boolean - Whether to fill the dataset with a color
                                datasetFill: true,
                                //String - A legend template
                                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                                maintainAspectRatio: true,
                                //Boolean - whether to make the chart responsive to window resizing
                                responsive: true
                            };
                            salesChart.Line(salesChartData, salesChartOptions);
                        } else {
                            swal({
                                title: "Error!",
                                text: data.message,
                                type: "error"
                            });
                        }
                    }
                });

                // Browser Chart
                //-------------
                //- PIE CHART -
                //-------------
                // Get context with jQuery - using jQuery's .get() method.
                var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
                var pieChart = new Chart(pieChartCanvas);
                var PieData = [
                    {
                        value: '{{ $chrome }}',
                        color: "#f56954",
                        highlight: "#f56954",
                        label: "Chrome"
                    },
                    {
                        value: '{{ $ie }}',
                        color: "#00a65a",
                        highlight: "#00a65a",
                        label: "IE"
                    },
                    {
                        value: '{{ $firefox }}',
                        color: "#f39c12",
                        highlight: "#f39c12",
                        label: "FireFox"
                    },
                    {
                        value: '{{ $safari }}',
                        color: "#00c0ef",
                        highlight: "#00c0ef",
                        label: "Safari"
                    },
                    {
                        value: '{{ $opera }}',
                        color: "#3c8dbc",
                        highlight: "#3c8dbc",
                        label: "Opera"
                    }
                ];
                var pieOptions = {
                    //Boolean - Whether we should show a stroke on each segment
                    segmentShowStroke: true,
                    //String - The colour of each segment stroke
                    segmentStrokeColor: "#fff",
                    //Number - The width of each segment stroke
                    segmentStrokeWidth: 1,
                    //Number - The percentage of the chart that we cut out of the middle
                    percentageInnerCutout: 50, // This is 0 for Pie charts
                    //Number - Amount of animation steps
                    animationSteps: 100,
                    //String - Animation easing effect
                    animationEasing: "easeOutBounce",
                    //Boolean - Whether we animate the rotation of the Doughnut
                    animateRotate: true,
                    //Boolean - Whether we animate scaling the Doughnut from the centre
                    animateScale: false,
                    //Boolean - whether to make the chart responsive to window resizing
                    responsive: true,
                    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                    maintainAspectRatio: false,
                    //String - A legend template
                };
                //Create pie or douhnut chart
                // You can switch between pie and douhnut using the method below.
                pieChart.Doughnut(PieData, pieOptions);
                //-----------------
                //- END PIE CHART -
                //-----------------
            } catch (e) {
                console.log(e)
            }
        });

        $('.filter').change(function () {
            $('#form-filter').submit();
        });
    </script>
@endsection
