@extends('layouts.main')

@section('title')
    Refunds
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class=" col-md-10 pull-left">
                                    <h4>Refund details</h4>
                                </div>
                            </div>
                            <div class="row">
                                @if(\Session::has('error'))
                                    <h4 style="color: red;">{{ \Session::get('error') }}</h4>
                                @endif
                            </div>
                        </div>
                        <div style="padding-bottom: 10px;"></div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-list">
                                    <thead>
                                        <tr>
                                            <th>Campaign</th>
                                            <th>Affiliate</th>
                                            <th>Refunded Amount</th>
                                            <th>Refunded Commission</th>
                                            <th>Refunded Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($refunds as $refund)
                                            <tr>
                                                <td>{{ $refund->campaign->name }}</td>
                                                <td>{{ $refund->order->log->affiliate->user->name }}</td>
                                                <td>${{ $refund['amount'] }}</td>
                                                <td>
                                                    @if($refund->order->product->method == 1)
                                                        ${{ $refund['amount'] * $refund->order->product->commission / 100 }}
                                                    @else
                                                        ${{ $refund['amount'] }}
                                                    @endif
                                                </td>
                                                <td>{{ date('l jS \of F Y,  h:i:s A',strtotime($refund['created_at']))  }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection