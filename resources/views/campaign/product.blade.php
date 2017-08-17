@extends('layouts.main')

@section('title')
    Campaign Products
@endsection

@section('style')

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
                                    <h4>Campaign Products</h4>
                                </div>
                                @if(isset($campaign) && is_null($campaign->product_type))
                                    <div class=" col-md-2 pull-right">
                                        <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#productChoiceModal">
                                            <i class="fa fa-plus fa-fw"></i>Product Type
                                        </a>
                                    </div>
                                @else
                                    @if ($campaign->product_type == '1' && count($products) == 0)
                                        <div class=" col-md-2 pull-right">
                                            <a class="btn btn-success btn-sm" id="triggerAddProductModal">
                                                <i class="fa fa-plus fa-fw"></i>Add Product
                                            </a>
                                        </div>
                                    @else
                                        <div class=" col-md-2 pull-right">
                                            <a class="btn btn-success btn-sm" id="triggerAddProductModal">
                                                <i class="fa fa-plus fa-fw"></i>Add Product
                                            </a>
                                        </div>
                                    @endif
                                @endif
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
                                            <td>Name</td>
                                            <td>URL</td>
                                            <td>Price</td>
                                            <td>Commission</td>
                                            <td>Frequency</td>
                                            <td>Plan</td>
                                            <td>Actions</td>
                                        </tr>
                                    </thead>
                                    <tbody style="overflow-x: hidden;">
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->url }}</td>
                                                <td>{{ $product->product_price }}</td>
                                                <td>{{ $product->method == 1 ? $product->commission . "%" : "$" . $product->commission }}</td>
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
                                                </td>
                                                <td>
                                                    <div class="row" style="width: 75%; padding: 0 0 0 20px">
                                                        <button class="btn btn-primary btn-xs editProductButton" data-name="{{ $product->name }}" data-id="{{ $product->id }}" ><span class="glyphicon glyphicon-pencil"></span></button>
                                                        <button class="btn btn-danger btn-xs deleteProduct" data-id="{{ $product->id }}" data-title="Delete"><span class="glyphicon glyphicon-trash"></span></button>
                                                    </div>
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
    <!-- Modal Add-Product -->
    <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-11">
                                <h3 class="modal-title" id="exampleModalLabel">Add Product</h3>
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
                    <div class="row">
                        <form id="addProductForm" method="POST" class="form-horizontal col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="product_name">Product Name:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control hide-error" id="product_name" name="product_name" placeholder="Add Product Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="product_url">Product URL:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control hide-error" id="product_url" name="product_url" placeholder="Add Product URL">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="upgrade_url">Upgrade URL:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control hide-error" id="upgrade_url" name="upgrade_url" placeholder="Add Upgrade URL">
                                </div>
                            </div>
