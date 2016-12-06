<?php /* Smarty version 2.6.28, created on 2016-12-05 17:09:02
         compiled from file:account_notes.tpl */ ?>

<div class="row">
<div class="col-md-12">
<div class="portlet" style="border-color:<?php echo $this->_tpl_vars['portlet_2']; ?>
;">
<div class="portlet-heading" style="background:<?php echo $this->_tpl_vars['portlet_2']; ?>
;"><div class="portlet-title" style="color:<?php echo $this->_tpl_vars['portlet_2_text']; ?>
;"><h4><?php echo $this->_tpl_vars['general_notes_title']; ?>
</h4></div></div>
<div class="portlet-body">
		
<?php unset($this->_sections['nr']);
$this->_sections['nr']['name'] = 'nr';
$this->_sections['nr']['loop'] = is_array($_loop=$this->_tpl_vars['note_results']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
    <table class="table table-bordered">        
        <tr>
        <td width="50%" style="background:<?php echo $this->_tpl_vars['portlet_1']; ?>
;"><span style="color:<?php echo $this->_tpl_vars['portlet_1_text']; ?>
;"><?php echo $this->_tpl_vars['general_notes_date']; ?>
: <?php echo $this->_tpl_vars['note_results'][$this->_sections['nr']['index']]['note_date']; ?>
</span></td>
        <td width="50%" style="background:<?php echo $this->_tpl_vars['portlet_1']; ?>
;"><span style="color:<?php echo $this->_tpl_vars['portlet_1_text']; ?>
;"><?php echo $this->_tpl_vars['general_notes_to']; ?>
: <?php echo $this->_tpl_vars['note_results'][$this->_sections['nr']['index']]['note_to']; ?>
</span></td>
        </tr>      
        <tr>
        <td width="100%" colspan="2"><b><?php echo $this->_tpl_vars['note_results'][$this->_sections['nr']['index']]['note_subject']; ?>
</b></td>
        </tr>
		<?php if (isset ( $this->_tpl_vars['note_results'][$this->_sections['nr']['index']]['draw_image'] ) && $this->_tpl_vars['note_results'][$this->_sections['nr']['index']]['note_image_location'] == '0'): ?>
        <tr>
        <td width="100%" colspan="2"><?php echo $this->_tpl_vars['note_results'][$this->_sections['nr']['index']]['draw_image']; ?>
</td>
        </tr>
		<?php endif; ?>
        <tr>
        <td width="100%" colspan="2"><?php echo $this->_tpl_vars['note_results'][$this->_sections['nr']['index']]['note_content']; ?>
</td>
        </tr>
		<?php if (isset ( $this->_tpl_vars['note_results'][$this->_sections['nr']['index']]['draw_image'] ) && $this->_tpl_vars['note_results'][$this->_sections['nr']['index']]['note_image_location'] == '1'): ?>
        <tr>
        <td width="100%" colspan="2"><?php echo $this->_tpl_vars['note_results'][$this->_sections['nr']['index']]['draw_image']; ?>
</td>
        </tr>
		<?php endif; ?> 
    </table>
	<br />
<?php endfor; else: ?>
<?php echo $this->_tpl_vars['general_notes_none']; ?>
    
<?php endif; ?>

</div>
</div>
</div>						
</div>