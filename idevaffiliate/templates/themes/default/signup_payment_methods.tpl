{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$signup_commission_title}</h4></div></div>
<div class="portlet-body">

{if (isset($select_multiple_methods))}
<div class="form-group">
<label class="col-md-3 control-label">{$signup_commission_howtopay}<span style="color:#CC0000;">*</span></label>
<div class="col-md-6">              
<select name="payment_method" class="form-control" id="payment_method">
{$select_available_payment_methods} 
</select>
<span class="help-block">{$payment_method_description}</span>
</div>
</div>

<!-- paypal settings -->
<div class="form-group payment_method" id="paypal_settings">
    <label class="col-md-3 control-label">{$paypal_email} <span style="color:#CC0000;">*</span></label>
    <div class="col-md-6">              
     <input type="text" class="form-control" name="pp_account" value="{$pp_account}" />
    </div>
</div>


<!-- stripe settings -->
<div class="form-group payment_method" id="stripe_settings" >
    <label class="col-md-3 control-label">{$stripe_acct_details}</label>
    <div class="col-md-6">
        <h5>{$stripe_connect}</h5>
    </div>     
</div>


{else}  
{$payment_method_description}
{/if}

</div>
</div>
</div>
</div>
{literal}
<script type="text/javascript">
jQuery(function($){
    function changePaymentMethod() {
        $('.payment_method').hide();
        $('span.payment_description').hide();
        var val = $('#payment_method').val();
        $('span.method_' + val).show();
        if(val == 1) {
                //paypal is selected
                $('#paypal_settings').show();
        }
        else if(val == 2) {
                //stripe selected
                $('#stripe_settings').show();
        }
    }

    changePaymentMethod(); 

    $('body').on('change', '#payment_method', function() {
        changePaymentMethod();	
    });
});
</script>
{/literal}        