<?php /* Smarty version 2.6.28, created on 2016-12-05 15:49:13
         compiled from file:menu_top.tpl */ ?>

<nav class="navbar-top<?php if (isset ( $this->_tpl_vars['cp_fixed_navbar'] )): ?> fixed<?php endif; ?>" role="navigation" style="background-color: <?php echo $this->_tpl_vars['header_background']; ?>
;">
<div class="navbar-inner <?php if (! isset ( $this->_tpl_vars['cp_page_width'] )): ?> container<?php endif; ?>">
<div class="navbar-header">
<button type="button" class="navbar-toggle pull-right" data-toggle="collapse" data-target=".top-collapse">
<i class="fa fa-bars"></i>
</button>
<div class="navbar-brand col-md-12">
<?php if (isset ( $this->_tpl_vars['main_logo'] )): ?><a href="index.php" class="brand"><img class="img-responsive" style="border:none;" src="<?php echo $this->_tpl_vars['main_logo']; ?>
" alt="<?php echo $this->_tpl_vars['sitename']; ?>
 - <?php echo $this->_tpl_vars['header_title']; ?>
"></a><?php endif; ?>
</div>
</div>

<div class="nav-top">

<form id="language_form" method="POST" action="">
<input type="hidden" id="idev_language" name="idev_language" value="" />
<input name="lang_token" value="<?php echo $this->_tpl_vars['language_token']; ?>
" type="hidden" />
</form>

<ul class="nav lang navbar-right <?php if (isset ( $this->_tpl_vars['cp_fixed_navbar'] )): ?>mobileFix<?php endif; ?>">
<li class="dropdown" style="background-color: <?php echo $this->_tpl_vars['top_menu_background']; ?>
;">
<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="user-info" style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['language_selected']; ?>
</span> <b class="caret"></b></a>
<ul class="dropdown-menu">
<?php unset($this->_sections['nr']);
$this->_sections['nr']['name'] = 'nr';
$this->_sections['nr']['loop'] = is_array($_loop=$this->_tpl_vars['language_pack']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<li><a href="#" onclick="document.getElementById('idev_language').value = '<?php echo $this->_tpl_vars['language_pack'][$this->_sections['nr']['index']]['value']; ?>
'; document.getElementById('language_form').submit(); return false;"><?php echo $this->_tpl_vars['language_pack'][$this->_sections['nr']['index']]['name']; ?>
</a></li>
<?php if (! $this->_sections['nr']['last']): ?><li class="divider"></li><?php endif; ?>
<?php endfor; endif; ?>
</ul>
</li>
</ul>

<?php if (isset ( $this->_tpl_vars['affiliateUsername'] )): ?>

<ul class="nav user-information navbar-right <?php if (isset ( $this->_tpl_vars['cp_fixed_navbar'] )): ?>mobileFix<?php endif; ?>">
<li class="dropdown" style="background-color: <?php echo $this->_tpl_vars['top_menu_background']; ?>
;">
<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="user-info" style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['affiliate_firstname']; ?>
 <?php echo $this->_tpl_vars['affiliate_lastname']; ?>
</span> <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a href="account.php?page=17"><?php echo $this->_tpl_vars['menu_drop_edit']; ?>
</a></li>
<li><a href="account.php?page=48"><?php echo $this->_tpl_vars['payment_settings']; ?>
</a></li>
<li><a href="account.php?page=18"><?php echo $this->_tpl_vars['menu_drop_password']; ?>
</a></li>
<?php if (isset ( $this->_tpl_vars['change_commission'] )): ?><li class="divider"></li><li><a href="account.php?page=19"><?php echo $this->_tpl_vars['menu_drop_change']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['pic_upload'] )): ?><li class="divider"></li><li><a href="account.php?page=43"><?php echo $this->_tpl_vars['menu_upload_picture']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['logos_enabled'] )): ?><li class="divider"></li><li><a href="account.php?page=27"><?php echo $this->_tpl_vars['menu_drop_heading_logo']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['use_faq'] ) && ( $this->_tpl_vars['faq_location'] == 2 )): ?><li class="divider"></li><li><a href="account.php?page=21"><?php echo $this->_tpl_vars['menu_drop_heading_faq']; ?>
</a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['testimonials'] )): ?><li class="divider"></li><li><a href="account.php?page=41"><?php echo $this->_tpl_vars['menu_offer_testi']; ?>
</a></li><?php endif; ?>
<li class="divider"></li>
<li><a href="index.php?logout=true"><i class="fa fa-power-off"></i> <?php echo $this->_tpl_vars['menu_logout']; ?>
</a></li>
</ul>
</li>
</ul>

<?php endif; ?>

<div class="collapse navbar-collapse top-collapse mobileFix">
<ul class="nav navbar-left navbar-nav">
<li style="background-color: <?php echo $this->_tpl_vars['top_menu_background']; ?>
;"><a href="index.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['header_indexLink']; ?>
</span></a></li>
<li style="background-color: <?php echo $this->_tpl_vars['top_menu_background']; ?>
;"><a href="account.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['header_accountLink']; ?>
</span></a></li>
<?php if (! isset ( $this->_tpl_vars['affiliateUsername'] )): ?><li style="background-color: <?php echo $this->_tpl_vars['top_menu_background']; ?>
;"><a href="signup.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['header_signupLink']; ?>
</span></a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['contact_form'] )): ?><li style="background-color: <?php echo $this->_tpl_vars['top_menu_background']; ?>
;"><a href="contact.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['header_emailLink']; ?>
</span></a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['use_faq'] ) && ( $this->_tpl_vars['faq_location'] == 1 )): ?><li style="background-color: <?php echo $this->_tpl_vars['top_menu_background']; ?>
;"><a href="faq.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['menu_faq']; ?>
</span></a></li><?php endif; ?>
<?php if (isset ( $this->_tpl_vars['testimonials'] ) && ( isset ( $this->_tpl_vars['testimonials_active'] ) )): ?><li style="background-color: <?php echo $this->_tpl_vars['top_menu_background']; ?>
;"><a href="testimonials.php"><span style="color: <?php echo $this->_tpl_vars['top_menu_text']; ?>
;"><?php echo $this->_tpl_vars['header_testimonials']; ?>
</span></a></li><?php endif; ?>

</ul>
</div>
</div>
</div>
</nav>