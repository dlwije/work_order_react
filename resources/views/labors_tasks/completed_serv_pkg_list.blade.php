@extends('template.master')
@section('title','Assigned Tasks')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush

@section('content')
    <div class="card" id="employee_list_view">
        <div class="card-header">
            <h3 class="card-title">Completed Service Packages</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Jo No</th>
                                <th>Service Name</th>
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
                laborsTaskList.init();
                // Pace.restart();
            })
            laborsTaskList = {
                ivm: null,
                init: function () {
                    laborsTaskList.ivm = new laborsTaskList.laborsTaskListViewModel();
                    ko.applyBindings(laborsTaskList.ivm, $('#employee_list_view')[0]);
                    laborsTaskList.ivm.listTable();
                },
                laborsTaskListViewModel: function () {
                    let self = this;

                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.comment_type = ko.observable("");

                    self.listTable = function () {

                        var table = $('#example2').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('service.completed.table.list') }}",
                            drawCallback: ( settings, json ) =>{ $('[data-toggle="tooltip"]').tooltip() },
                            columns: [
                                {data: 'serial_no', name: 'serial_no'},
                                // {data: 'task_name', name: 'task_name'},
                                {data: 'service_name', name: 'service_name'},
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
                            beforeSend: function (jqXHR, settings) {

                                StartLoading();
                            },
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                if(data.status){

                                    $('#show_mod_title').html(data.data[0].full_name)
                                    $('#show_mod_fname').html(data.data[0].first_name)
                                    $('#show_mod_lname').html(data.data[0].last_name)
                                    $('#emp_show_mod').modal('show')

                                }else
                                    CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: function( jqXhr, textStatus, errorThrown ){
                            }
                        });

                    }

                    self.startTimer = function(fbID, time_type){
                        self.comment_type(time_type);
                        Swal.fire({
                            title: 'Are you sure, You want to start the task?',
                            icon: 'warning',
                            buttonsStyling: false,
                            showCancelButton: true,
                            confirmButtonText: 'Yes, start!',
                            customClass: {
                                confirmButton: 'btn btn-primary mr-1',
                                cancelButton: 'btn btn-warning'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: "{{ route('task.start.timer.submit') }}",
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        tsk_id: fbID,
                                        "time_type": self.comment_type(),
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
                                            sweetAlertMsg(MsgHead.PE,'User does not have the right permissions.','warning');
                                        }else{
                                            var errors = jqXhr.responseJSON.message;

                                            var errorList = "<ul>";

                                            $.each(errors, function (i, error) {
                                                errorList += '<li class="text-left text-danger">' + error + '</li>';
                                            })

                                            errorList += "</ul>"

                                            sweetAlertMsg('Form validation Error', errorList, 'warning');
                                        }
                                    }
                                });

                            } else { sweetAlertMsg('Canceled!','Record deletion canceled.','info'); }
                        })
                    }

                    self.endTimer = function(fbID, time_type){
                        self.comment_type(time_type);
                        Swal.fire({
                            title: 'Are you sure, You want to end the task?',
                            icon: 'warning',
                            buttonsStyling: false,
                            showCancelButton: true,
                            confirmButtonText: 'Yes, end!',
                            customClass: {
                                confirmButton: 'btn btn-primary mr-1',
                                cancelButton: 'btn btn-warning'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: "{{ route('task.end.timer.submit') }}",
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        tsk_id: fbID,
                                        "time_type": self.comment_type(),
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
                                            sweetAlertMsg(MsgHead.PE,'User does not have the right permissions.','warning');
                                        }else{
                                            var errors = jqXhr.responseJSON.message;

                                            var errorList = "<ul>";

                                            $.each(errors, function (i, error) {
                                                errorList += '<li class="text-left text-danger">' + error + '</li>';
                                            })

                                            errorList += "</ul>"

                                            sweetAlertMsg('Form validation Error', errorList, 'warning');
                                        }
                                    }
                                });

                            } else { sweetAlertMsg('Canceled!','Record deletion canceled.','info'); }
                        })
                    }

                    self.finishTimer = function(fbID, time_type){
                        self.comment_type(time_type);
                        Swal.fire({
                            title: 'Are you sure, You want to finish the task?',
                            icon: 'warning',
                            buttonsStyling: false,
                            showCancelButton: true,
                            confirmButtonText: 'Yes, finish!',
                            customClass: {
                                confirmButton: 'btn btn-primary mr-1',
                                cancelButton: 'btn btn-warning'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: "{{ route('task.finish.timer.submit') }}",
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        tsk_id: fbID,
                                        "time_type": self.comment_type(),
                                    },
                                    beforeSend: function (jqXHR, settings) { },
                                    success: function( data, textStatus, jQxhr ){

                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!',data.message,'success');
                                        }else
                                            sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                    },
                                    error: function( jqXhr, textStatus, errorThrown ){
                                        if(jqXhr.status == 403){
                                            sweetAlertMsg(MsgHead.PE,'User does not have the right permissions.','warning');
                                        }else{
                                            var errors = jqXhr.responseJSON.message;

                                            var errorList = "<ul>";

                                            $.each(errors, function (i, error) {
                                                errorList += '<li class="text-left text-danger">' + error + '</li>';
                                            })

                                            errorList += "</ul>"

                                            sweetAlertMsg('Form validation Error', errorList, 'warning');
                                        }
                                    }
                                });

                            } else { sweetAlertMsg('Canceled!','Record deletion canceled.','info'); }
                        })
                    }
                }
            }
        </script>
    @endpush
@endsection

