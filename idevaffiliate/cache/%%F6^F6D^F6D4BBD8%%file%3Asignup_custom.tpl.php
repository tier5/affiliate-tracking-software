<?php /* Smarty version 2.6.28, created on 2016-12-05 19:26:08
         compiled from file:signup_custom.tpl */ ?>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_1']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_1']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_1_text']; ?>
;"><h4><?php echo $this->_tpl_vars['custom_fields_title']; ?>
</h4></div></div>
<div class="portlet-body">

<?php unset($this->_sections['nr']);
$this->_sections['nr']['name'] = 'nr';
$this->_sections['nr']['loop'] = is_array($_loop=$this->_tpl_vars['custom_input_results']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['nr']['show'] = true;
$this->_sections['nr']['max'] = $this->_sections['nr']['loop'];
$this->_sections['nr']['step'] = 1;
$this->_sections['nr']['start'] = $this->_sections['nr']['step'] > 0 ? 0 : $this->_sections['nr']['loop']-1;
if ($this->_sections['nr']['show']) {
    $this->_sections['nr']['total'] = $this->_sections['nr']['loop'];
    if ($this->_sections['nr']['total'] == 0)
        $this->_sections['nr']['show'] = false;
} else
    $this->_sections['nr']['total'] = 0;
if ($this->_sections['nr']['show']):

            for ($this->_sections['nr']['index'] = $this->_sections['nr']['start'], $this->_sections['nr']['iteration'] = 1;
                 $this->_sections['nr']['iteration'] <= $this->_sections['nr']['total'];
                 $this->_sections['nr']['index'] += $this->_sections['nr']['step'], $this->_sections['nr']['iteration']++):
$this->_sections['nr']['rownum'] = $this->_sections['nr']['iteration'];
$this->_sections['nr']['index_prev'] = $this->_sections['nr']['index'] - $this->_sections['nr']['step'];
$this->_sections['nr']['index_next'] = $this->_sections['nr']['index'] + $this->_sections['nr']['step'];
$this->_sections['nr']['first']      = ($this->_sections['nr']['iteration'] == 1);
$this->_sections['nr']['last']       = ($this->_sections['nr']['iteration'] == $this->_sections['nr']['total']);
?>
<div class="form-group">
<label class="col-md-3 control-label"><?php echo $this->_tpl_vars['custom_input_results'][$this->_sections['nr']['index']]['custom_title']; ?>
<?php if (( $this->_tpl_vars['custom_input_results'][$this->_sections['nr']['index']]['custom_required'] == 1 )): ?> <span style="color:#CC0000;">*</span><?php endif; ?></label>
<div class="col-md-6"> <input type="text" name="<?php echo $this->_tpl_vars['custom_input_results'][$this->_sections['nr']['index']]['custom_name']; ?>
" class="form-control" value="<?php echo $this->_tpl_vars['custom_input_results'][$this->_sections['nr']['index']]['custom_value']; ?>
" /></div>
</div>
<?php endfor; endif; ?>

</div>
</div>
</div>
</div>