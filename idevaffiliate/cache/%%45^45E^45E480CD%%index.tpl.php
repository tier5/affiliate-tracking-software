<?php /* Smarty version 2.6.28, created on 2016-12-05 15:49:13
         compiled from index.tpl */ ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (isset ( $this->_tpl_vars['logout_msg'] )): ?>
<div class="row">
<div class="col-md-12" style="margin-top:15px;">
<div class="alert alert-success"><?php echo $this->_tpl_vars['logout_msg']; ?>
</div>
</div>
</div>
<?php endif; ?>

<div class="row">

<div class="col-md-<?php if (! isset ( $this->_tpl_vars['affiliateUsername'] )): ?>7<?php else: ?>12<?php endif; ?>" style="margin-top:15px;">
<div class="portlet portlet-basic">
<div class="portlet-body">

<?php if (( isset ( $this->_tpl_vars['show_seal'] ) )): ?>
<div class="row">
<div class="col-md-<?php if (! isset ( $this->_tpl_vars['affiliateUsername'] )): ?>9<?php else: ?>10<?php endif; ?>"><p><h4><?php echo $this->_tpl_vars['index_heading_1']; ?>
</h4></p><p><?php echo $this->_tpl_vars['index_paragraph_1']; ?>
</p></div>
<div class="col-md-<?php if (! isset ( $this->_tpl_vars['affiliateUsername'] )): ?>3<?php else: ?>2<?php endif; ?>" align="center"><a href="#modal-1" data-target="#modal-1" data-toggle="modal"><img class="img-responsive" src="<?php echo $this->_tpl_vars['seal_image']; ?>
" style="width:142px; height:142px; border:none;" /></a></div>
</div>

<div class="modal fade" id="modal-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $this->_tpl_vars['modal_close']; ?>
"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" style="color:<?php echo $this->_tpl_vars['heading_text']; ?>
;"><?php echo $this->_tpl_vars['accountability_title']; ?>
</h4>
      </div>
      <div class="modal-body">
        <p><?php echo $this->_tpl_vars['accountability_text']; ?>
</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->_tpl_vars['modal_close']; ?>
</button>
      </div>
    </div>
  </div>
</div>
<?php else: ?>
<h4><?php echo $this->_tpl_vars['index_heading_1']; ?>
</h4>
<p><?php echo $this->_tpl_vars['index_paragraph_1']; ?>
</p>
<?php endif; ?>
<h4><?php echo $this->_tpl_vars['index_heading_2']; ?>
</h4>
<p><?php echo $this->_tpl_vars['index_paragraph_2']; ?>
</p>
<h4><?php echo $this->_tpl_vars['index_heading_3']; ?>
</h4>
<p><?php echo $this->_tpl_vars['index_paragraph_3']; ?>
</p>
<?php if (isset ( $this->_tpl_vars['cp_page_width'] )): ?><p><a href="signup.php" class="btn btn-success"><?php echo $this->_tpl_vars['header_signupLink']; ?>
</a></p><?php endif; ?>
</div>
</div>
</div>

<?php if (! isset ( $this->_tpl_vars['affiliateUsername'] )): ?>

<div class="col-md-5" style="margin-top:25px;">
<div class="portlet portlet-basic">
<div class="portlet-heading"><div class="portlet-title"><h4><?php echo $this->_tpl_vars['index_login_title']; ?>
</h4></div></div>
<div class="portlet-body">
<form method="post" action="login.php">      
<div class="form-group">
<label><?php echo $this->_tpl_vars['index_login_username']; ?>
</label>
<input class="form-control" placeholder="<?php echo $this->_tpl_vars['index_login_username']; ?>
" type="text" name="userid" value="<?php echo $this->_tpl_vars['index_login_username_field']; ?>
"/>
</div>
<div class="form-group">
<label><?php echo $this->_tpl_vars['index_login_password']; ?>
</label>
<input class="form-control" placeholder="<?php echo $this->_tpl_vars['index_login_password']; ?>
" type="password" name="password" value="<?php echo $this->_tpl_vars['index_login_password_field']; ?>
" autocomplete="off" />
</div>
<div class="form-actions">               
<button type="submit" class="btn btn-inverse"><?php echo $this->_tpl_vars['index_login_button']; ?>
</button>
<?php if (isset ( $this->_tpl_vars['idev_facebook_enabled'] )): ?><span class="pull-right"><a href="<?php echo $this->_tpl_vars['fb_login_url']; ?>
" class="btn btn-social btn-facebook"><i class="fa fa-facebook"></i> <?php echo $this->_tpl_vars['fb_login']; ?>
</a></span><?php endif; ?>
</div>
<input name="token_affiliate_login" value="<?php echo $this->_tpl_vars['login_token']; ?>
" type="hidden" />
</form>
</div>

