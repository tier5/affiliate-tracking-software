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
                                <div class=" col-md-2 pull-right">
                                    <button class="btn btn-success" id="triggerAddProductModal">
                                        Add Product
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
                                            <td>Product Name</td>
                                            <td>Commission</td>
                                            <td>Method</td>
                                            <td>Frequency</td>
                                            <td>Plan</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{!! isset($product->name) ? $product->name : '' !!}</td>
                                            <td>{!! isset($product->commission) ? $product->commission : '' !!}</td>
                                        </tr>
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
                                <label class="control-label col-md-2" for="product_pricing">Commission Settings :</label>
                                <div class="col-md-3">
                                    <label class="control-label">Commission:</label>
                                    <input type="text" name="price" id="product_pricing" class="form-control" placeholder="Add Commission">
                                </div>
                                <div class="col-md-3">
                                    <label class="control-label">Method:</label>
                                    <select name="method" id="method" class="form-control">
                                        <option value="">----Select----</option>
                                        <option value="1">Percentage ( % )</option>
                                        <option value="2">Dollar ( $ )</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Frequency:</label>
                                    <select name="price_frequency" class="form-control" id="price_frequency">
                                        <option value="">-------Select-------</option>
                                        <option value="1">One-Time</option>
                                        <option value="2">Recurring</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group plan" style="display:none">
                                <label class="control-label col-md-3" for="plan">Plan :</label>
                                    <input type="radio" name="plan" id="planDaily" class="pricingPlan" value="1">Daily
                                    <input type="radio" name="plan" id="planMonthly" class="pricingPlan" value="2">Monthly
                                    <input type="radio" name="plan" id="planQuarterly" class="pricingPlan" value="3">Quarterly
                                    <input type="radio" name="plan" id="planYearly" class="pricingPlan" value="4">Yearly
                            </div>
                            <input name="campaign_id" id="campaign_id" type="hidden" value="{{ $campaign_id }}">
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
                    <button type="button" class="btn btn-primary" id="add-Product">Add Product</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>

    $('#triggerAddProductModal').click(function(){
        $('#product_name').val('');
        $('#product_pricing').val('');
        $('#method').val('');
        $('#price_frequency').val('');
        $('.pricingPlan').val('');
        $('.plan').hide();
        $('#addProduct').modal('show');
    });

    $('#price_frequency').change(function(){
        var priceFrequency=$('#price_frequency').val();
        if(priceFrequency == 2) {
            $('.plan').show();
        }else{
            $('.plan').hide();
        }
    });

    $('#add-Product').click(function(){

        var name = $('#product_name').val();
        if(name == ''){
            $('#error_text').text('Please Enter A Product Name');
            $('#error').show();
            return false;
        }
        var pricing = $('#product_pricing').val();
        if(pricing == ''){
            $('#error_text').text('Please Enter The Required Commission');
            $('#error').show();
            return false;
        }
        if(!$.isNumeric(pricing)){
            $('#error_text').text('Please Enter A Valid Commission');
            $('#error').show();
            return false;
        }
        var pricingMethod = $('#method').val();
        if(pricingMethod == ''){
            $('#error_text').text('Please Enter The Required Pricing Method');
            $('#error').show();
            return false;
        }
        var pricingFrequency = $('#price_frequency').val();
        if(pricingFrequency == ''){
            $('#error_text').text('Please Enter The Required Pricing Frequency');
            $('#error').show();
            return false;
        }
        if(pricingFrequency == 2){
            var pricingPlan = $('.pricingPlan').val();
            if(pricingPlan == '') {
                $('#error_text').text('Please Enter The Required Pricing Plan');
                $('#error').show();
                return false;
            }
        }else{
            var pricingPlan=null;
        }
        var campaign_id = $('#campaign_id').val();
        $.ajax({
            url: "{{ route('create.product') }}",
            type: "POST",
            data: {
                campaign_id: campaign_id,
                name: name,
                pricing: pricing,
                pricingMethod: pricingMethod,
                pricingFrequency: pricingFrequency,
                pricingPlan: pricingPlan,
                _token: "{{ csrf_token() }}"
            },
            success: function (data) {
                if (data.success) {
                    $('#add-Product').modal('hide');
                    swal({
                        title: "Success!",
                        text: data.message,
                        type: "success"
                    }).then( function(){
                        $('#addProductForm')[0].reset();
                        window.location.reload();
                    },function (dismiss) {
                        $('#addProductForm')[0].reset();
                        window.location.reload();
                    });
                } else {
                    switch(data.status) {
                        case '23000':
                            $('#error_text').text('Duplicate Data Entry.');
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

    $('.hide-error').on('keyup',function () {
        $('#error').hide();
        $('#error_text').hide();
    });
</script>
@endsection
