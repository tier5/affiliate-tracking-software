
<div class="content" id="thankyou">
{{ content() }}

<div class="title">Thank you for signing up</div>
<div class="name"><?=(isset($_SESSION['name'])?$_SESSION['name']:'')?></div>
<div class="description">please check your email address <a href="mailto:<?=(isset($_SESSION['email'])?$_SESSION['email']:'')?>"><?=(isset($_SESSION['email'])?$_SESSION['email']:'')?></a> to confirm your account.</div>

</div>