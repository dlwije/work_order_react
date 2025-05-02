@extends('template.master')
@section('title','Approved Job orders')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@section('content')
    <div class="card" id="task_list_view">
        <div class="card-header">
            <h3 class="card-title">Approved Job orders</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>WO No</th>
                                <th>JO No</th>
                                <th>Task Name</th>
                                <th>Hours</th>
                                <th>Rate</th>
                                <th>Total</th>
                                <th>Status</th>
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

        <!--Showing model -->
        <div class="modal fade" id="assign_tech_mod">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Assign Technician</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">WO No</label>
                                    <span class="form-control" id="id_wono" data-bind="html: wo_no" readonly></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">JO No</label>
                                    <span class="form-control" id="id_jono" data-bind="html: jo_no" readonly></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">JO Date</label>
                                    <span class="form-control text-right" id="id_jodate" data-bind="html: jo_date" readonly></span>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Hours</label>
                                    <input type="number" class="form-control text-center" id="id_task_hours" data-bind="value: task_hours" disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Act: Hours</label>
                                    <input type="text" class="form-control text-right" id="id_task_act_hours" data-bind="value: task_act_hours" disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Labor cost</label>
                                    <input type="text" class="form-control text-right" id="id_labor_cost" data-bind="value: labor_cost" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="control-label">Labor task</label>
                                    <input type="text" class="form-control" id="id_labor_task" data-bind="value: labor_task" disabled>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Hour rate</label>
                                    <input type="text" class="form-control text-right" id="id_task_hour_rate" data-bind="value: task_hour_rate" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Total</label>
                                    <input type="text" class="form-control text-right" id="id_task_rate_total" data-bind="value: task_rate_total" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="control-label">Task description</label>
                                    <textarea class="form-control" rows="1" id="id_task_description" data-bind="value: task_description" disabled></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Technician Name</label>
                                    <select class="form-control" id="id_mod_technician_drop" onchange=""></select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add a labor to task" onclick="jobOrderList.ivm.addItemsToGrid()" style="margin-top: 32px;"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th style="" class="text-center">Assigned Technicians</th>
                                            <th  class="text-center" style="">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody data-bind="foreach: task_technician_list">
                                        <tr>
                                            <td data-bind="text: col1" class="text-center"></td>
                                            <td class="text-center" ><i class="fa fa-trash-alt" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Remove this row" data-bind="click: $parent.removeGridRow.bind($data, $index())"></i></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="jobOrderList.ivm.submitTechAssignData()">Submit</button>
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
                jobOrderList.init();
                // Pace.restart();
                $('#assign_tech_mod').on('hide.bs.modal', function () {
                    jobOrderList.ivm.clearForm();
                })
            })
            jobOrderList = {
                ivm: null,
                init: function () {
                    jobOrderList.ivm = new jobOrderList.jobOrderListViewModel();
                    ko.applyBindings(jobOrderList.ivm, $('#task_list_view')[0]);
                    jobOrderList.ivm.listTable();
                },
                jobOrderListViewModel: function () {
                    let self = this;

                    self.itemID = ko.observable("");
                    self.wo_no = ko.observable("");
                    self.jo_no = ko.observable("");
                    self.jo_date = ko.observable("");

                    self.labor_task = ko.observable("");
                    self.task_hours = ko.observable("");
                    self.task_hour_rate = ko.observable("");
                    self.task_rate_total = ko.observable("");
                    self.task_description = ko.observable("");
                    self.task_act_hours = ko.observable("");
                    self.labor_cost = ko.observable("");
                    self.task_technician_drop_id = ko.observable("");
                    self.task_technician_drop_name = ko.observable("");

                    self.task_technician_list = ko.observableArray();
                    self.task_tech_delete_list = ko.observableArray();

                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.listTable = () =>{

                        var table = $('#example2').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('joborder.approved.table.list') }}",
                            // initComplete: ( settings, json ) =>{ $('[data-toggle="tooltip"]').tooltip() },
                            drawCallback: ( settings, json ) =>{ $('[data-toggle="tooltip"]').tooltip() },
                            columns: [
                                {data: 'woSerialNo', name: 'woSerialNo'},
                                {data: 'serial_no', name: 'serial_no'},
                                {data: 'task_name', name: 'task_name'},
                                {data: 'hours', name: 'hours', sClass: 'text-center'},
                                {data: 'hourly_rate', name: 'hourly_rate', sClass: 'text-right'},
                                {data: 'total', name: 'total', sClass: 'text-right'},
                                {data: 'taskStatus', name: 'taskStatus'},
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ]
                        });
                    }

                    self.showTaskFullView = (empID) =>{
                        self.itemID(empID);
                        select2DropdownForModel('id_mod_technician_drop','assign_tech_mod','{{ route('technician.drop.list') }}')
                        $.ajax({
                            url: "{{ route('joborder.task.edit.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                emp_id: self.itemID(),
                            },
                            beforeSend: function (jqXHR, settings) {

                                StartLoading();
                            },
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                if(data.status){

                                    self.wo_no(data.data.head['woSerialNo']);
                                    self.jo_no(data.data.head.serial_no);
                                    self.jo_date(data.data.head.jo_date);

                                    self.labor_task(data.data.tsks['task_name']);
                                    self.task_hours(data.data.tsks['hours']);
                                    self.task_hour_rate(accounting.formatNumber(data.data.tsks['hourly_rate'],2));
                                    self.task_rate_total(accounting.formatNumber(data.data.tsks['total'],2));
                                    self.task_description(data.data.tsks['task_description']);
                                    self.task_act_hours(data.data.tsks['actual_hours']);
                                    self.labor_cost(accounting.formatNumber(data.data.tsks['labor_cost'],2));

                                    $.each(data.data.tsk_tech, (i, item)=>{
                                        self.task_technician_list.push(new gridTaskTechnicianListRows(item));
                                    })

                                    $('#assign_tech_mod').modal('show')

                                }else
                                    sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: function( jqXhr, textStatus, errorThrown ){
                            }
                        });

                    }

                    self.addItemsToGrid = () => {
                        self.task_technician_drop_id(getSelect2Val('id_mod_technician_drop'));

                        if(typeof self.task_technician_drop_id() === "undefined" || self.task_technician_drop_id() == ""){

                            sweetAlertMsg('Form Validation Error!',"Please select a Technician first.",'warning')
                            return;
                        }
                        self.task_technician_drop_name(getSelect2Text('id_mod_technician_drop'));
                        self.task_technician_list.push(new gridTaskTechnicianListRows());
                        $('[data-toggle="tooltip"]').tooltip()
                        APP_selectEmpty('id_mod_technician_drop');
                    }

                    let gridTaskTechnicianListRows = function (obj) {
                        let item = this;

                        if(typeof obj === "undefined"){

                            item.col1 = (typeof self.task_technician_drop_name() === "undefined" || self.task_technician_drop_name() == null) ? ko.observable("") : ko.observable(self.task_technician_drop_name());
                            item.col2 = (typeof self.task_technician_drop_id() === "undefined" || self.task_technician_drop_id() == null) ? ko.observable("") : ko.observable(self.task_technician_drop_id());
                            item.col3 = ko.observable("");
                        }else{
                            item.col1 = (typeof obj.full_name === "undefined" || obj.full_name == null) ? ko.observable("") : ko.observable(obj.full_name);
                            item.col2 = (typeof obj.tech_id === "undefined" || obj.tech_id == null) ? ko.observable("") : ko.observable(obj.tech_id);
                            item.col3 = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
                        }
                    }

                    self.removeGridRow = (indexNo, rowData) => {
                        self.task_technician_list.remove(rowData);
                        if(rowData.col3() && rowData.col3()!== "") {
                            self.task_tech_delete_list.push(rowData.col3())
                        }
                    }

                    self.approveTask = (fbID) =>{

                        Swal.fire({
                            title: 'Are you sure, You want to approve this?',
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
                                    url: "{{ route('joborder.approve.cost') }}",
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

                                sweetAlertMsg('Canceled!','Record approving canceled.','info');
                            }
                        })
                    }

                    self.submitTechAssignData = () =>{
                        $.ajax({
                            url: "{{ route('joborder.task.technician.submit') }}",
                            dataType: 'json',
                            type: 'post',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "item_id": self.itemID(),
                                "task_tech_list": ko.toJSON(self.task_technician_list),
                                "task_tech_delete_list": ko.toJSON(self.task_tech_delete_list),

                            },
                            success: function( data, textStatus, jQxhr ){

                                if(data.status){

                                    $('#assign_tech_mod').modal('hide')
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
                    }

                    self.clearForm = () => {
                        self.wo_no("");
                        self.jo_no("");
                        self.jo_date("");

                        self.labor_task("");
                        self.task_hours("");
                        self.task_hour_rate("");
                        self.task_rate_total("");
                        self.task_description("");
                        self.task_act_hours("");
                        self.labor_cost("");
                        self.task_technician_drop_id("");
                        self.task_technician_drop_name("");

                        self.task_technician_list.removeAll();
                        self.task_tech_delete_list.removeAll();
                        APP_selectEmpty('id_mod_technician_drop');
                    }
                }
            }
        </script>
    @endpush
@endsection

