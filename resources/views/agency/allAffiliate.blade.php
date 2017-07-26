@extends('layouts.main')

@section('title')
   All Affiliate
@endsection

@section('content')
    <div class="content-wrapper">
            <section class="content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">AFFILIATES</h3>

                                <div class="box-tools">
                                    <div style="width: 150px;" class="input-group input-group-sm">
                                        <input type="text" placeholder="Search" class="form-control pull-right" name="table_search">

                                        <div class="input-group-btn">
                                            <button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive no-padding">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>AFFILIATE</th>
                                            <th>EMAIL</th>
                                            <th>PHONE</th>
                                            <th>AFFILIATE URL</th>
                                            <th>STATUS</th>
                                            <th>JOINED</th>
                                            <th>VISITORS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($affiliates as $affiliate)
                                        <tr>
                                            <td><a href="{{route("agency.affiliateDetail",["affiliateId" => $affiliate->id])}}">{!! isset($affiliate->user->name) ? $affiliate->user->name : '' !!}</a></td>
                                            <td>{!! isset($affiliate->user->email) ? $affiliate->user->email : '' !!}</td>
                                            <td>{!! $affiliate->affiliate_phone !!}</td>
                                            <td>{!! $affiliate->affiliate_url !!}</td>
                                            <td>{!! (!is_null($affiliate->user) && is_null($affiliate->user->deleted_at)) ? 'Active' : 'Inactive' !!}</td>
                                            <td>{!! isset($affiliate->user->created_at) ? $affiliate->user->created_at->toFormattedDateString() : '' !!}</td>
                                            <td></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">No Affiliates Found.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>

                    </div>
                </div>
            </section>
    </div>
@endsection