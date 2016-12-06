{*
	--------------------------------------------------------------------------------------------------------------
	iDevAffiliate HTML Front-End Template
	--------------------------------------------------------------------------------------------------------------
	Theme Name: Default Theme
	--------------------------------------------------------------------------------------------------------------
*}

<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$char_set}">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
<title>{$sitename} - {$header_title}</title>

        <link rel="stylesheet" type="text/css" href="templates/source/common/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="templates/source/common/font-awesome/font-awesome.css">
		<link rel="stylesheet" type="text/css" href="templates/source/common/bootstrap/css/bootstrap-social.css">
		<link rel="stylesheet" type="text/css" href="templates/source/common/css/animate.css" >
		<link rel="stylesheet" type="text/css" href="templates/source/common/pace/css/pace.css">
		<link rel="stylesheet" type="text/css" href="templates/themes/{$active_theme}/css/style_login.css">
   
		<!--[if lt IE 9]>
		{literal}
		<script type="text/javascript" src="templates/source/common/js/html5shiv.js"></script>
		<script type="text/javascript" src="templates/source/common/js/respond.min.js"></script>
		{/literal}
		<![endif]-->

</head>

<body class="fixed-left login-page" style="background:{$background_color};>

<div class="container">
<div class="full-content-center">

<p class="text-center">{if isset($main_logo)}<a href="index.php" class="brand"><img style="border:none;" src="{$main_logo}" alt="{$sitename} - {$header_title}"></a>{/if}</p>
<div class="login-wrap animated flipInX">
<div class="login-block">   
    
    
{if isset($login_invalid)}
<div class="row">
<div class="col-sm-12 portlets">
<div class="alert alert-danger">{$login_invalid}</div>
</div>
</div>
{/if}

{if isset($login_details)}
<div class="row">
<div class="col-sm-12 portlets">
<div class="alert alert-info">{$login_details}</div>
</div>
</div>
{/if} 

{if !isset($lost_password_request)}

<h2>{$login_left_column_title}</h2>
<p>{$login_left_column_text}</p>

<form role="form" method="POST" action="login.php">
<input name="token_affiliate_login" value="{$login_token}" type="hidden" />
<div class="form-group login-input">
<i class="fa fa-user overlay"></i>
<input type="text" class="form-control text-input" placeholder="{$login_username}" name="userid" />
</div>
<div class="form-group login-input">
<i class="fa fa-key overlay"></i>
<input class="form-control text-input" placeholder="{$login_password}" type="password" name="password" autocomplete="off">
</div>
<div class="row">
<div class="col-sm-6">
<button type="submit" class="btn btn-darkblue-1 btn-block">{$login_now}</button>
</div>
<div class="col-sm-6">
<a href="login.php?lost_password=true" class="btn btn-default btn-block">{$login_send_title}</a>
</div>
{if isset($idev_facebook_enabled)}
<div class="col-sm-12" style="padding-top:20px; padding-bottom:20px; text-align:center;">
<a href="{$fb_login_url}" class="btn btn-social btn-facebook"><i class="fa fa-facebook"></i> {$fb_login}</a>
</div>
{/if}
</div>
<div class="row">
<div class="col-sm-12">
<a href="index.php" class="btn btn-sm btn-primary btn-block">{$login_return}</a>
</div>
</div>

</form>

{else}

<h2>{$login_send_title}</h2>
<p>{$login_lost_details}</p>

<form role="form" method="POST" action="login.php">
<input name="token_affiliate_creds" value="{$send_pass_token}" type="hidden" />
<input name="lost_password" value="true" type="hidden" />
<div class="form-group login-input">
<i class="fa fa-user overlay"></i>
<input type="text" class="form-control text-input" placeholder="{$login_send_username}" name="sendpass" />
</div>
<div class="row">
<div class="col-sm-6">
<button type="submit" class="btn btn-darkblue-1 btn-block">{$login_send_pass}</button>
</div>
<div class="col-sm-6">
<a href="login.php" class="btn btn-default btn-block">{$login_now}</a>
</div>
</div>
<div class="row">
<div class="col-sm-12">
<a href="index.php" class="btn btn-sm btn-primary btn-block">{$login_return}</a>
</div>
</div>
</form>

{/if}

</div>
</div>
</div>
</div>

{literal}
<script type="text/javascript" src="templates/source/lightbox/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="templates/source/common/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="templates/source/common/pace/js/pace.min.js"></script>
{/literal}

</body>
</html>