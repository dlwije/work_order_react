@extends('template.master')
@section('title','Employees')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush

@section('content')
    <div class="card" id="employee_list_view">
        <div class="card-header">
            <h3 class="card-title">Employees</h3>
            <div class="card-tools">
                @can('employee-add')
                    <a class="btn btn-sm btn-success no-shadow" href="{{ route('employee.create') }}">  <i class="glyphicon glyphicon-plus myicon-right"></i> Add New</a>
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
{{--                                <th>ID</th>--}}
                                <th>Employee No</th>
                                <th>Full Name</th>
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

    <!--Showing model -->
    <div class="modal fade" id="emp_show_mod">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Details of <span id="show_mod_title" ></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-sm-3">First Name</dt>
                        <dd class="col-sm-9" id="show_mod_fname"></dd>
                        <dt class="col-sm-3">Last Name</dt>
                        <dd class="col-sm-9" id="show_mod_lname"></dd>
                    </dl>
                </div>
                <div class="modal-footer justify-content-between">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    @push('scripts')
        <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script>

            $(document).ready(function () {
                employeeList.init();
                // Pace.restart();
            })
            employeeList = {
                ivm: null,
                init: function () {
                    employeeList.ivm = new employeeList.employeeListViewModel();
                    ko.applyBindings(employeeList.ivm, $('#employee_list_view')[0]);
                    employeeList.ivm.listTable();
                },
                employeeListViewModel: function () {
                    let self = this;

                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.listTable = function () {

                        var table = $('#example2').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('employee.table.list') }}",
                            drawCallback: ( settings, json ) =>{ $('[data-toggle="tooltip"]').tooltip() },
                            columns: [

                                {data: 'emp_no', name: 'emp_no'},
                                {data: 'full_name', name: 'full_name'},
                                {data: 'updated_at', name: 'updated_at'},
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ]
                        });
                    }

                    self.showDetails = function (empID) {

                        $.ajax({
                            url: "{{ route('employee.show') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                emp_id: empID,
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading(); },
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                if(data.status){

                                    $('#show_mod_title').html(data.data[0].full_name)
                                    $('#show_mod_fname').html(data.data[0].first_name)
                                    $('#show_mod_lname').html(data.data[0].last_name)
                                    $('#emp_show_mod').modal('show')

                                }else
                                    sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: function( jqXhr, textStatus, errorThrown ){
                            }
                        });

                    }

                    self.deleteRecord = function(fbID){

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
                                    url: "{{ route('employee.destroy') }}",
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        emp_id: fbID,
                                    },
                                    beforeSend: function (jqXHR, settings) {

                                    },
                                    success: function( data, textStatus, jQxhr ){

                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!',data.message,'success');
                                        }else
                                            sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                    },
                                    error: function( jqXhr, textStatus, errorThrown ){
                                        if(jqXhr.status == 403){
                                            sweetAlertMsg(MsgHead.PE,'Users does not have the right permissions.','warning');
                                        }
                                    }
                                });

                            } else {

                                sweetAlertMsg('Canceled!','Record deletion canceled.','info');
                            }
                        })
                    }
                }
            }
        </script>
    @endpush
@endsection

