@extends('template.master')
@section('title','Users')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Users</h3>
            <div class="card-tools">
                @can('user-create')
                    <a class="btn btn-sm btn-success no-shadow" href="{{ route('user.create') }}">  <i class="glyphicon glyphicon-plus myicon-right"></i> Add New</a>
                @endcan
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Updated On</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script>
            $(function () {

                var table = $('#example2').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('users.list') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'email', name: 'email'},
                        {data: 'roles', name: 'roles'},
                        {data: 'updated_at', name: 'updated_at'},
                        // {data: 'phone', name: 'phone'},
                        // {data: 'dob', name: 'dob'},
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

            });
            function goToEdit(id){

                alert(id);
            }

            function deleteConfirm(id) {
                let submitResp = false;
                Swal.fire({
                    title: 'Are you sure, You want to delete this?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, confirm it!',
                    customClass: {
                        confirmButton: 'btn btn-primary mr-1',
                        cancelButton: 'btn btn-warning'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/user/'+id,
                            type: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading(); },
                            success: function (responseData) {
                                StopLoading();

                                $('#example2').DataTable().ajax.reload();
                                sweetAlertMsg('Success',responseData.message,'success')
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                StopLoading();
                                console.log(jqXHR)
                                sweetAlertMsg('Error','Form Submissions Error','warning');
                            }
                        });
                    }
                })
            }
        </script>
    @endpush
@endsection