<!--                            <div class="form-group">
                                <label class="control-label col-md-2" for="downgrade_url">Downgrade URL:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control hide-error" id="downgrade_url" name="downgrade_url" placeholder="Add Downgrade URL">
                                </div>
                            </div>-->
                            <div class="form-group">
                                <label class="control-label col-md-2" for="product_price">Product Price:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control hide-error" id="product_price" name="product_price" placeholder="Add Product Price">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="product_commission">Commission Settings :</label>
                                <div class="col-md-3">
                                    <label class="control-label">Commission:</label>
                                    <input type="text" name="price" id="product_commission" class="form-control hide-error" placeholder="Add Commission">
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Method:</label>
                                    <select name="method" id="method" class="form-control hide-error">
                                        <option value="">----Select----</option>
                                        <option value="1">Percentage ( % )</option>
                                        <option value="2">Dollar ( $ )</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Frequency:</label>
                                    <select name="price_frequency" class="form-control hide-error" id="price_frequency">
                                        <option value="">-------Select-------</option>
                                        <option value="1">One-Time</option>
                                        <option value="2">Recurring</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group plan" style="display:none">
                                <label class="control-label col-md-3" for="plan">Plan :</label>
                                <input type="radio" name="plan" id="planDaily" class="commissionPlan" value="1">Daily
                                <input type="radio" name="plan" id="planMonthly" class="commissionPlan" value="2">Monthly
                                <input type="radio" name="plan" id="planQuarterly" class="commissionPlan" value="3">Quarterly
                                <input type="radio" name="plan" id="planYearly" class="commissionPlan" value="4">Yearly
                            </div>
                            <input name="campaign_id" id="campaign_id" type="hidden" value="{{ $campaign_id }}">
                            <input name="product_id" id="product_id" type="hidden" value="">
                            <div class="form-group" id="error" style="display: none; color: red;">
                                <label class="control-label col-md-2" for="edit_error">Error:</label>
                                <div class="col-md-10">
                                    <h4 id="error_text"></h4>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="add-Product" data-type="save"><i class="fa fa-floppy-o"></i> Save Product</button>
                    @if(isset($campaign) && $campaign->product_type == 2)
                        <button type="button" class="btn btn-primary" id="add-Funnel" data-type="save_continue"><i class="fa fa-arrow-right"></i> Save &amp; Continue</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Product Choice-->
    <div class="modal fade" id="productChoiceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-11">
                                <h3 class="modal-title" id="exampleModalLabel">Product Type : </h3>
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
                    <div class="row">
                        <form id="productChoiceForm" method="POST" class="form-horizontal col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-4" for="product_type">Choose Product Type : </label>
                                <div class="col-md-8">
                                    <input type="radio" class="product_type" id="product_type_single" name="product_type" value="1"> Single Product
                                    <input type="radio" class="product_type" id="product_type_funnel" name="product_type" value="2"> Funnel
                                </div>
                            </div>
                            <input name="campaign_id" id="campaign_id" type="hidden" value="{{ $campaign_id }}">
                            <div class="form-group" id="error" style="display: none; color: red;">
                                <label class="control-label col-md-2" for="error">Error:</label>
                                <div class="col-md-10">
                                    <h4 id="error_text"></h4>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="product-preference-btn"><i class="fa fa-plus"></i>Add Product Type</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#product-preference-btn').click(function() {
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

            var campaign_id = $('#campaign_id').val();

            $.ajax({
                type: "POST",
                url: '{{ route('choice.product') }}',
                data: {
                 product_type: product_type,
                 campaign_id: campaign_id,
                 _token: '{{ csrf_token() }}'
                },
                statusCode: {
                    201: function(response) {
                        $('#productChoiceModal').modal('hide');
                        swal({
                            title: "Success!",
                            text: response.message,
                            type: "success"
                        }).then( function(){
                            window.location.reload();
                        }, function (dismiss) {
                            window.location.reload();
                        });
                    },
                    404: function(xhr) {
                        $('#error_text').html(xhr.responseJSON.error);
                        $('#error').show();
                    },
                    500: function(xhr) {
                        $('#error_text').html(xhr.responseJSON.error);
                        $('#error').show();
                    }
                }
            });
        });

        $('#triggerAddProductModal').click(function() {
            $('#exampleModalLabel').text('Add Product');
            $('#product_name').val('');
            $('#product_url').val('');
            $('#product_price').val('');
            $('#product_commission').val('');
            $('#method').val('');
            $('#price_frequency').val('');
            $('#upgrade_url').val('');
//            $('#downgrade_url').val('');
            $('.commissionPlan').val('');
            $('.plan').hide();
            $('#error').hide();
            $('#error_text').text('');
            $('.commissionPlan').attr('checked', false);
            $('#add-Product').attr('data-type', 'save');
            $('#add-Product').html('<i class="fa fa-floppy-o"></i> Save Product');
            $('#add-Funnel').attr('data-type', 'save_continue');
            $('#add-Funnel').html('<i class="fa fa-arrow-right"></i> Save & Continue');
            $('#add-Funnel').show();
            $('#addProduct').modal('show');
        });

        $('#price_frequency').change(function(){
            var priceFrequency=$('#price_frequency').val();
            if(priceFrequency == 2) {
                $('.plan').show();
            }else{
                $('.plan').hide();
                $('.commissionPlan').prop('checked',false);
            }
        });

        $('#add-Product, #add-Funnel').click(function() {

            var type = $(this).attr('data-type');

            var name = $('#product_name').val();
            if(name == ''){
                $('#error_text').text('Please Enter A Product Name');
                $('#error').show();
                return false;
            } else {
                $('#error').hide();
            }
            var url = $('#product_url').val();
            if(url == ''){
                $('#error_text').text('Please Enter A Product URL');
                $('#error').show();
                return false;
            } else {
                $('#error').hide();
            }
            if (!(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(url))){
                $('#error_text').text('Please Enter A Valid Product URL');
                $('#error').show();
                return false;
            } else {
                $('#error').hide();
            }
            var upgrade_url = $('#upgrade_url').val();
            if(upgrade_url == ''){
                $('#error_text').text('Please Enter An Upgrade URL');
                $('#error').show();
                return false;
            } else {
                $('#error').hide();
            }
            if (!(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(upgrade_url))){
                $('#error_text').text('Please Enter A Valid Upgrade URL');
                $('#error').show();
                return false;
            } else {
                $('#error').hide();
            }
//            var downgrade_url = $('#downgrade_url').val();
//            if(downgrade_url == ''){
//                $('#error_text').text('Please Enter A Downgrade URL');
//                $('#error').show();
//                return false;
//            } else {
//                $('#error').hide();
//            }
//            if (!(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(downgrade_url))){
//                $('#error_text').text('Please Enter A Valid Downgrade URL');
//                $('#error').show();
//                return false;
//            } else {
//                $('#error').hide();
//            }
            var price = $('#product_price').val();
            if(price == ''){
                $('#error_text').text('Please Enter Enter The Required Product Price');
                $('#error').show();
                return false;
            } else {
                $('#error').hide();
            }
            if(!$.isNumeric(price)){
                $('#error_text').text('Please Enter A Valid Product Price (It should contains digits only)');
                $('#error').show();
                return false;
            } else {
                $('#error').hide();
            }
            var commission = $('#product_commission').val();
            if(commission == ''){
                $('#error_text').text('Please Enter The Required Commission');
                $('#error').show();
                return false;
            } else {
                $('#error').hide();
            }
            if(!$.isNumeric(commission)){
                $('#error_text').text('Please Enter A Valid Commission (It should contains digits only)');
                $('#error').show();
                return false;
            } else {
                $('#error').hide();
            }
            var commissionMethod = $('#method').val();
            if(commissionMethod == ''){
                $('#error_text').text('Please Enter The Required Pricing Method');
                $('#error').show();
                return false;
            }
            var commissionPlan = null;
            var commissionFrequency = $('#price_frequency').val();
            if(commissionFrequency == ''){
                $('#error_text').text('Please Enter The Required Pricing Frequency');
                $('#error').show();
                return false;
            } else {
                $('#error').hide();
            }
            if (commissionFrequency == 2) {
                if ($('#planDaily').is(':checked')) {
                    commissionPlan = $('#planDaily').val();
                } else if ($('#planMonthly').is(':checked')) {
                    commissionPlan = $('#planMonthly').val();
                } else if ($('#planQuarterly').is(':checked')) {
                    commissionPlan = $('#planQuarterly').val();
                } else if ($('#planYearly').is(':checked')) {
                    commissionPlan = $('#planYearly').val();
                }
                if(commissionPlan == null) {
                    $('#error_text').text('Please Enter The Required Pricing Plan');
                    $('#error').show();
                    return false;
                } else {
                    $('#error').hide();
                }
            }
            var campaign_id = $('#campaign_id').val();

            var API_URL;
            var API_METHOD;
            var product_id;
            switch(type) {
                case "save":
                    API_URL = "{{ route('create.product') }}";
                    API_METHOD = "POST";
                    product_id = null;
                    break;
                case "edit":
                    API_URL = "{{ route('edit.product') }}";
                    API_METHOD = "PUT";
                    product_id = $('#product_id').val();
                    break;
                case "save_continue":
                    API_URL = "{{ route('create.product') }}";
                    API_METHOD = "POST";
                    break;
                case "continue_editing":
                    API_URL = "{{ route('edit.product') }}";
                    API_METHOD = "PUT";
                    product_id = $('#product_id').val();
                    break;
                default:
                    API_URL = "{{ route('create.product') }}";
                    API_METHOD = "POST";
                    product_id = null;
            }

            $.ajax({
                url: API_URL,
                type: API_METHOD,
                data: {
                    id: product_id,
                    campaign_id: campaign_id,
                    name: name,
                    url: url,
                    upgrade_url: upgrade_url,
//                    downgrade_url: downgrade_url,
                    product_price: price,
                    commission: commission,
                    commissionMethod: commissionMethod,
                    commissionFrequency: commissionFrequency,
                    commissionPlan: commissionPlan,
                    _token: "{{ csrf_token() }}"
                },
                statusCode: {
                    201: function (response) {
                        $('#addProductForm')[0].reset();
                        if (type == 'save_continue') {
                            swal({
                                title: "Success!",
                                text: response.message,
                                type: "success",
                                allowOutsideClick: false
                            });
                        } else {
                            swal({
                                title: "Success!",
                                text: response.message,
                                type: "success",
                                allowOutsideClick: false
                            }).then(function () {
                                window.location.reload();
                            }, function(dismiss) {
                                window.location.reload();
                            });
                        }
                    },
                    400: function(xhr) {
                        $('#error_text').html(xhr.responseJSON.error);
                        $('#error').show();
                    },
                    404: function(xhr) {
                        $('#error_text').html(xhr.responseJSON.error);
                        $('#error').show();
                    },
                    409: function(xhr) {
                        $('#error_text').html(xhr.responseJSON.error);
                        $('#error').show();
                    },
                    500: function(xhr) {
                        $('#error_text').html(xhr.responseJSON.error);
                        $('#error').show();
                    }
                }
            });
        });

        $('.deleteProduct').click(function() {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('delete.product') }}",
                type: "DELETE",
                data: { id: $(this).data('id'), _token: "{{ csrf_token() }}" },
                statusCode: {
                    204: function(response) {
                        window.location.reload();
                    },
                    404: function(response) {
                        console.log(response.error);
                    },
                    500: function(response) {
                        console.log(response.error);
                    }
                }
            });
        });

        $('.editProductButton').click(function() {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('get.product') }}",
                type: "GET",
                data: { id: $(this).data('id'), _token: "{{ csrf_token() }}" },
                statusCode: {
                    200: function(response) {
                        $('#exampleModalLabel').text('Edit Product');
                        $('#product_name').val(response.product.name);
                        $('#product_url').val(response.product.url);
                        $('#upgrade_url').val(response.product.upgrade_url);
                        $('#downgrade_url').val(response.product.downgrade_url);
                        $('#product_price').val(response.product.product_price);
                        $('#product_commission').val(response.product.commission);
                        $('#method').val(response.product.method);
                        $('#price_frequency').val(response.product.frequency);
                        if (response.product.frequency == 2) {
                            $('input[name=plan][value='+response.product.plan+']').attr('checked', true);
                            $('.plan').show();
                        } else {
                            $('.plan').hide();
                        }
                        $('#product_id').val(response.product.id);
                        $('#error').hide();
                        $('#error_text').text('');
                        $('#add-Product').attr('data-type', 'edit');
                        $('#add-Product').html('<i class="fa fa-floppy-o"></i> Update Product');
                        $('#add-Funnel').attr('data-type', 'continue_editing');
                        $('#add-Funnel').html('<i class="fa fa-arrow-right"></i> Continue Editing');
                        $('#add-Funnel').hide();
                        $('#addProduct').modal('show');
                    },
                    404: function(response) {
                        console.log(response.error);
                    },
                    500: function(response) {
                        console.log(response.error);
                    }
                }
            });
        });

        $('.product_type').on('click',function () {
            $('#error_product_pref').hide();
            $('#error_text_product_pref').text('');
        });

        $('.commissionPlan').on('click',function () {
            $('#error').hide();
            $('#error_text').text('');
        });

        $('.hide-error').on('keyup',function () {
            $('#error').hide();
            $('#error_text').text('');
        });

        $('.hide-error').on('change',function () {
            $('#error').hide();
            $('#error_text').text('');
        });
    </script>
@endsection
