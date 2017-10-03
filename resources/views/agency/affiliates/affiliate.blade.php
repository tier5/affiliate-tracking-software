@extends('layouts.main')
@section('title')
    Add Affiliate
@endsection
@section('content')
    <div class="content-wrapper">
            <div class="container" >
                <div class="row" style="margin-top: 30px;">
                    <div class="col-md-11">
                        @if (!empty($message))
                            <div class="alert alert-success alert-dismissable">{{ $message }}</div>
                        @endif
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h5><strong>Affiliate: </strong>{!! (!is_null($affiliate->user)) ? $affiliate->user->name : '' !!}</h5>
                                <h5><strong>Email: </strong>{!! (!is_null($affiliate->user)) ? $affiliate->user->email : '' !!}</h5>
                                <h5><strong>Joined At: </strong>{!! (!is_null($affiliate->user)) ? $affiliate->user->created_at->toFormattedDateString() : ''!!}</h5>
                            </div>
                            <div class="panel-body">
                                <i class="fa fa-info fa-fw"></i><strong>Affiliate Information :</strong>
                                <hr>
                                <strong>Affiliate Link:</strong><br>
                                <strong>Affiliate's Dashboard: </strong><a href="{{route('agency.affiliateDashboard',['affiliateKey' => $affiliate->affiliate_key])}}">{{url('/')}}/affiliate/{{$affiliate->affiliate_key}}</a><br>
                                <strong>Affiliate Status: </strong>  {!! (!is_null($affiliate->user) && is_null($affiliate->user->deleted_at)) ? 'Active' : 'Inactive'!!}<br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-11">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              TRAFFIC
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <thead>
                                        <th>IP</th>
                                        <th>PLATFORM</th>
                                        <th>BROWSER</th>
                                        <th>STATUS</th>
                                        <th>FIRST SEEN</th>
                                        <th>LAST VISIT</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Visitor at 111.93.181.226 </td>
                                            <td>Visitor at 111.93.181.226 </td>
                                            <td>Chrome</td>
                                            <td>Visitor</td>
                                            <td>07/24/17</td>
                                            <td>07/26/17 at 12:26 AM</td>
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