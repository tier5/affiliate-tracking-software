<?php /* Smarty version 2.6.28, created on 2016-12-05 15:49:21
         compiled from login.tpl */ ?>

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['char_set']; ?>
">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<title><?php echo $this->_tpl_vars['sitename']; ?>
 - <?php echo $this->_tpl_vars['header_title']; ?>
</title>

        <link rel="stylesheet" type="text/css" href="templates/source/common/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="templates/source/common/font-awesome/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="templates/source/common/bootstrap/css/bootstrap-social.css">
		<link rel="stylesheet" type="text/css" href="templates/source/common/css/animate.css" >
		<link rel="stylesheet" type="text/css" href="templates/source/common/pace/css/pace.css">
		<link rel="stylesheet" type="text/css" href="templates/themes/<?php echo $this->_tpl_vars['active_theme']; ?>
/css/style_login.css">
   
		<!--[if lt IE 9]>
		<?php echo '
		<script type="text/javascript" src="templates/source/common/js/html5shiv.js"></script>
		<script type="text/javascript" src="templates/source/common/js/respond.min.js"></script>
		'; ?>

		<![endif]-->

</head>

<body class="fixed-left login-page" style="background:<?php echo $this->_tpl_vars['background_color']; ?>
;>

<div class="container">
<div class="full-content-center">

<p class="text-center"><?php if (isset ( $this->_tpl_vars['main_logo'] )): ?><a href="index.php" class="brand"><img style="border:none;" src="<?php echo $this->_tpl_vars['main_logo']; ?>
" alt="<?php echo $this->_tpl_vars['sitename']; ?>
 - <?php echo $this->_tpl_vars['header_title']; ?>
"></a><?php endif; ?></p>
<div class="login-wrap animated flipInX">
<div class="login-block">   
    
    
<?php if (isset ( $this->_tpl_vars['login_invalid'] )): ?>
<div class="row">
<div class="col-sm-12 portlets">
<div class="alert alert-danger"><?php echo $this->_tpl_vars['login_invalid']; ?>
</div>
</div>
</div>
<?php endif; ?>

<?php if (isset ( $this->_tpl_vars['login_details'] )): ?>
<div class="row">
<div class="col-sm-12 portlets">
<div class="alert alert-info"><?php echo $this->_tpl_vars['login_details']; ?>
</div>
</div>
</div>
<?php endif; ?> 

<?php if (! isset ( $this->_tpl_vars['lost_password_request'] )): ?>

<h2><?php echo $this->_tpl_vars['login_left_column_title']; ?>
</h2>
<p><?php echo $this->_tpl_vars['login_left_column_text']; ?>
</p>

<form role="form" method="POST" action="login.php">
<input name="token_affiliate_login" value="<?php echo $this->_tpl_vars['login_token']; ?>
" type="hidden" />
<div class="form-group login-input">
<i class="fa fa-user overlay"></i>
<input type="text" class="form-control text-input" placeholder="<?php echo $this->_tpl_vars['login_username']; ?>
" name="userid" />
</div>
<div class="form-group login-input">
<i class="fa fa-key overlay"></i>
<input class="form-control text-input" placeholder="<?php echo $this->_tpl_vars['login_password']; ?>
" type="password" name="password" autocomplete="off">
</div>
<div class="row">
<div class="col-sm-6">
<button type="submit" class="btn btn-darkblue-1 btn-block"><?php echo $this->_tpl_vars['login_now']; ?>
</button>
</div>
<div class="col-sm-6">
<a href="login.php?lost_password=true" class="btn btn-default btn-block"><?php echo $this->_tpl_vars['login_send_title']; ?>
</a>
</div>
<?php if (isset ( $this->_tpl_vars['idev_facebook_enabled'] )): ?>
<div class="col-sm-12" style="padding-top:20px; padding-bottom:20px; text-align:center;">
<a href="<?php echo $this->_tpl_vars['fb_login_url']; ?>
" class="btn btn-social btn-facebook"><i class="fa fa-facebook"></i> <?php echo $this->_tpl_vars['fb_login']; ?>
</a>
</div>
<?php endif; ?>
</div>
<div class="row">
<div class="col-sm-12">
<a href="index.php" class="btn btn-sm btn-primary btn-block"><?php echo $this->_tpl_vars['login_return']; ?>
</a>
</div>
</div>

</form>

<?php else: ?>

<h2><?php echo $this->_tpl_vars['login_send_title']; ?>
</h2>
<p><?php echo $this->_tpl_vars['login_lost_details']; ?>
</p>

<form role="form" method="POST" action="login.php">
<input name="token_affiliate_creds" value="<?php echo $this->_tpl_vars['send_pass_token']; ?>
" type="hidden" />
<input name="lost_password" value="true" type="hidden" />
<div class="form-group login-input">
<i class="fa fa-user overlay"></i>
<input type="text" class="form-control text-input" placeholder="<?php echo $this->_tpl_vars['login_send_username']; ?>
" name="sendpass" />
</div>
<div class="row">
<div class="col-sm-6">
<button type="submit" class="btn btn-darkblue-1 btn-block"><?php echo $this->_tpl_vars['login_send_pass']; ?>
</button>
</div>
<div class="col-sm-6">
<a href="login.php" class="btn btn-default btn-block"><?php echo $this->_tpl_vars['login_now']; ?>
</a>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<a href="index.php" class="btn btn-sm btn-primary btn-block"><?php echo $this->_tpl_vars['login_return']; ?>
</a>
</div>
</div>
</form>

<?php endif; ?>

</div>
</div>
</div>
</div>

<?php echo '
<script type="text/javascript" src="templates/source/lightbox/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="templates/source/common/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="templates/source/common/pace/js/pace.min.js"></script>
'; ?>


</body>
</html>