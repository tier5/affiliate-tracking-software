<?php /* Smarty version 2.6.28, created on 2016-12-05 19:26:08
         compiled from file:signup_terms.tpl */ ?>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_1']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_1']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_1_text']; ?>
;"><h4><?php echo $this->_tpl_vars['signup_terms_title']; ?>
</h4></div></div>
<div class="portlet-body">

<div class="form-group">
<div class="col-md-12">
<textarea class="form-control" name="terms" rows="10" readonly><?php echo $this->_tpl_vars['terms_t']; ?>
</textarea>
</div>
</div>
 
<?php if (isset ( $this->_tpl_vars['terms_required'] )): ?>
<div class="form-group">
<div class="col-md-12">
<input type="checkbox" name="accepted" value="1"<?php echo $this->_tpl_vars['terms_checked']; ?>
> <span style="color:#CC0000;">*</span> <?php echo $this->_tpl_vars['signup_terms_agree']; ?>

</div>
</div>
<?php endif; ?>

</div>
</div>
</div>
</div>