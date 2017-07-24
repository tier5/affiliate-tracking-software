<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>RV Affiliate</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/admin/dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="/admin/plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="/admin/plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="/admin/plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="/admin/plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">RV</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">RV Affiliate</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="collapse navbar-collapse" id="app-navbar-collapse">
          <!-- Left Side Of Navbar -->
          <ul class="nav navbar-nav">
              &nbsp;
          </ul>
          <ul style="margin-right: 8px;" class="nav navbar-nav navbar-right">
              <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                          <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                Logout
                          </a>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                          </form>
                      </li>
                  </ul>
              </li>
          </ul>
      </div>    
    </nav>
  </header>

  <!--import sidebar nav -->
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>
        <li{{{ (stripos(url()->current(), 'links') === false ? ' class="active"' : '') }}}>
          <a href="/affiliate">
            <i class="fa fa-pie-chart"></i> <span>Dashboard</span>
          </a>
        </li>
        <!--<li{{{ (stripos(url()->current(), 'links') !== false ? ' class="active"' : '') }}}>
          <a href="/affiliate/links">
            <i class="fa fa-link"></i>
            <span>Links</span>
          </a>
        </li> -->
        @if(auth()->user()->role == 'admin')
        <li{{{ (stripos(url()->current(), 'links') !== false ? ' class="active"' : '') }}}>
          <a href="{{ route('getAdmin') }}">
            <i class="fa fa-link"></i>
            <span>Add Affiliate </span>
          </a>
        </li>
         @else
        <li{{{ (stripos(url()->current(), 'links') !== false ? ' class="active"' : '') }}}>
          <a href="{{ route('showAffiliate',[Auth::user()->id]) }}">
            <i class="fa fa-link"></i>
            <span>Add Affiliate</span>
          </a>
        </li>
        @endif
        <li{{{ (stripos(url()->current(), 'links') !== false ? ' class="active"' : '') }}}>
          <a href="{{ route('allAffiliate') }}">
            <i class="fa fa-link"></i>
            <span>Show Affiliate</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
 <div class="clear"> </div> 
  <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">

 

 @section('content')
 
 @if(!empty($result) || session()->has('message'))
 <div class="container" >
    <div class="row" style="margin-top: 30px;">
        <div class="col-md-8 col-md-offset-2">
         @if (!empty($message))
              <div class="alert alert-success alert-dismissable">{{ $message }}</div>
          @endif
            <div class="panel panel-default">
                <div class="panel-heading">Add Affiliate</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('addAffiliate') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus>

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="name" type="password" class="form-control" name="password" value="{{ old('password') }}" required autofocus>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        

                         <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <!--<label for="name" class="col-md-4 control-label">Affiliate URL</label> -->

                            <div class="col-md-6">
                                <?php if(!empty($result) || session()->has('message')){ 
                                   //$url = $result['url'].'?id='.uniqid(); 
                                   $key = uniqid(); 
                                   $url = url('/').'?id='.$key;
                                                                        ?>
                                <input id="url" type="hidden" name="url" value="{{ $url }}" >
                                <input id="key" type="hidden" name="key" value="{{ $key }}" >
                                <?php } ?>
                            </div>
                        </div>

                       

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                <textarea id="description" class="form-control" name="description" required>{{ old('description') }}</textarea>

                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Add Affiliate
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
  </div>
  

<!-- jQuery 2.2.3 -->
<script src="/admin/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="/admin/plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="/admin/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/admin/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="/admin/plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="/admin/plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="/admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/admin/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/admin/dist/js/app.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/admin/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/admin/dist/js/demo.js"></script>
</body>
</html>
