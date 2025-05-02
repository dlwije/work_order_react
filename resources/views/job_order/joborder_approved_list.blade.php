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
                                <th>Customer Name</th>
                                <th>Vin No</th>
                                {{--                                <th>Total</th>--}}
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

                        </div>

                        <!-- Start: Service package Table -->
                        <div class="callout border-primary">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-condensed table-bordered table-hover mb-0">
                                            <thead>
                                            <tr>
                                                <th style="" class="text-center">Service Name</th>
                                                <th style="" class="text-center">Description</th>
                                                <th style="" class="text-center">Vehicle Type</th>
                                                {{--                                                <th class="text-center" style="">Qty</th>--}}
                                                <th class="text-center" style="">Price</th>
                                                <th class="text-center" style="">
                                                    Subtotal(<span data-bind="html: default_currenty"></span>)
                                                </th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody data-bind="foreach: servicePkgList">
                                            <tr>
                                                <td data-bind="text: col1" class="text-center"></td>
                                                <td data-bind="text: col2" class="text-center"></td>
                                                <td data-bind="text: col3" class="text-center"></td>
                                                <td data-bind="text: col4" class="text-right"></td>
                                                <td data-bind="text: col5" class="text-right"></td>
                                                <td></td>
                                            </tr>
                                            <!-- Technician assign row -->
                                            <tr>
                                                <td colspan="2" style="padding: 5px 0 0 0" data-toggle="tooltip" data-placement="bottom" title="Select Labor for the task">
                                                    <select class="form-control" data-bind="attr: { id: 'id_tech_serv_drop_'+col7() }" onchange=""></select>
                                                </td>
                                                <td colspan="3"></td>
                                                <td style="padding: 5px 5px 5px 0px">
                                                    <button type="button" class="btn btn-primary float-right" data-toggle="tooltip" data-placement="top" title="Add a labor to above task" data-bind="click: $parent.addLaborToServPkgGrid.bind($data, $index())" ><i class="fas fa-plus"></i></button>
                                                </td>
                                            </tr>
                                            <!-- Assigned Labors(technicians) with time -->
                                            <tr>
                                                <td colspan="6" style="padding: 0;border: none;">
                                                    <div data-bind="foreach: col10">
                                                        <div class="ml-2">
                                                            <label class="">Technician: </label><span data-bind="html: full_name"></span> /
                                                            <label class="">Total Time: </label> <span data-bind="html: totTime"></span> /
                                                            <label class="">Status: </label> <span data-bind="html: jbState"></span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-condensed table-bordered table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th style="" class="text-center">Service Package</th>
                                                        <th style="" class="text-center">Assigned Technicians</th>
                                                        <th  class="text-center" style="">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody data-bind="foreach: serv_pkg_technician_list">
                                                    <tr>
                                                        <td data-bind="text: col5" class="text-center"></td>
                                                        <td data-bind="text: col1" class="text-center"></td>
                                                        <td class="text-center" >
                                                            <i class="fa fa-trash-alt" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Remove this row" data-bind="click: $parent.removeServTechGridRow.bind($data, $index())"></i>
                                                            <i class="fa fa-hand-point-right ml-2" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Send to rework again." data-bind="click: $parent.sendToRework.bind($data, $index())"></i>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End: Service package Table -->

                        <!-- Job order task table -->
                        <div class="callout callout-info">
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th style="" class="text-center">Labor Task</th>
                                        <th style="" class="text-center">Hours</th>
                                        <th style="" class="text-center">Act: Hours</th>
                                        <th  class="text-center" style="" >Hour Rate</th>
                                        <th  class="text-center" style="" >Labor Cost</th>
                                        <th  class="text-center" style="" >Total</th>
                                        <th  class="text-center" style="" >Action</th>
                                    </tr>
                                    </thead>
                                    <tbody data-bind="foreach: laborTaskList">
                                    <tr style="background-color: aliceblue">
                                        <td class="text-center" style="padding: 0" data-bind="text: col1"></td>
                                        <td class="text-center" style="padding: 0">
                                            <input type="number" class="form-control text-center" data-bind="value: col3 ">
                                        </td>
                                        <td style="padding: 0">
                                            <input type="number" class="form-control text-center" data-bind="value: col4">
                                        </td>
                                        <td style="padding: 0">
                                            <input type="number" class="form-control text-right" data-bind="value: col5 ">
                                        </td>
                                        <td style="padding: 0">
                                            <input type="number" class="form-control text-right" data-bind="value: col6">
                                        </td>
                                        <td data-bind="text: col7" class="text-right ">DisAmount</td>
                                        <td class="text-right "></td>
                                    </tr>
                                    <!-- Task description edit row -->
                                    <tr>
                                        <td colspan="6" style="padding: 0" data-toggle="tooltip" data-placement="bottom" title="Edit task description">
                                            <textarea rows="1" class="form-control" data-bind="value: col2"></textarea>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <!-- Technician assign row -->
                                    <tr>
                                        <td colspan="2" style="padding: 5px 0 0 0" data-toggle="tooltip" data-placement="bottom" title="Select Labor for the task">
                                            <select class="form-control" data-bind="attr: { id: 'id_technician_drop_'+col9() }" onchange=""></select>
                                        </td>
                                        <td colspan="4"></td>
                                        <td style="padding: 5px 5px 5px 0px">
                                            <button type="button" class="btn btn-primary float-right" data-toggle="tooltip" data-placement="top" title="Add a labor to above task" data-bind="click: $parent.addLaborToGrid.bind($data, $index())" ><i class="fas fa-plus"></i></button>
                                        </td>
                                    </tr>
                                    <!-- Assigned Labors(technicians) with time -->
                                    <tr>
                                        <td colspan="7" style="padding: 0;border: none;">
                                            <div data-bind="foreach: col12">
                                                <div class="ml-2">
                                                    <label class="">Technician: </label><span data-bind="html: full_name"></span> /
                                                    <label class="">Total Time: </label> <span data-bind="html: totTime"></span> /
                                                    <label class="">Task Status: </label> <span data-bind="html: jbState"></span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-condensed table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th style="" class="text-center">Task Assigned</th>
                                                <th style="" class="text-center">Assigned Technicians</th>
                                                <th  class="text-center" style="">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody data-bind="foreach: task_technician_list">
                                            <tr>
                                                <td data-bind="text: col5" class="text-center"></td>
                                                <td data-bind="text: col1" class="text-center"></td>
                                                <td class="text-center" >
                                                    <i class="fa fa-trash-alt" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Remove this row" data-bind="click: $parent.removeGridRow.bind($data, $index())"></i>
                                                    <i class="fa fa-hand-point-right ml-2" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Send to rework again." data-bind="click: $parent.sendToRework.bind($data, $index())"></i>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
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

        <!-- Begin:Task Comments model -->
        <div class="modal fade" id="task_comment_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="task_comment_modal_title"></span>Explain the rework need to be done here.</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card direct-chat direct-chat-primary">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <!-- Conversations are loaded here -->
                                <div class="direct-chat-messages" style="height: 400px;" data-bind="foreach: taskCommentsList">
                                    <!-- Message. Default to the left -->
                                    <div class="direct-chat-msg" data-bind="style: { display: jobOrderList.ivm.logged_user_id() == col4() ? 'none' : '' }">
                                        <div class="direct-chat-infos clearfix">
                                            <span class="direct-chat-name float-left" data-bind="html: col2"></span>
                                            <span class="direct-chat-timestamp float-right" data-bind="html: col3"></span>
                                        </div>
                                        <!-- /.direct-chat-infos -->
                                        <img class="direct-chat-img" src="{{asset('img/default_user_img.png')}}" alt="Message User Image">
                                        <!-- /.direct-chat-img -->
                                        <div class="direct-chat-text" data-bind="html: col1">
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->

                                    <!-- Message to the right -->
                                    <div class="direct-chat-msg right"  data-bind="style: { display: jobOrderList.ivm.logged_user_id() == col4() ? '' : 'none' }">
                                        <div class="direct-chat-infos clearfix" data-bind="style: { color: col5() == 7 ? '#28a745' : col5() == 1 ? '#d52614' : '' }">
                                            <span class="direct-chat-name float-right" data-bind="html: col2"></span>
                                            <span class="direct-chat-timestamp float-left" data-bind="html: col3"></span>
                                        </div>
                                        <!-- /.direct-chat-infos -->
                                        <img class="direct-chat-img" src="{{asset('img/default_user_img.png')}}" alt="Message User Image">
                                        <!-- /.direct-chat-img -->
                                        <div class="direct-chat-text" data-bind="html: col1"></div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                    <!-- /.direct-chat-msg -->


                                </div>
                                <!--/.direct-chat-messages-->
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="input-group">
{{--                                    <textarea name="message" id="message_txt" placeholder="Type Message ..." onkeypress="jobOrderList.ivm.checkEnterKey()" class="form-control" data-bind="value: taskMessage"></textarea>--}}
                                    <input type="text" name="message" id="message_txt" placeholder="Type Message ..." onkeypress="jobOrderList.ivm.checkEnterKey(event)" class="form-control" data-bind="value: taskMessage">
                                    <span class="input-group-append">
                                            <button type="button" class="btn btn-primary" onclick="jobOrderList.ivm.sendTaskMessage()">Send</button>
                                    </span>
                                    <button class="btn btn-outline-success ml-1" data-toggle="tooltip" data-placement="bottom" title="Click to refresh chat/comment window" onclick="jobOrderList.ivm.refreshTaskComments()"><i class="fas fa-sync-alt"></i></button>
                                </div>
                            </div>
                            <!-- /.card-footer-->
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="jobOrderList.ivm.submitRework()" data-toggle="tooltip" data-placement="bottom" title="Click done button to send the rework">Done</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- End:Task Comments model -->

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
                    $('#example2').DataTable().ajax.reload();
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

                    self.default_currenty = ko.observable(site.settings.default_currency);
                    self.is_product_discount = ko.observable(site.settings.product_discount);
                    self.is_tax1 = ko.observable(site.settings.tax1);
                    self.is_product_serial = ko.observable(site.settings.product_serial);
                    self.logged_user_id = ko.observable("{{ auth()->user()->id }}");

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
                    self.jo_task_id_assigned = ko.observable("");
                    self.task_name_assigned = ko.observable("");
                    self.task_technician_drop_id = ko.observable("");
                    self.task_technician_drop_name = ko.observable("");

                    self.jo_serv_pkg_id_assigned = ko.observable("");
                    self.serv_pkg_name_assigned = ko.observable("");
                    self.serv_pkg_technician_drop_id = ko.observable("");
                    self.serv_pkg_technician_drop_name = ko.observable("");

                    self.serv_pkg_technician_list = ko.observableArray();
                    self.serv_pkg_tech_delete_list = ko.observableArray();

                    self.tot_serv_pkg_amnt = ko.observable("");
                    self.servicePkgList = ko.observableArray();
                    self.deleteServicePkgList = ko.observableArray();

                    self.laborTaskList = ko.observableArray();
                    self.laborTaskTechTimeList = ko.observableArray();

                    self.task_technician_list = ko.observableArray();
                    self.task_tech_delete_list = ko.observableArray();

                    //task comments variables here
                    self.jo_task_id = ko.observable("");
                    self.comment_type = ko.observable("");
                    self.taskMessage = ko.observable("");
                    self.taskCommentsList = ko.observableArray();
                    self.deleteTaskCommentsList = ko.observableArray();

                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.listTable = () =>{

                        var table = $('#example2').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('joborder.approved.table.list') }}",
                            drawCallback: ( settings, json ) =>{ $('[data-toggle="tooltip"]').tooltip() },
                            columns: [
                                {data: 'woSerialNo', name: 'woSerialNo'},
                                {data: 'serial_no', name: 'serial_no'},
                                {data: 'name', name: 'name'},
                                {data: 'vinNo', name: 'vinNo', sClass: 'text-center'},
                                // {data: 'hourly_rate', name: 'hourly_rate', sClass: 'text-right'},
                                // {data: 'total', name: 'total', sClass: 'text-right'},
                                {data: 'taskStatus', name: 'taskStatus'},
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ]
                        });

                        var id = App_GetParameterByName();
                        if(id != null && id!=='' ){
                            self.itemID(id);
                            self.showJoborderFullView(id);
                        }
                    }

                    self.showJoborderFullView = (empID) =>{
                        $('#example2').DataTable().ajax.reload();
                        self.itemID(empID);
                        select2DropdownForModel('id_mod_technician_drop','assign_tech_mod','{{ route('technician.drop.list') }}')
                        $.ajax({
                            url: "{{ route('joborder.edit.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                emp_id: self.itemID(),
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading(); },
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                if(data.status){

                                    self.wo_no(data.data.wo_data['serial_no']);
                                    self.jo_no(data.data.head_data.serial_no);
                                    self.jo_date(data.data.head_data.jo_date);

                                    self.servicePkgList.removeAll();
                                    $.each(data.data.serv_pkg_data, (i,item) => {
                                        self.servicePkgList.push(new gridServPkgListRows(item));

                                        id = 'id_tech_serv_drop_'+(item.serv_pkg.id);
                                        select2DropdownForModel(id,'assign_tech_mod','{{ route('technician.drop.list') }}')
                                    })

                                    self.serv_pkg_technician_list.removeAll();
                                    $.each(data.data.serv_pkg_tech, (i, item) => {
                                        self.serv_pkg_technician_list.push(new gridServPkgTechnicianListRows(item));
                                    })

                                    self.laborTaskList.removeAll();
                                    $.each(data.data.task_data, (i,item) => {
                                        self.laborTaskList.push(new gridLaborTaskListRows(item,i));

                                        id = 'id_technician_drop_'+(item.task.id);
                                        select2DropdownForModel(id,'assign_tech_mod','{{ route('technician.drop.list') }}')
                                    })

                                    self.task_technician_list.removeAll();
                                    $.each(data.data.tsk_tech, (i, item)=>{
                                        self.task_technician_list.push(new gridTaskTechnicianListRows(item));
                                    })

                                    $('[data-toggle="tooltip"]').tooltip()
                                    $('#assign_tech_mod').modal('show')

                                }else
                                    sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: function( jqXhr, textStatus, errorThrown ){
                            }
                        });
                    }

                    /*service pkg*/
                    self.calculateServPkgTblTot = () => {

                        var lt_arr_length = self.servicePkgList().length;
                        var subtotal = 0;

                        for (var i = 0; i < lt_arr_length; i++) {

                            subtotal += parseFloat(self.servicePkgList()[i].col5().replace(/[^0-9-.]/g, ''));
                        }
                        self.tot_serv_pkg_amnt(accounting.formatNumber(subtotal, 2))
                        // self.calculatePartsTblTot();
                    }

                    let gridServPkgListRows = function (obj) {
                        let item = this;
                        if (typeof obj === "undefined") {

                            item.col1 = (typeof self.service_pkg_name() === "undefined" || self.service_pkg_name() == null) ? ko.observable("") : ko.observable(self.service_pkg_name());
                            item.col2 = (typeof self.service_pkg_descript() === "undefined" || self.service_pkg_descript() == null) ? ko.observable("") : ko.observable(self.service_pkg_descript());
                            item.col3 = (typeof self.service_pkg_v_type() === "undefined" || self.service_pkg_v_type() == null) ? ko.observable("") : ko.observable(self.service_pkg_v_type());
                            item.col4 = (typeof self.service_pkg_price() === "undefined" || self.service_pkg_price() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.service_pkg_price(), 2));
                            item.col5 = (typeof self.service_pkg_tot() === "undefined" || self.service_pkg_tot() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.service_pkg_tot(), 2));
                            item.col6 = ko.observable(self.service_pkg_id());
                            item.col7 = ko.observable('');
                            item.col8 = ko.observable("");
                            item.col9 = ko.observable("");
                            item.col10 = ko.observable("");
                        }else{
                            item.col1 = (typeof obj.serv_pkg.service_name === "undefined" || obj.serv_pkg.service_name == null) ? ko.observable("") : ko.observable(obj.serv_pkg.service_name);
                            item.col2 = (typeof obj.serv_pkg.description === "undefined" || obj.serv_pkg.description == null) ? ko.observable("") : ko.observable(obj.serv_pkg.description);
                            item.col3 = (typeof obj.serv_pkg.type_name === "undefined" || obj.serv_pkg.type_name == null) ? ko.observable("") : ko.observable(obj.serv_pkg.type_name);
                            item.col4 = (typeof obj.serv_pkg.price === "undefined" || obj.serv_pkg.price == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.serv_pkg.price, 2));
                            item.col5 = (typeof obj.serv_pkg.price === "undefined" || obj.serv_pkg.price == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.serv_pkg.price, 2));
                            item.col6 = ko.observable(obj.serv_pkg.service_pkg_id);
                            item.col7 = ko.observable(obj.serv_pkg.id);
                            item.col8 = (typeof obj.serv_pkg.is_approve_cost === "undefined" || obj.serv_pkg.is_approve_cost == null) ? ko.observable("") : ko.observable(obj.serv_pkg.is_approve_cost);
                            item.col9 = (typeof obj.serv_pkg.is_approve_work === "undefined" || obj.serv_pkg.is_approve_work == null) ? ko.observable("") : ko.observable(obj.serv_pkg.is_approve_work);
                            item.col10 = (typeof obj.pkg_techs === "undefined" || obj.pkg_techs == null) ? ko.observable("") : ko.observable(obj.pkg_techs);
                        }
                    }
                    /*end service pkg*/

                    let gridLaborTaskListRows = function (obj,i) {
                        let item = this;

                        if(typeof obj === "undefined"){

                            item.col1 = (typeof self.task_drop_name() === "undefined" || self.task_drop_name() == null) ? ko.observable("") : ko.observable(self.task_drop_name());
                            item.col2 = (typeof self.task_description() === "undefined" || self.task_description() == null) ? ko.observable("") : ko.observable(self.task_description());
                            item.col3 = (typeof self.task_hours() === "undefined" || self.task_hours() == null) ? ko.observable("") : ko.observable(self.task_hours());
                            item.col4 = (typeof self.task_act_hours() === "undefined" || self.task_act_hours() == null) ? ko.observable("") : ko.observable(self.task_act_hours());
                            item.col5 = (typeof self.task_hour_rates() === "undefined" || self.task_hour_rates() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.task_hour_rates(),2));
                            item.col6 = (typeof self.task_labor_cost() === "undefined" || self.task_labor_cost() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.task_labor_cost(),2));
                            item.col7 = (typeof self.task_labor_total() === "undefined" || self.task_labor_total() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.task_labor_total(),2));
                            item.col8 = (typeof self.task_drop_id() === "undefined" || self.task_drop_id() == null) ? ko.observable("") : ko.observable(self.task_drop_id());
                            item.col9 = ko.observable("");
                            item.col10 = ko.observable("");
                            item.col11 = ko.observable("");
                        }else{
                            item.col1 = (typeof obj.task.task_name === "undefined" || obj.task.task_name == null) ? ko.observable("") : ko.observable(obj.task.task_name);
                            item.col2 = (typeof obj.task.task_description === "undefined" || obj.task.task_description == null) ? ko.observable("") : ko.observable(obj.task.task_description);
                            item.col3 = (typeof obj.task.hours === "undefined" || obj.task.hours == null) ? ko.observable("") : ko.observable(obj.task.hours);
                            item.col4 = (typeof obj.task.actual_hours === "undefined" || obj.task.actual_hours == null) ? ko.observable("") : ko.observable(obj.task.actual_hours);
                            item.col5 = (typeof obj.task.hourly_rate === "undefined" || obj.task.hourly_rate == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.task.hourly_rate,2));
                            item.col6 = (typeof obj.task.labor_cost === "undefined" || obj.task.labor_cost == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.task.labor_cost,2));
                            item.col7 = (typeof obj.task.total === "undefined" || obj.task.total == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.task.total,2));
                            item.col8 = (typeof obj.task.task_id === "undefined" || obj.task.task_id == null) ? ko.observable("") : ko.observable(obj.task.task_id);
                            item.col9 = (typeof obj.task.id === "undefined" || obj.task.id == null) ? ko.observable("") : ko.observable(obj.task.id);
                            item.col10 = (typeof obj.task.is_approve_cost === "undefined" || obj.task.is_approve_cost == null) ? ko.observable("") : ko.observable(obj.task.is_approve_cost);
                            item.col11 = (typeof obj.task.is_approve_work === "undefined" || obj.task.is_approve_work == null) ? ko.observable("") : ko.observable(obj.task.is_approve_work);
                            item.col12 = (typeof obj.task_techs === "undefined" || obj.task_techs == null) ? ko.observable("") : ko.observable(obj.task_techs);
                        }
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

                    self.addLaborToServPkgGrid = (indexNo, rowData) => {
                        self.serv_pkg_technician_drop_id(getSelect2Val('id_tech_serv_drop_'+rowData.col7()));

                        if(typeof self.serv_pkg_technician_drop_id() === "undefined" || self.serv_pkg_technician_drop_id() == ""){

                            sweetAlertMsg('Form Validation Error!',"Please select a Technician first.",'warning')
                            return;
                        }
                        self.jo_serv_pkg_id_assigned(rowData.col7());
                        self.serv_pkg_name_assigned(rowData.col1());
                        self.serv_pkg_technician_drop_name(getSelect2Text('id_tech_serv_drop_'+rowData.col7()));
                        self.serv_pkg_technician_list.push(new gridServPkgTechnicianListRows());
                        $('[data-toggle="tooltip"]').tooltip()
                        APP_selectEmpty('id_tech_serv_drop_'+rowData.col7());
                        self.jo_serv_pkg_id_assigned("");
                        self.serv_pkg_name_assigned("");
                    }
                    let gridServPkgTechnicianListRows = function (obj) {
                        let item = this;

                        if(typeof obj === "undefined"){

                            item.col1 = (typeof self.serv_pkg_technician_drop_name() === "undefined" || self.serv_pkg_technician_drop_name() == null) ? ko.observable("") : ko.observable(self.serv_pkg_technician_drop_name());
                            item.col2 = (typeof self.serv_pkg_technician_drop_id() === "undefined" || self.serv_pkg_technician_drop_id() == null) ? ko.observable("") : ko.observable(self.serv_pkg_technician_drop_id());
                            item.col3 = ko.observable("");
                            item.col4 = (typeof self.jo_serv_pkg_id_assigned() === "undefined" || self.jo_serv_pkg_id_assigned() == null) ? ko.observable("") : ko.observable(self.jo_serv_pkg_id_assigned());
                            item.col5 = (typeof self.serv_pkg_name_assigned() === "undefined" || self.serv_pkg_name_assigned() == null) ? ko.observable("") : ko.observable(self.serv_pkg_name_assigned());
                        }else{
                            item.col1 = (typeof obj.full_name === "undefined" || obj.full_name == null) ? ko.observable("") : ko.observable(obj.full_name);
                            item.col2 = (typeof obj.tech_id === "undefined" || obj.tech_id == null) ? ko.observable("") : ko.observable(obj.tech_id);
                            item.col3 = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
                            item.col4 = (typeof obj.jo_serv_pkg_id === "undefined" || obj.jo_serv_pkg_id == null) ? ko.observable("") : ko.observable(obj.jo_serv_pkg_id);
                            item.col5 = (typeof obj.service_name === "undefined" || obj.service_name == null) ? ko.observable("") : ko.observable(obj.service_name);
                        }
                        item.col6 = ko.observable("serv");
                    }
                    self.removeServTechGridRow = (indexNo, rowData) => {
                        self.serv_pkg_technician_list.remove(rowData);
                        if(rowData.col3() && rowData.col3()!== "") {
                            self.serv_pkg_tech_delete_list.push(rowData.col3())
                        }
                    }

                    self.addLaborToGrid = (indexNo, rowData) => {
                        self.task_technician_drop_id(getSelect2Val('id_technician_drop_'+rowData.col9()));

                        if(typeof self.task_technician_drop_id() === "undefined" || self.task_technician_drop_id() == ""){

                            sweetAlertMsg('Form Validation Error!',"Please select a Technician first.",'warning')
                            return;
                        }
                        self.jo_task_id_assigned(rowData.col9());
                        self.task_name_assigned(rowData.col1());
                        self.task_technician_drop_name(getSelect2Text('id_technician_drop_'+rowData.col9()));
                        self.task_technician_list.push(new gridTaskTechnicianListRows());
                        $('[data-toggle="tooltip"]').tooltip()
                        APP_selectEmpty('id_technician_drop_'+rowData.col9());
                        self.jo_task_id_assigned("");
                        self.task_name_assigned("");
                    }
                    let gridTaskTechnicianListRows = function (obj) {
                        let item = this;

                        if(typeof obj === "undefined"){

                            item.col1 = (typeof self.task_technician_drop_name() === "undefined" || self.task_technician_drop_name() == null) ? ko.observable("") : ko.observable(self.task_technician_drop_name());
                            item.col2 = (typeof self.task_technician_drop_id() === "undefined" || self.task_technician_drop_id() == null) ? ko.observable("") : ko.observable(self.task_technician_drop_id());
                            item.col3 = ko.observable("");
                            item.col4 = (typeof self.jo_task_id_assigned() === "undefined" || self.jo_task_id_assigned() == null) ? ko.observable("") : ko.observable(self.jo_task_id_assigned());
                            item.col5 = (typeof self.task_name_assigned() === "undefined" || self.task_name_assigned() == null) ? ko.observable("") : ko.observable(self.task_name_assigned());
                        }else{
                            item.col1 = (typeof obj.full_name === "undefined" || obj.full_name == null) ? ko.observable("") : ko.observable(obj.full_name);
                            item.col2 = (typeof obj.tech_id === "undefined" || obj.tech_id == null) ? ko.observable("") : ko.observable(obj.tech_id);
                            item.col3 = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
                            item.col4 = (typeof obj.jo_task_id === "undefined" || obj.jo_task_id == null) ? ko.observable("") : ko.observable(obj.jo_task_id);
                            item.col5 = (typeof obj.task_name === "undefined" || obj.task_name == null) ? ko.observable("") : ko.observable(obj.task_name);
                        }
                        item.col6 = ko.observable("tsk");
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

                    self.approveJobOrderWork = (fbID) =>{

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
                                    url: "{{ route('joborder.approve.work') }}",
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: { "_token": "{{ csrf_token() }}", emp_id: fbID, },
                                    beforeSend: function (jqXHR, settings) { },
                                    success: function( data, textStatus, jQxhr ){

                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!',data.message,'success');
                                            /*setTimeout(function () {
                                                window.top.location = "{{ url('/joborder-assign-window') }}/"+fbID;
                                            },200)*/
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
                                "jo_id": self.itemID(),
                                "task_tech_list": ko.toJSON(self.task_technician_list),
                                "task_tech_delete_list": ko.toJSON(self.task_tech_delete_list),
                                "serv_pkg_tech_list": ko.toJSON(self.serv_pkg_technician_list),
                                "serv_pkg_tech_delete_list": ko.toJSON(self.serv_pkg_tech_delete_list),
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

                    self.sendToRework = (indexNo, rowData) =>{
                        self.showTaskCommentView(rowData.col4(), rowData.col6())
                        // console.log(rowData.col4())
                    }

                    self.submitRework = () => {
                        Swal.fire({
                            title: 'Are you sure, You want to send this for rework?',
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
                                    url: "{{ route('joborder.send.rework') }}",
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        job_id: self.itemID(),
                                        jo_tsk_serv_id: self.jo_task_id(),
                                        "com_type": self.comment_type(),
                                    },
                                    beforeSend: function (jqXHR, settings) { },
                                    success: function( data, textStatus, jQxhr ){

                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            $('#task_comment_mod').modal('hide');
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

                    /** Task and service pkg wise message functions below */

                    self.checkEnterKey = function (event) {
                        if(event.code == "Enter"){
                            self.sendTaskMessage()
                        }
                    }
                    let gridTaskCommentListRows = function (obj,i) {
                        let item = this;

                        if(typeof obj === "undefined"){

                        }else{
                            item.col1 = (typeof obj.message === "undefined" || obj.message == null) ? ko.observable("") : ko.observable(obj.message);
                            item.col2 = (typeof obj.full_name === "undefined" || obj.full_name == null) ? ko.observable("") : ko.observable(obj.full_name);
                            item.col3 = (typeof obj.sentTime === "undefined" || obj.sentTime == null) ? ko.observable("") : ko.observable(obj.sentTime);
                            item.col4 = (typeof obj.user_id === "undefined" || obj.user_id == null) ? ko.observable("") : ko.observable(obj.user_id);
                            item.col5 = (typeof obj.user_role_id === "undefined" || obj.user_role_id == null) ? ko.observable("") : ko.observable(obj.user_role_id);
                        }
                    }

                    self.showTaskCommentView = (jb_task_id, com_type) => {
                        self.taskCommentsList.removeAll();
                        self.jo_task_id(jb_task_id);
                        self.comment_type(com_type);
                        $.ajax({
                            url: "{{ route('task.comment.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "com_type": self.comment_type(),
                                task_id: self.jo_task_id(),
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading();},
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                if(data.status){

                                    $.each(data.data, (i,item) => {
                                        self.taskCommentsList.push(new gridTaskCommentListRows(item, "up"));
                                    })
                                    if(!$('#task_comment_mod').hasClass('show')) {
                                        $('#task_comment_mod').modal('show');
                                    }
                                }else{
                                    sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                }
                            },
                            error: function( jqXhr, textStatus, errorThrown ){
                                StopLoading();
                                if(jqXhr.status == 403){
                                    sweetAlertMsg(MsgHead.PE,'Users does not have the right permissions.','warning');
                                }
                            }
                        });
                    }

                    self.refreshTaskComments = () => {
                        self.taskCommentsList.removeAll();
                        $.ajax({
                            url: "{{ route('task.comment.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "com_type": self.comment_type(),
                                task_id: self.jo_task_id(),
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading();},
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                if(data.status){

                                    $.each(data.data, (i,item) => {
                                        self.taskCommentsList.push(new gridTaskCommentListRows(item, "up"));
                                    })
                                    if(!$('#task_comment_mod').hasClass('show')) {
                                        $('#task_comment_mod').modal('show');
                                    }
                                }else{
                                    sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                }
                            },
                            error: function( jqXhr, textStatus, errorThrown ){
                                StopLoading();
                                if(jqXhr.status == 403){
                                    sweetAlertMsg(MsgHead.PE,'Users does not have the right permissions.','warning');
                                }
                            }
                        });
                    }

                    self.sendTaskMessage = () =>{

                        if(self.taskMessage() === ""){
                            sweetAlertMsg("Required!","Message "+MsgContent.CBE,'warning')
                            return;
                        }
                        $.ajax({
                            url: "{{ route('task.comment.create.submit') }}",
                            dataType: 'json',
                            type: 'post',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                jo_id: self.itemID(),
                                "com_type": self.comment_type(),
                                jo_task_id: self.jo_task_id(),
                                task_message: self.taskMessage(),
                            },
                            success: function( data, textStatus, jQxhr ){

                                if(data.status){
                                    self.taskMessage("");
                                    self.showTaskCommentView(self.jo_task_id(), self.comment_type());
                                    // sweetAlertMsg('Success!',data.message,'success');
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
                    /** Task and service package message submit */

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

