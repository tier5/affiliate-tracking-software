@extends('layouts.main')

@section('title')
    Campaign
@endsection

@section('style')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {display:none;}

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
        .popover{
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1010;
            display: none;
            max-width: 600px;
            padding: 1px;
            text-align: left;
            white-space: normal;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border: 1px solid rgba(0, 0, 0, 0.2);
            -webkit-border-radius: 6px;
            -moz-border-radius: 6px;
            border-radius: 6px;
            -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            -webkit-background-clip: padding-box;
            -moz-background-clip: padding;
            background-clip: padding-box;
        }
        .popover-content {
            padding: 9px 5px !important;
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
                                    <h4>Campaigns</h4>
                                </div>
                                <div class=" col-md-2 pull-right">
                                    <button class="btn btn-success"  data-toggle="modal" data-target="#addCampaign">
                                        Add Campaign
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                @if(\Session::has('error'))
                                    <h4 style="color: red;">{{ \Session::get('error') }}</h4>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-list">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Approve</th>
                                            <th>Order Page Url</th>
                                            <th>Sales Page Url</th>
                                            <th>Registration URL</th>
                                            <th>Add Product</th>
                                            <th>Details</th>
                                            <th>Scripts</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($campaigns) > 0)
                                        @foreach($campaigns as $campaign)
                                            <tr>
                                                <td>{{ $campaign->name }}</td>
                                                <td>{{ $campaign->approval == 1 ? 'Auto' : 'Manual' }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-success copy"><i class="fa fa-copy fa-fw"></i>Copy</button>
                                                    <span class="url" style="display: none;">{{ $campaign->campaign_url }}</span>
                                                    <button class="btn btn-sm btn-warning" data-toggle="popover" data-container="body" data-placement="top" data-content="{{ $campaign->campaign_url }}"><i class="fa fa-info-circle fa-fw"></i>View</button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-success copy"><i class="fa fa-copy fa-fw"></i>Copy</button>
                                                    <span class="url" style="display: none;"> {{ $campaign->sales_url }}</span>
                                                    <button class="btn btn-sm btn-warning" data-toggle="popover" data-container="body" data-placement="top" data-content="{{ $campaign->sales_url }}"><i class="fa fa-info-circle fa-fw"></i>View</button>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-success copy"><i class="fa fa-copy fa-fw"></i>Copy</button>
                                                    <span class="url" style="display: none;">{{ route('affiliate.registerForm',[$campaign->key])}}</span>
                                                    <button class="btn btn-sm btn-warning" data-toggle="popover" data-container="body" data-placement="top" data-content="{{ route('affiliate.registerForm',[$campaign->key])}}"><i class="fa fa-info-circle fa-fw"></i>View</button>
                                                </td>
                                                <td>
                                                    <a href="{{ route('campaign.products',[$campaign->id]) }}" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus"></span></a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('all.affiliate') }}?campaign={{ $campaign->id }}" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-list-alt" title="details"></span></a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('details.campaign',[$campaign->key]) }}" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-tags" title="Scripts"></span></a>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <button class="btn btn-primary btn-xs editCampaignButton" data-campaign_url="{{ $campaign->campaign_url }}" data-sales_url="{{ $campaign->sales_url }}" data-name="{{ $campaign->name }}" data-approve="{{ $campaign->approval }}" data-id="{{ $campaign->id }}" ><span class="glyphicon glyphicon-pencil"></span></button>
                                                        <button class="btn btn-danger btn-xs deleteCampaign" data-id="{{ $campaign->id }}" data-title="Delete"><span class="glyphicon glyphicon-trash"></span></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="5">{{$campaigns->render()}}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5">No Campaign Available</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addCampaign" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create a Campaign</h4>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <form id="addCampaignForm" method="POST" class="form-horizontal col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="campaign_name">Campaign Name:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control hide-error" id="campaign_name" name="campaign_name" placeholder="Enter Campaign Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="sales_url">Sales Page URL:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control hide-error" id="sales_url" name="sales_url" placeholder="Enter Sales URl">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="campaign_url">Order Page URL:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control hide-error" id="campaign_url" name="campaign_url" placeholder="Enter Campaign URl">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="approve">Auto Approve:</label>
                                <div class="col-md-10">
                                    <label class="switch">
                                        <input name="approve" id="approve" value="off" type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="product_type">Product Type:</label>
                                <div class="col-md-10">
                                    <input name="product_type" id="product_type_single" value="1" type="radio" /> Single Product
                                    <input name="product_type" id="product_type_funnel" value="2" type="radio" /> Funnel
                                </div>
                            </div>
                            <div class="form-group" id="error" style="display: none; color: red;">
                                <label class="control-label col-md-2" for="error">Error:</label>
                                <div class="col-md-10">
                                    <h4 id="error_text"></h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-offset-2 col-md-10">
                                    <button type="button" id="create-campaign" class="btn btn-success form-control">Create Campaign</button>
                                </div>
                            </div>
                        </form>
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editCampaignModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-11">
                                <h3 class="modal-title" id="exampleModalLabel">Edit Campaign - <span id="campaignNameShow"></span></h3>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <form id="editCampaignForm" method="POST" class="form-horizontal col-md-12">
                        <div class="form-group">
                            <label class="control-label col-md-2" for="edit_campaign_name">Campaign Name:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control hide-error" id="edit_campaign_name" name="edit_campaign_name" placeholder="Edit Campaign Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" for="edit_sales_url">Sales Page URL:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control hide-error" id="edit_sales_url" name="edit_sales_url" placeholder="Enter Sales URl">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" for="edit_campaign_url">Order Page URL:</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control hide-error" id="edit_campaign_url" name="edit_campaign_url" placeholder="Enter Campaign URl">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2" for="edit_approve">Auto Approve:</label>
                            <div class="col-md-10">
                                <input type="radio" value="2" name="edit_approve" id="edit_approve_off"> No </label>
                                <input type="radio" value="1" name="edit_approve" id="edit_approve_on"> YES</label>
                            </div>
                        </div>
                        <input id="edit_id" type="hidden">
                        <div class="form-group" id="edit_error" style="display: none; color: red;">
                            <label class="control-label col-md-2" for="edit_error">Error:</label>
                            <div class="col-md-10">
                                <h4 id="edit_error_text"></h4>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="edit_campaign">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.editCampaignButton').on('click',function () {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var approve = $(this).data('approve');
            var campaign_url = $(this).data('campaign_url');
            var sales_url = $(this).data('sales_url');
            $('#campaignNameShow').text(name);
            $('#edit_campaign_name').val(name);
            $('#edit_campaign_url').val(campaign_url);
            $('#edit_sales_url').val(sales_url);
            $('#edit_id').val(id);
            if(approve == 1){
                $("#edit_approve_on").prop("checked", true);
            } else {
                $("#edit_approve_off").prop("checked", true);
            }
            $('#editCampaignModal').modal('show');
        });
        $('#edit_campaign').on('click',function () {
            var name = $('#edit_campaign_name').val();
            var id = $('#edit_id').val();
            if(name == ''){
                $('#edit_error_text').text('Please Enter A Campaign Name');
                $('#edit_error').show();
                return false;
            }
            var url = $('#edit_campaign_url').val();
            if(url == ''){
                $('#edit_error_text').text('Please Enter A Campaign URL');
                $('#edit_error').show();
                return false;
            }
            if(!(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(url))){
                $('#edit_error_text').text('Please Enter A Valid Campaign URL');
                $('#edit_error').show();
                return false;
            }
            var salesUrl = $('#edit_sales_url').val();
            if(salesUrl == ''){
                $('#edit_error_text').text('Please Enter A Sales URL');
                $('#edit_error').show();
                return false;
            }
            var status = $("input[name='edit_approve']:checked").val();
            $.ajax({
                url: "{{ route('edit.campaign') }}",
                type: "POST",
                data: {
                    id: id,
                    name: name,
                    sales_url : salesUrl,
                    campaign_url : url,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    if (data.success) {
                        $('#editCampaignModal').modal('hide');
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
        });

        $('.deleteCampaign').on('click',function () {
            var id = $(this).data('id');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url: "{{ route('delete.campaign') }}",
                    type: "POST",
                    data: {
                        id: id,
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
            });
        });
        $('#create-campaign').on('click',function () {
            var name = $('#campaign_name').val();
            if(name == ''){
                $('#error_text').text('Please Enter A Campaign Name');
                $('#error').show();
                return false;
            }
            var url = $('#campaign_url').val();
            if(url == ''){
                $('#error_text').text('Please Enter A Campaign URL');
                $('#error').show();
                return false;
            }
            if(!(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(url))){
                $('#error_text').text('Please Enter A Valid Campaign URL');
                $('#error').show();
                return false;
            }
            var salesUrl = $('#sales_url').val();
            if(salesUrl == ''){
                $('#error_text').text('Please Enter A Sales URL');
                $('#error').show();
                return false;
            }
            if(!(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(salesUrl))){
                $('#error_text').text('Please Enter A Valid Sales URL');
                $('#error').show();
                return false;
            }
            var product_type = '';
            if ($('#product_type_single').is(':checked')){
                product_type = $('#product_type_single').val();
            } else if ($('#product_type_funnel').is(':checked')){
                product_type = $('#product_type_funnel').val();
            }

            if(product_type == ''){
                $('#error_text').text('Please Enter Your Preference');
                $('#error').show();
                return false;
            }
            var approve = $('#approve').val();
            var user_id = "{{ \Auth::user()->id }}";
            var key = randomString(32);
            $.ajax({
                url: "{{ route('create.campaign') }}",
                type: "POST",
                data: {
                    name: name,
                    url: url,
                    sales_url : salesUrl,
                    approve: approve,
                    key: key,
                    user_id: user_id,
                    product_type: product_type,
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    if (data.success) {
                        $('#addCampaign').modal('hide');
                        swal({
                            title: "Success!",
                            text: data.message,
                            type: "success"
                        }).then( function(){
                            $('#addCampaignForm')[0].reset();
                            window.location.reload();
                        },function (dismiss) {
                            $('#addCampaignForm')[0].reset();
                            window.location.reload();
                        });
                    } else {
                        switch(data.status) {
                            case '23000':
                                $('#error_text').text('Campaign name is in use! Please use some different name !!!');
                                $('#error').show();
                                break;
                            case '400':
                                $('#error_text').text('Bad Request');
                                $('#error').show();
                                break;
                            case '401':
                                $('#error_text').text('Unauthorized');
                                $('#error').show();
                                break;
                            case '403':
                                $('#error_text').text('Forbidden');
                                $('#error').show();
                                break;
                            case '404':
                                $('#error_text').text('Not Found');
                                $('#error').show();
                                break;
                            case '500':
                                $('#error_text').text('Internal Server Error');
                                $('#error').show();
                                break;
                            default:
                                $('#error_text').text(data.message);
                                $('#error').show();
                                break;
                        }
                    }
                },
            });
        });
        $('#approve').on('change',function () {
            if($('#approve').val() == 'on'){
                $('#approve').val('off');
            } else {
                $('#approve').val('on')
            }
        })
        $('.hide-error').on('keyup',function () {
            $('#error').hide();
            $('#edit_error').hide();
        });
        function randomString(length) {
            var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'.split('');

            if (! length) {
                length = Math.floor(Math.random() * chars.length);
            }

            var str = '';
            for (var i = 0; i < length; i++) {
                str += chars[Math.floor(Math.random() * chars.length)];
            }
            return str;
        };
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        };
        $(document).delegate('.copy','click',function(){
           var $this = $(this).next('span');
           copyToClipboard($this);
           toastr.info('Copied To Clipboard');
        });
    </script>
@endsection
