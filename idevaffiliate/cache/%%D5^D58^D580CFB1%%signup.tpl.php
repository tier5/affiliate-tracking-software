<?php /* Smarty version 2.6.28, created on 2016-12-05 19:26:08
         compiled from signup.tpl */ ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (isset ( $this->_tpl_vars['maintenance_mode'] )): ?>

<div class="row">
<div class="col-md-12" style="margin-top:25px;">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_5']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_5']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_5_text']; ?>
;"><h4><?php echo $this->_tpl_vars['signup_maintenance_heading']; ?>
</h4></div></div>
<div class="portlet-body">
<p><?php echo $this->_tpl_vars['signup_maintenance_info']; ?>
</p>
</div>
</div>
</div>
</div>

<?php else: ?>

<div class="page-header title" style="background:<?php echo $this->_tpl_vars['heading_back']; ?>
;">
<h1 style="color:<?php echo $this->_tpl_vars['heading_text']; ?>
;"><?php echo $this->_tpl_vars['signup_left_column_title']; ?>
</h1>
</div>

<?php if (isset ( $this->_tpl_vars['signup_complete'] )): ?>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_6']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_6']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_6_text']; ?>
;"><h4><?php echo $this->_tpl_vars['signup_page_success']; ?>
</h4></div></div>
<div class="portlet-body">
<div class="alert alert-success"><?php echo $this->_tpl_vars['signup_success_email_comment']; ?>
</div>
<a href="account.php" class="btn btn-success"><?php echo $this->_tpl_vars['signup_success_login_link']; ?>
</a>
</div>
</div>
</div>
</div>

<?php else: ?>

<?php if (isset ( $this->_tpl_vars['display_signup_errors'] )): ?>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_5']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_5']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_5_text']; ?>
;"><h4><?php echo $this->_tpl_vars['error_title']; ?>
</h4></div></div>
<div class="portlet-body">
<div class="alert alert-danger"><?php echo $this->_tpl_vars['error_list']; ?>
</div>
</div>
</div>
</div>
</div>

<?php endif; ?>

<?php if (! isset ( $this->_tpl_vars['signup_complete'] ) && ! isset ( $this->_tpl_vars['display_signup_errors'] )): ?>

<div class="row">
<div class="col-md-12">
<div class="portlet portlet-basic">
<div class="portlet-body">
<p><?php echo $this->_tpl_vars['signup_left_column_text']; ?>
</p>
</div>
</div>
</div>
</div>

<?php endif; ?>

		<?php if (isset ( $this->_tpl_vars['idev_facebook_enabled'] ) && ! isset ( $this->_tpl_vars['display_signup_errors'] )): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:signup_facebook.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>

<?php if (! isset ( $this->_tpl_vars['idev_facebook_required'] )): ?>

<form class="form-horizontal" action="signup.php" method="POST" id="signup_form">
<input type="hidden" value="1" name="submit1"/>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_1']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_1']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_1_text']; ?>
;"><h4><?php echo $this->_tpl_vars['signup_login_title']; ?>
</h4></div></div>
<div class="portlet-body">

        <div class="form-group">
        <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_login_username']; ?>
 <span style="color:#CC0000;">*</span></label>
        <div class="col-md-6">
		<div class="input-group">
		<input type="text" class="form-control" name="username"  value="<?php if (isset ( $this->_tpl_vars['postuser'] )): ?><?php echo $this->_tpl_vars['postuser']; ?>
<?php endif; ?>" tabindex="1" />
		<span class="input-group-btn">
		<button class="btn btn-default" type="button" data-target="#modal-1" data-toggle="modal"><i class="fa fa-question-circle"></i></button>
		</span>
		</div>
		</div>
		</div>

        <div class="form-group">
        <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_login_password']; ?>
 <span style="color:#CC0000;">*</span></label>
        <div class="col-md-6">
		<div class="input-group">
		<input type="password" class="form-control" name="password" value="<?php if (isset ( $this->_tpl_vars['postpass'] )): ?><?php echo $this->_tpl_vars['postpass']; ?>
