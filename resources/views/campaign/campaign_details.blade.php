@extends('layouts.main')

@section('title')
    Campaign Details
@endsection

@section('style')
    <style>
        .tab-content {
            box-shadow: 0 0 1px;
        }
        .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover{background-color: #3C8DBC;
            color: #fff; padding: 10px 30px;}
        .tab-pane{padding: 10px;}
    </style>
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {display:none;}

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class=" col-md-4 pull-left">
                                    <h4>Campaigns Details</h4>
                                </div>
                            </div>
                            <div id="loader" style="line-height: 100px; text-align: center; display: none;">
                                <strong>Please Wait</strong> <img alt="activity indicator" src="{{ url('/') }}/images/ajax-loader.gif">
                            </div>
                            <div class="row">
                                @if(\Session::has('error'))
                                    <h4 style="color: red;">{{ \Session::get('error') }}</h4>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
                                        <li><a data-toggle="tab" href="#menu1">Product Script</a></li>
                                        <li><a data-toggle="tab" href="#menu2">Checkout Script</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="home" class="tab-pane fade in active">
                                            <div class="panel panel-default panel-info">
                                                <div class="panel-heading">
                                                    <div class="row">
                                                        <div class=" col-md-10 pull-left">
                                                            <h4>Affiliates</h4>
                                                        </div>
                                                        <div class="col-md-2 pull-right">
                                                            <button class="btn btn-success" data-toggle="modal" data-target="#addAffiliateModal">Add Affiliate</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <tr>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Status</th>
                                                                <th>Joined</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @foreach($campaigns->affiliate as $affiliate)
                                                                <tr>
                                                                    <td>{{ $affiliate->name }}</td>
                                                                    <td>{{ $affiliate->email }}</td>
                                                                    <td>
                                                                        <div class="row">
                                                                            <div class="col-md-6">
                                                                                {{ $affiliate->approve_status == 1?'Approved':'Pending' }}
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                @if($affiliate->approve_status != 1)
                                                                                    <label class="switch">
                                                                                        <input name="approve" id="approve" data-id="{{ $affiliate->id }}" type="checkbox">
                                                                                        <span class="slider"></span>
                                                                                    </label>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ date("F j, Y, g:i a",strtotime($affiliate->created_at)) }}</td>
                                                                    <th>
                                                                        <a href="{{ route('details.affiliate',[$affiliate->id]) }}" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-list-alt"></span></a>
                                                                        <button class="btn btn-danger btn-xs deleteAffiliate" data-id="{{ $affiliate->id }}"><span class="glyphicon glyphicon-trash"></span></button>
                                                                    </th>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="menu1" class="tab-pane fade">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="pull-right">
                                                        <a title="copy to clip-board" id="urlCopy" class="btn btn-success btn-md"><span class="glyphicon glyphicon-copy"></span> </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <strong id="copyScript"><p>&lt;script src="{{ url('/') }}/js/affiliate_track.js" type="application/javascript"&gt;&lt;/script&gt;</p>
                                            <p>&lt;script type="application/javascript"&gt;</p>
                                                   <p> &nbsp;&nbsp;&nbsp;Affiliate.key = '{{ $campaigns->key }}';</p>
                                                    <p> &nbsp;&nbsp;&nbsp;Affiliate._init();</p>
                                                    <p>    Affiliate._watch();</p>
                                           <p> &lt;/script&gt;</p></strong>
                                        </div>
                                        <div id="menu2" class="tab-pane fade">
                                            <strong><p>&lt;script src="{{ url('/') }}/js/affiliate_track.js" type="application/javascript"&gt;&lt;/script&gt;</p>
                                                <p>&lt;script type="application/javascript"&gt;</p>
                                                    <p> &nbsp;&nbsp;&nbsp;Affiliate.key = '{{ $campaigns->key }}';</p>
                                                    <p> &nbsp;&nbsp;&nbsp;Affiliate._init();</p>
                                                    <p> &nbsp;&nbsp;&nbsp;Affiliate._watch()</p>
                                                    <p> &nbsp;&nbsp;&nbsp;Affiliate._checkout();</p>
                                                <p> &lt;/script&gt;</p></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal -->
    <div id="addAffiliateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Affiliate</h4>
                </div>
                <div class="modal-body">
                    <form id="register-form" action="{{route('affiliate.registration')}}" method="post">
                        <div class="form-group">
                            <input type="text" name="registration_username" id="username" tabindex="1" class="form-control hide-error" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input type="email" name="registration_email" id="email" tabindex="1" class="form-control hide-error" placeholder="Email Address">
                        </div>
                        <div class="form-group">
                            <input type="password" name="registration_password" id="password" tabindex="2" class="form-control hide-error" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <input type="password" name="registration_confirm_password" id="confirm-password" tabindex="2" class="form-control hide-error" placeholder="Confirm Password">
                        </div>
                        <div class="form-group" id="error" style="display: none;">
                            <h4 id="error_text" style="color: red;"></h4>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <input type="button" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-success" value="Register Now">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        };

        $('#urlCopy').on('click',function(){
            var url=$('#copyScript');
            copyToClipboard(url);
            toastr.info('Copied To Clipboard');
        });
        $('#approve').on('change',function () {
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('approve.affiliate') }}",
                type: "POST",
                data: {
                    id: id,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function(){ jQuery("#loader").show(); },
                complete: function(){ jQuery("#loader").hide(); },
                success: function (data) {
                    if (data.success) {
                        swal({
                            title: "Success!",
                            text: data.message,
                            type: "success"
                        }).then( function(){
                            window.location.reload();
                        },function (dismiss) {
                            window.location.reload();
                        });
                    } else {
                        swal({
                            title: "Error!",
                            text: data.message,
                            type: "error"
                        });
                    }
                }
            });
        });
        $('#register-submit').on('click',function () {
            var name = $('#username').val();
            if(name == ''){
                $('#error_text').text('Please Enter a User Name');
                $('#error').show();
                return false;
            }
            var email = $('#email').val();
            if(email == ''){
                $('#error_text').text('Please Enter a Email');
                $('#error').show();
                return false;
            }
            var password = $('#password').val();
            if(password == '' || password.length < 6){
                $('#error_text').text('Please Enter a Password and Password should be at-least 6 character long');
                $('#error').show();
                return false;
            }
            var cPassword = $('#confirm-password').val();
            if(cPassword == '' || cPassword.length < 6){
                $('#error_text').text('Please Enter a Confirm Password');
                $('#error').show();
                return false;
            }
            if(cPassword != password){
                $('#error_text').text('Password and Confirm password not Match');
                $('#error').show();
                return false;
            }
            $.ajax({
                url: "{{ route('add.affiliate.new') }}",
                type: "POST",
                data: {
                    name: name,
                    email: email,
                    password: password,
                    key: '{{ $campaigns->key }}',
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    if (data.success) {
                        $('#addAffiliateModal').modal('hide');
                        swal({
                            title: "Success!",
                            text: data.message,
                            type: "success"
                        }).then( function(){
                            window.location.reload();
                        },function (dismiss) {
                            window.location.reload();
                        });
                    } else {
                        swal({
                            title: "Error!",
                            text: data.message,
                            type: "error"
                        });
                    }
                }
            });
        });
        $('.hide-error').on('click',function () {
            $('#error').hide();
        });
        $('.deleteAffiliate').on('click',function () {
            var id = $(this).data('id');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(function () {
                $.ajax({
                    url: "{{ route('delete.affiliate') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        if (data.success) {
                            swal({
                                title: "Success!",
                                text: data.message,
                                type: "success"
                            }).then(function () {
                                window.location.reload();
                            }, function (dismiss) {
                                window.location.reload();
                            });
                        } else {
                            swal({
                                title: "Error!",
                                text: data.message,
                                type: "error"
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
