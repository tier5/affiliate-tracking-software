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
                                <div class=" col-md-4 pull-left">
                                    <h4>Affiliate Details</h4>
                                </div>
                            </div>
                            <div id="loader" style="line-height: 100px; text-align: center; display: none;">
                                <strong>Please Wait</strong> <img alt="activity indicator" src="{{ url('/') }}/images/ajax-loader.gif">
                            </div>
                            <div class="row">
                                @if(\Session::has('error'))
                                    <h4 style="color: red;">{{ \Session::get('error') }}</h4>
                                @endif
                                @if(\Session::has('success'))
                                        <h4 style="color: green;">{{ \Session::get('success') }}</h4>
                                    @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <section class="table-bordered">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Affiliate Information</h4>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-3">
                                        Affiliate Email :
                                    </div>
                                    <div class="col-md-9">
                                        {{ $affiliate->user->email }}
                                    </div>
                                </div>
                                <div style="padding-bottom: 5px;"></div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Affiliate Referring Link :
                                    </div>
                                    <div class="col-md-8">
                                        <span id="url">{{ $affiliate->campaign->sales_url }}?affiliate_id={{ $affiliate->key }}</span>
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-primary btn-sm pull-right" id="copy" class="pull-right" style="cursor:pointer"><i class="fa fa-copy fa-fw"></i> | Copy Link</a>
                                    </div>
                                </div>
                                <div style="padding-bottom: 5px;"></div>
                                {{-- <div class="row">
                                    <div class="col-md-3">
                                        Affiliate Registration Link :
                                    </div>
                                    <div class="col-md-8">
                                        {{ route('affiliate.registerForm',[$affiliate->campaign->key])}}
                                    </div>
                                    <div class="col-md-1">
                                        <a class="btn btn-primary btn-sm pull-right" href="{{route('affiliate.sendEmail',['affiliate' => $affiliate->id])}}" class="pull-right"><i class="fa fa-envelope fa-fw"></i> | Send Email</a>
                                    </div>
                                </div> --}}
                                <div class="row">
                                    <div class="col-md-3">
                                        Affiliate's Commision:
                                    </div>
                                    <div class="col-md-8">
                                        <strong>{{ "$" . number_format($grossCommission, 2, '.', ',') }}</strong>
                                    </div>
                                </div>
                            </section>
                            <div style="padding-bottom: 25px;"></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#allTraffic">All Traffic</a></li>
                                            <li><a data-toggle="tab" href="#leadsOnly">Leads Only</a></li>
                                            <li><a data-toggle="tab" href="#salesOnly">Sales Only</a></li>
                                            <li><a data-toggle="tab" href="#commisonsOnly">Commisions Only</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="allTraffic" class="tab-pane fade in active">
                                                <div class="panel panel-default panel-info">
                                                    <div class="panel-heading">
                                                        <div class="row">
                                                            <div class=" col-md-10 pull-left">
                                                                <h4>All Traffic</h4>
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
                                                                <h4>Leads Only</h4>
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
                                            <div id="salesOnly" class="tab-pane fade">
                                                <div class="panel panel-default panel-info">
                                                    <div class="panel-heading">
                                                        <div class="row">
                                                            <div class=" col-md-10 pull-left">
                                                                <h4>Sales Only</h4>
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
                                            </div>
                                            <div id="commisonsOnly" class="tab-pane fade">
                                                <div class="panel panel-default panel-info">
                                                    <div class="panel-heading">
                                                        <div class="row">
                                                            <div class=" col-md-10 pull-left">
                                                                <h4>Commisions Only</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered datatable">
                                                                <thead>
                                                                <tr>
                                                                    <th>PRODUCT NAME</th>
                                                                    <th>UNIT SOLD</th>
                                                                    <th>SALE PRICE</th>
                                                                    <th>COMMISSION</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @forelse($commisonsOnly as $eachSales)
                                                                    <tr>
                                                                        <td>{{ $eachSales['name'] }}</td>
                                                                        <td>{{ $eachSales['unit_sold'] }}</td>
                                                                        <td>{{ "$" . number_format($eachSales['sale_price'], 2, '.', ',') }}</td>
                                                                        <td>{{ "$" . number_format($eachSales['commission'], 2, '.', ',') }}</td>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
<script>
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

        $('#copy').on('click',function(){
            var url=$('#url');
            copyToClipboard(url);
            toastr.info('Copied To Clipboard');
        });
    });
</script>
{{--<script>--}}
    {{--(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){--}}
            {{--(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),--}}
        {{--m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)--}}
    {{--})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');--}}

    {{--ga('create', 'UA-103810207-1', 'auto');--}}
    {{--ga('send', 'pageview');--}}

{{--</script>--}}
@endsection
