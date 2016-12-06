<?php /* Smarty version 2.6.28, created on 2016-12-05 17:09:02
         compiled from file:account_general_stats.tpl */ ?>

<div class="row" style="margin-top:25px;">

<!--Note: adjusted state boxes in 3 columns width only-->
<!--<div class="col-sm-<?php if (isset ( $this->_tpl_vars['cp_page_width'] )): ?>3<?php else: ?>6<?php endif; ?>">-->
<div class="col-sm-6 col-md-3">
<div class="widget stateBox" style="background:<?php echo $this->_tpl_vars['box_tt_back']; ?>
; color:<?php echo $this->_tpl_vars['box_tt_text']; ?>
;">
<div class="body">
<div class="background-icon-right"><span class="fa fa-shopping-cart"></span></div>
<h2 class="heading"><?php echo $this->_tpl_vars['total_transactions']; ?>
</h2>
<p style="margin-top:15px;"><?php echo $this->_tpl_vars['account_total_transactions']; ?>
</p>
</div>
</div>
</div>

<div class="col-sm-6 col-md-3">
<div class="widget stateBox" style="background:<?php echo $this->_tpl_vars['box_ce_back']; ?>
; color:<?php echo $this->_tpl_vars['box_ce_text']; ?>
;">
<div class="body">
<div class="background-icon-right"><span class="fa fa-paperclip"></span></div>
<h2 class="heading"><?php echo $this->_tpl_vars['current_total_commissions']; ?>
</h2>
<p style="margin-top:15px;"><?php echo $this->_tpl_vars['general_current_earnings']; ?>
</p>
</div>
</div>
</div>

<!--Note: Arranging all boxes in 1 row-->
<!--
<?php if (! isset ( $this->_tpl_vars['cp_page_width'] )): ?>
</div>
<div class="row">
<?php endif; ?>
-->

<div class="col-sm-6 col-md-3">
<div class="widget stateBox" style="background:<?php echo $this->_tpl_vars['box_te_back']; ?>
; color:<?php echo $this->_tpl_vars['box_te_text']; ?>
;">
<div class="body">
<div class="background-icon-right"><span class="fa fa-refresh fa-spin"></span></div>
<h2 class="heading"><?php echo $this->_tpl_vars['total_amount_earned']; ?>
 <?php echo $this->_tpl_vars['total_amount_earned_currency']; ?>
</h2>
<p style="margin-top:15px;"><?php echo $this->_tpl_vars['account_earned_todate']; ?>
</p>
</div>
</div>
</div>

<div class="col-sm-6 col-md-3">
<div class="widget stateBox" style="background:<?php echo $this->_tpl_vars['box_uv_back']; ?>
; color:<?php echo $this->_tpl_vars['box_uv_text']; ?>
;">
<div class="body">
<div class="background-icon-right"><span class="fa fa-child"></span></div>
<h2 class="heading"><?php echo $this->_tpl_vars['unchits']; ?>
<span class="pull-right"><?php echo $this->_tpl_vars['perc']; ?>
%</span></h2>
<p style="margin-top:15px;"><?php echo $this->_tpl_vars['general_traffic_unique']; ?>
<span class="pull-right"><?php echo $this->_tpl_vars['general_traffic_ratio']; ?>
</span></p>
</div>
</div>
</div>

</div>

<?php if ($this->_tpl_vars['linking_code'] == 'available'): ?>

<div class="row">

<div class="col-md-6">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_2']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_2']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_2_text']; ?>
;"><h4><?php echo $this->_tpl_vars['progress_title']; ?>
 <?php echo $this->_tpl_vars['eligible_percent']; ?>
% <?php echo $this->_tpl_vars['progress_complete']; ?>
</h4></div></div>
<div class="portlet-body">
<div class="progress no-rounded progress-striped active">
<div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="<?php echo $this->_tpl_vars['eligible_percent']; ?>
" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $this->_tpl_vars['eligible_percent']; ?>
%"></div>
</div>
<?php echo $this->_tpl_vars['eligible_info']; ?>

</div>
</div>
</div>

<div class="col-md-6">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_2']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_2']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_2_text']; ?>
;"><h4><?php echo $this->_tpl_vars['account_standard_linking_code']; ?>
</h4></div></div>
<div class="portlet-body">
<textarea rows="2" class="form-control"><?php echo $this->_tpl_vars['box_code']; ?>
</textarea>
</div>
</div>
</div>

</div>


<?php endif; ?>

<div class="row">

<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_3']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_3']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_3_text']; ?>
;"><h4><?php echo $this->_tpl_vars['general_last_30_days_activity']; ?>
</h4></div></div>
<div class="portlet-body">
<div id="area-affiliate"></div>
</div>
</div>
</div>
</div>

<?php echo '
<script type="text/javascript">

Morris.Bar({
  element: \'area-affiliate\',
  data: [
    '; ?>

	<?php echo $this->_tpl_vars['chart_array']; ?>

	<?php echo '
  ],
  xkey: \'d\',
  ykeys: [\'a\', \'b\'],
  labels: [\''; ?>
<?php echo $this->_tpl_vars['general_last_30_days_activity_traffic']; ?>
<?php echo '\', \''; ?>
<?php echo $this->_tpl_vars['general_last_30_days_activity_commissions']; ?>
<?php echo '\']
});

</script>
'; ?>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'file:account_notes.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>