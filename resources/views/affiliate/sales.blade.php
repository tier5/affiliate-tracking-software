@extends('layouts.main')

@section('title')
    Affiliate sales
@endsection

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.1/css/buttons.dataTables.min.css">
    <style>
        a.dt-button.redColor {
            background-color: #4CAF50 !important; /* Green */
            border: none;
            color: white !important;
            padding: 7px 40px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 18px;
            margin: 4px 2px;
            -webkit-transition-duration: 0.4s; /* Safari */
            transition-duration: 0.4s;
            cursor: pointer;
        }
        a.dt-button.redColor {
            background-color: black !important;
            color: black !important;
            border: 2px solid blue;
        }

        a.dt-button.redColor:hover {
            background-color: black !important;
            color: blue !important;
        }
        div.dt-buttons{
            position:relative;
            float:left;
        }
    </style>
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
                                    <h4>All Sales</h4>
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
                                    <select class="form-control filterCampaign" name="campaign_id">
                                        <option value="0"> Select Campaign</option>
                                        @foreach($campaignDropDown as $campaign)
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
                                        <th>Email</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Commission</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sales as $sale)
                                            <tr>
                                                <td>{{ ($sale['saleEmail'] != '')?$sale['saleEmail']:$sale['email'] }}</td>
                                                <td>{{ $sale['name'] }}</td>
                                                <td>${{ $sale['total_sale_price'] }}</td>
                                                <td>${{ $sale['my_commission'] }}</td>
                                                <td>
                                                    {{ ($sale['status']==2)?'Refunded':'sales' }}
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
    <script src="https://cdn.datatables.net/buttons/1.4.1/js/dataTables.buttons.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.4.1/js/buttons.html5.min.js"></script>
    <script>
        $(function () {
            $('.table-list').DataTable({
                "dom": 'Bfrtip',
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "buttons": [
                    {
                        extend: 'excel',
                        text: 'Export' ,
                        className: 'redColor'
                    }
                ]
            });
            $('.filterCampaign').on('change',function () {
                var campaign = $(this).val();
                setGetParameter('campaign',campaign);
            });
        });
    </script>
@endsection
