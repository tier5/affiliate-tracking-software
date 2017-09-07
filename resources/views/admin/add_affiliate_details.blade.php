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
                                <div class=" col-md-10 pull-left">
                                    <h4>All affiliate</h4>
                                </div>
                            </div>
                            <div class="row">
                                @if(\Session::has('error'))
                                    <h4 style="color: red;">{{ \Session::get('error') }}</h4>
                                @endif
                            </div>
                        </div>
                        <div style="padding-bottom: 10px;"></div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="info-box" style="min-height: 47px !important;">
                                    <div class="info-box-content">
                                        <div class="col-md-2">
                                            Filter by Campaign
                                        </div>
                                        <div class="col-md-6">
                                            <select name="campaign" class="form-control filter">
                                                <option value="0">Select Campaign</option>
                                                @forelse($campaignDropDown as $campaign)
                                                    <option value="{{ $campaign->id }}" {{ request()->has('campaign') ? (request()->input('campaign') == $campaign->id ? 'selected' : null) : null }}>{{ $campaign->name }}</option>
                                                @empty
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="box1">
                                            <div class="row one-row">
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">
                                                        @if($visitors > 0)
                                                            ${!! round($gross_commission /  $visitors,2) !!}
                                                        @else
                                                            {{ $visitors }}
                                                        @endif
                                                    </div>
                                                    <div class="normal-txt">EPC</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">{{ $visitors }}</div>
                                                    <div class="normal-txt">Unique Clicks</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">{{ $totalSales }}</div>
                                                    <div class="normal-txt">Sales</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">
                                                        @if($visitors > 0 & $totalSales >0)
                                                            {!! round($totalSales / $visitors*100,2) !!}%
                                                        @else
                                                            0
                                                        @endif
                                                    </div>
                                                    <div class="normal-txt">Conversion Rate</div>
                                                </div>
                                            </div>
                                            <div class="row one-row">
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">{{ "$" . number_format($gross_commission, 2, '.', ',') }}</div>
                                                    <div class="normal-txt">Gross Commission</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">${{ round($gross_commission-$refundCommission,2) }}</div>
                                                    <div class="normal-txt">Net Commission</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">{{ $refundCount }}</div>
                                                    <div class="normal-txt">Refunds</div>
                                                </div>
                                                <div class="col-md-3 col-sm-3">
                                                    <div class="blue-txt">${{ round($refundCommission,2) }}</div>
                                                    <div class="normal-txt">Refunds Amount</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- USERS LIST -->
                                <div class="box box-success">
                                    <div class="box-header with-border">
                                        <h2 class="box-title">Commission Details</h2>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                       <div class="row">
                                           <div class="col-md-12">
                                               <div class="col-md-3">
                                                   <h2>Total Commission: ${{ $netCommission }}</h2>
                                               </div>
                                               <div class="col-md-3">
                                                   <h2>Commission Paid: ${{ $paidCommission }}</h2>
                                               </div>
                                               <div class="col-md-3">
                                                   <h2>Commission Due: ${{ round($netCommission - $paidCommission,2) }}</h2>
                                               </div>
                                               @if(count($affiliate) > 0)
                                               <div class="col-md-3" style="padding-top: 16px;">
                                                   <button class="btn btn-success" id="pay_commission" data-affiliate="{{ $affiliate[0]->user_id }}" data-campaign="{{ $affiliate[0]->campaign_id }}" data-commission="{{ $netCommission - $paidCommission }}" data-toggle="modal">Pay Commission</button>
                                               </div>
                                               @endif
                                           </div>
                                       </div>
                                        <!-- /.users-list -->
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!--/.box -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- USERS LIST -->
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h2 class="box-title">Commissions on sold products</h2>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body no-padding">
                                        <div class="table table-responsive">
                                            <table id="sold-product" class="table table-bordered table-hover datatable">
                                                <thead>
                                                <td>Email</td>
                                                <td>Product Name</td>
                                                <td>Price</td>
                                                <td>My Commission</td>
                                                <td>Status</td>
                                                </thead>
                                                <tbody>
                                                @foreach($sold_products as $product)
                                                    <tr>
                                                        <td>{{ $product['saleEmail']?$product['saleEmail']:$product['email'] }}</td>
                                                        <td>{{ $product['name'] }}</td>
                                                        <td>${{ $product['total_sale_price'] }}</td>
                                                        <td>{{ "$" . number_format($product['my_commission'], 2, '.', ',') }}</td>
                                                        <td>{{ ($product['status'] == 2)?'Refunded':'sale' }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- /.users-list -->
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!--/.box -->
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Pay Commission</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="money" class="control-label col-md-4">Commission amount: </label>
                                    <div class="col-md-8">
                                        <input type="text" id="money" class="form-control">
                                    </div>
                                </div>
                                <input type="hidden" id="commission_pay">
                                <input type="hidden" id="affiliate_pay">
                                <input type="hidden" id="campaign_pay">
                                <div class="form-group" id='errorData' style="display: none; color: red;">
                                    <label for="error" class="control-label col-md-4">Error: </label>
                                    <div class="col-md-8">
                                        <h4><div id="error"></div></h4>
                                    </div>
                                </div>
                                <button type="button" id="final_pay" class="btn btn-success form-control">Pay</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('/') }}/admin/dist/js/pages/dashboard2.js"></script>
        <script type="text/javascript">
        $(document).ready(function() {
            $.fn.dataTable.ext.errMode = 'none';
            $('.datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": true,
                "autoWidth": false
            });

            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($(element).text()).select();
                document.execCommand("copy");
                $temp.remove();
            };

            $('.copy').on('click',function(){
                var url=$(this).parent().prev().children('.url');
                copyToClipboard(url);
                toastr.info('Copied To Clipboard');
            });
            $('.filter').on('change',function () {
                var campaign = $(this).val();
                setGetParameter('campaign',campaign);
            });
            $('#pay_commission').on('click',function () {
                var affiliate = $(this).data('affiliate');
                var campaign = $(this).data('campaign');
                var commission = $(this).data('commission');
                $('#money').val(commission);
                $('#campaign_pay').val(campaign);
                $('#affiliate_pay').val(affiliate);
                $('#commission_pay').val(commission);
                $('#errorData').hide();
                $('#myModal').modal('show');
            });
            $('#final_pay').on('click',function () {
                var campaign = $('#campaign_pay').val();
                var affiliate = $('#affiliate_pay').val();
                var commission = parseFloat($('#commission_pay').val());
                var acctualCommission = parseFloat($('#money').val());
                if(acctualCommission > commission){
                    $('#error').text('Commission should not be exceeding  $'+ commission);
                    $('#errorData').show();
                    return false;
                }
                if(acctualCommission <= 0){
                    $('#error').text('Please Enter some commission amount');
                    $('#errorData').show();
                    return false;
                }
                var reg = /^-?\d*\.?\d*$/;
                if(!reg.test(acctualCommission)){
                    $('#error').text('Please Enter some commission amount');
                    $('#errorData').show();
                    return false;
                }
                swal({
                    title: 'Are you sure?',
                    text: "you wish to mark these commissions as paid?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, pay!'
                }).then(function () {
                    $.ajax({
                        url: "{{ route('pay.commission') }}",
                        type: "POST",
                        data: {
                            commission: acctualCommission,
                            affiliate: affiliate,
                            campaign: campaign,
                            _token: "{{ csrf_token() }}"
                        },
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
                })
            });
            $('#money').on('keyup',function () {
                $('#errorData').hide();
            });
        });
    </script>
@endsection
