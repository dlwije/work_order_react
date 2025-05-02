@extends('template.master')
@section('title','Labor Tasks')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@section('content')
    <div class="card" id="labortask_list_view">
        <div class="card-header">
            <h3 class="card-title">Labor Tasks</h3>
            <div class="card-tools">
                @can('labortask-add')
                    <a class="btn btn-sm btn-success no-shadow" href="javascript:0" onclick="laborTaskList.ivm.addNew()">  <i class="glyphicon glyphicon-plus myicon-right"></i> Add New</a>
                @endcan
                    @can('labortask-excel-import')
                        <a class="btn btn-sm btn-primary no-shadow" href="{{ route('labortask.excel') }}">  <i class="fas fa-file-excel"></i> Import</a>
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
                                <th>Task Code</th>
                                <th>Task Name</th>
                                <th>Source</th>
                                <th>Category</th>
                                <th>Model/Class</th>
                                <th>Hour</th>
                                <th>Rate</th>
            {{--                    <th>Active Status</th>--}}
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
                        <h4 class="modal-title">Task</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Task Code</label>
                                    <input type="text" class="form-control" id="id_tsk_code" data-bind="value: mod_tsk_code">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Task Hour</label>
                                    <input type="text" class="form-control text-center" id="id_mod_tsk_hour" data-bind="value: mod_tsk_hour">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Rate</label>
                                    <input type="text" class="form-control text-right" id="id_mod_tsk_rate" data-bind="value: mod_tsk_rate">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Warranty Rate</label>
                                    <input type="text" class="form-control text-right" id="id_mod_tsk_warr_rate" data-bind="value: mod_tsk_warr_rate">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Task Name</label>
                                    <input type="text" class="form-control" id="id_tsk_name" data-bind="value: mod_tsk_name" >
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Task Description</label>
                                    <textarea class="form-control" id="id_tsk_description" data-bind="value: mod_tsk_description" rows="1"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Source</label>
                                    <select class="form-control" id="id_mod_tsk_source" onchange="laborTaskList.ivm.setSourceDropId()"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Category</label>
                                    <select class="form-control" id="id_mod_tsk_category" onchange="laborTaskList.ivm.setCategoryDropId()"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Model/Class</label>
                                    <select class="form-control" id="id_mod_tsk_mod_cls" onchange="laborTaskList.ivm.setModClsDropId()"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">

                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="laborTaskList.ivm.submitTaskData()">Submit</button>
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
                    self.mod_tsk_code = ko.observable("");
                    self.mod_tsk_name = ko.observable("");
                    self.mod_tsk_description = ko.observable("");
                    self.mod_tsk_source = ko.observable("");
                    self.mod_tsk_category = ko.observable("");
                    self.mod_tsk_mod_cls = ko.observable("");
                    self.mod_tsk_hour = ko.observable("");
                    self.mod_tsk_rate = ko.observable("");
                    self.mod_tsk_warr_rate = ko.observable("");


                    //getting predefined msg strings from common file
                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.designInitialize = () => {
                        self.listTable();
                    }

                    //datatable function
                    self.listTable = function () {

                        $('#id_lbr_task_tbl thead tr').clone(true).appendTo( '#id_lbr_task_tbl thead' );
                        $('#id_lbr_task_tbl thead tr:eq(1) th').each( function (i) {
                            if(i != (7)) {
                                var title = $(this).text();
                                if(i==5)
                                    $(this).html('<input type="text" class="form-control" placeholder=" Search ' + title + '" />');
                                else if(i==6)
                                    $(this).html('<input type="text" class="form-control" placeholder=" Search ' + title + '" />');
                                else
                                    $(this).html('<input type="text" class="form-control" placeholder=" Search ' + title + '" />');

                                $('input', this).on('keyup change', function () {
                                    if (table.column(i).search() !== this.value) {
                                        table
                                            .column(i)
                                            .search(this.value)
                                            .draw();
                                    }
                                });
                            }
                        });

                        var table = $('#id_lbr_task_tbl').DataTable({
                            orderCellsTop: true,
                            fixedHeader: true,
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('labortask.table.list') }}",
                            columns: [
                                {data: 'task_code', name: 'task_code'},
                                {data: 'task_name', name: 'task_name'},
                                {data: 'task_source_name', name: 'task_source_name'},
                                {data: 'task_category_name', name: 'task_category_name'},
                                {data: 'task_type_name', name: 'task_type_name'},
                                {data: 'task_hour', name: 'task_hour', sClass: 'text-center'},
                                {data: 'normal_rate', name: 'normal_rate', sClass: 'text-right'},
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ],
                            initComplete: function () {
                                this.api().columns().every(function () {
                                    var column = this;
                                    var input = document.createElement("input");
                                    $(input).appendTo($(column.footer()).empty())
                                        .on('change', function () {
                                            column.search($(this).val(), false, false, true).draw();
                                        });
                                });
                            }
                        });
                    }

                    self.setSourceDropId = () => { self.mod_tsk_source(getSelect2Val('id_mod_tsk_source')); }
                    self.setCategoryDropId = () => { self.mod_tsk_category(getSelect2Val('id_mod_tsk_category')); }
                    self.setModClsDropId = () => { self.mod_tsk_mod_cls(getSelect2Val('id_mod_tsk_mod_cls')); }

                    self.addNew = () => {

                        select2Dropdown('id_mod_tsk_category','{{ route('task.category.drop.list')}} ');
                        select2Dropdown('id_mod_tsk_source','{{ route('task.source.drop.list')}} ');
                        select2Dropdown('id_mod_tsk_mod_cls','{{ route('task.type.drop.list')}} ');

                        $('#task_edit_mod').modal('show');
                    }
                    /*used function for edit the task data*/
                    self.editDetails = function (empID) {
                        self.tsk_id(empID);
                        $.ajax({
                            url: "{{ route('task.selected.data') }}",
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
                                if(data.status){

                                    self.mod_tsk_code(data.data[0].task_code);
                                    self.mod_tsk_name(data.data[0].task_name);
                                    self.mod_tsk_description(data.data[0].task_description);
                                    self.mod_tsk_source(data.data[0].source_id);
                                    self.mod_tsk_category(data.data[0].category_id);
                                    self.mod_tsk_mod_cls(data.data[0].task_type_id);
                                    self.mod_tsk_hour(data.data[0].task_hour);
                                    self.mod_tsk_rate(data.data[0].normal_rate);
                                    self.mod_tsk_warr_rate(data.data[0].warranty_rate);
                                    select2Dropdown('id_mod_tsk_category','{{ route('task.category.drop.list') }} ', '{{ route('task.category.byid.drop.list') }}', self.mod_tsk_source());
                                    select2Dropdown('id_mod_tsk_source','{{ route('task.source.drop.list') }} ', '{{ route('task.source.byid.drop.list') }}', self.mod_tsk_category());
                                    select2Dropdown('id_mod_tsk_mod_cls','{{ route('task.type.drop.list') }} ', '{{ route('task.type.byid.drop.list') }}', self.mod_tsk_mod_cls());
                                    $('#task_edit_mod').modal('show')

                                }else
                                    CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: ( jqXhr, textStatus, errorThrown ) => {
                            }
                        });

                    }

                    self.submitTaskData = () =>{

                        let url = "{{ route('task.edit.submit') }}";
                        if(self.tsk_id() == "")
                            url = '{{ route('task.create.submit') }}';

                        $.ajax({
                            url: url,
                            dataType: 'json',
                            type: 'post',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                item_id: self.tsk_id(),
                                "tsk_code": self.mod_tsk_code(),
                                "tsk_name": self.mod_tsk_name(),
                                "tsk_description": self.mod_tsk_description(),
                                "tsk_source": self.mod_tsk_source(),
                                "tsk_category": self.mod_tsk_category(),
                                "tsk_mod_cls": self.mod_tsk_mod_cls(),
                                "hours": self.mod_tsk_hour(),
                                "hourly_rate": self.mod_tsk_rate(),
                                "warr_hourly_rate": self.mod_tsk_warr_rate(),
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
                                    sweetAlertMsg(MsgHead.PE,'Users does not have the right permissions.','warning');
                                }
                            }
                        });
                    }

                    self.clearModelData = () => {
                        self.tsk_id("");
                        self.mod_tsk_code("");
                        self.mod_tsk_name("");
                        self.mod_tsk_description("");
                        self.mod_tsk_source("");
                        self.mod_tsk_category("");
                        self.mod_tsk_mod_cls("");
                        self.mod_tsk_hour("");
                        self.mod_tsk_rate("");
                        self.mod_tsk_warr_rate("");

                        APP_selectEmpty('id_mod_tsk_category');
                        APP_selectEmpty('id_mod_tsk_source');
                        APP_selectEmpty('id_mod_tsk_mod_cls');
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
                                    url: "{{ route('task.destroy.submit') }}",
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
                                            sweetAlertMsg(MsgHead.PE,'Users does not have the right permissions.','warning');
                                        }
                                    }
                                });

                            } else { CommonMsg.ivm.sweetAlertMsg('Canceled!','Record deletion canceled.','info'); }
                        })
                    }
                }
            }
        </script>
    @endpush
@endsection

