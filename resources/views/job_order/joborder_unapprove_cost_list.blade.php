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

        <!--Job order review model -->
        <div class="modal fade" id="task_review_mod">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Job Order review</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Labor task table list -->
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
                                    {{--                                        <th  class="text-center" style="">Action</th>--}}
                                </tr>
                                </thead>
                                <tbody data-bind="foreach: laborTaskList">
                                <tr style="background-color: aliceblue">
                                    <td class="text-center" style="padding: 0" data-bind="text: col1">

                                    </td>
                                    <td class="text-center" style="padding: 0">
                                        <input type="number" class="form-control text-center" data-bind="value: col3, event: { blur: $parent.calculateTblLineTotal.bind($data, col3) }">
                                    </td>
                                    <td style="padding: 0">
                                        <input type="number" class="form-control text-center" data-bind="value: col4">
                                    </td>
                                    <td style="padding: 0">
                                        <input type="number" class="form-control text-right" data-bind="value: col5, event: { blur: $parent.calculateTblLineTotal.bind($data, col5) }">
                                    </td>
                                    <td style="padding: 0">
                                        <input type="number" class="form-control text-right" data-bind="value: col6">
                                    </td>
                                    <td data-bind="text: col7" class="text-right ">DisAmount</td>
                                    {{--                                        <td class="text-center" ><i class="fa fa-trash-alt" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Remove this row" data-bind="click: $parent.removeGridRow.bind($data, $index())"></i></td>--}}
                                </tr>
                                <tr>
                                    <td colspan="9" style="padding: 0" data-toggle="tooltip" data-placement="bottom" title="Edit task description">
                                        <textarea rows="1" class="form-control" data-bind="value: col2"></textarea>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Start: Service package Table -->
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
                                        </tr>
                                        </thead>
                                        <tbody data-bind="foreach: servicePkgList">
                                        <tr>
                                            <td data-bind="text: col1" class="text-center"></td>
                                            <td data-bind="text: col2" class="text-center"></td>
                                            <td data-bind="text: col3" class="text-center"></td>
                                            <td data-bind="text: col4" class="text-right"></td>
                                            <td data-bind="text: col5" class="text-right"></td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- End: Service package Table -->

                        <!-- inventory items table list -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="" class="text-center">Product</th>
                                            <th style="" class="text-center">Net Unit Price</th>
                                            <th class="text-center" style="">Qty</th>
                                            <th class="text-center"
                                                data-bind="style: { display: jobOrderList.ivm.is_product_discount() == 1 ? '' : 'none' }">
                                                Discount
                                            </th>
                                            <th class="text-center"
                                                data-bind="style: { display: jobOrderList.ivm.is_tax1() == 1 ? '' : 'none' }">
                                                Product Tax
                                            </th>
                                            <th class="text-center" style="">Subtotal(<span
                                                    data-bind="html: default_currenty"></span>)
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody data-bind="foreach: jbItemList">
                                        <tr data-bind="attr: { data_item_id: item_id, id: 'row_'+item_id(), class: validateClass1(), title: item_row_tooltip() }" data-toggle="tooltip" data-placement="top">
                                            <td class="text-center">
                                                <span data-bind="html: item_code"></span> -
                                                <span data-bind="html: item_name"></span>
                                                <span data-bind="style: { display: item_option_name() == 0 ? 'none' : '' }">
                                                    (<span data-bind="html: item_option_name"></span>)
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <span data-bind="style: { display: item_status() == 'serv' ? 'none' : '' }, html: item_grid_price"></span>
                                                <span data-bind="style: { display: item_status() == 'serv' ? '' : 'none' },html: 0.00"></span>
                                            </td>
                                            <td class="text-center">
                                                <span data-bind="html: item_qty,style: { display: item_status() == 'serv' ? '' : 'none' }"></span>
                                                <input type="text" class="form-control rquantity text-center" data-bind="value: item_qty, style: { display: item_status() == 'serv' ? 'none' : '' }, event: { blur: $parent.calculateRQuantity.bind($data, $index()), click: $parent.setOldItemQty.bind($data, $index()) }">
                                            </td>
                                            <td class="text-right" data-bind="style: { display: jobOrderList.ivm.is_product_discount() == 1 ? '' : 'none' }">
                                                <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? 'none' : '' }, text: item_discount"></span>
                                                <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? '' : 'none' }, text: 0.00"></span>
                                            </td>
                                            <td class="text-right" data-bind="style: { display: jobOrderList.ivm.is_tax1() == 1 ? '' : 'none' }">
                                                <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? 'none' : '' }, text: '('+tax_rate()+') '+tax_val()"></span>
                                                <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? '' : 'none' }, text: 0.00"></span>
                                            </td>
                                            <td class="text-right" >
                                                <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? 'none' : '' }, text: item_grid_sub_tot"></span>
                                                <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? '' : 'none' }, text: 0.00"></span>
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
                        <button type="button" class="btn btn-success" onclick="jobOrderList.ivm.submitJobTaskData()">Submit</button>
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

                    self.default_currenty = ko.observable(site.settings.default_currency);
                    self.is_product_discount = ko.observable(site.settings.product_discount);
                    self.is_tax1 = ko.observable(site.settings.tax1);
                    self.is_product_serial = ko.observable(site.settings.product_serial);

                    self.itemID = ko.observable("");
                    self.wo_no = ko.observable("");
                    self.jo_no = ko.observable("");
                    self.jo_date = ko.observable("");

                    //wo order
                    self.woRemark = ko.observable("");

                    self.laborTaskList = ko.observableArray();
                    self.jbItemList = ko.observableArray();

                    self.labor_task = ko.observable("");
                    self.task_hours = ko.observable("");
                    self.task_hour_rate = ko.observable("");
                    self.task_rate_total = ko.observable("");
                    self.task_description = ko.observable("");
                    self.task_act_hours = ko.observable("");
                    self.labor_cost = ko.observable("");

                    self.deleteJbItemList = ko.observableArray();

                    self.tot_serv_pkg_amnt = ko.observable("");
                    self.servicePkgList = ko.observableArray();
                    self.deleteServicePkgList = ko.observableArray();

                    self.old_item_qty = ko.observable("");

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
                    }

                    self.reviewJoborder = (empID) =>{
                        self.itemID(empID);
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
                                    self.servicePkgList.removeAll();
                                    $.each(data.data.serv_pkg_data, (i,item) => {
                                        self.servicePkgList.push(new gridServPkgListRows(item));
                                    })

                                    $('[data-toggle="tooltip"]').tooltip()
                                    self.laborTaskList.removeAll();
                                    $.each(data.data.task_data, (i,item) => {
                                        self.laborTaskList.push(new gridLaborTaskListRows(item));
                                    })

                                    $.each(data.data.item_data, (i,item) => {
                                        self.jbItemList.push(new gridItemListRows(item, "up"));
                                    })

                                    $('#task_review_mod').modal('show')

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
                        self.calculatePartsTblTot();
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
                        }else{
                            item.col1 = (typeof obj.serv_pkg.service_name === "undefined" || obj.serv_pkg.service_name == null) ? ko.observable("") : ko.observable(obj.serv_pkg.service_name);
                            item.col2 = (typeof obj.serv_pkg.description === "undefined" || obj.serv_pkg.description == null) ? ko.observable("") : ko.observable(obj.serv_pkg.description);
                            item.col3 = (typeof obj.serv_pkg.type_name === "undefined" || obj.serv_pkg.type_name == null) ? ko.observable("") : ko.observable(obj.serv_pkg.type_name);
                            item.col4 = (typeof obj.serv_pkg.price === "undefined" || obj.serv_pkg.price == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.serv_pkg.price, 2));
                            item.col5 = (typeof obj.serv_pkg.price === "undefined" || obj.serv_pkg.price == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.serv_pkg.price, 2));
                            item.col6 = ko.observable(obj.serv_pkg.service_pkg_id);
                            item.col7 = ko.observable(obj.serv_pkg.id);
                        }
                    }
                    /*end service pkg*/

                    let gridLaborTaskListRows = function (obj) {
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
                        }
                    }

                    self.calculateLineTotal = () => {
                        if(isNaN(self.task_hours())) self.task_hours(0);
                        var amount = (self.task_hours() * parseFloat(self.task_hour_rate().replace(/[^0-9-.]/g, '')));
                        self.task_rate_total(accounting.formatNumber(amount,2));
                    }

                    self.calculateTblLineTotal = (indexNo, rowData) => {
                        if(isNaN(rowData.col3())) rowData.col3(0);
                        var amount = (rowData.col3() * parseFloat(rowData.col5().replace(/[^0-9-.]/g, '')));
                        rowData.col7(accounting.formatNumber(amount,2));
                    }

                    /** Start of inventory items **/
                    self.removeItemGridRow = (indexNo, rowData) => {
                        self.jbItemList.remove(rowData);
                        if(rowData.db_item_id() && rowData.db_item_id() !== ""){
                            self.deleteJbItemList.push(rowData.db_item_id());
                        }
                        self.calculatePartsTblTot();
                    }

                    let gridItemListRows = function (obj, state) {
                        let item = this;

                        /*console.log(obj)
                        console.log(state)*/
                        if (state == "up") {

                            item.serv_pkg_id = ko.observable(obj.row.service_pkg_id);
                            let itemType = '';
                            if(item.serv_pkg_id() != "" && item.serv_pkg_id() != 0) itemType = 'serv';

                            item.item_status = ko.observable(itemType);
                            item.validateClass1 = ko.observable('');
                            item.item_row_tooltip = ko.observable('');
                            if(item.item_status() == "serv") { item.validateClass1('bg-gray-light'); item.serv_pkg_id(item.serv_pkg_id()); item.item_row_tooltip('Item of '+obj.row.service_name)}

                            item.item_id = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id); // auto generated unique hash
                            item.db_item_id = (typeof obj.row.id === "undefined" || obj.row.id == null) ? ko.observable("") : ko.observable(obj.row.id);// db auto incremented id
                            item.order = ko.observable(obj.order ? obj.order : new Date().getTime());
                            item.product_id = (typeof obj.row.product_id === "undefined" || obj.row.product_id == null) ? ko.observable("") : ko.observable(obj.row.product_id);
                            item.item_type = (typeof obj.row.product_type === "undefined" || obj.row.product_type == null) ? ko.observable("") : ko.observable(obj.row.product_type);
                            item.combo_items = ko.observable(false);
                            item.item_price_raw = (typeof obj.row.unit_price === "undefined" || obj.row.unit_price == null) ? ko.observable("") : ko.observable(obj.row.unit_price);
                            item.item_price = (typeof obj.row.unit_price === "undefined" || obj.row.unit_price == null) ? ko.observable("") : ko.observable(obj.row.unit_price);
                            item.item_qty = (typeof obj.row.quantity === "undefined" || obj.row.quantity == null) ? ko.observable("") : ko.observable(obj.row.quantity);
                            item.item_aqty = ko.observable(0);
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
                            let product_tax =0;
                            product_tax += (tax_val * item.item_qty());
                            item.tax_val_raw = ko.observable(product_tax);
                            item.tax_val = ko.observable(formatMoney(product_tax));
                            item.tax_rate = ko.observable(tax_rate);
                            item.item_price_raw(item.item_tax_method() == 0 ? (item.unit_price() - tax_val) : (item.unit_price()));
                            item.item_price(formatMoney(item.item_tax_method() == 0 ? (item.unit_price() - tax_val) : (item.unit_price())));
                            item.unit_price(formatDecimal(item.unit_price() + parseFloat(item.item_discount_raw()), 4));

                            item.item_grid_sub_tot_raw = ko.observable(((parseFloat(item.item_price_raw()) + parseFloat(tax_val)) * parseFloat(item.item_qty())));
                            item.item_grid_sub_tot = ko.observable(formatMoney((parseFloat(item.item_price_raw()) + parseFloat(tax_val)) * parseFloat(item.item_qty())));
                            item.item_grid_price = ko.observable(formatMoney(item.item_price_raw()));

                        } else{

                            item.item_status = ko.observable(state);
                            item.validateClass1 = ko.observable('');
                            item.serv_pkg_id = ko.observable('');
                            item.item_row_tooltip = ko.observable('');
                            if(state == "serv") { item.validateClass1('bg-gray-light'); item.serv_pkg_id(self.service_pkg_id()); item.item_row_tooltip('Item of '+self.service_pkg_name())}

                            item.item_id = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
                            item.db_item_id = (typeof obj.item_id === "undefined" || obj.item_id == null) ? ko.observable("") : ko.observable(obj.item_id);
                            item.order = (typeof obj.order === "undefined" || obj.order == null) ? ko.observable("") : ko.observable(obj.order ? obj.order : new Date().getTime());
                            item.product_id = (typeof obj.row.id === "undefined" || obj.row.id == null) ? ko.observable("") : ko.observable(obj.row.id);
                            item.item_type = (typeof obj.row.type === "undefined" || obj.row.type == null) ? ko.observable("") : ko.observable(obj.row.type);
                            item.combo_items = (typeof obj.combo_items === "undefined" || obj.combo_items == null) ? ko.observable("") : ko.observable(obj.combo_items);
                            item.item_price = (typeof obj.row.price === "undefined" || obj.row.price == null) ? ko.observable("") : ko.observable(obj.row.price);
                            item.item_qty = (typeof obj.row.qty === "undefined" || obj.row.qty == null) ? ko.observable("") : ko.observable(obj.row.qty);
                            item.item_aqty = (typeof obj.row.quantity === "undefined" || obj.row.quantity == null) ? ko.observable("") : ko.observable(obj.row.quantity);
                            item.item_tax_method = (typeof obj.row.tax_method === "undefined" || obj.row.tax_method == null) ? ko.observable("") : ko.observable(obj.row.tax_method);
                            item.item_ds = (typeof obj.row.discount === "undefined" || obj.row.discount == null) ? ko.observable("") : ko.observable(obj.row.discount);
                            item.item_discount = ko.observable(0);
                            item.item_option = (typeof obj.row.option === "undefined" || obj.row.option == null) ? ko.observable("") : ko.observable(obj.row.option);
                            item.item_option_name = ko.observable('');
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
                                    item.item_discount(parseFloat((item.unit_price() * parseFloat(pds[0])) / 100, 4));
                                } else {
                                    item.item_discount(parseFloat(ds));
                                }
                            } else {
                                item.item_discount(parseFloat(ds));
                            }

                            item.unit_price(formatDecimal(item.unit_price() - (item.item_discount())));

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
                            item.tax_val = ko.observable(tax_val);
                            item.tax_rate = ko.observable(tax_rate);
                            item.item_price(item.item_tax_method() == 0 ? (item.unit_price() - tax_val) : (item.unit_price()));
                            item.unit_price(formatDecimal(item.unit_price() + parseFloat(item.item_discount()), 4));

                            item.item_grid_sub_tot = ko.observable(formatDecimal((parseFloat(item.item_price()) + parseFloat(tax_val)) * parseFloat(item.item_qty()), 2));
                            item.item_grid_price = ko.observable(formatDecimal(item.item_price(), 2));
                            item.item_price(formatDecimal(item.item_price, 2));
                        }
                        /*end of else clause on joborder item array filling*/
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

                        total += ((parseFloat(item_price) + parseFloat(tax_val)) * parseFloat(item_qty));

                        /*console.log("product_tax: "+product_tax);
                        console.log("item_price: "+item_price);
                        console.log("unit_price: "+unit_price);
                        console.log("product_discount: "+product_discount);
                        console.log("tax_val: "+tax_val);
                        console.log("tax_rate: "+tax_rate);
                        console.log("total"+total);

                        console.log(rowData)*/

                        rowData.item_grid_sub_tot_raw(parseFloat(total));
                        rowData.item_grid_sub_tot(formatMoney(parseFloat(total)));
                        rowData.item_grid_price(formatMoney((isNaN(unit_price) ? 0 : unit_price) - item_discount));
                        rowData.item_qty(item_qty);
                        rowData.item_discount_raw(item_discount);
                        rowData.item_discount(formatMoney(item_discount));
                        rowData.tax_val_raw(product_tax);
                        rowData.tax_val(formatMoney(product_tax));
                        rowData.tax_rate(tax_rate)

                        self.calculatePartsTblTot();
                    }

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
                    /** End of inventory items adding **/

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
                                    data: { "_token": "{{ csrf_token() }}", emp_id: fbID, },
                                    beforeSend: function (jqXHR, settings) { },
                                    success: function( data, textStatus, jQxhr ){

                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!',data.message,'success');
                                            setTimeout(function () {
                                                window.top.location = "{{ url('/joborder-assign-window') }}/"+fbID;
                                            },200)
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

                    self.submitJobTaskData = () =>{
                        $.ajax({
                            url: "{{ route('joborder.review.submit') }}",
                            dataType: 'json',
                            type: 'post',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                emp_id: self.itemID(),
                                "grid_task_rows": ko.toJSON(self.laborTaskList),
                                "grid_item_rows": ko.toJSON(self.jbItemList),
                                "delete_grid_item_rows": ko.toJSON(self.deleteJbItemList),
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
                        self.laborTaskList.removeAll();
                        self.jbItemList.removeAll();
                    }
                }
            }
        </script>
    @endpush
@endsection

