{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<div class="page-header title" style="background:{$heading_back};">
<h1 style="color:{$heading_text};">{$payment_settings}</h1>
</div>

{if isset($display_edit_errors)}
<div class="alert alert-danger"><h4>{$error_title}</h4>{$error_list}
</div>
{/if}

{if isset($edit_success)}
<div class="alert alert-success">{$edit_success}
</div>
{/if}

<form method="POST" action="account.php" class="form-horizontal" id="account_edit_form">
<input type="hidden" name="edit_payment" value="1">
<input type="hidden" name="page" value="48">
<input type="hidden" name="commission_payment" value="1">

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:{$portlet_1};">
<div class="portlet-heading" style="background:{$portlet_1};"><div class="portlet-title" style="color:{$portlet_1_text};"><h4>{$signup_commission_title}</h4></div></div>
<div class="portlet-body">

{if (isset($select_multiple_methods))}
<div class="form-group">
<label class="col-md-3 control-label">{$signup_commission_howtopay}</label>
<div class="col-md-6">              
<select name="pay_method" class="form-control" id="payment_method">
{$select_available_payment_methods} 
</select>
<span class="help-block">{$payment_method_description}</span>
</div>
</div>

<!-- paypal settings -->
<div class="form-group payment_method" id="paypal_settings">
    <label class="col-md-3 control-label">{$paypal_email}</label>
    <div class="col-md-6">              
     <input type="text" class="form-control" name="pp_account" value="{$pp_account}" />
    </div>
</div>


<!-- stripe settings -->
{if $showStripeForm=='yes'}

    <div class="form-group payment_method" id="stripe_settings">
        <label class="col-md-3 control-label">{$stripe_settings}</label>
        <div class="col-md-6">              
            <a class="stripe-connect blue" href="{$stripeUrl}"><span>{$stripe_connect_edit}</span></a>
        </div>
    </div>  

{else}
    <div class="form-group payment_method" id="stripe_settings">
        <label class="col-md-3 control-label">{$stripe_settings}</label>
        <div class="col-md-6">              
            <span style="color:#CC0000;">{$stripeToken}</span> <br>
            <label for="delete_stripe_account"><input type="checkbox" class="checkbox-inline" name="delete_stripe_account" id="delete_stripe_account" value="delete_stripe" /> &nbsp; {$stripe_delete}</label>
        </div>
    </div>
{/if}




{else}  
{$payment_method_description}
{/if}

<div class="form-group">
<div class="col-sm-offset-3 col-sm-6">
<button type="submit" class="btn btn-inverse" id="edit_payment_button">{$edit_payment_settings}</button>
</div>
</div>

</div>
</div>
</div>
</div>
{literal}
<script type="text/javascript">
jQuery(function($){
    var show_stripe_form = '{/literal}{$showStripeForm}{literal}';

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
                $('#edit_payment_button').hide();
        }

        if ( val != 2 || show_stripe_form != 'yes') {
            $('#edit_payment_button').show();
        }

	}
 
	changePaymentMethod(); 

	$('body').on('change', '#payment_method', function() {
		changePaymentMethod();	
	}); 
        
        $('body').on('click', '#delete_stripe_account', function(){
            if($(this).is(':checked')) {
                if(confirm('{/literal}{$stripe_confirm}{literal}')) {
                    $(this).prop('checked', true);
                } else {
                    $(this).prop('checked', false);
                }
            }
        });

});
</script>
{/literal}


</form>