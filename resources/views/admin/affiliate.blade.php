@extends('layouts.main')

@section('title')
    All affiliate
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
                                <label class="col-md-2 col-sm-2 col-xs-2">
                                    Filter by Campaign
                                </label>
                                <div class="col-md-4">
                                    <select class="form-control filter" name="affiliate_id">
                                        <option value="0"> Select Campaign</option>
                                        @foreach($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}" {{ (isset($_GET['campaign']) && $_GET['campaign'] > 0 && $_GET['campaign'] == $campaign->id )?'selected':'' }}>{{ $campaign->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-list">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Campaign</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($affiliates as $affiliate)
                                            <tr>
                                                <td>{{ $affiliate->user->name }}</td>
                                                <td>{{ $affiliate->user->email }}</td>
                                                <td>{{ $affiliate->campaign->name }}</td>
                                                <td>
                                                    <a href="{{route('admin.affiliate.login',['affiliate' => $affiliate->id])}}" class="btn btn-success btn-xs" title="Login"><i class="fa fa-sign-in"></i></a>
                                                    <button class="btn btn-info btn-xs showAffiliateDetails" data-aff_id="{{ $affiliate->user_id }}" title="Details"><i class="fa fa-bar-chart"></i></button>
                                                    <button type="button" class="btn btn-warning btn-xs editAffiliate" data-name="{{ $affiliate->user->name }}" data-email="{{ $affiliate->user->email }}" data-aff_id="{{ $affiliate->user_id }}"><i class="fa fa-pencil"></i> </button>
                                                </td>
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
    <!-- Modal -->
    <div id="editAffiliateModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Affiliate (<small><span id="show_name"></span></small>)</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="edit_name">
                                        Name
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" id="edit_name" class="form-control edit_error_remove">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4" for="edit_email">
                                        Email
                                    </label>
                                    <div class="col-md-6">
                                        <input type="text" id="edit_email" class="form-control edit_error_remove">
                                    </div>
                                </div>
                                <input type="hidden" id="user_id_edit">
                                <div class="form-group" id="edit_error"  style="color: red; display: none;">
                                    <label class="control-label col-md-4" for="edit_email">
                                        Error
                                    </label>
                                    <div class="col-md-6">
                                        <span id="edit_error_message"></span>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success form-control" id="editSubmit">Edit</button>
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
<script>
    $(function(){
        $('.table-list').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
        $(document).delegate('.showAffiliateDetails', 'click', function () {
            var affiliate = $(this).data('aff_id');
            window.location.href = "{{ route('all.details.affiliate',['']) }}"+"/"+affiliate;
        });
        $('.filter').on('change',function () {
            var campaign = $(this).val();
            setGetParameter('campaign',campaign);
        });
        $('.editAffiliate').on('click',function () {
            var name = $(this).data('name');
            var email = $(this).data('email');
            var user_id = $(this).data('aff_id');
            $('#show_name').text(name);
            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#user_id_edit').val(user_id);
            $('#editAffiliateModal').modal('show');
        });
        $('#editSubmit').on('click',function () {
            var name = $('#edit_name').val();
            if(name == ''){
                $('#edit_error_message').text('Name can not be empty');
                $('#edit_error').show();
            }
            var email = $('#edit_email').val();
            if(name == ''){
                $('#edit_error_message').text('Email can not be empty');
                $('#edit_error').show();
            }
            var user_id = $('#user_id_edit').val();
            $.ajax({
                url: "{{ route('edit.affiliate') }}",
                type: "POST",
                data: {
                    id: user_id,
                    name: name,
                    email: email,
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    if (data.success) {
                        swal({
                            title: "Success!",
                            text: data.message,
                            type: "success"
                        }).then(function () {
                            window.location.reload();
                        }, function (dismiss) {
                            window.location.reload();
                        });
                    } else {
                        $('#edit_error_message').text(data.message);
                        $('#edit_error').show();
                    }
                }
            });
        });
        $('.edit_error_remove').on('keyup',function () {
            $('#edit_error').hide();
        });
    });
</script>
@endsection
