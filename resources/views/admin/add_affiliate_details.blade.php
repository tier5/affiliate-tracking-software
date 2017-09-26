@extends('layouts.main')

@section('title')
    Affiliate Details
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class=" col-md-10 pull-left">
                                    <h4>Affiliate Name: {{ $affiliateUser->name }} (
                                        <small>{{  $affiliateUser->email }}</small>
                                        )
                                    </h4>
                                </div>
                            </div>
                            <div class="row">
                                @if(\Session::has('error'))
                                    <h4 style="color: red;">{{ \Session::get('error') }}</h4>
                                @endif
                            </div>
                        </div>
                        <div style="padding-bottom: 10px;"></div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="info-box" style="min-height: 47px !important;">
                                    <div class="info-box-content">
                                        <div class="col-md-2">
                                            Filter by Campaign
                                        </div>
                                        <div class="col-md-6">
                                            <select name="campaign" class="form-control filter">
                                                <option value="0">Select Campaign</option>
                                                @forelse($campaignDropDown as $campaign)
                                                    <option value="{{ $campaign->id }}" {{ request()->has('campaign') ? (request()->input('campaign') == $campaign->id ? 'selected' : null) : null }}>{{ $campaign->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="box1">
                                            <div class="row one-row">
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">
                                                        @if($visitors > 0)
                                                            ${!! number_format(($gross_commission /  $visitors),2,'.','') !!}
                                                        @else
                                                            {{ number_format($visitors,2,'.','') }}
                                                        @endif
                                                    </div>
                                                    <div class="normal-txt">EPC</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt"> {{ $visitors }}</div>
                                                    <div class="normal-txt">Unique Clicks</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt"> {{ $sales }}</div>
                                                    <div class="normal-txt">Customers</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">
                                                        @if($visitors > 0 & $totalSales >0)
                                                            {!! round($totalSales / $visitors*100,2) !!}%
                                                        @else
                                                            0%
                                                        @endif
                                                    </div>
                                                    <div class="normal-txt">Conversion Rate</div>
                                                </div>
                                            </div>
                                            <div class="row one-row">
                                                {{--<div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">{{ "$" . round($gross_commission) }}</div>
                                                    <div class="normal-txt">Gross Commission</div>
                                                </div>--}}
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">
                                                        ${{ round($gross_commission-$refundCommission) }}</div>
                                                    <div class="normal-txt">Net Commission</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">{{ $newSalesCount }}</div>
                                                    <div class="normal-txt">Sales</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">{{ $refundCount }}</div>
                                                    <div class="normal-txt">Refunds</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">${{ round($refundCommission) }}</div>
                                                    <div class="normal-txt">Refunds Amount</div>
                                                </div>
                                            </div>
                                            <div class="row one-row">
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">{{ "$" . round($paidCommission) }}</div>
                                                    <div class="normal-txt">Commission Paid</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">
                                                        ${{ round($gross_commission - $refundCommission - $paidCommission) }}</div>
                                                    <div class="normal-txt">Commission Due</div>
                                                </div>
                                                @if(count($affiliate) > 0)
                                                    @if(isset($_GET['campaign']) && $_GET['campaign'] > 0)
                                                        <div class="col-md-3 col-sm-3" style="padding-top: 16px;">
                                                            <button class="btn btn-info" id="pay_commission"
                                                                    data-affiliate="{{ $affiliate[0]->user_id }}"
                                                                    data-campaign="{{ $_GET['campaign'] }}"
                                                                    data-commission="{{ $netCommission - $paidCommission }}"
                                                                    data-toggle="modal">Pay Commission
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--<div class="row">
                            <div class="col-md-12">
                                <!-- USERS LIST -->
                                <div class="box box-success">
                                    <div class="box-header with-border">
                                        <h2 class="box-title">Commission Details</h2>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-3">
                                                    <h2>Total Commission: ${{ $netCommission }}</h2>
                                                </div>
                                                <div class="col-md-3">
                                                    <h2>Commission Paid: ${{ $paidCommission }}</h2>
                                                </div>
                                                <div class="col-md-3">
                                                    <h2>Commission Due:
                                                        ${{ round($netCommission - $paidCommission,2) }}</h2>
                                                </div>
                                                @if(count($affiliate) > 0)
                                                    @if(isset($_GET['campaign']) && $_GET['campaign'] > 0)
                                                        <div class="col-md-3" style="padding-top: 16px;">
                                                            <button class="btn btn-success" id="pay_commission"
                                                                    data-affiliate="{{ $affiliate[0]->user_id }}"
                                                                    data-campaign="{{ $_GET['campaign'] }}"
                                                                    data-commission="{{ $netCommission - $paidCommission }}"
                                                                    data-toggle="modal">Pay Commission
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <!-- /.users-list -->
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!--/.box -->
                            </div>
                            <!-- /.col -->
                        </div>--}}
                        <div class="row">
                            <div class="col-md-12">
                                <!-- USERS LIST -->
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h2 class="box-title">Affiliate Link</h2>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <div class="row">
                                            @foreach($affiliate as $value)
                                                <div class="col-md-12">
                                                    <div class="col-md-2">
                                                        {{ $value->campaign->name }}
                                                    </div>
                                                    <div class="col-md-8">
                                                        <span class="url"> {{ ($value->campaign->sales_url != '')?$value->campaign->sales_url:$value->campaign->campaign_url }}?affiliate_id={{ $value->key }}</span>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-warning btn-sm copy">Copy</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- /.users-list -->
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!--/.box -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- USERS LIST -->
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h2 class="box-title">Activities</h2>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <div class="col-md-12 col-sm-12">
                                            <ul class="nav nav-tabs">
                                                <li class="active"><a data-toggle="tab" href="#allTraffic">Incoming Traffics</a></li>
                                                <li><a data-toggle="tab" href="#leadsOnly">Leads</a></li>
                                                <li><a data-toggle="tab" href="#commisonsOnly">Sales</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div id="allTraffic" class="tab-pane fade in active">
                                                    <div class="panel panel-default panel-info">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class=" col-md-10 pull-left">
                                                                    <h4>All incoming traffics</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered datatable">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>WHO</th>
                                                                        <th>BROWSER</th>
                                                                        <th>PLATFORM</th>
                                                                        <th>TYPE</th>
                                                                        <th>FIRST SEEN</th>
                                                                        <th>LAST VISIT</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @forelse($allTraffic as $eachTraffic)
                                                                        <tr>
                                                                            <td>{{ (isset($eachTraffic->email) && !is_null($eachTraffic->email)) ? $eachTraffic->email : (isset($eachTraffic->ip) && !is_null($eachTraffic->ip) ?  $eachTraffic->ip : '')  }}</td>
                                                                            <td>{{ (isset($eachTraffic->browser) && !is_null($eachTraffic->browser)) ? $eachTraffic->browser : ''  }}</td>
                                                                            <td>{{ (isset($eachTraffic->os) && !is_null($eachTraffic->os)) ? $eachTraffic->os : ''  }}</td>
                                                                            @if(isset($eachTraffic->type) && !is_null($eachTraffic->type) && $eachTraffic->type == 1)
                                                                                <td>{{ 'Visitor' }}</td>
                                                                            @elseif(isset($eachTraffic->type) && !is_null($eachTraffic->type) && $eachTraffic->type == 2)
                                                                                <td>{{ 'Sales' }}</td>
                                                                            @elseif((isset($eachTraffic->type) && !is_null($eachTraffic->type) && $eachTraffic->type == 3))
                                                                                <td>{{'Leads'}}</td>
                                                                            @endif
                                                                            <td>{{ (isset($eachTraffic->created_at) && !is_null($eachTraffic->created_at)) ?  date('l jS \of F Y,  h:i:s A',strtotime($eachTraffic->created_at)) : '' }}</td>
                                                                            <td>{{ (isset($eachTraffic->updated_at) && !is_null($eachTraffic->updated_at)) ?  date('l jS \of F Y,  h:i:s A',strtotime($eachTraffic->updated_at)) : '' }}</td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="6">There Are No Activities</td>
                                                                        </tr>
                                                                    @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="leadsOnly" class="tab-pane fade">
                                                    <div class="panel panel-default panel-info">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class=" col-md-10 pull-left">
                                                                    <h4>Leads only traffics</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered datatable">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>WHO</th>
                                                                        <th>BROWSER</th>
                                                                        <th>PLATFORM</th>
                                                                        <th>TYPE</th>
                                                                        <th>FIRST SEEN</th>
                                                                        <th>LAST VISIT</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @forelse($leadsOnly as $eachLeads)
                                                                        <tr>
                                                                            <td>{{ (isset($eachLeads->email) && !is_null($eachLeads->email)) ? $eachLeads->email : ((isset($eachLeads->ip) && !is_null($eachLeads->ip)) ?  $eachLeads->ip : '')  }}</td>
                                                                            <td>{{ (isset($eachLeads->browser) && !is_null($eachLeads->browser)) ? $eachLeads->browser : ''  }}</td>
                                                                            <td>{{ (isset($eachLeads->os) && !is_null($eachLeads->os)) ? $eachLeads->os : ''  }}</td>
                                                                            @if(isset($eachLeads->type) && !is_null($eachLeads->type) && $eachLeads->type == 1)
                                                                                <td>{{ 'Visitor' }}</td>
                                                                            @elseif(isset($eachLeads->type) && !is_null($eachLeads->type) && $eachLeads->type == 2)
                                                                                <td>{{ 'Sales' }}</td>
                                                                            @elseif((isset($eachLeads->type) && !is_null($eachLeads->type) && $eachLeads->type == 3))
                                                                                <td>{{'Leads'}}</td>
                                                                            @endif
                                                                            <td>{{ (isset($eachLeads->created_at) && !is_null($eachLeads->created_at)) ?  date('l jS \of F Y,  h:i:s A',strtotime($eachLeads->created_at)) : '' }}</td>
                                                                            <td>{{ (isset($eachLeads->updated_at) && !is_null($eachLeads->updated_at)) ?  date('l jS \of F Y,  h:i:s A',strtotime($eachLeads->updated_at)) : '' }}</td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="6">There Are No Activities</td>
                                                                        </tr>
                                                                    @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{--<div id="salesOnly" class="tab-pane fade">
                                                    <div class="panel panel-default panel-info">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class=" col-md-10 pull-left">
                                                                    <h4>Sales only traffics</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered datatable">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>WHO</th>
                                                                        <th>BROWSER</th>
                                                                        <th>PLATFORM</th>
                                                                        <th>TYPE</th>
                                                                        <th>FIRST SEEN</th>
                                                                        <th>LAST VISIT</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @forelse($salesOnly as $eachSales)
                                                                        <tr>
                                                                            <td>{{ (isset($eachSales->email) && !is_null($eachSales->email)) ? $eachSales->email : ((isset($eachSales->ip) && !is_null($eachSales->ip)) ?  $eachSales->ip : '')  }}</td>
                                                                            <td>{{ (isset($eachSales->browser) && !is_null($eachSales->browser)) ? $eachSales->browser : ''  }}</td>
                                                                            <td>{{ (isset($eachSales->os) && !is_null($eachSales->os)) ? $eachSales->os : ''  }}</td>
                                                                            @if(isset($eachSales->type) && !is_null($eachSales->type) && $eachSales->type == 1)
                                                                                <td>{{ 'Visitor' }}</td>
                                                                            @elseif(isset($eachSales->type) && !is_null($eachSales->type) && $eachSales->type == 2)
                                                                                <td>{{ 'Sales' }}</td>
                                                                            @elseif((isset($eachSales->type) && !is_null($eachSales->type) && $eachSales->type == 3))
                                                                                <td>{{'Leads'}}</td>
                                                                            @endif
                                                                            <td>{{ (isset($eachSales->created_at) && !is_null($eachSales->created_at)) ?  date('l jS \of F Y,  h:i:s A',strtotime($eachSales->created_at)) : '' }}</td>
                                                                            <td>{{ (isset($eachSales->updated_at) && !is_null($eachSales->updated_at)) ?  date('l jS \of F Y,  h:i:s A',strtotime($eachSales->updated_at)) : '' }}</td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="6">There Are No Activities</td>
                                                                        </tr>
                                                                    @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>--}}
                                                <div id="commisonsOnly" class="tab-pane fade">
                                                    <div class="panel panel-default panel-info">
                                                        <div class="panel-heading">
                                                            <div class="row">
                                                                <div class=" col-md-10 pull-left">
                                                                    <h4>Commissions only traffics</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered datatable">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>CAMPAIGN</th>
                                                                        <th>EMAIL</th>
                                                                        <th>PRODUCT NAME</th>
                                                                        <th>PRODUCT PRICE</th>
                                                                        <th>SALE PRICE</th>
                                                                        <th>COMMISSION</th>
                                                                        <th>STATUS</th>
                                                                        <th>DATE</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @forelse($commisonsOnly as $eachSales)
                                                                        <tr>
                                                                            <td>{{ $eachSales['campaign'] }}</td>
                                                                            <td>{{ $eachSales['saleEmail'] != ''?$eachSales['saleEmail']:$eachSales['email'] }}</td>
                                                                            <td>{{ $eachSales['name'] }}</td>
                                                                            <td>{{ "$" . number_format($eachSales['product_price'], 2, '.', ',') }}</td>
                                                                            <td>{{ "$" . number_format($eachSales['total_sale_price'], 2, '.', ',') }}</td>
                                                                            <td>{{ "$" . number_format($eachSales['my_commission'], 2, '.', ',') }}</td>
                                                                            <td>{!! $eachSales['type'] == 'Refunded'? 'Refunded' : $eachSales['status'] !!}</td>
                                                                            <td>{{ (isset($eachSales['date'])) ?  date('l jS \of F Y,  h:i:s A',strtotime($eachSales['date'])) : '' }}</td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-warning btn-xs refund" {{ $eachSales['type'] == 'Refunded'?'disabled':'' }} data-sales_id="{{ $eachSales['id'] }}">Refund</button>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="4">There Are No Activities</td>
                                                                        </tr>
                                                                    @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!--/.box -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Pay Commission</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="money" class="control-label col-md-4">Commission amount: </label>
                                    <div class="col-md-8">
                                        <input type="text" id="money" class="form-control">
                                    </div>
                                </div>
                                <input type="hidden" id="commission_pay">
                                <input type="hidden" id="affiliate_pay">
                                <input type="hidden" id="campaign_pay">
                                <div class="form-group" id='errorData' style="display: none; color: red;">
                                    <label for="error" class="control-label col-md-4">Error: </label>
                                    <div class="col-md-8">
                                        <h4>
                                            <div id="error"></div>
                                        </h4>
                                    </div>
                                </div>
                                <button type="button" id="final_pay" class="btn btn-success form-control">Pay</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('/') }}/admin/dist/js/pages/dashboard2.js"></script>
        
    <script type="text/javascript">
        $(document).ready(function () {
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

            $('.copy').on('click', function () {
                var url = $(this).parent().prev().children('.url');
                copyToClipboard(url);
                toastr.info('Copied To Clipboard');
            });
            $('.filter').on('change', function () {
                var campaign = $(this).val();
                setGetParameter('campaign', campaign);
            });
            $('#pay_commission').on('click', function () {
                var affiliate = $(this).data('affiliate');
                var campaign = $(this).data('campaign');
                var commission = $(this).data('commission');
                $('#money').val(commission);
                $('#campaign_pay').val(campaign);
                $('#affiliate_pay').val(affiliate);
                $('#commission_pay').val(commission);
                $('#errorData').hide();
                $('#myModal').modal('show');
            });
            $('#final_pay').on('click', function () {
                var campaign = $('#campaign_pay').val();
                var affiliate = $('#affiliate_pay').val();
                var commission = parseFloat($('#commission_pay').val());
                var acctualCommission = parseFloat($('#money').val());
                if (acctualCommission > commission) {
                    $('#error').text('Commission should not be exceeding  $' + commission);
                    $('#errorData').show();
                    return false;
                }

                if(acctualCommission <= 0){
                    $('#error').text('Please enter some commission amount');
                    $('#errorData').show();
                    return false;
                }
                var reg = /^-?\d*\.?\d*$/;

                if(!reg.test(acctualCommission)){
                    $('#error').text('Please enter some commission amount');
                    $('#errorData').show();
                    return false;
                }
                swal({
                    title: 'Are you sure?',
                    text: "you wish to mark these commissions as paid?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, pay!'
                }).then(function () {
                    $.ajax({
                        url: "{{ route('pay.commission') }}",
                        type: "POST",
                        data: {
                            commission: acctualCommission,
                            affiliate: affiliate,
                            campaign: campaign,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            if (data.success) {
                                swal({
                                    title: "Success!",
                                    text: data.message,
                                    type: "success"
                                }).then(function () {
                                    window.location.reload();
                                }, function (dismiss) {
                                    window.location.reload();
                                });
                            } else {
                                swal({
                                    title: "Error!",
                                    text: data.message,
                                    type: "error"
                                });
                            }
                        }
                    });
                })
            });
            $('#money').on('keyup', function () {
                $('#errorData').hide();
            });
            $('.refund').on('click',function () {
                var order_id =  $(this).data('sales_id');
                swal({
                    title: 'Are you sure?',
                    text: "You want to refund this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Refund it!'
                }).then(function () {
                    $.ajax({
                        url: "{{ route('sale.refund') }}",
                        type: "POST",
                        data: {
                            id: order_id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            if (data.success) {
                                swal({
                                    title: "Success!",
                                    text: data.message,
                                    type: "success"
                                }).then( function(){
                                    window.location.reload();
                                },function (dismiss) {
                                    window.location.reload();
                                });
                            } else {
                                swal({
                                    title: "Error!",
                                    text: data.message,
                                    type: "error"
                                });
                            }
                        }
                    });
                })
            })
        });
    </script>
@endsection
