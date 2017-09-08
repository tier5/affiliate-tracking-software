@extends('layouts.main')

@section('title')
    Admin Details
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
             <!-- Info boxes -->
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <form class="form info-box" method="get" id="form-filter">
                        <div class="info-box-content row">
                        @if(isset($userType) && $userType == 'admin')
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label class="col-md-4 col-sm-4 col-xs-4 col-md-offset-2 col-sm-offset-2 col-xs-offset-2">Filter
                                    by Campaign </label>
                                <div class="col-md-8">
                                    <select class="form-control filterCampaign" name="campaign_id">
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
                                    <select class="form-control filterAffiliate" name="affiliate_id">
                                        <option value="0"> Select Affiliate</option>
                                        @forelse($affiliatesDropdown as $affiliate)
                                            <option value="{{ $affiliate->user_id }}" {{ request()->has('affiliate_id') ? (request()->input('affiliate_id') == $affiliate->user_id ? 'selected' : null) : null }}>{{ $affiliate->user->name }}</option>
                                        @empty
                                        @endforelse 
                                    </select>
                                </div>
                            </div>
                            @elseif(isset($userType) && $userType == 'affiliate')
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <label class="col-md-4 col-sm-4 col-xs-4 col-md-offset-2 col-sm-offset-2 col-xs-offset-2">Filter
                                    by Campaign </label>
                                <div class="col-md-8">
                                    <select class="form-control filterCampaignAffiliate" name="campaign_id">
                                        <option value="0"> Select Campaign</option>
                                        @forelse($campaignsDropdown as $campaign)
                                            <option value="{{ $campaign->id }}" {{ request()->has('campaign') ? (request()->input('campaign') == $campaign->id ? 'selected' : null) : null }}>{{ $campaign->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>
                        <!-- /.info-box-content -->
                    </form>
                    <!-- /.info-box -->
                </div>
                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class=" col-md-4 pull-left">
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
                            <div style="padding-bottom: 25px;"></div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
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
                                                                        @if(isset($linkName) && $linkName == 'refund')
                                                                            <td>{{'Refunded'}}</td>
                                                                        @elseif(isset($eachTraffic->type) && !is_null($eachTraffic->type) && $eachTraffic->type == 1)
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

        $('.filterAffiliate').on('change',function () {
            var affiliate = $(this).val();
            setGetParameter('affiliate_id',affiliate);
        });
        $('.filterCampaign').on('change',function () {
            var campaign = $(this).val();
            setGetParameter('campaign_id',campaign);
        });
        $('.filterCampaignAffiliate').on('change',function () {
            var campaign = $(this).val();
            setGetParameter('campaign',campaign);
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
