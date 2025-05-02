@extends('template.master')
@section('title','Assigned Tasks')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-ui/jquery-ui.min.css')}}">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
<style>
    td.dt-control {
        background: url('../assets/plugins/datatables/resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }
    tr.shown td.dt-control {
        background: url('../assets/plugins/datatables/resources/details_close.png') no-repeat center center;
    }
</style>
@endpush

@section('content')
    <div class="card" id="employee_list_view">
        <div class="card-header">
            <h3 class="card-title">Assigned Tasks</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>WO SerialNo</th>
                                <th>Jo SerialNo</th>
                                <th>Job Date</th>
                                <th>Customer Name</th>
                                <th>Vin Number</th>
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
                        <h4 class="modal-title">Job order Task List</h4>
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
                        <!-- Job order task table -->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="id_lbr_task_tbl" class="table table-bordered table-hover responsive" style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th></th>
{{--                                        <th>Task Code</th>--}}
                                        <th>Task Name</th>

                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="id_lbr_task_tbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Job order service package table -->
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="id_lbr_serv_pkg_tbl" class="table table-bordered table-hover responsive" style="width: 100%;">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        {{--                                        <th>Task Code</th>--}}
                                        <th>Service Name</th>

                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="id_lbr_serv_pkg_tbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal">Done</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!--Begin:Inventory item adding model -->
        <div class="modal fade" id="lbr_inven_item_mod">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="item_add_modal_title"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ui-front">
                        <!-- Start: of item table filling area-->
                        <div class="row mb-3" data-bind="style: { display: laborsTaskList.ivm.item_input_state() ? '' : 'none' }">
                            <div class="col-md-12">
                                <div class="" style="background-color: #f6f6f6;">
                                    <div class="form-group mb-0">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <span class="fas fa-barcode fa-2x" id="basic-addon1"></span>
                                                    </span>
                                            </div>
                                            <input type="text" class="form-control form-control-lg" id="add_item"
                                                   placeholder="{{__('placeholders.add_product_to_order')}}"
                                                   aria-describedby="basic-addon1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End: of item table filling area-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="" class="text-center">Product</th>
                                            <th class="text-center" style="">Qty</th>

                                            <th class="text-center" style=""></th>
                                        </tr>
                                        </thead>
                                        <tbody data-bind="foreach: jbItemList">
                                        <tr data-bind="attr: { data_item_id: item_id, id: 'row_'+item_id(), class: validateClass1() }">
                                            <td class="text-center">
                                                <span data-bind="html: item_code"></span> -
                                                <span data-bind="html: item_name"></span>
                                                <span data-bind="style: { display: item_option_name() == 0 ? 'none' : '' }">
                                                    (<span data-bind="html: item_option_name"></span>)
                                                </span>
                                                <span style="cursor: pointer;" class="fa-pull-right fas fa-edit" data-bind="style: { display: db_item_id() == '' ? '' : 'none' }, event: { click: $parent.editItemRow.bind($data, $index()) }"></span>
                                            </td>

                                            <td class="text-center">
                                                <span data-bind="html: item_qty,style: { display: db_item_id() == '' ? 'none' : '' }"></span>
                                                <input type="text" class="form-control rquantity text-center" data-bind="value: item_qty, style: { display: db_item_id() == '' ? '' : 'none' }, event: { blur: $parent.calculateRQuantity.bind($data, $index()), click: $parent.setOldItemQty.bind($data, $index()) }">
                                            </td>
                                            <td class="text-center">
                                                <i class="fa fa-trash-alt" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Remove this row" data-bind="style: { display: db_item_id() == '' ? '' : 'none' },click: $parent.removeItemGridRow.bind($data, $index())"></i>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">

                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-bind="style: { display: laborsTaskList.ivm.itemValidateCls() == 'bg-warning' || !laborsTaskList.ivm.item_input_state() ? 'none' : '' }" onclick="laborsTaskList.ivm.submitTaskItems()">Submit</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.End: Inventory item adding modal -->

        <!--Edit Item Row model -->
        <div class="modal fade" id="edit_item_row_mod">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="row_edit_modal_title"></span></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="form-group col-md-12"
                                 data-bind="style: { display: laborsTaskList.ivm.is_product_serial() == 1 ? '' : 'none' }">
                                <label for="ID_mod_pro_serial" class="control-label">Serial No</label>
                                <input type="text" class="form-control" id="ID_mod_pro_serial"
                                       data-bind="value: mod_pro_serial">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="ID_mod_pquantity" class="control-label">Quantity</label>
                                <input type="text" class="form-control text-center" id="ID_mod_pquantity"
                                       data-bind="value: mod_pquantity" onblur="laborsTaskList.ivm.setModelAmountChange()">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="ID_mod_punit" class="control-label">Product Unit</label>
                                <div id="punits-div"></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="ID_mod_poption" class="control-label">Product Option</label>
                                <div id="poptions-div"></div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="">Done</button>
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
                        <h4 class="modal-title"><span id="task_comment_modal_title"></span>Comment window</h4>
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
                                    <div class="direct-chat-msg" data-bind="style: { display: laborsTaskList.ivm.logged_user_id() == col4() ? 'none' : '' }">
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
                                    <div class="direct-chat-msg right"  data-bind="style: { display: laborsTaskList.ivm.logged_user_id() == col4() ? '' : 'none' }">
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
                                    <input type="text" name="message" id="message_txt" placeholder="Type Message ..." onkeypress="laborsTaskList.ivm.checkEnterKey(event)" class="form-control" data-bind="value: taskMessage">
                                    <span class="input-group-append">
                                            <button type="button" class="btn btn-primary" onclick="laborsTaskList.ivm.sendTaskMessage()">Send</button>
                                    </span>
                                    <button class="btn btn-outline-success ml-1" data-toggle="tooltip" data-placement="bottom" title="Click to refresh chat/comment window" onclick="laborsTaskList.ivm.refreshTaskComments()"><i class="fas fa-sync-alt"></i></button>
                                </div>
                            </div>
                            <!-- /.card-footer-->
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="">Done</button>
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
        <script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
        <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
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
                    laborsTaskList.ivm.designInitialize();
                    laborsTaskList.ivm.listTable();
                },
                laborsTaskListViewModel: function () {
                    let self = this;

                    self.logged_user_id = ko.observable("{{ auth()->user()->id }}");

                    self.itemID = ko.observable("");
                    self.wo_no = ko.observable("");
                    self.jo_no = ko.observable("");
                    self.jo_date = ko.observable("");
                    self.woCustomer = ko.observable("");
                    self.jo_task_id = ko.observable("");
                    self.jo_serv_pkg_id = ko.observable("");

                    self.item_input_state = ko.observable(true);
                    self.comment_type = ko.observable("");

                    self.laborTaskList = ko.observableArray();

                    self.servicePkgList = ko.observableArray();
                    self.deleteServicePkgList = ko.observableArray();

                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    //Item adding varials start here
                    self.default_currenty = ko.observable(site.settings.default_currency);
                    self.is_product_discount = ko.observable(site.settings.product_discount);
                    self.is_tax1 = ko.observable(site.settings.tax1);
                    self.is_product_serial = ko.observable(site.settings.product_serial);


                    self.jbItemList = ko.observableArray();
                    self.deleteJbItemList = ko.observableArray();
                    self.old_item_qty = ko.observable("");

                    //task comments variables here
                    self.taskMessage = ko.observable("");
                    self.taskCommentsList = ko.observableArray();
                    self.deleteTaskCommentsList = ko.observableArray();
                    self.itemValidateCls = ko.observable("");

                    /*row edit model variables*/
                        self.tableRowData = ko.observable("");
                        self.table_index_id = ko.observable("");
                        self.mod_edit_tax = ko.observable("");
                        self.mod_pr_tax = ko.observable("");
                        self.mod_tax_method = ko.observable("");
                        self.mod_pro_serial = ko.observable("");
                        self.mod_pquantity = ko.observable("");//mod item quantity
                        self.mod_punit = ko.observable("");
                        self.mod_poption = ko.observable("");
                        self.mod_poption_price = ko.observable(0);
                        self.mod_pdiscount = ko.observable("");
                        self.mod_pprice_raw = ko.observable("");
                        self.mod_pprice = ko.observable("");
                        self.mod_real_unit_price = ko.observable("");
                        self.net_price = ko.observable("");
                        self.pro_tax = ko.observable("");
                        self.mod_psubt_raw = ko.observable("");
                        self.mod_psubt = ko.observable("");
                        self.mod_padiscount = ko.observable("");
                        self.mod_base_quantity = ko.observable("");
                        self.mod_base_unit_price = ko.observable("");
                        self.mod_item_price = ko.observable("");

                        self.tot_labor_tsk_amnt = ko.observable("");
                        self.tot_parts_amnt = ko.observable("");
                        self.tot_other_amnt = ko.observable("");
                        self.sub_tot_amnt = ko.observable("");
                        self.tax1_amnt = ko.observable("");
                        self.sup_charge_amnt = ko.observable("");
                        self.shipping_amnt = ko.observable("");
                        self.tot_amnt = ko.observable("");
                        self.discount_amnt = ko.observable("");
                        self.fee_amnt = ko.observable("");
                        self.diag_fee_amnt = ko.observable("");
                        self.grand_tot_amnt = ko.observable("");

                    self.listTable = function () {

                        var table = $('#example2').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('joborder.assigned.table.list') }}",
                            drawCallback: ( settings, json ) =>{ $('[data-toggle="tooltip"]').tooltip() },
                            columns: [
                                {data: 'woSerialNo', name: 'woSerialNo'},
                                {data: 'serial_no', name: 'serial_no'},
                                {data: 'jo_date', name: 'jo_date'},
                                {data: 'name', name: 'name'},
                                {data: 'vinNo', name: 'vinNo'},
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ]
                        });

                    }

                    self.showJoborderTasks = (empID) =>{
                        self.itemID(empID);

                        $.ajax({
                            url: "{{ route('joborder.tasks.assigned.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                emp_id: self.itemID(),
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading();},
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                if(data.status){

                                    self.wo_no(data.data.wo_data['serial_no']);
                                    self.woCustomer(data.data.wo_data['customer_id']);
                                    self.jo_no(data.data.head_data.serial_no);
                                    self.jo_date(data.data.head_data.jo_date);

                                    if ( $.fn.DataTable.isDataTable('#id_lbr_task_tbl') ) {
                                        $('#id_lbr_task_tbl').DataTable().destroy();
                                    }

                                    $('#id_lbr_task_tbl tbody').empty();

                                    let table = $('#id_lbr_task_tbl').DataTable({
                                        orderCellsTop: true,
                                        fixedHeader: true,
                                        processing: true,
                                        serverSide: true,
                                        "searching": false,
                                        ajax: "{{ url('/task-assigned-data-list') }}/"+self.itemID(),
                                        columns: [
                                            {
                                                className: 'dt-control',
                                                orderable: false,
                                                data: null,
                                                defaultContent: '',
                                            },
                                            // {data: 'task_code', name: 'task_code'},
                                            {data: 'taskDescriptEdited', name: 'taskDescriptEdited'},
                                            {
                                                data: 'action',
                                                name: 'action',
                                                orderable: false,
                                                searchable: false
                                            },
                                        ],
                                        initComplete: function () {

                                            $('[data-toggle="tooltip"]').tooltip()
                                            $('#assign_tech_mod').modal('show')
                                        }
                                    });

                                    // Add event listener for opening and closing details
                                    $('#id_lbr_task_tbl tbody').on('click', 'td.dt-control', function () {
                                        var tr = $(this).closest('tr');
                                        var row = table.row(tr);

                                        if (row.child.isShown()) {
                                            // This row is already open - close it
                                            row.child.hide();
                                            tr.removeClass('shown');
                                        } else {
                                            // Open this row
                                            row.child(format(row.data())).show();
                                            tr.addClass('shown');
                                        }
                                    });

                                    /* Formatting function for row details - modify as you need */
                                    function format(d) {
                                        // `d` is the original data object for the row
                                        return (
                                            '<table style="width: 100%">' +
                                                '<thead>' +
                                                    '<tr>' +
                                                        '<th>Task Description</th>' +
                                                    '</tr>' +
                                                '</thead>' +
                                                '<tbody>' +
                                                    '<tr>' +
                                                        '<td>' + d.task_description +'</td>' +
                                                    '</tr>' +
                                                '</tbody>' +
                                            '</table>'
                                        );
                                    }

                                    if ( $.fn.DataTable.isDataTable('#id_lbr_serv_pkg_tbl') ) {
                                        $('#id_lbr_serv_pkg_tbl').DataTable().destroy();
                                    }

                                    $('#id_lbr_serv_pkg_tbl tbody').empty();

                                    let table2 = $('#id_lbr_serv_pkg_tbl').DataTable({
                                        orderCellsTop: true,
                                        fixedHeader: true,
                                        processing: true,
                                        serverSide: true,
                                        "searching": false,
                                        ajax: "{{ url('/serv-pkg-assigned-data-list') }}/"+self.itemID(),
                                        columns: [
                                            {
                                                className: 'dt-control',
                                                orderable: false,
                                                data: null,
                                                defaultContent: '',
                                            },
                                            // {data: 'task_code', name: 'task_code'},
                                            {data: 'taskDescriptEdited', name: 'taskDescriptEdited'},
                                            {
                                                data: 'action',
                                                name: 'action',
                                                orderable: false,
                                                searchable: false
                                            },
                                        ],
                                        initComplete: function () {

                                            $('[data-toggle="tooltip"]').tooltip()
                                            $('#assign_tech_mod').modal('show')
                                        }
                                    });

                                    // Add event listener for opening and closing details
                                    $('#id_lbr_serv_pkg_tbl tbody').on('click', 'td.dt-control', function () {
                                        var tr = $(this).closest('tr');
                                        var row = table2.row(tr);

                                        if (row.child.isShown()) {
                                            // This row is already open - close it
                                            row.child.hide();
                                            tr.removeClass('shown');
                                        } else {
                                            // Open this row
                                            row.child(format(row.data())).show();
                                            tr.addClass('shown');
                                        }
                                    });

                                    /* Formatting function for row details - modify as you need */
                                    function format(d) {
                                        // `d` is the original data object for the row
                                        return (
                                            '<table style="width: 100%">' +
                                            '<thead>' +
                                            '<tr>' +
                                            '<th>Task Description</th>' +
                                            '</tr>' +
                                            '</thead>' +
                                            '<tbody>' +
                                            '<tr>' +
                                            '<td>' + d.description +'</td>' +
                                            '</tr>' +
                                            '</tbody>' +
                                            '</table>'
                                        );
                                    }

                                }else
                                    sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: function( jqXhr, textStatus, errorThrown ){
                                StopLoading();
                                if(jqXhr.status == 403){
                                    sweetAlertMsg(MsgHead.PE,'Users does not have the right permissions.','warning');
                                }
                            }
                        });
                    }

                    let gridLaborTaskListRows = function (obj,i) {
                        let item = this;

                        if(typeof obj === "undefined"){

                        }else{
                            item.col1 = (typeof obj.task_name === "undefined" || obj.task_name == null) ? ko.observable("") : ko.observable(obj.task_name);
                            item.col2 = (typeof obj.task_description === "undefined" || obj.task_description == null) ? ko.observable("") : ko.observable(obj.task_description);
                            item.col3 = (typeof obj.hours === "undefined" || obj.hours == null) ? ko.observable("") : ko.observable(obj.hours);
                            // item.col4 = (typeof obj.actual_hours === "undefined" || obj.actual_hours == null) ? ko.observable("") : ko.observable(obj.actual_hours);
                            // item.col5 = (typeof obj.hourly_rate === "undefined" || obj.hourly_rate == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.hourly_rate,2));
                            // item.col6 = (typeof obj.labor_cost === "undefined" || obj.labor_cost == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.labor_cost,2));
                            // item.col7 = (typeof obj.total === "undefined" || obj.total == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.total,2));
                            item.col4 = (typeof obj.task_id === "undefined" || obj.task_id == null) ? ko.observable("") : ko.observable(obj.task_id);
                            item.col5 = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
                            // item.col10 = (typeof obj.is_approve_cost === "undefined" || obj.is_approve_cost == null) ? ko.observable("") : ko.observable(obj.is_approve_cost);
                            // item.col11 = (typeof obj.is_approve_work === "undefined" || obj.is_approve_work == null) ? ko.observable("") : ko.observable(obj.is_approve_work);
                        }
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
                                        "time_type": self.comment_type(),
                                        tsk_id: fbID,
                                    },
                                    beforeSend: function (jqXHR, settings) { StartLoading(); },
                                    success: function( data, textStatus, jQxhr ){
                                        StopLoading();
                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            $('#id_lbr_task_tbl').DataTable().ajax.reload();
                                            $('#id_lbr_serv_pkg_tbl').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!',data.message,'success');
                                        }else
                                            sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                    },
                                    error: function( jqXhr, textStatus, errorThrown ){
                                        StopLoading();
                                        if(jqXhr.status == 403){
                                            sweetAlertMsg(MsgHead.PE,'Users does not have the right permissions.','warning');
                                        }else{
                                            console.log(jqXhr)
                                            var errors = '';
                                            if(typeof jqXhr.responseJSON != "undefined")
                                                errors = jqXhr.responseJSON.message;


                                            var errorList = "<ul>";

                                            $.each(errors, function (i, error) {
                                                errorList += '<li class="text-left text-danger">' + error + '</li>';
                                            })

                                            errorList += "</ul>"

                                            sweetAlertMsg('Form validation Error', errorList, 'warning');
                                        }
                                    }
                                });

                            } else { sweetAlertMsg('Canceled!','Task starting canceled.','info'); }
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
                                        "time_type": self.comment_type(),
                                        tsk_id: fbID,
                                    },
                                    beforeSend: function (jqXHR, settings) { StartLoading(); },
                                    success: function( data, textStatus, jQxhr ){
                                        StopLoading();
                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            $('#id_lbr_task_tbl').DataTable().ajax.reload();
                                            $('#id_lbr_serv_pkg_tbl').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!',data.message,'success');
                                        }else
                                            sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                    },
                                    error: function( jqXhr, textStatus, errorThrown ){
                                        StopLoading();
                                        if(jqXhr.status == 403){
                                            sweetAlertMsg(MsgHead.PE,'Users does not have the right permissions.','warning');
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

                            } else { sweetAlertMsg('Canceled!','Task ending canceled.','info'); }
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
                                        "time_type": self.comment_type(),
                                        tsk_id: fbID,
                                    },
                                    beforeSend: function (jqXHR, settings) { StartLoading(); },
                                    success: function( data, textStatus, jQxhr ){
                                        StopLoading();
                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            $('#id_lbr_task_tbl').DataTable().ajax.reload();
                                            $('#id_lbr_serv_pkg_tbl').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!',data.message,'success');
                                        }else
                                            sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                    },
                                    error: function( jqXhr, textStatus, errorThrown ){
                                        StopLoading();
                                        if(jqXhr.status == 403){
                                            sweetAlertMsg(MsgHead.PE,'Users does not have the right permissions.','warning');
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

                            } else { sweetAlertMsg('Canceled!','Task finishing canceled.','info'); }
                        })
                    }

                    self.showItemAddingView = (jb_task_id) => {
                        self.item_input_state(true);
                        self.jbItemList.removeAll();
                        self.jo_task_id(jb_task_id);
                        $('#item_add_modal_title').html("");
                        $.ajax({
                            url: "{{ route('joborder.task.item.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                task_id: self.jo_task_id(),
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading();},
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                if(data.status){

                                    $.each(data.data.item_data, (i,item) => {
                                        self.jbItemList.push(new gridItemListRows(item, "up"));
                                    })
                                    $('#lbr_inven_item_mod').modal('show');
                                    $('#item_add_modal_title').html(data.data.task_data['task_name']);
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

                    self.approveServPkg = (jo_serv_id) =>{

                        Swal.fire({
                            title: 'Are you sure, You want to accept this?',
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
                                let url = '{{ route('service.package.tech.approve') }}';
                                $.ajax({
                                    url: url,
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        item_id: jo_serv_id
                                    },
                                    success: function (data, textStatus, jQxhr) {

                                        if (data.status) {

                                            $('#id_lbr_task_tbl').DataTable().ajax.reload();
                                            $('#id_lbr_serv_pkg_tbl').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!', data.message, 'success');
                                        } else
                                            sweetAlertMsg(MsgHead.FSE, MsgContent.FSE, 'warning');
                                    },
                                    error: function (jqXhr, textStatus, errorThrown) {
                                        if (jqXhr.status == 403) {
                                            sweetAlertMsg(MsgHead.PE, 'Users does not have the right permissions.', 'warning');
                                        }
                                    }
                                });
                            } else { CommonMsg.ivm.sweetAlertMsg('Canceled!','Record approving canceled.','info'); }
                        });
                    }

                    self.showServPkgItemsView = (jb_serv_pkg_id) => {
                        self.item_input_state(false);
                        self.jbItemList.removeAll();
                        self.jo_serv_pkg_id(jb_serv_pkg_id);
                        $('#item_add_modal_title').html("");
                        $.ajax({
                            url: "{{ route('joborder.serv_pkg.item.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                jo_id: self.itemID(),
                                serv_pkg_id: self.jo_serv_pkg_id(),
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading();},
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                if(data.status){

                                    $.each(data.data.item_data, (i,item) => {
                                        self.jbItemList.push(new gridItemListRows(item, "up"));
                                    })
                                    $('#lbr_inven_item_mod').modal('show');
                                    $('#item_add_modal_title').html(data.data.serv_pkg_data['service_name']);
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
                    self.designInitialize = () => {
                        $("#add_item").autocomplete({
                            source: function (request, response) {
                                if (!self.woCustomer()) {
                                    $('#add_item').val('').removeClass('ui-autocomplete-loading');
                                    sweetAlertMsg('Info', '{{ __('messages.select_above') }}', 'warning')
                                    $('#add_item').focus();
                                    return false;
                                }
                                $.ajax({
                                    type: 'get',
                                    url: '{{route('product.suggestion.list')}}',
                                    dataType: "json",
                                    data: {
                                        term: request.term,
                                        warehouse_id: site.settings.default_warehouse,
                                        customer_id: self.woCustomer()
                                    },
                                    success: function (data) {
                                        $(this).removeClass('ui-autocomplete-loading');
                                        response(data);
                                    }
                                });
                            },
                            minLength: 1,
                            autoFocus: false,
                            delay: 250,
                            response: function (event, ui) {
                                if ($(this).val().length >= 16 && ui.content[0].id == 0) {
                                    sweetAlertMsg('Info', '{{ __('messages.no_match_found') }}', 'warning')
                                    self.clearAutoCompleteInput()
                                } else if (ui.content.length == 1 && ui.content[0].id != 0) {
                                    ui.item = ui.content[0];
                                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);
                                    $(this).autocomplete('close');
                                    $(this).removeClass('ui-autocomplete-loading');
                                } else if (ui.content.length == 1 && ui.content[0].id == 0) {
                                    self.clearAutoCompleteInput()
                                }
                            },
                            select: function (event, ui) {
                                event.preventDefault();
                                if (ui.item.id !== 0) {
                                    var row = self.add_invoice_item(ui.item);
                                    if (row) $(this).val('');
                                    self.clearAutoCompleteInput()
                                } else {
                                }
                            }
                        });
                    }

                    self.clearAutoCompleteInput = () => {
                        $('#add_item').focus();
                        $('#add_item').removeClass('ui-autocomplete-loading');
                        $('#add_item').val('');
                    }

                    /** start of inventory items adding */
                    self.removeItemGridRow = (indexNo, rowData) => {
                        self.jbItemList.remove(rowData);
                        if(rowData.db_item_id() && rowData.db_item_id() !== ""){
                            self.deleteJbItemList.push(rowData.db_item_id());
                        }
                        console.log(self.deleteJbItemList());
                        self.calculatePartsTblTot();
                    }
                    //add items to table grid
                    self.add_invoice_item = (itemData) => {
                        if (itemData == null) return;

                        slitems = {};
                        var item_id = itemData.id;
                        if (slitems[item_id]) {
                            var new_qty = parseFloat(slitems[item_id].row.qty) + 1;
                            slitems[item_id].row.base_quantity = new_qty;
                            if (slitems[item_id].row.unit != slitems[item_id].row.base_unit) {
                                $.each(slitems[item_id].units, function () {
                                    if (this.id == slitems[item_id].row.unit) {
                                        slitems[item_id].row.base_quantity = unitToBaseQty(new_qty, this);
                                    }
                                });
                            }
                            slitems[item_id].row.qty = new_qty;
                        } else {
                            slitems[item_id] = itemData;
                        }
                        slitems[item_id].order = new Date().getTime();

                        $.each(slitems, function (i, item) {
                            self.jbItemList.push(new gridItemListRows(item));
                        })

                        self.calculatePartsTblTot();
                    }

                    self.setOldItemQty = (indexNo, rowData) => {
                        self.old_item_qty(rowData.item_qty())
                    }

                    //function for calculate page table qty. Not edit item model row qty
                    self.calculateRQuantity = (indexNo, rowData) => {

                        if (!is_numeric(rowData.item_qty())) {

                            rowData.item_qty(self.old_item_qty())
                            sweetAlertMsg('Info', '{{ __('messages.unexpected_value') }}', 'warning')
                            return;
                        }
                        var new_qty = parseFloat(rowData.item_qty());

                        self.jbItemList()[indexNo].base_quantity(new_qty);
                        rowData.base_quantity(new_qty)

                        if (self.jbItemList()[indexNo].product_unit() != self.jbItemList()[indexNo].base_unit()) {
                            $.each(self.jbItemList()[indexNo].units(), function () {
                                if (this.id == self.jbItemList()[indexNo].product_unit()) {
                                    self.jbItemList()[indexNo].base_quantity(unitToBaseQty(new_qty, this));
                                    rowData.base_quantity = unitToBaseQty(new_qty, this);
                                }
                            });
                        }
                        self.jbItemList()[indexNo].item_qty(new_qty);
                        // slitems[item_id].row.qty = new_qty;
                        rowData.qty = new_qty;
                        self.calculateInvenTblLineTotal(indexNo, rowData);
                    }

                    self.calculateInvenTblLineTotal = (indexNo, rowData) => {

                        let unit_price = isNaN(rowData.real_unit_price()) ? 0 : rowData.real_unit_price();
                        let item_price = rowData.item_price_raw();
                        let item_qty = rowData.item_qty();
                        let product_discount = 0, product_tax = 0, total = 0;

                        //checking product has multiple options, if it was itemprice will change to option wise price
                        if (rowData.item_option() !== 0) {
                            item_price = parseFloat(unit_price) + parseFloat(rowData.item_option_price());
                            unit_price = item_price;
                            console.log("1st")
                        }

                        //calculate discount
                        var item_discount = 0,
                            ds = rowData.item_ds() ? rowData.item_ds() : '0';
                        if (ds.indexOf('%') !== -1) {
                            var pds = ds.split('%');
                            if (!isNaN(pds[0])) {
                                item_discount = formatDecimal(parseFloat((unit_price * parseFloat(pds[0])) / 100), 4);
                            } else {
                                item_discount = parseFloat(ds);
                            }
                        } else {
                            item_discount = parseFloat(ds);
                        }
                        product_discount += (item_discount * item_qty);
                        unit_price -= item_discount;
                        // console.log("ds"+ds);
                        // console.log("item_discount"+item_discount);

                        let tax_val =0;
                        let tax_rate =0;
                        //checking tax is enabled product wise o POS system
                        if (site.settings.tax1 == 1) {

                            //calculate product base tax rate
                            if (rowData.tax() !== null && rowData.tax() != 0) {

                                if (rowData.tax().type == 1) {
                                    if (rowData.item_tax_method() == '0') {
                                        tax_val = formatDecimal((unit_price * parseFloat(rowData.tax().rate)) / (100 + parseFloat(rowData.tax().rate)), 4);
                                        tax_rate = formatDecimal(rowData.tax().rate) + '%';
                                        // net_price -= rowData.tax_val;
                                    } else {
                                        tax_val = formatDecimal((unit_price * parseFloat(rowData.tax().rate)) / 100, 4);
                                        tax_rate = formatDecimal(rowData.tax().rate) + '%';
                                    }
                                } else if (rowData.tax().type == 2) {
                                    tax_val = parseFloat(rowData.tax().rate);
                                    tax_rate = rowData.tax().rate;
                                }
                            }
                        }

                        product_tax += (tax_val * item_qty);
                        item_price = rowData.item_tax_method() == 0 ? formatDecimal(unit_price - tax_val, 4) : formatDecimal(unit_price);
                        unit_price = (unit_price + item_discount);

                        console.log("rowData.item_type :"+rowData.item_type());
                        console.log("rowData.item_options :"+rowData.item_options());
                        console.log("rowData.item_option :"+rowData.item_option());
                        console.log("rowData.base_quantity :"+rowData.base_quantity());
                        console.log("rowData.item_aqty :"+rowData.item_aqty());
                        console.log("rowData.combo_items :"+rowData.combo_items());
                        console.log("rowData.item_id :"+rowData.item_id());

                        let same_it_qty = 0;
                        $.each(self.jbItemList(), function (i, item) {

                            if(i > 0 && self.jbItemList()[i-1].item_code() == item.item_code()) {
                                same_it_qty += parseFloat(self.jbItemList()[i-1].base_quantity());
                            }
                        })
                        same_it_qty += item_qty;

                        rowData.validateClass1('');
                        self.itemValidateCls('');
                        if (rowData.item_type() == 'standard' && same_it_qty > rowData.item_aqty()) {
                            console.log('if31')
                            rowData.validateClass1('bg-warning');
                            self.itemValidateCls('bg-warning');
                            if (site.settings.overselling != 1) {
                                console.log("if2.1")
                                $('#add_joborder, #edit_joborder').attr('disabled', true);
                            }
                        } else if (rowData.item_type() == 'standard' && rowData.item_options() !== false) {
                            console.log("if1")
                            console.log(rowData.item_options())
                            $.each(rowData.item_options(), function (i, option) {
                                console.log(option)
                                if (option.id == rowData.item_option() && parseFloat(rowData.base_quantity()) > parseFloat(option.quantity)) {
                                    console.log("if1.1")
                                    rowData.validateClass1('bg-warning');
                                    self.itemValidateCls('bg-warning');
                                    if (site.settings.overselling != 1) {
                                        $('#add_joborder, #edit_joborder').attr('disabled', true);
                                    }
                                }
                            });
                        } else if (rowData.item_type() == 'standard' && rowData.base_quantity() > rowData.item_aqty()) {
                            console.log('if2')
                            self.itemValidateCls('bg-warning');
                            if (site.settings.overselling != 1) {
                                console.log("if2.1")
                                $('#add_joborder, #edit_joborder').attr('disabled', true);
                            }
                        }else if (rowData.item_type() == 'combo') {
                            console.log("if4");
                            if (rowData.combo_items() === false) {
                                console.log("if4.1")
                                self.itemValidateCls('bg-warning');
                                $('#row_' + rowData.item_id()).addClass('bg-warning');
                                if (site.settings.overselling != 1) {
                                    $('#add_joborder, #edit_joborder').attr('disabled', true);
                                }
                            } else {
                                console.log('else');
                                $.each(rowData.combo_items(), function () {
                                    if (parseFloat(this.quantity) < parseFloat(this.qty) * rowData.base_quantity() && this.type == 'standard') {
                                        console.log('else1.1')
                                        self.itemValidateCls('bg-warning');
                                        $('#row_' + rowData.item_id()).addClass('bg-warning');
                                        if (site.settings.overselling != 1) {
                                            $('#add_joborder, #edit_joborder').attr('disabled', true);
                                        }
                                    }
                                });
                            }
                        }

                        total += ((parseFloat(item_price) + parseFloat(tax_val)) * parseFloat(item_qty));

                        /*console.log("product_tax: "+product_tax);
                        console.log("item_price: "+item_price);
                        console.log("unit_price: "+unit_price);
                        console.log("product_discount: "+product_discount);
                        console.log("tax_val: "+tax_val);
                        console.log("tax_rate: "+tax_rate);
                        console.log("total"+total);*/

                        console.log(rowData)

                        rowData.item_grid_sub_tot_raw(parseFloat(total));
                        rowData.item_grid_sub_tot(formatMoney(parseFloat(total)));
                        rowData.item_grid_price(formatMoney((isNaN(unit_price) ? 0 : unit_price) - item_discount));
                        rowData.item_qty(item_qty);
                        // self.jbItemList()[self.table_index_id()].item_ds(self.mod_pdiscount());
                        rowData.item_discount_raw(item_discount);
                        rowData.item_discount(formatMoney(item_discount));
                        rowData.tax_val_raw(product_tax);
                        rowData.tax_val(formatMoney(product_tax));
                        rowData.tax_rate(tax_rate)

                        self.calculatePartsTblTot();
                    }

                    self.editItemRow = (indexNo, rowData) => {

                        console.log(rowData);
                        self.table_index_id(indexNo); //this is use for identify the selected row from item grid

                        $('#row_edit_modal_title').html(rowData.item_name() + ' (' + rowData.item_code() + ')');
                        select2DropdownForModel('ID_mod_edit_tax', 'edit_item_row_mod', '{{ route('taxrate.all.drop.list') }}');

                        self.mod_real_unit_price(rowData.real_unit_price());
                        self.mod_base_quantity(isNaN(rowData.base_quantity()) ? 0 : rowData.base_quantity());
                        self.mod_base_unit_price(isNaN(rowData.base_unit_price()) ? 0 : rowData.base_unit_price());
                        self.mod_item_price(isNaN(rowData.item_price_raw()) ? 0 : rowData.item_price_raw());
                        self.mod_pquantity(isNaN(rowData.item_qty()) ? 0 : rowData.item_qty());

                        let base_quantity = self.mod_base_quantity();
                        let base_unit_price = self.mod_base_unit_price();
                        let unit_price = isNaN(self.mod_real_unit_price()) ? 0 : self.mod_real_unit_price();
                        let item_price = self.mod_item_price();
                        let item_qty = self.mod_pquantity();
                        let product_discount = 0;
                        let product_tax = 0;
                        let total = 0;

                        self.mod_pprice_raw(isNaN(rowData.unit_price()) ? 0 : rowData.unit_price());
                        self.mod_pprice(formatMoney(isNaN(rowData.unit_price()) ? 0 : rowData.unit_price()));

                        //setting item price by checking relatively item unit(each etc...)
                        if (rowData.product_units() && rowData.fup() != 1 && rowData.product_unit() != rowData.base_unit()) {
                            $.each(rowData.product_units(), function () {
                                if (this.id == rowData.product_unit()) {
                                    base_quantity = formatDecimal(unitToBaseQty(item_qty, this), 4);
                                    unit_price = formatDecimal(parseFloat(base_unit_price) * unitToBaseQty(1, this), 4);
                                }
                            });
                        }

                        //checking product has multiple options, if it was itemprice will change to option wise price
                        if (rowData.item_options() !== false) {
                            $.each(rowData.item_options(), function () {
                                if (this.id == rowData.item_option() && this.price != 0 && this.price != '' && this.price != null) {
                                    self.mod_poption_price(this.price);
                                    item_price = parseFloat(unit_price) + parseFloat(self.mod_poption_price());
                                    unit_price = item_price;
                                    self.mod_pprice_raw(parseFloat(self.mod_real_unit_price()) + parseFloat(self.mod_poption_price()));
                                    self.mod_pprice(formatMoney(parseFloat(self.mod_real_unit_price()) + parseFloat(self.mod_poption_price())));
                                }else { self.mod_poption_price(0); }
                            });
                        }

                        //calculate discount
                        var item_discount = 0,
                            ds = rowData.item_ds() ? rowData.item_ds() : '0';
                        if (ds.indexOf('%') !== -1) {
                            var pds = ds.split('%');
                            if (!isNaN(pds[0])) {
                                item_discount = formatDecimal(parseFloat((unit_price * parseFloat(pds[0])) / 100), 4);
                            } else {
                                item_discount = parseFloat(ds);
                            }
                        } else {
                            item_discount = parseFloat(ds);
                        }
                        product_discount += (item_discount * item_qty);
                        unit_price -= item_discount;

                        //checking tax is enabled product wise o POS system
                        if (site.settings.tax1 == 1) {
                            self.mod_edit_tax(rowData.tax().id);
                            self.mod_pr_tax(rowData.tax());
                            self.mod_tax_method(rowData.item_tax_method());
                            select2Dropdown('ID_mod_edit_tax', '{{ route('taxrate.all.drop.list') }}', '{{ route('taxrate.all.byid.drop.list') }}', self.mod_edit_tax());

                            //calculate product base tax rate
                            if (rowData.tax() !== null && rowData.tax() != 0) {

                                if (rowData.tax().type == 1) {
                                    if (rowData.item_tax_method() == '0') {
                                        rowData.tax_val((unit_price * parseFloat(rowData.tax().rate)) / (100 + parseFloat(rowData.tax().rate)), 4);
                                        rowData.tax_rate(rowData.tax().rate) + '%';
                                        // net_price -= rowData.tax_val;
                                    } else {
                                        rowData.tax_val((unit_price * parseFloat(rowData.tax().rate)) / 100, 4);
                                        rowData.tax_rate(rowData.tax().rate) + '%';
                                    }
                                } else if (rowData.tax().type == 2) {
                                    rowData.tax_val(parseFloat(rowData.tax().rate));
                                    rowData.tax_rate(rowData.tax().rate);
                                }
                            }
                        }

                        product_tax += (rowData.tax_val() * item_qty);
                        item_price = rowData.item_tax_method() == 0 ? formatDecimal(unit_price - rowData.tax_val(), 4) : formatDecimal(unit_price);
                        unit_price = (unit_price + item_discount);

                        self.mod_pprice_raw(isNaN(unit_price) ? 0 : unit_price);
                        self.mod_pprice(formatMoney(isNaN(unit_price) ? 0 : unit_price));

                        //setting product specific units
                        uopt = '<p style="margin: 12px 0 0 0;">n/a</p>';
                        $.each(rowData.product_units(), function () {
                            uopt = $('<select id="punit" name="punit" class="form-control select" onchange="laborsTaskList.ivm.setPUnit()" />');
                            if (this.id == rowData.product_unit()) {
                                $('<option />', {value: this.id, text: this.name, selected: true}).appendTo(uopt);
                            } else {
                                $('<option />', {value: this.id, text: this.name}).appendTo(uopt);
                            }
                        })
                        $('#punits-div').html(uopt);

                        //setting product specific options
                        var opt = '<p style="margin: 12px 0 0 0;">n/a</p>';
                        if (rowData.item_options() !== false) {
                            var o = 1;
                            opt = $('<select id="poption" name="poption" class="form-control select" onchange="laborsTaskList.ivm.setPOption()" />');

                            $.each(rowData.item_options(), function () {
                                if (o == 1) {
                                    if (rowData.item_option() == '') {
                                        self.mod_poption(this.id);
                                    } else {
                                        self.mod_poption(rowData.item_option());
                                    }
                                }

                                if (this.id == rowData.item_option()) {
                                    $('<option />', {value: this.id, text: this.name, selected: true}).appendTo(opt);
                                } else {
                                    $('<option />', {value: this.id, text: this.name}).appendTo(opt);
                                }

                                o++;
                            });
                        } else { self.mod_poption(0); }
                        $('#poptions-div').html(opt);

                        total += ((parseFloat(item_price) + parseFloat(rowData.tax_val())) * parseFloat(item_qty));

                        self.mod_item_price(isNaN(item_price) ? 0 : item_price);

                        self.mod_pro_serial(rowData.item_serial())
                        self.mod_pdiscount(rowData.item_ds())
                        self.pro_tax(formatMoney(product_tax, 2));
                        self.net_price(formatMoney(parseFloat(item_price)));
                        self.mod_psubt_raw(parseFloat(total));
                        self.mod_psubt(formatMoney(parseFloat(total)));

                        self.tableRowData(rowData);//set the selected table row data

                        $('#edit_item_row_mod').modal('show');
                    }
                    //End: editItemRow

                    self.setEditTax = () => {
                        self.mod_edit_tax(getSelect2Val('ID_mod_edit_tax'));
                        self.getTaxRateDetails();
                    }
                    self.setPUnit = () => {
                        self.mod_punit(getSelect2Val('punits-div'));
                        self.jbItemList()[self.table_index_id()].product_unit = self.mod_punit();
                    }
                    self.setPOption = () => {
                        self.mod_poption(getSelect2Val('poptions-div'));
                        self.getProductVariantDetails()
                    }

                    self.getTaxRateDetails = () => {
                        try {

                            if (typeof self.mod_edit_tax() === "undefined") return;

                            $.ajax({
                                url: '{{route('taxrate.selected.data')}}',
                                type: 'GET',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                dataType: 'json',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "ItemId": self.mod_edit_tax()
                                },
                                beforeSend: (jqXHR, settings) => { StartLoading(); },
                                success: (result) => {
                                    StopLoading();
                                    if (result.status == true) {
                                        self.mod_pr_tax(result.data[0]);
                                        self.mod_pr_tax().type = result.data[0].type;
                                        self.mod_pr_tax().rate = result.data[0].rate;
                                        self.setModelAmountChange();
                                    }
                                },
                                error: (jqXHR, textStatus, errorThrown) => {
                                    StopLoading();

                                    if (jqXHR.status == 403) {
                                        sweetAlertMsg('Permission Error', 'Users does not have the right permissions.', 'warning');
                                    } else {
                                        var errors = jqXHR.responseJSON.message;

                                        var errorList = "<ul>";

                                        $.each(errors, function (i, error) {
                                            errorList += '<li class="text-left text-danger">' + error + '</li>';
                                        })

                                        errorList += "</ul>"

                                        sweetAlertMsg('Form validation Error', errorList, 'warning');
                                    }
                                }
                            });
                        } catch (e) {
                        }
                    }

                    self.getProductVariantDetails = () => {
                        try {

                            if (typeof self.mod_poption() === "undefined") return;

                            $.ajax({
                                url: '{{route('product.variant.selected.data')}}',
                                type: 'GET',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                dataType: 'json',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "ItemId": self.mod_poption()
                                },
                                beforeSend: (jqXHR, settings) => { StartLoading(); },
                                success: (result) => {
                                    StopLoading();
                                    if (result.status == true) {

                                        self.jbItemList()[self.table_index_id()].item_options(result.data[0]);
                                        self.jbItemList()[self.table_index_id()].item_option_name(result.data[0].name);
                                        self.jbItemList()[self.table_index_id()].item_option_price(result.data[0].price);
                                        self.jbItemList()[self.table_index_id()].item_option(result.data[0].id);
                                        self.jbItemList()[self.table_index_id()].item_aqty(formatDecimal(result.data[0].quantity),2);
                                        self.mod_poption(result.data[0].id);
                                        self.mod_poption_price(result.data[0].price);
                                        self.setModelAmountChange();
                                    }
                                },
                                error: (jqXHR, textStatus, errorThrown) => {
                                    StopLoading();

                                    if (jqXHR.status == 403) {
                                        sweetAlertMsg('Permission Error', 'Users does not have the right permissions.', 'warning');
                                    } else {
                                        var errors = jqXHR.responseJSON.message;

                                        var errorList = "<ul>";

                                        $.each(errors, function (i, error) {
                                            errorList += '<li class="text-left text-danger">' + error + '</li>';
                                        })

                                        errorList += "</ul>"

                                        sweetAlertMsg('Form validation Error', errorList, 'warning');
                                    }
                                }
                            });
                        } catch (e) {
                        }
                    }

                    self.setModelAmountChange = () => {

                        let base_unit_price = self.mod_base_unit_price();
                        let unit_price = isNaN(self.mod_real_unit_price()) ? 0 : self.mod_real_unit_price();
                        let item_price = self.mod_item_price();
                        let item_qty = self.mod_pquantity();
                        let product_discount = 0, product_tax = 0, total = 0;

                        // console.log("item_price"+item_price);
                        // console.log("self.mod_poption_price()"+self.mod_poption_price());
                        //checking product has multiple options, if it was itemprice will change to option wise price
                        if (self.mod_poption() !== 0) {
                            item_price = parseFloat(unit_price) + parseFloat(self.mod_poption_price());
                            unit_price = item_price;
                            console.log("1st")
                        }

                        //calculate discount
                        var item_discount = 0,
                            ds = self.mod_pdiscount() ? self.mod_pdiscount() : '0';
                        if (ds.indexOf('%') !== -1) {
                            var pds = ds.split('%');
                            if (!isNaN(pds[0])) {
                                item_discount = formatDecimal(parseFloat((unit_price * parseFloat(pds[0])) / 100), 4);
                            } else {
                                item_discount = parseFloat(ds);
                            }
                        } else {
                            item_discount = parseFloat(ds);
                        }
                        product_discount += (item_discount * item_qty);
                        unit_price -= item_discount;
                        // console.log("ds"+ds);
                        // console.log("item_discount"+item_discount);

                        let tax_val =0;
                        let tax_rate =0;
                        //checking tax is enabled product wise o POS system
                        if (site.settings.tax1 == 1) {

                            //calculate product base tax rate
                            if (self.mod_pr_tax() !== null && self.mod_pr_tax() != 0) {

                                if (self.mod_pr_tax().type == 1) {
                                    if (self.mod_tax_method() == '0') {
                                        tax_val = formatDecimal((unit_price * parseFloat(self.mod_pr_tax().rate)) / (100 + parseFloat(self.mod_pr_tax().rate)), 4);
                                        tax_rate = formatDecimal(self.mod_pr_tax().rate) + '%';
                                        // net_price -= rowData.tax_val;
                                    } else {
                                        tax_val = formatDecimal((unit_price * parseFloat(self.mod_pr_tax().rate)) / 100, 4);
                                        tax_rate = formatDecimal(self.mod_pr_tax().rate) + '%';
                                    }
                                } else if (self.mod_pr_tax().type == 2) {
                                    tax_val = parseFloat(self.mod_pr_tax().rate);
                                    tax_rate = self.mod_pr_tax().rate;
                                }
                            }
                        }

                        product_tax += (tax_val * item_qty);
                        item_price = self.mod_tax_method() == 0 ? formatDecimal(unit_price - tax_val, 4) : formatDecimal(unit_price);
                        unit_price = (unit_price + item_discount);

                        total += ((parseFloat(item_price) + parseFloat(tax_val)) * parseFloat(item_qty));

                        /*console.log("product_tax: "+product_tax);
                        console.log("item_price: "+item_price);
                        console.log("unit_price: "+unit_price);
                        console.log("product_discount: "+product_discount);
                        console.log("tax_val: "+tax_val);
                        console.log("total"+total);*/
                        self.mod_pprice_raw(isNaN(unit_price) ? 0 : unit_price);
                        self.mod_pprice(formatMoney(isNaN(unit_price) ? 0 : unit_price));

                        self.pro_tax(formatMoney(product_tax));
                        self.net_price(formatMoney(parseFloat(item_price)));
                        self.mod_psubt_raw(parseFloat(total));
                        self.mod_psubt(formatMoney(parseFloat(total)));

                        self.jbItemList()[self.table_index_id()].item_grid_sub_tot_raw(self.mod_psubt_raw());
                        self.jbItemList()[self.table_index_id()].item_grid_sub_tot(formatMoney(self.mod_psubt_raw()));
                        self.jbItemList()[self.table_index_id()].item_grid_price(formatMoney(self.mod_pprice_raw() - item_discount));
                        self.jbItemList()[self.table_index_id()].item_qty(self.mod_pquantity());
                        self.jbItemList()[self.table_index_id()].base_quantity(item_qty);
                        self.jbItemList()[self.table_index_id()].item_ds(self.mod_pdiscount());
                        self.jbItemList()[self.table_index_id()].item_discount_raw(item_discount);
                        self.jbItemList()[self.table_index_id()].item_discount(formatMoney(item_discount));
                        self.jbItemList()[self.table_index_id()].item_option(self.mod_poption());

                        self.jbItemList()[self.table_index_id()].tax(self.mod_pr_tax());
                        self.jbItemList()[self.table_index_id()].tax_val_raw(product_tax);
                        self.jbItemList()[self.table_index_id()].tax_val(formatMoney(product_tax));
                        self.jbItemList()[self.table_index_id()].tax_rate(tax_rate);

                        let item_type = self.jbItemList()[self.table_index_id()].item_type();
                        let item_options = self.jbItemList()[self.table_index_id()].item_options();
                        let item_option = self.jbItemList()[self.table_index_id()].item_option();
                        let base_quantity = self.jbItemList()[self.table_index_id()].base_quantity();
                        let item_aqty = self.jbItemList()[self.table_index_id()].item_aqty();
                        let combo_items = self.jbItemList()[self.table_index_id()].combo_items();
                        let item_id = self.jbItemList()[self.table_index_id()].item_id();

                        console.log("item_type :"+item_type);
                        console.log("item_options :"+item_options);
                        console.log("item_option :"+item_option);
                        console.log("base_quantity :"+base_quantity);
                        console.log("item_aqty :"+item_aqty);
                        console.log("combo_items :"+combo_items);
                        console.log("item_id :"+item_id);

                        /** apply qty validation color */
                        self.jbItemList()[self.table_index_id()].validateClass1('');
                        if (item_type == 'standard' && item_options !== false) {
                            console.log("if1")
                            if (base_quantity > item_aqty) {
                                self.jbItemList()[self.table_index_id()].validateClass1('bg-warning');

                                if (site.settings.overselling != 1) {
                                    $('#add_joborder, #edit_joborder').attr('disabled', true);
                                }
                            }
                        } else if (item_type == 'standard' && base_quantity > item_aqty) {
                            self.jbItemList()[self.table_index_id()].validateClass1('bg-warning');
                            if (site.settings.overselling != 1) {
                                console.log("if2.1")
                                $('#add_joborder, #edit_joborder').attr('disabled', true);
                            }
                        } else if (item_type == 'combo') {
                            console.log("if3");
                            if (combo_items === false) {
                                console.log("if3.1")
                                self.jbItemList()[self.table_index_id()].validateClass1('bg-warning');
                                // $('#row_' + item_id).addClass('bg-warning');
                                if (site.settings.overselling != 1) {
                                    $('#add_joborder, #edit_joborder').attr('disabled', true);
                                }
                            } else {
                                console.log('else');
                                $.each(combo_items, function () {
                                    if (parseFloat(this.quantity) < (parseFloat(this.qty) * base_quantity) && this.type == 'standard') {
                                        self.jbItemList()[self.table_index_id()].validateClass1('bg-warning');
                                        // $('#row_' + item_id).addClass('bg-warning');
                                        if (site.settings.overselling != 1) {
                                            $('#add_joborder, #edit_joborder').attr('disabled', true);
                                        }
                                    }
                                });
                            }
                        }

                        self.calculatePartsTblTot();
                        console.log(self.jbItemList()[self.table_index_id()])
                    }
                    //End: setModelAmountChange

                    self.calculatePartsTblTot = (formState) => {

                        var lt_arr_length = self.jbItemList().length;
                        var subtotal = 0;

                        for (var i = 0; i < lt_arr_length; i++) {

                            subtotal += parseFloat(self.jbItemList()[i].item_grid_sub_tot_raw());
                        }
                        self.tot_parts_amnt(accounting.formatNumber(subtotal, 2));
                        self.calculateSubTot();
                    }

                    self.calculateSubTot = () => {
                        var subTot = parseFloat(self.tot_parts_amnt().replace(/[^0-9-.]/g, '')) + parseFloat(self.tot_labor_tsk_amnt().replace(/[^0-9-.]/g, ''));

                        self.sub_tot_amnt(accounting.formatNumber(subTot, 2));
                        self.calculateTot();
                    }

                    self.calculateTot = () => {
                        let sub_tot = self.sub_tot_amnt() == "" ? 0 : self.sub_tot_amnt().replace(/[^0-9-.]/g, '');
                        let tax1 = self.tax1_amnt() == "" ? 0 : self.tax1_amnt().replace(/[^0-9-.]/g, '');
                        let sup_charge = self.sup_charge_amnt() == "" ? 0 : self.sup_charge_amnt().replace(/[^0-9-.]/g, '');
                        let shipping = self.shipping_amnt() == "" ? 0 : self.shipping_amnt().replace(/[^0-9-.]/g, '');

                        let tot = parseFloat(isNaN(sub_tot) ? 0 : sub_tot) + parseFloat(isNaN(tax1) ? 0 : tax1) + parseFloat(isNaN(sup_charge) ? 0 : sup_charge) + parseFloat(isNaN(shipping) ? 0 : shipping);

                        self.tot_amnt(accounting.formatNumber(tot, 2));
                        self.calculateDiscount();
                    }

                    self.calculateDiscount = () => {
                        let ds = self.discount_amnt() == "" ? 0 : self.discount_amnt();
                        let tot_amnt = parseFloat(self.tot_amnt() == "" ? 0 : self.tot_amnt().replace(/[^0-9-.]/g, ''));
                        let item_discount = 0;
                        let grand_tot = 0;

                        if (ds != "" && !isNaN(ds)) {
                            if (ds.indexOf('%') !== -1) {
                                var pds = ds.split('%');
                                if (!isNaN(pds[0])) {
                                    item_discount = parseFloat((tot_amnt * parseFloat(pds[0])) / 100);
                                } else {
                                    item_discount = parseFloat(ds);
                                }
                            } else {
                                item_discount = parseFloat(ds);
                            }
                        }
                        grand_tot = tot_amnt - item_discount;
                        self.grand_tot_amnt(accounting.formatNumber(grand_tot, 2))
                    }

                    let gridItemListRows = function (obj, state) {
                        let item = this;
                        if (state == "up") {

                            item.item_id = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id); // auto generated unique hash
                            item.db_item_id = (typeof obj.row.jb_task_item_id === "undefined" || obj.row.jb_task_item_id == null) ? ko.observable("") : ko.observable(obj.row.jb_task_item_id);// db auto incremented id
                            item.order = ko.observable(obj.order ? obj.order : new Date().getTime());
                            item.product_id = (typeof obj.row.product_id === "undefined" || obj.row.product_id == null) ? ko.observable("") : ko.observable(obj.row.product_id);
                            item.item_type = (typeof obj.row.product_type === "undefined" || obj.row.product_type == null) ? ko.observable("") : ko.observable(obj.row.product_type);
                            item.combo_items = ko.observable(false);
                            item.item_price_raw = (typeof obj.row.unit_price === "undefined" || obj.row.unit_price == null) ? ko.observable("") : ko.observable(obj.row.unit_price);
                            item.item_price = (typeof obj.row.unit_price === "undefined" || obj.row.unit_price == null) ? ko.observable("") : ko.observable(obj.row.unit_price);
                            item.item_qty = (typeof obj.row.qty === "undefined" || obj.row.qty == null) ? ko.observable("") : ko.observable(obj.row.qty);
                            item.item_aqty = (typeof obj.row.quantity === "undefined" || obj.row.quantity == null) ? ko.observable("") : ko.observable(obj.row.quantity);
                            item.item_tax_method = (typeof obj.row.tax_method === "undefined" || obj.row.tax_method == null) ? ko.observable("") : ko.observable(obj.row.tax_method);
                            item.item_ds = (typeof obj.row.discount === "undefined" || obj.row.discount == null) ? ko.observable("") : ko.observable(obj.row.discount);
                            item.item_discount_raw = (typeof obj.row.item_discount === "undefined" || obj.row.item_discount == null) ? ko.observable("") : ko.observable(obj.row.item_discount);
                            item.item_discount = (typeof obj.row.item_discount === "undefined" || obj.row.item_discount == null) ? ko.observable("") : ko.observable(obj.row.item_discount);
                            item.item_option = (typeof obj.row.item_option_id === "undefined" || obj.row.item_option_id == null) ? ko.observable("") : ko.observable(obj.row.item_option_id);
                            item.item_option_name = (typeof obj.row.option_name === "undefined" || obj.row.option_name == null) ? ko.observable("") : ko.observable(obj.row.option_name);
                            item.item_option_price = ko.observable(0);
                            item.item_code = (typeof obj.row.product_code === "undefined" || obj.row.product_code == null) ? ko.observable("") : ko.observable(obj.row.product_code);
                            item.item_serial = ko.observable("");
                            item.item_name = (typeof obj.row.product_name === "undefined" || obj.row.product_name == null) ? ko.observable("") : ko.observable(obj.row.product_name.replace(/"/g, '&#034;').replace(/'/g, '&#039;'));
                            item.product_unit = (typeof obj.row.product_unit_id === "undefined" || obj.row.product_unit_id == null) ? ko.observable("") : ko.observable(obj.row.product_unit_id);
                            item.base_quantity = (typeof obj.row.unit_quantity === "undefined" || obj.row.unit_quantity == null) ? ko.observable("") : ko.observable(obj.row.unit_quantity);
                            item.real_unit_price = (typeof obj.row.real_unit_price === "undefined" || obj.row.real_unit_price == null) ? ko.observable("") : ko.observable(obj.row.real_unit_price);
                            item.unit_price = (typeof obj.row.real_unit_price === "undefined" || obj.row.real_unit_price == null) ? ko.observable("") : ko.observable(obj.row.real_unit_price);
                            item.product_units = (typeof obj.units === "undefined" || obj.units == null) ? ko.observable("") : ko.observable(obj.units);
                            item.item_options = (typeof obj.options === "undefined" || obj.options == null) ? ko.observable("") : ko.observable(obj.options);
                            item.base_unit = (typeof obj.row.base_unit === "undefined" || obj.row.base_unit == null) ? ko.observable("") : ko.observable(obj.row.base_unit);// base unit is simply unit of product
                            item.fup = ko.observable(false);
                            item.base_unit_price = (typeof obj.row.base_unit_price === "undefined" || obj.row.base_unit_price == null) ? ko.observable("") : ko.observable(obj.row.base_unit_price);// base unit price is simply price of product

                            //setting item price by checking relatively item unit(each etc...)
                            if (obj.units && item.fup() != 1 && item.product_unit != item.base_unit()) {
                                $.each(obj.units, function () {
                                    if (this.id == item.product_unit) {
                                        item.base_quantity = formatDecimal(unitToBaseQty(item.item_qty(), this), 4);
                                        item.unit_price = formatDecimal(parseFloat(item.base_unit_price()) * unitToBaseQty(1, this), 4);
                                    }
                                });
                            }

                            //setting item price by checking relatively item options(medium, small etc...)
                            let sel_opt = '';
                            if (obj.options !== false) {
                                $.each(obj.options, function () {

                                    if (this.id == item.item_option()) {
                                        sel_opt = this.name;
                                        item.item_option_name(sel_opt);
                                        if (this.price != 0 && this.price != '' && this.price != null) {

                                            // item_price = unit_price + parseFloat(this.price) * parseFloat(base_quantity);
                                            item.item_option_price(parseFloat(this.price));
                                            item.item_price_raw(parseFloat(item.unit_price()) + parseFloat(this.price));
                                            item.item_price(parseFloat(item.unit_price()) + parseFloat(this.price));
                                            item.unit_price(item.item_price());
                                        }
                                    }
                                });
                            }

                            //setting item discount added from POS item wise
                            let ds = item.item_ds() ? item.item_ds() : '0';
                            if (ds.indexOf('%') !== -1) {
                                let pds = ds.split('%');
                                if (!isNaN(pds[0])) {
                                    item.item_discount_raw(parseFloat((item.unit_price() * parseFloat(pds[0])) / 100, 4));
                                    item.item_discount(formatMoney(parseFloat((item.unit_price() * parseFloat(pds[0])) / 100, 4)));
                                } else {
                                    item.item_discount_raw(parseFloat(ds));
                                    item.item_discount(formatMoney(parseFloat(ds)));
                                }
                            } else {
                                item.item_discount_raw(parseFloat(ds));
                                item.item_discount(formatMoney(parseFloat(ds)));
                            }

                            item.unit_price(formatDecimal(item.unit_price() - (item.item_discount_raw())));

                            //setting tax data
                            item.tax = (typeof obj.tax_rate === "undefined" || obj.tax_rate == null) ? ko.observable("") : ko.observable(obj.tax_rate);

                            let tax_val  = 0, tax_rate = 0;

                            //check tax enabling on POS and if it was calculate tax for the product
                            if (site.settings.tax1 == 1) {
                                if (item.tax() !== false && item.tax() != 0) {
                                    if (item.tax().type == 1) {
                                        if (item.item_tax_method() == '0') {
                                            tax_val = formatDecimal((item.unit_price() * parseFloat(item.tax().rate)) / (100 + parseFloat(item.tax().rate)), 4);
                                            tax_rate = formatDecimal(item.tax().rate) + '%';
                                        } else {
                                            tax_val = formatDecimal((item.unit_price() * parseFloat(item.tax().rate)) / 100, 4);
                                            tax_rate = formatDecimal(item.tax().rate) + '%';
                                        }
                                    } else if (item.tax().type == 2) {
                                        tax_val = parseFloat(item.tax().rate);
                                        tax_rate = item.tax().rate;
                                    }
                                    // console.log(item.pr_tax_val)
                                    // console.log(item.pr_tax_rate)
                                }
                            }
                            item.tax_val_raw = ko.observable(tax_val);
                            item.tax_val = ko.observable(formatMoney(tax_val));
                            item.tax_rate = ko.observable(tax_rate);
                            item.item_price_raw(item.item_tax_method() == 0 ? (item.unit_price() - tax_val) : (item.unit_price()));
                            item.item_price(formatMoney(item.item_tax_method() == 0 ? (item.unit_price() - tax_val) : (item.unit_price())));
                            item.unit_price(formatDecimal(item.unit_price() + parseFloat(item.item_discount_raw()), 4));

                            item.item_grid_sub_tot_raw = ko.observable(((parseFloat(item.item_price_raw()) + parseFloat(0)) * parseFloat(item.item_qty())));
                            item.item_grid_sub_tot = ko.observable(formatMoney((parseFloat(item.item_price_raw()) + parseFloat(0)) * parseFloat(item.item_qty())));
                            item.item_grid_price = ko.observable(formatMoney(item.item_price_raw()));

                            /** Item qty validation adding */
                            item.validateClass1 = ko.observable('');
                            if (item.item_type() == 'standard' && obj.options !== false) {
                                console.log("if1")
                                $.each(obj.options, function (i, option) {
                                    if (option.id == item.item_option() && parseFloat(item.base_quantity()) > parseFloat(option.quantity)) {
                                        item.validateClass1('bg-warning');

                                        if (site.settings.overselling != 1) {
                                            $('#add_joborder, #edit_joborder').attr('disabled', true);
                                        }
                                    }
                                });
                            } else if (item.item_type() == 'standard' && parseFloat(item.base_quantity()) > parseFloat(item.item_aqty())) {
                                item.validateClass1('bg-warning');
                                if (site.settings.overselling != 1) {
                                    console.log("if2.1")
                                    $('#add_joborder, #edit_joborder').attr('disabled', true);
                                }
                            } else if (item.item_type() == 'combo') {
                                console.log("if3")
                                if (item.combo_items === false) {
                                    console.log("if3.1")
                                    item.validateClass1('bg-warning');
                                    $('#row_' + item.item_id()).addClass('bg-warning');
                                    if (site.settings.overselling != 1) {
                                        $('#add_joborder, #edit_joborder').attr('disabled', true);
                                    }
                                } else {
                                    console.log('else');
                                    $.each(item.combo_items(), function () {
                                        if (parseFloat(this.quantity) < (parseFloat(this.qty) * item.base_quantity()) && this.type == 'standard') {
                                            item.validateClass1('bg-warning');
                                            $('#row_' + item.item_id()).addClass('bg-warning');
                                            if (site.settings.overselling != 1) {
                                                $('#add_joborder, #edit_joborder').attr('disabled', true);
                                            }
                                        }
                                    });
                                }
                            }

                        } else{

                            item.item_id = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
                            item.db_item_id = ko.observable("");
                            item.order = (typeof obj.order === "undefined" || obj.order == null) ? ko.observable("") : ko.observable(obj.order ? obj.order : new Date().getTime());
                            item.product_id = (typeof obj.row.id === "undefined" || obj.row.id == null) ? ko.observable("") : ko.observable(obj.row.id);
                            item.item_type = (typeof obj.row.type === "undefined" || obj.row.type == null) ? ko.observable("") : ko.observable(obj.row.type);
                            item.combo_items = (typeof obj.combo_items === "undefined" || obj.combo_items == null) ? ko.observable("") : ko.observable(obj.combo_items);
                            item.item_price_raw = (typeof obj.row.price === "undefined" || obj.row.price == null) ? ko.observable("") : ko.observable(obj.row.price);
                            item.item_price = (typeof obj.row.price === "undefined" || obj.row.price == null) ? ko.observable("") : ko.observable(obj.row.price);
                            item.item_qty = (typeof obj.row.qty === "undefined" || obj.row.qty == null) ? ko.observable("") : ko.observable(obj.row.qty);
                            item.item_aqty = (typeof obj.row.quantity === "undefined" || obj.row.quantity == null) ? ko.observable("") : ko.observable(obj.row.quantity);
                            item.item_tax_method = (typeof obj.row.tax_method === "undefined" || obj.row.tax_method == null) ? ko.observable("") : ko.observable(obj.row.tax_method);
                            item.item_ds = (typeof obj.row.discount === "undefined" || obj.row.discount == null) ? ko.observable("") : ko.observable(obj.row.discount);
                            item.item_discount_raw = ko.observable(0);
                            item.item_discount = ko.observable(0);
                            item.item_option = (typeof obj.row.option === "undefined" || obj.row.option == null) ? ko.observable("") : ko.observable(obj.row.option);
                            item.item_option_name = ko.observable('');
                            item.item_option_price = ko.observable(0);
                            item.item_code = (typeof obj.row.code === "undefined" || obj.row.code == null) ? ko.observable("") : ko.observable(obj.row.code);
                            item.item_serial = (typeof obj.row.serial === "undefined" || obj.row.serial == null) ? ko.observable("") : ko.observable(obj.row.serial);
                            item.item_name = (typeof obj.row.name === "undefined" || obj.row.name == null) ? ko.observable("") : ko.observable(obj.row.name.replace(/"/g, '&#034;').replace(/'/g, '&#039;'));
                            item.product_unit = (typeof obj.row.unit === "undefined" || obj.row.unit == null) ? ko.observable("") : ko.observable(obj.row.unit);
                            item.base_quantity = (typeof obj.row.base_quantity === "undefined" || obj.row.base_quantity == null) ? ko.observable("") : ko.observable(obj.row.base_quantity);
                            item.real_unit_price = (typeof obj.row.real_unit_price === "undefined" || obj.row.real_unit_price == null) ? ko.observable("") : ko.observable(obj.row.real_unit_price);
                            item.unit_price = (typeof obj.row.real_unit_price === "undefined" || obj.row.real_unit_price == null) ? ko.observable("") : ko.observable(obj.row.real_unit_price);
                            item.product_units = (typeof obj.units === "undefined" || obj.units == null) ? ko.observable("") : ko.observable(obj.units);
                            item.item_options = (typeof obj.options === "undefined" || obj.options == null) ? ko.observable("") : ko.observable(obj.options);
                            item.base_unit = (typeof obj.row.base_unit === "undefined" || obj.row.base_unit == null) ? ko.observable("") : ko.observable(obj.row.base_unit);
                            item.fup = (typeof obj.row.fup === "undefined" || obj.row.fup == null) ? ko.observable("") : ko.observable(obj.row.fup);
                            item.base_unit_price = (typeof obj.row.base_unit_price === "undefined" || obj.row.base_unit_price == null) ? ko.observable("") : ko.observable(obj.row.base_unit_price);

                            //setting item price by checking relatively item unit(each etc...)
                            if (obj.units && obj.row.fup != 1 && item.product_unit != obj.row.base_unit) {
                                $.each(obj.units, function () {
                                    if (this.id == item.product_unit) {
                                        item.base_quantity = formatDecimal(unitToBaseQty(obj.row.qty, this), 4);
                                        item.unit_price = formatDecimal(parseFloat(obj.row.base_unit_price) * unitToBaseQty(1, this), 4);
                                    }
                                });
                            }

                            //setting item price by checking relatively item options(medium, small etc...)
                            let sel_opt = '';
                            if (obj.options !== false) {
                                $.each(obj.options, function () {

                                    if (this.id == item.item_option()) {
                                        sel_opt = this.name;
                                        item.item_option_name(sel_opt);
                                        if (this.price != 0 && this.price != '' && this.price != null) {

                                            // item_price = unit_price + parseFloat(this.price) * parseFloat(base_quantity);
                                            item.item_option_price(parseFloat(this.price));
                                            item.item_price(parseFloat(item.unit_price()) + parseFloat(this.price));
                                            item.item_price_raw(parseFloat(item.unit_price()) + parseFloat(this.price));
                                            item.unit_price(item.item_price());
                                        }
                                    }
                                });
                            }

                            //setting item discount added from POS item wise
                            let ds = item.item_ds() ? item.item_ds() : '0';
                            if (ds.indexOf('%') !== -1) {
                                let pds = ds.split('%');
                                if (!isNaN(pds[0])) {
                                    item.item_discount_raw(parseFloat((item.unit_price() * parseFloat(pds[0])) / 100, 4));
                                    item.item_discount(formatMoney(parseFloat((item.unit_price() * parseFloat(pds[0])) / 100, 4)));
                                } else {
                                    item.item_discount_raw(parseFloat(ds));
                                    item.item_discount(formatMoney(parseFloat(ds)));
                                }
                            } else {
                                item.item_discount_raw(parseFloat(ds));
                                item.item_discount(formatMoney(parseFloat(ds)));
                            }

                            item.unit_price(formatDecimal(item.unit_price() - (item.item_discount_raw())));

                            //setting tax data
                            item.tax = (typeof obj.tax_rate === "undefined" || obj.tax_rate == null) ? ko.observable("") : ko.observable(obj.tax_rate);

                            let tax_val  = 0, tax_rate = 0;

                            //check tax enabling on POS and if it was calculate tax for the product
                            if (site.settings.tax1 == 1) {
                                if (item.tax() !== false && item.tax() != 0) {
                                    if (item.tax().type == 1) {
                                        if (item.item_tax_method() == '0') {
                                            tax_val = formatDecimal((item.unit_price() * parseFloat(item.tax().rate)) / (100 + parseFloat(item.tax().rate)), 4);
                                            tax_rate = formatDecimal(item.tax().rate) + '%';
                                        } else {
                                            tax_val = formatDecimal((item.unit_price() * parseFloat(item.tax().rate)) / 100, 4);
                                            tax_rate = formatDecimal(item.tax().rate) + '%';
                                        }
                                    } else if (item.tax().type == 2) {
                                        tax_val = parseFloat(item.tax().rate);
                                        tax_rate = item.tax().rate;
                                    }
                                    // console.log(item.pr_tax_val)
                                    // console.log(item.pr_tax_rate)
                                }
                            }
                            item.tax_val_raw = ko.observable(tax_val);
                            item.tax_val = ko.observable(formatMoney(tax_val));
                            item.tax_rate = ko.observable(tax_rate);
                            item.item_price_raw(item.item_tax_method() == 0 ? (item.unit_price() - tax_val) : (item.unit_price()));
                            item.item_price(formatMoney(item.item_tax_method() == 0 ? (item.unit_price() - tax_val) : (item.unit_price())));
                            item.unit_price(formatDecimal(item.unit_price() + parseFloat(item.item_discount_raw()), 4));

                            item.item_grid_sub_tot_raw = ko.observable(((parseFloat(item.item_price_raw()) + parseFloat(tax_val)) * parseFloat(item.item_qty())));
                            item.item_grid_sub_tot = ko.observable(formatMoney((parseFloat(item.item_price_raw()) + parseFloat(tax_val)) * parseFloat(item.item_qty())));
                            item.item_grid_price = ko.observable(formatMoney(item.item_price_raw()));

                            /** item qty validation adding */
                            item.validateClass1 = ko.observable('');
                            if (item.item_type() == 'standard' && obj.options !== false) {
                                console.log("if1")
                                $.each(obj.options, function () {
                                    if (this.id == item.item_option() && item.base_quantity() > this.quantity) {
                                        item.validateClass1('bg-warning');

                                        if (site.settings.overselling != 1) {
                                            $('#add_joborder, #edit_joborder').attr('disabled', true);
                                        }
                                    }
                                });
                            } else if (item.item_type() == 'standard' && item.base_quantity() > item.item_aqty()) {
                                item.validateClass1('bg-warning');
                                if (site.settings.overselling != 1) {
                                    console.log("if2.1")
                                    $('#add_joborder, #edit_joborder').attr('disabled', true);
                                }
                            } else if (item.item_type() == 'combo') {
                                console.log("if3")
                                if (item.combo_items === false) {
                                    console.log("if3.1")
                                    item.validateClass1('bg-warning');
                                    if (site.settings.overselling != 1) {
                                        $('#add_joborder, #edit_joborder').attr('disabled', true);
                                    }
                                } else {
                                    console.log('else');
                                    $.each(item.combo_items(), function () {
                                        if (parseFloat(this.quantity) < parseFloat(this.qty) * item.base_quantity() && this.type == 'standard') {
                                            item.validateClass1('bg-warning');
                                            if (site.settings.overselling != 1) {
                                                $('#add_joborder, #edit_joborder').attr('disabled', true);
                                            }
                                        }
                                    });
                                }
                            }
                        }
                        /*end of else clause on joborder item array filling*/
                    }
                    /** End of inventory items adding **/

                    self.taskItemValidation = () => {
                        var lt_arr_length = self.jbItemList().length;

                        if(lt_arr_length < 1) {
                            sweetAlertMsg('Required!',"Please add items first.",'warning');
                            return false;
                        };

                        for (var i =0; i < lt_arr_length; i++){

                            if(parseFloat(self.jbItemList()[i].base_quantity()) > parseFloat(self.jbItemList()[i].item_aqty())){

                                let item_name = self.jbItemList()[i].item_code()+""+ self.jbItemList()[i].item_name()+"("+ self.jbItemList()[i].item_option_name()+")";
                                sweetAlertMsg('Error!',item_name+" Don't have sufficient System Qty.",'warning');
                                return false;
                            }
                        }
                        let same_it_qty = 0;
                        $.each(self.jbItemList(), function (i, item) {

                            if(i > 0 && self.jbItemList()[i-1].item_code() == item.item_code()) {
                                same_it_qty += parseFloat(self.jbItemList()[i-1].base_quantity());
                                same_it_qty += parseFloat(item.base_quantity());

                                if(same_it_qty > item.item_aqty()) return false;
                            }
                        })

                        return true;
                    }
                    /** task wise item submit */
                    self.submitTaskItems = () =>{

                        if(!self.taskItemValidation()) return;
                        $.ajax({
                            url: "{{ route('joborder.task.item.create.submit') }}",
                            dataType: 'json',
                            type: 'post',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                jo_id: self.itemID(),
                                jo_task_id: self.jo_task_id(),
                                "grid_item_rows": ko.toJSON(self.jbItemList),
                                "delete_grid_item_rows": ko.toJSON(self.deleteJbItemList),
                            },
                            success: function( data, textStatus, jQxhr ){

                                if(data.status){

                                    self.jbItemList.removeAll();
                                    self.deleteJbItemList.removeAll();

                                    $('#lbr_inven_item_mod').modal('hide')
                                    // $('#example2').DataTable().ajax.reload();
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
                    /** Task and service package message submit */
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

                }
            }
        </script>
    @endpush
@endsection