</div>
<?php if (! isset ( $this->_tpl_vars['cp_page_width'] )): ?><p style="margin-top:25px;"><a href="signup.php" class="btn btn-block btn-danger"><?php echo $this->_tpl_vars['header_signupLink']; ?>
</a></p><?php endif; ?>
</div>

<?php endif; ?>

</div>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_3']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_3']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_3_text']; ?>
;"><h4><?php echo $this->_tpl_vars['index_table_title']; ?>
</h4></div></div>
<div class="portlet-body">
                             
                <table class="table table-bordered table-striped" style="color:<?php echo $this->_tpl_vars['text_color']; ?>
;">
				<tbody>                  
                    <tr>
					<td><?php echo $this->_tpl_vars['index_table_commission_type']; ?>
</td>
                    <td><?php echo $this->_tpl_vars['commission_type_info']; ?>
</td>
                   </tr>

			
                                        <?php if (isset ( $this->_tpl_vars['choose_percentage_payout'] )): ?>
					
                    <tr>
					<td><?php echo $this->_tpl_vars['index_table_sale']; ?>
:</td>
					<td><div class="label label-danger"><?php echo $this->_tpl_vars['bot1']; ?>
%</div> <?php echo $this->_tpl_vars['index_table_sale_text']; ?>
</td>
					</tr>
                    <?php endif; ?>
                    
                    <?php if (isset ( $this->_tpl_vars['choose_flatrate_payout'] )): ?>
                    <tr>
					<td><?php echo $this->_tpl_vars['index_table_sale']; ?>
:</td>
					<td><div class="label label-danger"><?php if ($this->_tpl_vars['cur_sym_location'] == 1): ?><?php echo $this->_tpl_vars['cur_sym']; ?>
<?php endif; ?><?php echo $this->_tpl_vars['bot2']; ?>
<?php if ($this->_tpl_vars['cur_sym_location'] == 2): ?> <?php echo $this->_tpl_vars['cur_sym']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['currency']; ?>
</div> <?php echo $this->_tpl_vars['index_table_sale_text']; ?>
</td>
					</tr>
                    <?php endif; ?>
                    
                    <?php if (isset ( $this->_tpl_vars['choose_perclick_payout'] )): ?>
                    <tr>
					<td><?php echo $this->_tpl_vars['index_table_click']; ?>
:</td>
					<td><div class="label label-danger"><?php if ($this->_tpl_vars['cur_sym_location'] == 1): ?><?php echo $this->_tpl_vars['cur_sym']; ?>
<?php endif; ?><?php echo $this->_tpl_vars['bot3']; ?>
<?php if ($this->_tpl_vars['cur_sym_location'] == 2): ?> <?php echo $this->_tpl_vars['cur_sym']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['currency']; ?>
</div> <?php echo $this->_tpl_vars['index_table_click_text']; ?>
</td>
					</tr>
					
                    <?php endif; ?>
                    
                                        
                    <?php if (isset ( $this->_tpl_vars['add_balance_row'] )): ?>
                    <tr>
					<td><?php echo $this->_tpl_vars['index_table_initial_deposit']; ?>
</td>
					<td><?php if ($this->_tpl_vars['cur_sym_location'] == 1): ?><?php echo $this->_tpl_vars['cur_sym']; ?>
<?php endif; ?><?php echo $this->_tpl_vars['init_deposit']; ?>
<?php if ($this->_tpl_vars['cur_sym_location'] == 2): ?> <?php echo $this->_tpl_vars['cur_sym']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['currency']; ?>
 - <font color="#CC0000"><b><?php echo $this->_tpl_vars['index_table_deposit_tag']; ?>
