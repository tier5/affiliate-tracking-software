<?php /* Smarty version 2.6.28, created on 2016-12-05 19:26:08
         compiled from file:signup_payment_methods.tpl */ ?>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_1']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_1']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_1_text']; ?>
;"><h4><?php echo $this->_tpl_vars['signup_commission_title']; ?>
</h4></div></div>
<div class="portlet-body">

<?php if (( isset ( $this->_tpl_vars['select_multiple_methods'] ) )): ?>
<div class="form-group">
<label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_commission_howtopay']; ?>
<span style="color:#CC0000;">*</span></label>
<div class="col-md-6">              
<select name="payment_method" class="form-control" id="payment_method">
<?php echo $this->_tpl_vars['select_available_payment_methods']; ?>
 
</select>
<span class="help-block"><?php echo $this->_tpl_vars['payment_method_description']; ?>
</span>
</div>
</div>

<!-- paypal settings -->
<div class="form-group payment_method" id="paypal_settings">
    <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['paypal_email']; ?>
 <span style="color:#CC0000;">*</span></label>
    <div class="col-md-6">              
     <input type="text" class="form-control" name="pp_account" value="<?php echo $this->_tpl_vars['pp_account']; ?>
" />
    </div>
</div>


<!-- stripe settings -->
<div class="form-group payment_method" id="stripe_settings" >
    <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['stripe_acct_details']; ?>
</label>
    <div class="col-md-6">
        <h5><?php echo $this->_tpl_vars['stripe_connect']; ?>
</h5>
    </div>     
</div>


<?php else: ?>  
<?php echo $this->_tpl_vars['payment_method_description']; ?>

<?php endif; ?>

</div>
</div>
</div>
</div>
<?php echo '
<script type="text/javascript">
jQuery(function($){
    function changePaymentMethod() {
        $(\'.payment_method\').hide();
        $(\'span.payment_description\').hide();
        var val = $(\'#payment_method\').val();
        $(\'span.method_\' + val).show();
        if(val == 1) {
                //paypal is selected
                $(\'#paypal_settings\').show();
        }
        else if(val == 2) {
                //stripe selected
                $(\'#stripe_settings\').show();
        }
    }

    changePaymentMethod(); 

    $(\'body\').on(\'change\', \'#payment_method\', function() {
        changePaymentMethod();	
    });
});
</script>
'; ?>
        