<?php endif; ?>" tabindex="2" autocomplete="off" />
		<span class="input-group-btn">
		<button class="btn btn-default" type="button" data-target="#modal-1" data-toggle="modal"><i class="fa fa-question-circle"></i></button>
		</span>
		</div>
        </div>  
		</div>		

<div class="modal fade" id="modal-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $this->_tpl_vars['modal_close']; ?>
"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $this->_tpl_vars['signup_login_username']; ?>
 & <?php echo $this->_tpl_vars['signup_login_password']; ?>
</h4>
      </div>
      <div class="modal-body">
        <p><?php echo $this->_tpl_vars['signup_login_minmax_chars']; ?>
</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->_tpl_vars['modal_close']; ?>
</button>
      </div>
    </div>
  </div>
</div>
		
        <div class="form-group">
        <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_login_password_again']; ?>
 <span style="color:#CC0000;">*</span></label>
        <div class="col-md-6">
		<div class="input-group">
		<input type="password" class="form-control" name="password_c" value="<?php echo $this->_tpl_vars['postpasc']; ?>
" tabindex="3" autocomplete="off" />
		<span class="input-group-btn">
		<button class="btn btn-default" type="button" data-target="#modal-2" data-toggle="modal"><i class="fa fa-question-circle"></i></button>
		</span>
		</div>
        </div>
        </div>
		
<div class="modal fade" id="modal-2" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $this->_tpl_vars['modal_close']; ?>
"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $this->_tpl_vars['signup_login_password_again']; ?>
</h4>
      </div>
      <div class="modal-body">
        <p><?php echo $this->_tpl_vars['signup_login_must_match']; ?>
</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->_tpl_vars['modal_close']; ?>
</button>
      </div>
    </div>
  </div>
</div>
		  
</div>
</div>
</div>
</div>
	  
		<?php if (isset ( $this->_tpl_vars['optionals_used'] )): ?>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_1']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_1']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_1_text']; ?>
;"><h4><?php echo $this->_tpl_vars['signup_standard_title']; ?>
</h4></div></div>
<div class="portlet-body">

           <?php if (isset ( $this->_tpl_vars['row_email'] )): ?>
            <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_standard_email']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_email'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">                  
            <input type="text" class="form-control" name="email" value="<?php echo $this->_tpl_vars['postemail']; ?>
" tabindex="4" />
            </div>
            </div>  
          <?php endif; ?>
		  
          <?php if (isset ( $this->_tpl_vars['row_company'] )): ?>
            <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_standard_company']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_company'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">           
            <input type="text" class="form-control" name="company" value="<?php echo $this->_tpl_vars['postcompany']; ?>
" tabindex="5" />
            </div>
            </div>  
          <?php endif; ?>
		  
          <?php if (isset ( $this->_tpl_vars['row_checks'] )): ?>
            <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_standard_checkspayable']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_checkspayable'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">             
            <input type="text" class="form-control" name="payable" value="<?php echo $this->_tpl_vars['postchecks']; ?>
" tabindex="6" />
            </div>
            </div>  
          <?php endif; ?>
		  
          <?php if (isset ( $this->_tpl_vars['row_website'] )): ?>
			<div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_standard_weburl']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_weburl'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">           
            <input type="text" class="form-control" name="url" value="<?php echo $this->_tpl_vars['postwebsite']; ?>
" tabindex="7" />
            </div>
			</div>  
          <?php endif; ?>
		  
          <?php if (isset ( $this->_tpl_vars['row_taxinfo'] )): ?>
			<div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_standard_taxinfo']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_taxinfo'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">             
            <input type="text" class="form-control" name="tax_id_ssn" value="<?php echo $this->_tpl_vars['posttax']; ?>
" tabindex="8" />
            </div>
			</div>  
          <?php endif; ?>
		  
</div>
</div>
</div>
</div>

		<?php endif; ?>
	   
		<?php if (isset ( $this->_tpl_vars['standards_used'] )): ?>
		
<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_1']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_1']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_1_text']; ?>
;"><h4><?php echo $this->_tpl_vars['signup_personal_title']; ?>
</h4></div></div>
<div class="portlet-body">

