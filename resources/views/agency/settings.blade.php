@extends('layouts.main')

@section('title')
    Settings
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container" >
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-8 col-md-offset-2">
                    @if (!empty(session('message')))
                        <div class="alert alert-success alert-dismissable">{{ Session('message') }}</div>
                    @endif
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="pull-left">
                                    Register URL
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#register">Register Your URL</button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="box-body table-responsive no-padding">
                                <table class="table">
                                    <thead>
                                        <th>URL</th>
                                        <th>KEY</th>
                                    </thead>
                                    <tbody>
                                    @if(count($url) > 0)
                                    @foreach($url as $value)
                                        <tr>
                                            <td>{{ $value['url'] }}</td>
                                            <td >{{ $value['key'] }}</td>
                                        </tr>
                                    @endforeach
                                    @else
                                        <tr>
                                            <th colspan="2">No URL Found</th>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="register" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Register your URL</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register.url') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">URL</label>

                            <div class="col-md-6">
                                <input id="name" type="url" class="form-control" name="url" value="{{ old('url') }}" required autofocus>

                                @if ($errors->has('url'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register URl
                                </button>
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