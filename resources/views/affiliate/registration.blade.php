<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register</title>
    <link href="{{url('/')}}/fav.png" rel="shortcut icon">
    <!--Registration Form CSS-->
    <link href="{{url('/')}}/css/affiliateRegister.css" rel="stylesheet" type="text/css">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ url('/') }}/admin/bootstrap/css/bootstrap.min.css">

    <!-- JQuery 1.10.2 -->
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
 <body>
 <div class="container" style="margin-top:5%;">
     <div class="row">
         @if(\Session::has('error'))
             <h4 style="color: red;">{{ \Session::get('error') }}</h4>
         @endif
     </div>
 </div>
 @if(Auth::check())
     <div class="container" style="margin-top:5%;">
         <div class="row">
             <div class="jumbotron" style="box-shadow: 2px 2px 4px #000000;">
                 <h2 class="text-center">You Cannot Promote Your Own Products!</h2>
                 <center><div class="btn-group" style="margin-top:50px;">
                     </div></center>
             </div>
         </div>
     </div>
 @else
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="lft-panel">
                 <h1 class="center-block">{{ $campaign->user->name }} ( <small style="color: blue;">{{ $campaign->user->email }}</small> )</h1>
                <p>
                    <h2>has invited you to become an affiliate for</h2>
                </p>
                <p class="center-block">
                    <h1>{{ $campaign->name }}</h1>
                </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-login">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
{{--
                                <form id="login-form" action="{{route('affiliate.login')}}" method="post" role="form" style="display: block;">
                                    {{csrf_field()}}
                                    <h2>Existing Affiliate</h2>
                                    <div class="form-group">
                                        <input type="email" name="login_email" id="email" tabindex="1" class="form-control" placeholder="Email" value="{{old('login_email')}}">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="login_password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                    </div>
                                    <div class="col-xs-6 form-group pull-left checkbox">
                                        <input id="checkbox1" type="checkbox" name="remember">
                                        <label for="checkbox1">Remember Me</label>
                                    </div>
                                    <input type="hidden" name="affiliateKey" value="{{ $campaign->key }}">
                                    <div class="col-xs-6 form-group pull-right">
                                        <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">
                                    </div>
                                    <br><br>
                                    <div class="form-group">
                                    @if(count($errors) > 0 && $errors->has('login_errors'))
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach($errors->all() as $key=>$error)
                                                    @if(count($errors) != $key+1)
                                                        <li>{{$error}}</li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    </div>
                                </form>
--}}
                                <form id="register-form" action="{{route('affiliate.registration')}}" method="post" {{--style="display: none;"--}}>
                                    {{csrf_field()}}
                                    <h2>Register Now</h2>
                                    <div class="form-group">
                                        <input type="text" name="registration_username" id="username" tabindex="1" class="form-control" placeholder="Username" value="{{old('registration_username')}}">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="registration_email" id="email" tabindex="1" class="form-control" placeholder="Email Address" value="{{old('registration_email')}}">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="registration_password" id="password" tabindex="2" class="form-control" placeholder="Password">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" name="registration_confirm_password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password">
                                    </div>
                                    <input type="hidden" name="affiliateKey" value="{{ $campaign->key }}">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-3">
                                                <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
                                            </div>
                                        </div>
                                    </div>
                                    @if(count($errors) > 0 && $errors->has('registration_errors'))
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach($errors->all() as $key=>$error)
                                                @if(count($errors) != $key+1)
                                                    <li>{{$error}}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                   {{-- <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-6 tabs">
                                <a href="javascript:void(0)" class="active" id="login-form-link"><div class="login">LOGIN</div></a>
                            </div>
                            <div class="col-xs-6 tabs">
                                <a href="javascript:void(0)" id="register-form-link"><div class="register">REGISTER</div></a>
                            </div>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>
    <!--Registration Form Javascript-->
    <script src="{{url('/')}}/js/affiliateRegister.js"></script>
    <script>
        @if($errors->first('registration_errors'))
            $("#register-form").show();
            $("#login-form").hide();
            $('#login-form-link').removeClass('active');
            $('#register-form-link').addClass('active');
            e.preventDefault();
        @endif
        @if($errors->first('login_errors'))
            $("#register-form").hide();
            $("#login-form").show();
            $('#login-form-link').addClass('active');
            $('#register-form-link').removeClass('active');
            e.preventDefault();
        @endif
    </script>
 @endif
 </body>
</html>
