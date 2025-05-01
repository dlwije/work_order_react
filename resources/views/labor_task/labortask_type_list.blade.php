@extends('template.master')
@section('title','Labor Tasks Type')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@section('content')
    <div class="card" id="labortask_list_view">
        <div class="card-header">
            <h3 class="card-title">Labor Task Type</h3>
            <div class="card-tools">
                @can('task-type-add')
                    <a class="btn btn-sm btn-success no-shadow" href="javascript:0" onclick="laborTaskList.ivm.addNew()">  <i class="glyphicon glyphicon-plus myicon-right"></i> Add New</a>
                @endcan
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="id_lbr_task_tbl" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Type Code</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="id_lbr_task_tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Labor Task Edit model -->
        <div class="modal fade" id="task_edit_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Task Type</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Type Code</label>
                                    <input type="text" class="form-control" id="id_tsk_code" data-bind="value: mod_tsk_cate_code">
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label class="control-label">Type Name</label>
                                    <input type="text" class="form-control" id="id_mod_tsk_hour" data-bind="value: mod_tsk_cate_name">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">

                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="laborTaskList.ivm.submitTaskCategoryData()">Submit</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>

    @push('scripts')
        <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
        <script>

            $(document).ready(function () {
                //initiate page javascript
                laborTaskList.init();
                //start top progress bar
                // Pace.restart();

                $('#task_edit_mod').on('hide.bs.modal', function () {
                    laborTaskList.ivm.clearModelData();
                })
            })
            laborTaskList = {
                ivm: null,
                init: function () {
                    laborTaskList.ivm = new laborTaskList.laborTaskListViewModel();

                    //binding the knockout to the dom
                    ko.applyBindings(laborTaskList.ivm, $('#labortask_list_view')[0]);

                    //initialize the page design needs when page loads
                    laborTaskList.ivm.designInitialize();
                },
                laborTaskListViewModel: function () {
                    let self = this;

                    self.tsk_id = ko.observable("");
                    self.mod_tsk_cate_code = ko.observable("");
                    self.mod_tsk_cate_name = ko.observable("");


                    //getting predefined msg strings from common file
                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.designInitialize = () => {
                        self.listTable();
                    }

                    //datatable function
                    self.listTable = function () {

                        var table = $('#id_lbr_task_tbl').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('task.type.table.list') }}",
                            columns: [
                                {data: 'id', name: 'id'},
                                {data: 'task_type_name', name: 'task_type_name'},
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ]
                        });
                    }

                    self.addNew = () => {

                        $('#task_edit_mod').modal('show');
                    }
                    /*used function for edit the task data*/
                    self.editDetails = function (empID) {
                        self.tsk_id(empID);
                        $.ajax({
                            url: "{{ route('task.type.selected.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                item_id: self.tsk_id(),
                            },
                            beforeSend: function (jqXHR, settings) {

                                StartLoading();
                            },
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                if(data.count > 0){

                                    // self.mod_tsk_cate_code(data.data[0].task_code);
                                    self.mod_tsk_cate_name(data.data[0].task_type_name);
                                    $('#task_edit_mod').modal('show')

                                }else
                                    CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: ( jqXhr, textStatus, errorThrown ) => {
                            }
                        });

                    }

                    self.submitTaskCategoryData = () =>{

                        let url = "{{ route('task.type.edit.submit') }}";
                        if(self.tsk_id() == "")
                            url = '{{ route('task.type.create.submit') }}';

                        $.ajax({
                            url: url,
                            dataType: 'json',
                            type: 'post',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                item_id: self.tsk_id(),
                                "tsk_type_code": self.mod_tsk_cate_code(),
                                "tsk_type_name": self.mod_tsk_cate_name()
                            },
                            success: function( data, textStatus, jQxhr ){

                                if(data.status){

                                    $('#task_edit_mod').modal('hide')
                                    $('#id_lbr_task_tbl').DataTable().ajax.reload();
                                    sweetAlertMsg('Success!',data.message,'success');
                                }else
                                    sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: function( jqXhr, textStatus, errorThrown ){
                                if(jqXhr.status == 403){
                                    sweetAlertMsg(MsgHead.PE,'User does not have the right permissions.','warning');
                                }
                            }
                        });
                    }

                    self.clearModelData = () => {
                        self.tsk_id("");
                        self.mod_tsk_cate_code("");
                        self.mod_tsk_cate_name("");
                    }

                    //delete record function
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
                                    url: "{{ route('task.type.destroy.submit') }}",
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        item_id: fbID,
                                    },
                                    beforeSend: function (jqXHR, settings) {

                                    },
                                    success: function( data, textStatus, jQxhr ){

                                        if(data.status){

                                            $('#id_lbr_task_tbl').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!',data.message,'success');
                                        }else
                                            sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                    },
                                    error: function( jqXhr, textStatus, errorThrown ){
                                        if(jqXhr.status == 403){
                                            sweetAlertMsg(MsgHead.PE,'User does not have the right permissions.','warning');
                                        }
                                    }
                                });

                            } else {

                                CommonMsg.ivm.sweetAlertMsg('Canceled!','Record deletion canceled.','info');
                            }
                        })
                    }
                }
            }
        </script>
    @endpush
@endsection

