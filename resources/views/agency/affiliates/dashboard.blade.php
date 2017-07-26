@extends('layouts.main')
@section('title')
    Add Affiliate
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="container" >
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-8 col-md-offset-2">
                    @if (!empty($message))
                        <div class="alert alert-success alert-dismissable">{{ $message }}</div>
                    @endif
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Your Referral Link
                        </div>
                        <div class="panel-body">
                            <i class="fa fa-info fa-fw"></i><strong>Affiliate Information :</strong>
                            <hr>
                            <strong>Affiliate: </strong>{!! (isset($affiliate) && !is_null($affiliate->user)) ? $affiliate->user->name : '' !!} <br>
                            <strong>Email: </strong>{!! (isset($affiliate) && !is_null($affiliate->user)) ? $affiliate->user->email : '' !!}<br>
                            <strong>Affiliate URL:</strong>{!! (isset($affiliate)) ? $affiliate->affiliate_url : '' !!}<br>
                            <div>
                                Share Your Link: <i class="fa fa-facebook fa-fw"></i>
                            </div>
                        </div>
                    </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Your Progress
                            </div>
                            <div class="panel-body">
                                <i class="fa fa-info fa-fw"></i><strong>Affiliate Information :</strong>
                                <hr>
                                <strong>Visitor:100</strong>&nbsp&nbsp&nbsp
                                <strong>Signed:100 </strong>&nbsp&nbsp&nbsp
                                <strong>Purchases:20 </strong>&nbsp&nbsp&nbsp
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Your Payments
                            </div>
                            <div class="panel-body">
                                Below you can see how much compensation you have been paid and how much you have accrued
                                <table class="table table-hover">
                                    <thead>
                                        <th>Compensation</th>
                                        <th>Commissions Due</th>
                                        <th>Commissions Upcoming</th>
                                        <th>Commissions Paid</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>United States Dollars </td>
                                            <td> $317.25</td>
                                            <td>$0.00</td>
                                            <td>$19434.50</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection