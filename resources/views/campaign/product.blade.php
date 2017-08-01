@extends('layouts.main')

@section('title')
    Campaign Products
@endsection

@section('style')

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
                                    <h4>Campaign Products</h4>
                                </div>
                                <div class=" col-md-2 pull-right">
                                    <button class="btn btn-success"  data-toggle="modal" data-target="#addProduct">
                                        Add Product
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                @if(\Session::has('error'))
                                    <h4 style="color: red;">{{ \Session::get('error') }}</h4>
                                @endif
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-list">
                                    <thead>
                                        <tr>
                                            <td>Product Name</td>
                                            <td>Payment Details</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>name</td>
                                            <td>Details</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-11">
                                <h3 class="modal-title" id="exampleModalLabel">Add Product</h3>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form id="addProductForm" method="POST" class="form-horizontal col-md-12">
                            <div class="form-group">
                                <label class="control-label col-md-2" for="product_name">Product Name:</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control hide-error" id="product_name" name="product_name" placeholder="Edit Product Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="product_details">Product Details:</label>
                                <div class="col-md-10">
                                    <textarea rows="5" cols="17" name="product_details" class="form-control product_details" id="product_details"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2" for="product_pricing">Payment Settings :</label>
                                <div class="col-md-5">
                                    <label class="control-label">Price:</label>
                                    <input type="text" name="price" id="product_pricing" class="form-control">
                                </div>
                                <div class="col-md-5">
                                    <label class="control-label">Payment Frequency:</label>
                                    <select name="price_frequency" class="form-control" id="price_frequency">
                                        <option value="one_time">One-Time</option>
                                        <option value="recurring">Recurring</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group"  id="recurring_options" style="display: none;">
                                <label class="control-label col-md-2">&nbsp;</label>
                                <div class="col-md-5">
                                    <label class="control-label">Recurring period:</label>
                                    <select name="recurringPeriod" class="form-control" id="recurringPeriod">
                                        <option value="day">Day(s)</option>
                                        <option value="month">Month(s)</option>
                                        <option value="year">Year(s)</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="control-label">Recurring Frequency:</label>
                                    <select name="recurring_frequency" class="form-control" id="recurring_frequency">
                                        <option value="">------Select------</option>
                                    </select>
                                </div>
                            </div>
                            <input name="campaign_id" id="campaign_id" type="hidden" value="{{ $campaign_id }}">
                            <div class="form-group" id="edit_error" style="display: none; color: red;">
                                <label class="control-label col-md-2" for="edit_error">Error:</label>
                                <div class="col-md-10">
                                    <h4 id="edit_error_text"></h4>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addProduct">Add Product</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')

@endsection
