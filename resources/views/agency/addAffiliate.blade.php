@extends('layouts.main')
@section('title')
    Add Affiliate
@endsection
@section('content')
    <div class="content-wrapper">
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
@endsection