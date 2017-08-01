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
                                        Affiliate Link :
                                    </div>
                                    <div class="col-md-6">
                                        <span id="url">{{ $affiliate->campaign->url }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <a id="copy" class="pull-right" style="cursor:pointer"><i class="fa fa-copy fa-fw"></i>Copy Link</a>
                                    </div>
                                </div>
                                <div style="padding-bottom: 5px;"></div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Affiliate Registration Link :
                                    </div>
                                    <div class="col-md-6">
                                        {{ route('affiliate.registerForm',[$affiliate->campaign->key])}}
                                    </div>
                                    <div class="col-md-3">
                                        <a href="#" class="pull-right">Send Email</a>
                                    </div>
                                </div>
                                <div style="padding-bottom: 5px;"></div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Affiliate Email :
                                    </div>
                                    <div class="col-md-9">
                                        {{ $affiliate->user->email }}
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
                                                            <table class="table table-bordered">
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
                                                                        <td>{{ (isset($eachTraffic->created_at) && !is_null($eachTraffic->created_at)) ?  $eachTraffic->created_at->toFormattedDateString().' at '.$eachTraffic->created_at->toTimeString() : '' }}</td>
                                                                        <td>{{ (isset($eachTraffic->updated_at) && !is_null($eachTraffic->updated_at)) ?  $eachTraffic->created_at->toDateTimeString().' ( '.$eachTraffic->updated_at->diffForHumans().' )' : '' }}</td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="6"> There Are No Activities</td>
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
                                                                <h4>All Traffic</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
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
                                                                        <td>{{ (isset($eachLeads->created_at) && !is_null($eachLeads->created_at)) ?  $eachLeads->created_at->toFormattedDateString().' at '.$eachTraffic->created_at->toTimeString() : '' }}</td>
                                                                        <td>{{ (isset($eachLeads->updated_at) && !is_null($eachLeads->updated_at)) ?  $eachLeads->updated_at->toDateTimeString().' ( '.$eachLeads->updated_at->diffForHumans().' )' : '' }}</td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="6"> There Are No Activities</td>
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
    function copyToClipboard(element) {
//        var $temp = $("<input>");
//        $("body").append($temp);
//        $temp.val($(element).text()).select();
        document.execCommand("copy");
       // $temp.remove();
    };

    $('#copy').on('click',function(){
        var url=$('#url').text();
        copyToClipboard(url);
        toastr.info('Copied To Clipboard');
    });

</script>
@endsection