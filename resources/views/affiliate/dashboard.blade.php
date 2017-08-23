@extends('layouts.main')

@section('title')
    Dashboard
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="min-height: 480px; margin-left: 0px;">
        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="ion ion-ios-gear"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Campaigns</span>
                            <span class="info-box-number">{{ $campaigns }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="ios ion-ios-world"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Visitors</span>
                            <span class="info-box-number">{{ $visitors }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-gray"><i class="ion ion-ios-people"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Leads</span>
                            <span class="info-box-number">{{ $leads }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>
                <!-- /.col -->
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-cart"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Sales</span>
                            <span class="info-box-number">{{ $totalSales }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                {{-- <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="ion ion-cash"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Sale Price</span>
                            <span class="info-box-number">{{ "$" . number_format($total_sale_price, 2, '.', ',') }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div> --}}
                <!-- /.col -->
                <div class="col-md-2 col-sm-4 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="ion ion-social-usd"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Gross Commission</span>
                            <span class="info-box-number">{{ "$" . number_format($gross_commission, 2, '.', ',') }}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-8">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Monthly Recap Report</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-wrench"></i></button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">Action</a></li>
                                        <li><a href="#">Another action</a></li>
                                        <li><a href="#">Something else here</a></li>
                                        <li class="divider"></li>
                                        <li><a href="#">Separated link</a></li>
                                    </ul>
                                </div>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
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
                <div class="col-md-4">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Your Referral Link</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <div class="clearfix">&nbsp;</div>
                            @foreach ($affiliate as $key => $value)
                                <div class="col-md-10 col-md-offset-1">
                                    <span class="url">{{ $value->campaign->sales_url }}?affiliate_id={{ $value->key }}</span>
                                </div>
                                <div class="col-md-1">
                                    <a class="btn btn-primary btn-sm pull-right copy pull-right" style="cursor:pointer"><i class="fa fa-copy fa-fw"></i> Copy Link</a>
                                </div>
                                <br />
                                <div class="clearfix">&nbsp;</div>
                            @endforeach
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-8">
                    <!-- /.box -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- USERS LIST -->
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Commissions on available products</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table id="available-product" class="table table-bordered table-hover datatable">
                                        <thead>
                                            <td>Product Name</td>
                                            <!-- <td>Product URL</td> -->
                                            <td>Price</td>
                                            <td>Commission</td>
                                            <td>Frequency</td>
                                            <td>Method</td>
                                        </thead>
                                        <tbody>
                                            @foreach($available_products as $product)
                                                <tr>
                                                    <td>{{ $product->name }}</td>
                                                   {{-- <td>{{ $product->url }}</td> --}}
                                                    <td>{{ "$" . number_format($product->product_price, 2, '.', ',') }}</td>
                                                    <td>{{ $product->method == 1 ? $product->commission  . "%" : "$" . number_format($product->commission, 2, '.', ',') }}</td>
                                                    <td>{{ $product->frequency == 1 ? "One-Time" : "Reccurring" }}</td>
                                                    <td>
                                                    @php
                                                        switch($product->plan) {
                                                            case 1:
                                                                @endphp
                                                                {{ "Daily" }}
                                                                @php
                                                                break;
                                                            case 2:
                                                                @endphp
                                                                {{ "Monthly" }}
                                                                @php
                                                                break;
                                                            case 3:
                                                                @endphp
                                                                {{ "Quarterly" }}
                                                                @php
                                                                break;
                                                            case 4:
                                                                @endphp
                                                                {{ "Yearly" }}
                                                                @php
                                                                break;
                                                            default:
                                                                @endphp
                                                                {{ "NA" }}
                                                                @php
                                                        }
                                                    @endphp
                                                </tr>
                                            @endforeach
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
                </div>
                <!-- Right col -->
                <div class="col-md-4">
                    <!-- /.box -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- USERS LIST -->
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Commissions on sold products</h3>

                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body no-padding">
                                    <table id="sold-product" class="table table-bordered table-hover datatable">
                                        <thead>
                                            <td>Product Name</td>
                                            <td>Unit Sold</td>
                                            <td>Sale Price</td>
                                            <td>My Commission</td>
                                        </thead>
                                        <tbody>
                                            @foreach($sold_products as $product)
                                                <tr>
                                                    <td>{{ $product['name'] }}</td>
                                                    <td>{{ $product['unit_sold'] }}</td>
                                                    <td>{{ "$" . number_format($product['total_sale_price'], 2, '.', ',') }}</td>
                                                    <td>{{ "$" . number_format($product['my_commission'], 2, '.', ',') }}</td>
                                                </tr>
                                            @endforeach
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
                </div>
            </div>
            <!-- /.row -->
        </section>
    </div>
@endsection

@section('script')
    <script src="{{ url('/') }}/admin/dist/js/pages/dashboard2.js"></script>
    <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyC2mKnuJAEplo1wkuweW0MSnaAs-zSyD_Y"></script>
    <script type="text/javascript" src="http://lab.abhinayrathore.com/ipmapper/ipmapper.js"></script>
    <script type="text/javascript">
        $(function(){
            'use strict';
            try{
                // Month Chart
                var id = '{{ \Auth::user()->id }}';
                $.ajax({
                    url: "{{ route('data.sales') }}",
                    type: "POST",
                    data: {
                        id: id,
                        user_type: 'affiliate',
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
                                        label: "Sales",
                                        fillColor: "rgb(210, 214, 222)",
                                        strokeColor: "rgb(210, 214, 222)",
                                        pointColor: "rgb(210, 214, 222)",
                                        pointStrokeColor: "#c1c7d1",
                                        pointHighlightFill: "#fff",
                                        pointHighlightStroke: "rgb(220,220,220)",
                                        data: data.sales
                                    },
                                    {
                                        label: "Leads",
                                        fillColor: "rgba(60,141,188,0.4)",
                                        strokeColor: "rgba(60,141,188,1)",
                                        pointColor: "#3b8bba",
                                        pointStrokeColor: "rgba(60,141,188,1)",
                                        pointHighlightFill: "#fff",
                                        pointHighlightStroke: "rgba(60,141,188,1)",
                                        data: data.leads
                                    },
                                    {
                                        label: "Visitors",
                                        fillColor: "rgba(90, 231, 218, 0.3)",
                                        strokeColor: "rgba(90, 231, 218, 1)",
                                        pointColor: "rgba(50,111,138,0.8)",
                                        pointStrokeColor: "rgba(10,101,198,10)",
                                        pointHighlightFill: "#fff",
                                        pointHighlightStroke: "rgba(50,111,138,0.8)",
                                        data: data.visitors
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
            } catch(e){
                console.log(e)
            }
        });
        $(document).ready(function() {
            $.fn.dataTable.ext.errMode = 'none';
            $('.datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": true,
                "autoWidth": false
            });

            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).text()).select();
                document.execCommand("copy");
                $temp.remove();
            };

            $('.copy').on('click',function(){
                var url=$(this).parent().prev().children('.url');
                copyToClipboard(url);
                toastr.info('Copied To Clipboard');
            });
        });
    </script>
@endsection
