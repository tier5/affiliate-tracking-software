<form id="addProductForm1" method="POST" class="form-horizontal col-md-12">
    <div class="form-group">
        <label class="control-label col-md-2" for="product_name1">Product Name:</label>
        <div class="col-md-10">
            <select id="product_name1" class="form-control hide-error">
                <option value="">Select a Product</option>
                @foreach($products as $product)
                    <option value="{{ $product['name'] }}" data-price="{{ $product['amount'] }}">{{ $product['name'] }}</option>
                @endforeach
            </select>
{{--
            <input type="text" class="form-control hide-error" id="product_name1" name="product_name1" placeholder="Add Product Name">
--}}
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2" for="product_url1">Product URL:</label>
        <div class="col-md-10">
            <input type="text" class="form-control hide-error" id="product_url1" name="product_url1" placeholder="Add Product URL">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2" for="upgrade_url1">Upgrade URL:</label>
        <div class="col-md-10">
            <input type="text" class="form-control hide-error" id="upgrade_url1" name="upgrade_url1" placeholder="Add Upgrade URL">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2" for="product_price1">Product Price:</label>
        <div class="col-md-10">
            <input type="text" readonly="readonly" class="form-control hide-error" id="product_price1" name="product_price1" placeholder="Add Product Price">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2" for="upgrade_commission1">Upgrade Commission:</label>
        <div class="col-md-10">
            <input type="text" class="form-control hide-error" id="upgrade_commission1" name="upgrade_commission1" placeholder="Add Upgrade Commission">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2" for="product_commission1">Commission Settings :</label>
        <div class="col-md-3">
            <label class="control-label">Commission:</label>
            <input type="text" name="price1" id="product_commission1" class="form-control hide-error" placeholder="Add Commission">
        </div>
        <div class="col-md-3">
            <label class="control-label">Method:</label>
            <select name="method" id="method1" class="form-control hide-error">
                <option value="">----Select----</option>
                <option value="1">Percentage ( % )</option>
                <option value="2">Dollar ( $ )</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="control-label">Frequency:</label>
            <select name="price_frequency1" class="form-control hide-error" id="price_frequency1">
                <option value="">-------Select-------</option>
                <option value="1">One-Time</option>
                <option value="2">Recurring</option>
            </select>
        </div>
    </div>
    <div class="form-group plan1" style="display:none">
        <label class="control-label col-md-3" for="plan1">Plan :</label>
        <input type="radio" name="plan" id="planDaily1" class="commissionPlan1" value="1">Daily
        <input type="radio" name="plan" id="planMonthly1" class="commissionPlan1" value="2">Monthly
        <input type="radio" name="plan" id="planQuarterly1" class="commissionPlan1" value="3">Quarterly
        <input type="radio" name="plan" id="planYearly1" class="commissionPlan1" value="4">Yearly
    </div>
    <input name="campaign_id1" id="campaign_id1" type="hidden" value="{{ $campaign->id }}">
    <input name="product_id1" id="product_id1" type="hidden" value="">
    <div class="form-group" id="error1" style="display: none; color: red;">
        <label class="control-label col-md-2" for="edit_error1">Error:</label>
        <div class="col-md-10">
            <h4 id="error_text1"></h4>
        </div>
    </div>
    <div class="form-group">
        <div class="pull-right pad-class">
        <button type="button" class="btn btn-primary" id="add-Product1" data-type="save"><i class="fa fa-floppy-o"></i> Save Product</button>
        @if(isset($campaign) && $campaign->product_type == 2)
            <button type="button" class="btn btn-primary" id="add-Funnel1" data-type="save_continue"><i class="fa fa-arrow-right"></i> Save &amp; Continue</button>
        @endif
        </div>
    </div>
</form>

