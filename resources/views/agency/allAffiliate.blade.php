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
                                <h3 class="box-title">Affilated User Table</h3>

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
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>URL</th>
                                            <th>Join</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $value)
                                        <tr>
                                            <td>{{ $value['name'] }}</td>
                                            <td>{{ $value['phone'] }}</td>
                                            <td>{{ $value['url'] }}</td>
                                            <td>{{ $value['joined'] }}</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </tbody>


                                    @endforeach
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>

                    </div>
                </div>
            </section>
    </div>
@endsection