<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="icon" href="/favicon.ico" />
  <title>Review Velocity</title>

  <link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css' />
  <!-- Bootstrap core CSS -->
  <link href="/css/bootstrap.min.css" rel="stylesheet" />

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <link href="/css/ie10-viewport-bug-workaround.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="/css/main.css" rel="stylesheet" />

  <link rel="stylesheet" href="/css/font-awesome.min.css" />
  <link rel="stylesheet" href="/css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <?php
    if (isset($sms_button_color) && $sms_button_color != '') {
      ?>
  <style>
    //body { background-color: rgba(<?=$r?>, <?=$g?>, <?=$b?>, 0.8); }
    //.subtext { color: White; }
    .review .rounded .row .btn-recommend, .review .rounded .row .btn-recommend:hover { background: none;
      background-color: <?=$sms_button_color?>; border-color: <?=$sms_button_color?>; }

  </style>
  <?php
    }
    ?>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>

<body>
{{ content() }}

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script>window.jQuery || document.write('<script src="/js/vendor/jquery.min.js"><\/script>')</script>
<script src="/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="/js/ie10-viewport-bug-workaround.js"></script>
<script src="/js/star-rating.js" type="text/javascript"></script>
<script src="/js/browser-deeplink.js"></script>
<script src="/js/main.js"></script>
</body>
</html>