<?php if (isset ( $this->_tpl_vars['row_fname'] )): ?>
		<div class="form-group">
           <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_personal_fname']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_fname'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">             
            <input type="text" class="form-control" name="f_name" value="<?php echo $this->_tpl_vars['postfname']; ?>
" tabindex="9" />
            </div>
			</div>  
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['row_lname'] )): ?>
           <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_personal_lname']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_lname'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">            
              <input type="text" class="form-control" name="l_name"  value="<?php echo $this->_tpl_vars['postlname']; ?>
"  tabindex="10" />
            </div>
          </div>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['row_addr1'] )): ?>
          <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_personal_addr1']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_ad1'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">           
              <input type="text" class="form-control" name="address_one"  value="<?php echo $this->_tpl_vars['postaddr1']; ?>
"  tabindex="11" />
            </div>
          </div>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['row_addr2'] )): ?>
          <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_personal_addr2']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_ad2'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">             
              <input type="text" class="form-control" name="address_two"  value="<?php echo $this->_tpl_vars['postaddr2']; ?>
"  tabindex="12" />
            </div>
          </div>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['row_city'] )): ?>
          <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_personal_city']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_city'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">            
              <input type="text" class="form-control" name="city"  value="<?php echo $this->_tpl_vars['postcity']; ?>
"  tabindex="13" />
            </div>
          </div>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['row_state'] )): ?>
          <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_personal_state']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_state'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">              
              <input type="text" class="form-control" name="state"  value="<?php echo $this->_tpl_vars['poststate']; ?>
"  tabindex="14" />
            </div>
          </div>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['row_phone'] )): ?>
		  <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_personal_phone']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_phone'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">              
              <input type="text" class="form-control" name="phone"  value="<?php echo $this->_tpl_vars['postphone']; ?>
"  tabindex="15" />
            </div>
          </div>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['row_fax'] )): ?>
          <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_personal_fax']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_fax'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">             
              <input type="text" class="form-control" name="fax"  value="<?php echo $this->_tpl_vars['postfaxnm']; ?>
"  tabindex="16" />
            </div>
          </div>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['row_zip'] )): ?>
             <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_personal_zip']; ?>
<?php if (isset ( $this->_tpl_vars['required_notice_zip'] )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
            <div class="col-md-6">            
              <input type="text" class="form-control" name="zip"  value="<?php echo $this->_tpl_vars['postzip']; ?>
"  tabindex="17" />
            </div>
          </div>
<?php endif; ?>
<?php if (isset ( $this->_tpl_vars['row_countries'] )): ?>
          <div class="form-group">
            <label class="col-md-3 control-label"><?php echo $this->_tpl_vars['signup_personal_country']; ?>
 <span style="color:#CC0000;">*</span></label>
<div class="col-md-6"><select class="form-control" name="country">
<?php echo $this->_tpl_vars['c_drop']; ?>

</select>

</div>
<?php endif; ?>

</div>
</div>
</div>
</div>

		<?php endif; ?>



        <?php if (isset ( $this->_tpl_vars['payment_choice_used'] )): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:signup_payment_choices.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>

		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:signup_payment_methods.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?> 

        <?php if (isset ( $this->_tpl_vars['terms_conditions'] )): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:signup_terms.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>

        <?php if (isset ( $this->_tpl_vars['canspam_conditions'] )): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:signup_canspam.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>

        <?php if (isset ( $this->_tpl_vars['insert_custom_fields'] )): ?>
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:signup_custom.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>

        <?php if (isset ( $this->_tpl_vars['security_required'] )): ?>
        	<?php if ($this->_tpl_vars['security_required']): ?>
        		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:signup_security_code.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php endif; ?>
        <?php endif; ?>
		
<div class="row">
<div class="col-md-offset-3 col-md-9">
<button type="submit" class="btn btn-primary"><?php echo $this->_tpl_vars['signup_page_button']; ?>
</button>
</div>
</div>


<div class="space-30"></div>

<?php endif; ?>
		
</form>

<?php endif; ?>
<?php endif; ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>