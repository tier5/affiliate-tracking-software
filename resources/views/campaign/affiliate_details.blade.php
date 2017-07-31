@extends('layouts.main')

@section('title')
    Affiliate Details
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
                                    <h4>Affiliate Details</h4>
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
                            <section class="table-bordered">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Affiliate Information</h4>
                                    </div>
                                </div>
                                <hr/>
                                <div class="row">
                                    <div class="col-md-3">
                                        Affiliate Link :
                                    </div>
                                    <div class="col-md-6">
                                        {{ $affiliate->campaign->url }}
                                    </div>
                                    <div class="col-md-3">
                                        <a href="#" class="pull-right">Copy Link</a>
                                    </div>
                                </div>
                                <div style="padding-bottom: 5px;"></div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Affiliate Registration Link :
                                    </div>
                                    <div class="col-md-6">
                                        {{ route('affiliate.registerForm',[$affiliate->campaign->key])}}
                                    </div>
                                    <div class="col-md-3">
                                        <a href="#" class="pull-right">Send Email</a>
                                    </div>
                                </div>
                                <div style="padding-bottom: 5px;"></div>
                                <div class="row">
                                    <div class="col-md-3">
                                        Affiliate Email :
                                    </div>
                                    <div class="col-md-9">
                                        {{ $affiliate->user->email }}
                                    </div>
                                </div>
                            </section>
                            <div style="padding-bottom: 25px;"></div>
                            <section class="table-bordered">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>Activity</h4>
                                    </div>
                                </div>
                                <hr/>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection