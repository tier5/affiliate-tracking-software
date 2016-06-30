<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css"/>
    <link href="/assets/global/css/plugins.min.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME GLOBAL STYLES -->

    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->
    <link href="/css/cardjs/card-js.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>

    <link href="/css/admin.css" rel="stylesheet" type="text/css" />
    <link href="/css/agencyorder.css" rel="stylesheet" type="text/css" />
    <link href="/css/subscription.css" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="favicon.ico" />

    <script type="text/javascript" src="/js/vendor/jquery-2.1.1.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" href="/js/vendor/minicolors/jquery.minicolors.css" />
</head>
<!-- END HEAD -->

<body>
{{ flashSession.output() }}
{{ content() }}
</body>
</html>