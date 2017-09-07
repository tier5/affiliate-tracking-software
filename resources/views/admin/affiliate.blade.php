@extends('layouts.main')

@section('title')
    All affiliate
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
                                    <h4>All affiliate</h4>
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
                                <label class="col-md-2 col-sm-2 col-xs-2">
                                    Filter by Campaign
                                </label>
                                <div class="col-md-4">
                                    <select class="form-control filter" name="affiliate_id">
                                        <option value="0"> Select Campaign</option>
                                        @foreach($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}" {{ (isset($_GET['campaign']) && $_GET['campaign'] > 0 && $_GET['campaign'] == $campaign->id )?'selected':'' }}>{{ $campaign->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-list">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Campaign</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($affiliates as $affiliate)
                                            <tr>
                                                <td>{{ $affiliate->user->name }}</td>
                                                <td>{{ $affiliate->user->email }}</td>
                                                <td>{{ $affiliate->campaign->name }}</td>
                                                <td>
                                                    <a href="{{route('admin.affiliate.login',['affiliate' => $affiliate->id])}}" class="btn btn-success btn-xs" title="Login"><i class="fa fa-sign-in"></i></a>
                                                    <button class="btn btn-info btn-xs showAffiliateDetails" data-aff_id="{{ $affiliate->user_id }}" title="Details"><i class="fa fa-bar-chart"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    $(function () {
        $('.table-list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
        $('.showAffiliateDetails').on('click',function () {
            var affiliate = $(this).data('aff_id');
            window.location.href = "{{ route('all.details.affiliate',['']) }}"+"/"+affiliate;
        });
        $('.filter').on('change',function () {
            var campaign = $(this).val();
            setGetParameter('campaign',campaign);
        });
    });
</script>
@endsection