</b></font></td>
					</tr>
                    <?php endif; ?>
                    <?php if (isset ( $this->_tpl_vars['add_requirements_row'] )): ?>
                    <tr>
					<td><?php echo $this->_tpl_vars['index_table_requirements']; ?>
</td>
					<td><?php if ($this->_tpl_vars['cur_sym_location'] == 1): ?><?php echo $this->_tpl_vars['cur_sym']; ?>
<?php endif; ?><?php echo $this->_tpl_vars['init_req']; ?>
<?php if ($this->_tpl_vars['cur_sym_location'] == 2): ?> <?php echo $this->_tpl_vars['cur_sym']; ?>
<?php endif; ?> <?php echo $this->_tpl_vars['currency']; ?>
 - <?php echo $this->_tpl_vars['index_table_requirements_tag']; ?>
</td>
					</tr>
                    <?php endif; ?>
                    <tr>
                    <td><?php echo $this->_tpl_vars['index_table_duration']; ?>
</td><td><?php echo $this->_tpl_vars['index_table_duration_tag']; ?>
</td>
                    </tr>
				</tbody>
                </table>
           
</div>
</div>
</div>
</div>

<?php if (isset ( $this->_tpl_vars['bar_comms_last_6'] ) || isset ( $this->_tpl_vars['pie_top_5_month'] )): ?>

<div class="row">
<?php if (isset ( $this->_tpl_vars['bar_comms_last_6'] )): ?>
<div class="col-md-<?php if (isset ( $this->_tpl_vars['pie_top_5_month'] )): ?>8<?php else: ?>12<?php endif; ?>">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_3']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_3']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_3_text']; ?>
;"><h4><?php echo $this->_tpl_vars['chart_last_6_months']; ?>
</h4></div></div>
<div class="portlet-body">
<div id="bar-example-index"></div>

<?php echo '
<script type="text/javascript">
    Morris.Bar({
    element: \'bar-example-index\',
    data: [
    {x: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][0]['name']; ?>
<?php echo '\', y: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][0]['commissions']; ?>
<?php echo '\'},
    {x: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][1]['name']; ?>
<?php echo '\', y: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][1]['commissions']; ?>
<?php echo '\'},
    {x: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][2]['name']; ?>
<?php echo '\', y: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][2]['commissions']; ?>
<?php echo '\'},
    {x: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][3]['name']; ?>
<?php echo '\', y: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][3]['commissions']; ?>
<?php echo '\'},
    {x: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][4]['name']; ?>
<?php echo '\', y: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][4]['commissions']; ?>
<?php echo '\'},
    {x: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][5]['name']; ?>
<?php echo '\', y: \''; ?>
<?php echo $this->_tpl_vars['monthly_commissions'][5]['commissions']; ?>
<?php echo '\'},
    ],
    xkey: \'x\',
    ykeys: [\'y\'],
    labels: [\''; ?>
<?php echo $this->_tpl_vars['chart_last_6_months_paid']; ?>
<?php echo '\'],
    barColors: function (row, series, type) {
    if (type === \'bar\') {
    var red = Math.ceil(255 * row.y / this.ymax);
    return \'rgb(\' + red + \',0,0)\';
    }
    else {
    return \'#000\';
    }
    }
    });
</script>
'; ?>

</div>
</div>
</div>

<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['pie_top_5_month'] )): ?>

<div class="col-md-<?php if (isset ( $this->_tpl_vars['bar_comms_last_6'] )): ?>4<?php else: ?>12<?php endif; ?>">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_3']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_3']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_3_text']; ?>
;"><h4><?php echo $this->_tpl_vars['chart_this_month']; ?>
</h4></div></div>
<div class="portlet-body">
<div id="donut-example-index"></div>
<?php if (! empty ( $this->_tpl_vars['top_affiliates'] )): ?>
<?php echo '
<script type="text/javascript">
	Morris.Donut({
	  element: \'donut-example-index\',
	  resize: true,
	  data: [
	    '; ?>
<?php echo $this->_tpl_vars['top_affiliates']; ?>
<?php echo '
	  ],
formatter: function (x, data) { return data.formatted; }
	});
</script>
'; ?>

<?php else: ?>
<?php echo $this->_tpl_vars['chart_this_month_none']; ?>

<?php endif; ?>
</div>
</div>
</div>

<?php endif; ?>

</div>

<?php endif; ?>



<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>