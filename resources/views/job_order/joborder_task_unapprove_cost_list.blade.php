@extends('template.master')
@section('title','Job orders')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush

@section('content')
    <div class="card" id="task_list_view">
        <div class="card-header">
            <h3 class="card-title">To Be Approve(cost) Job orders</h3>
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
        <div class="modal fade" id="task_review_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Task review</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">WO No</label>
                                    <span class="form-control" id="id_wono" data-bind="html: wo_no" readonly></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">JO No</label>
                                    <span class="form-control" id="id_jono" data-bind="html: jo_no" readonly></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">JO Date</label>
                                    <span class="form-control text-right" id="id_jodate" data-bind="html: jo_date" readonly></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label class="control-label">Labor task</label>
                                    <input type="text" class="form-control" id="id_labor_task" data-bind="value: labor_task">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Hours</label>
                                    <input type="number" class="form-control text-center" id="id_task_hours" data-bind="value: task_hours, event: {blur: calculateLineTotal} ">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Hour rate</label>
                                    <input type="text" class="form-control text-right" id="id_task_hour_rate" data-bind="value: task_hour_rate, event: {blur: calculateLineTotal} ">
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
                                    <textarea class="form-control" rows="2" id="id_task_description" data-bind="value: task_description"></textarea>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Act: Hours</label>
                                    <input type="number" class="form-control text-right" id="id_task_act_hours" data-bind="value: task_act_hours">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Labor cost</label>
                                    <input type="number" class="form-control text-right" id="id_labor_cost" data-bind="value: labor_cost">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">

                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="jobOrderList.ivm.submitTaskData()">Submit</button>
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
        <script>

            $(document).ready(function () {
                jobOrderList.init();
                // Pace.restart();
                $('#task_review_mod').on('hide.bs.modal', function () {
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

                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.listTable = () =>{

                        var table = $('#example2').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('joborder.approve.cost.table.list') }}",
                            initComplete: ( settings, json ) =>{ $('[data-toggle="tooltip"]').tooltip() },
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

                    self.reviewTask = (empID) =>{
                        self.itemID(empID);
                        $.ajax({
                            url: "{{ route('joborder.task.edit.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: { "_token": "{{ csrf_token() }}", emp_id: self.itemID(), },
                            beforeSend: function (jqXHR, settings) { StartLoading(); },
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

                                    $('#task_review_mod').modal('show')

                                }else
                                    sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: function( jqXhr, textStatus, errorThrown ){
                            }
                        });

                    }

                    self.calculateLineTotal = () => {
                        if(isNaN(self.task_hours())) self.task_hours(0);
                        var amount = (self.task_hours() * parseFloat(self.task_hour_rate().replace(/[^0-9-.]/g, '')));
                        self.task_rate_total(accounting.formatNumber(amount,2));
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
                                            sweetAlertMsg(MsgHead.PE,'User does not have the right permissions.','warning');
                                        }
                                    }
                                });

                            } else {

                                sweetAlertMsg('Canceled!','Record approving canceled.','info');
                            }
                        })
                    }

                    self.submitTaskData = () =>{
                        $.ajax({
                            url: "{{ route('joborder.task.edit.submit') }}",
                            dataType: 'json',
                            type: 'post',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                emp_id: self.itemID(),
                                "task_name": self.labor_task(),
                                "task_description": self.task_description(),
                                "hours": self.task_hours(),
                                "hourly_rate": self.task_hour_rate(),
                                "actual_hours": self.task_act_hours(),
                                "labor_cost": self.labor_cost(),
                                "total": self.task_rate_total(),
                            },
                            success: function( data, textStatus, jQxhr ){

                                if(data.status){

                                    $('#task_review_mod').modal('hide')
                                    $('#example2').DataTable().ajax.reload();
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
                    }
                }
            }
        </script>
    @endpush
@endsection