<script>
    $('#product_name1').on('change',function () {
        var price = $(this).find(':selected').data('price');
        $('#product_price1').val(price/100);
    });

    $('#add-Product1, #add-Funnel1').click(function() {
        var type = $(this).attr('data-type');

        var name = $('#product_name1').val();
        if(name == ''){
            $('#error_text1').text('Please Enter A Product Name');
            $('#error1').show();
            return false;
        } else {
            $('#error1').hide();
        }
        var url = $('#product_url1').val();
        if(url == ''){
            $('#error_text1').text('Please Enter A Product URL');
            $('#error1').show();
            return false;
        } else {
            $('#error1').hide();
        }
        if (!(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(url))){
            $('#error_text1').text('Please Enter A Valid Product URL');
            $('#error1').show();
            return false;
        } else {
            $('#error1').hide();
        }
        var upgrade_url = $('#upgrade_url1').val();
        if(upgrade_url == ''){
            $('#error_text1').text('Please Enter An Upgrade URL');
            $('#error1').show();
            return false;
        } else {
            $('#error1').hide();
        }
        if (!(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(upgrade_url))){
            $('#error_text1').text('Please Enter A Valid Upgrade URL');
            $('#error1').show();
            return false;
        } else {
            $('#error1').hide();
        }
        var price = $('#product_price1').val();
        if(price == ''){
            $('#error_text1').text('Please Enter Enter The Required Product Price');
            $('#error1').show();
            return false;
        } else {
            $('#error1').hide();
        }
        if(!$.isNumeric(price)){
            $('#error_text1').text('Please Enter A Valid Product Price (It should contains digits only)');
            $('#error1').show();
            return false;
        } else {
            $('#error1').hide();
        }
        var commission = $('#product_commission1').val();
        if(commission == ''){
            $('#error_text1').text('Please Enter The Required Commission');
            $('#error1').show();
            return false;
        } else {
            $('#error1').hide();
        }
        if(!$.isNumeric(commission)){
            $('#error_text1').text('Please Enter A Valid Commission (It should contains digits only)');
            $('#error1').show();
            return false;
        } else {
            $('#error1').hide();
        }
        var commissionMethod = $('#method1').val();
        if(commissionMethod == ''){
            $('#error_text1').text('Please Enter The Required Pricing Method');
            $('#error1').show();
            return false;
        }
        var commissionPlan = null;
        var commissionFrequency = $('#price_frequency1').val();
        if(commissionFrequency == ''){
            $('#error_text1').text('Please Enter The Required Pricing Frequency');
            $('#error1').show();
            return false;
        } else {
            $('#error1').hide();
        }
        var upgrade_commission = $('#upgrade_commission1').val();
        if(upgrade_commission == ''){
            $('#error_text1').text('Please Enter The Required Upgrade Commission');
            $('#error1').show();
            return false;
        }
        /*if (commissionFrequency == 2) {
            if ($('#planDaily1').is(':checked')) {
                commissionPlan = $('#planDaily1').val();
            } else if ($('#planMonthly1').is(':checked')) {
                commissionPlan = $('#planMonthly1').val();
            } else if ($('#planQuarterly1').is(':checked')) {
                commissionPlan = $('#planQuarterly1').val();
            } else if ($('#planYearly1').is(':checked')) {
                commissionPlan = $('#planYearly1').val();
            }
            if(commissionPlan == null) {
                $('#error_text1').text('Please Enter The Required Pricing Plan');
                $('#error1').show();
                return false;
            } else {
                $('#error1').hide();
            }
        }*/
        var campaign_id = $('#campaign_id1').val();

        var API_URL;
        var API_METHOD;
        var product_id;
        switch(type) {
            case "save":
                API_URL = "{{ route('create.product') }}";
                API_METHOD = "POST";
                product_id = null;
                break;
            case "save_continue":
                API_URL = "{{ route('create.product') }}";
                API_METHOD = "POST";
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
                product_price: price,
                commission: commission,
                commissionMethod: commissionMethod,
                commissionFrequency: commissionFrequency,
                commissionPlan: commissionPlan,
                upgradeCommission: upgrade_commission,
                _token: "{{ csrf_token() }}"
            },
            statusCode: {
                201: function (response) {
                    $('#addProductForm1')[0].reset();
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
                    $('#error_text1').html(xhr.responseJSON.error);
                    $('#error1').show();
                },
                404: function(xhr) {
                    $('#error_text1').html(xhr.responseJSON.error);
                    $('#error1').show();
                },
                409: function(xhr) {
                    $('#error_text1').html(xhr.responseJSON.error);
                    $('#error1').show();
                },
                500: function(xhr) {
                    $('#error_text1').html(xhr.responseJSON.error);
                    $('#error1').show();
                }
            }
        });
    });
    $('#price_frequency1').change(function(){
        var priceFrequency=$('#price_frequency1').val();
        if(priceFrequency == 2) {
            //$('.plan1').show();
        }else{
            //$('.plan1').hide();
            $('.commissionPlan1').prop('checked',false);
        }
    });
</script>