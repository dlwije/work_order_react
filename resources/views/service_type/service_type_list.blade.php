@extends('template.master')
@section('title','Service Packages')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
    <!-- autocomplete css-->
<link rel="stylesheet" href="{{asset('assets/plugins/jquery-ui/jquery-ui.min.css')}}">
@endpush

@section('content')
    <div class="card" id="item_list_view">
        <div class="card-header">
            <h3 class="card-title">Service Package</h3>
            <div class="card-tools">
                @can('service_pkg-add')
                    <a class="btn btn-sm btn-success no-shadow" href="javascript:0" onclick="serviceTypeList.ivm.addNew()">  <i class="glyphicon glyphicon-plus myicon-right"></i> Add New</a>
                @endcan
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="ID_servicetype_tbl" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <th>Service Name</th>
                    <th>Price</th>
                    <th>Vehicle Type</th>
                    <th>Created On</th>
{{--                    <th>Active Status</th>--}}
                    <th></th>
                </tr>
                </thead>
                <tbody id="ID_servicetype_tbody">
                </tbody>
            </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Service type Edit model -->
        <div class="modal fade" id="serv_type_mod">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Service Package</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body ui-front">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Service Name</label><span class="text-danger">*</span>
                                    <input type="text" id="ID_serviceName" class="form-control" data-bind="value: serviceName">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Service Description</label>
                                    <textarea class="form-control" rows="1" data-bind="value: serviceDescript"></textarea>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Price</label><span class="text-danger">*</span>
                                    <input type="text" id="ID_price" class="form-control text-right" data-bind="value: price" disabled>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Vehicle Type</label><span class="text-danger">*</span>
                                    <select class="form-control " id="ID_vehicleType" data-bind="value:VehicleTypeNo">

                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Start: of item table filling area-->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-group mb-0">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <span class="fas fa-barcode fa-2x" id="basic-addon1"></span>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg" id="add_item" placeholder="{{__('placeholders.add_product_to_order')}}" aria-describedby="basic-addon1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End: of item table filling area-->
                        <div class="row">
                            <div class="col-sm-12" style="margin-top: 12px;">
                                <div class=" table-responsive">
                                    <table class="table table-condensed table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th style="" class="text-center">Product</th>
                                            <th style="" class="text-center">Net Unit Price</th>
                                            <th class="text-center" style="">Qty</th>
                                            <th class="text-center" data-bind="style: { display: serviceTypeList.ivm.is_product_discount() == 1 ? '' : 'none' }">
                                                Discount
                                            </th>
                                            <th class="text-center" data-bind="style: { display: serviceTypeList.ivm.is_tax1() == 1 ? '' : 'none' }">
                                                Product Tax
                                            </th>
                                            <th class="text-center" style="">
                                                Subtotal(<span data-bind="html: default_currenty"></span>)
                                            </th>
                                            <th class="text-center" style=""></th>
                                        </tr>
                                        </thead>
                                        <tbody data-bind="foreach: grnItemList">
                                        <tr data-bind="attr: { data_item_id: item_id, id: 'row_'+item_id() }">
                                            <td class="text-center">
                                                <span data-bind="html: item_code"></span> -
                                                <span data-bind="html: item_name"></span>
                                                <span data-bind="style: { display: item_option_name() == 0 ? 'none' : '' }">
                                                    (<span data-bind="html: item_option_name"></span>)
                                                </span>
                                                <span style="cursor: pointer;" class="fa-pull-right fas fa-edit" data-bind="event: { click: $parent.editItemRow.bind($data, $index()) }"></span>
                                            </td>
                                            <td class="text-right">
                                                <input type="text" class="form-control text-right" data-bind="value: item_grid_price, event: { blur: $parent.setItemGridPrice.bind($data, $index()) }">
                                            </td>
                                            <td class="text-center">
                                                <input type="text" class="form-control rquantity text-center" data-bind="value: item_qty, event: { blur: $parent.calculateRQuantity.bind($data, $index()), click: $parent.setOldItemQty.bind($data, $index()) }">
                                            </td>
                                            <td data-bind="text: item_discount, style: { display: serviceTypeList.ivm.is_product_discount() == 1 ? '' : 'none' }" class="text-right"></td>
                                            <td data-bind="text: '('+tax_rate()+') '+tax_val(), style: { display: serviceTypeList.ivm.is_tax1() == 1 ? '' : 'none' }" class="text-right"></td>
                                            <td data-bind="text: item_grid_sub_tot" class="text-right "></td>
                                            <td class="text-center">
                                                <i class="fa fa-trash-alt" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Remove this row" data-bind="click: $parent.removeItemGridRow.bind($data, $index())"></i>
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
                        <button type="button" class="btn btn-success" onclick="serviceTypeList.ivm.insertItemData()">Submit</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.End: service type modal -->

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
                                 data-bind="style: { display: serviceTypeList.ivm.is_tax1() == 1 ? '' : 'none' }">
                                <label for="ID_mod_edit_tax" class="control-label">Product Tax</label>
                                <select class="form-control " id="ID_mod_edit_tax" onchange="serviceTypeList.ivm.setEditTax()">
                                    <option></option>

                                </select>
                            </div>
                            <div class="form-group col-md-12"
                                 data-bind="style: { display: serviceTypeList.ivm.is_product_serial() == 1 ? '' : 'none' }">
                                <label for="ID_mod_pro_serial" class="control-label">Serial No</label>
                                <input type="text" class="form-control" id="ID_mod_pro_serial"
                                       data-bind="value: mod_pro_serial">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="ID_mod_pquantity" class="control-label">Quantity</label>
                                <input type="text" class="form-control text-center" id="ID_mod_pquantity"
                                       data-bind="value: mod_pquantity" onblur="serviceTypeList.ivm.setModelAmountChange()">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="ID_mod_punit" class="control-label">Product Unit</label>
                                <div id="punits-div"></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="ID_mod_poption" class="control-label">Product Option</label>
                                <div id="poptions-div"></div>
                            </div>
                            <div class="form-group col-md-12"
                                 data-bind="style: { display: serviceTypeList.ivm.is_product_discount() == 1 ? '' : 'none' }">
                                <label for="ID_mod_pdiscount" class="control-label">Product Discount <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="You can use discount as a percentage wise or as a fixed amount."></i></label>
                                <input type="text" class="form-control text-right" id="ID_mod_pdiscount"
                                       data-bind="value: mod_pdiscount" onblur="serviceTypeList.ivm.setModelAmountChange();">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="ID_mod_pprice" class="control-label">Unit Price</label>
                                <input type="text" class="form-control text-right" id="ID_mod_pprice"
                                       data-bind="value: mod_pprice" readonly>
                            </div>

                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th style="width:25%;">Net Unit Price</th>
                                    <th style="width:25%;text-align: right;"><span id="net_price"
                                                                                   data-bind="html: net_price"></span>
                                    </th>
                                    <th style="width:25%;">Product Tax</th>
                                    <th style="width:25%;text-align: right;"><span id="pro_tax"
                                                                                   data-bind="html: pro_tax"></span>
                                    </th>
                                </tr>
                            </table>
                            <div class=" col-md-12"
                                 data-bind="style: { display: serviceTypeList.ivm.is_product_discount() == 1 ? '' : 'none' }">
                                <div class="form-group">
                                    <label for="ID_mod_psubt" class="control-label">Subtotal</label>
                                    <input type="text" class="form-control text-right" id="ID_mod_psubt"
                                           data-bind="value: mod_psubt" disabled>
                                </div>
                                {{--<div class="form-group">
                                    <label for="ID_mod_pdiscount" class="control-label">Adjust Subtotal</label>
                                    <input type="text" class="form-control" id="ID_mod_padiscount"
                                           data-bind="value: mod_padiscount">
                                </div>--}}
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
        <!-- /.End: Item Edit modal -->
    </div>

    @push('scripts')
        <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
        <!-- autocomplete js -->
        <script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
        <script>

            $(document).ready(function () {
                //initiate page javascript
                serviceTypeList.init();
                //start top progress bar
                // Pace.restart();
                $('#edit_item_row_mod').on('hide.bs.modal', function () {
                    serviceTypeList.ivm.clearTblRowEditFields();
                })
                $('#serv_type_mod').on('hide.bs.modal', function () {
                    serviceTypeList.ivm.clearForm();
                })
            })
            serviceTypeList = {
                ivm: null,
                init: function () {
                    serviceTypeList.ivm = new serviceTypeList.serviceTypeListViewModel();

                    //binding the knockout to the dom
                    ko.applyBindings(serviceTypeList.ivm, $('#item_list_view')[0]);

                    //initialize the datatable when page loads
                    serviceTypeList.ivm.designInitialize();
                },
                serviceTypeListViewModel: function () {
                    let self = this;

                    self.default_currenty = ko.observable(site.settings.default_currency);
                    self.is_product_discount = ko.observable(site.settings.product_discount);
                    self.is_tax1 = ko.observable(site.settings.tax1);
                    self.is_product_serial = ko.observable(site.settings.product_serial);

                    //declaring the knockout variables
                    self.isNew = ko.observable(true);
                    self.service_pkg_id = ko.observable("");
                    self.woCustomerId = ko.observable("");

                    self.serviceName = ko.observable("");
                    self.serviceDescript = ko.observable("");
                    self.price = ko.observable("");
                    self.VehicleTypeNo = ko.observable("");
                    self.isActive = ko.observable(true);

                    self.itemType= ko.observable("");
                    self.ItemNameID = ko.observable("");
                    self.itemName = ko.observable("");
                    self.itemDescription = ko.observable("");
                    self.itemQty = ko.observable("");
                    self.itemRate = ko.observable("");
                    self.discount_amount = ko.observable("");
                    self.itemAmount = ko.observable("");
                    self.itemOnhand = ko.observable("");

                    self.grnItemList = ko.observableArray();
                    self.deleteItemList = ko.observableArray();

                    //Item table variables
                    self.old_item_qty = ko.observable("");

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

                    //getting predefined msg strings from common file
                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.designInitialize = () => {
                        self.listTable();
                        self.woCustomerId(1);// assign walk-in customer table id
                    }

                    self.clearAutoCompleteInput = () => {
                        $('#add_item').focus();
                        $('#add_item').removeClass('ui-autocomplete-loading');
                        $('#add_item').val('');
                    }

                    //datatable function
                    self.listTable = function () {
                        select2Dropdown("ID_vehicleType","{{route('vehicletype.drop.list')}}");

                        var table = $('#ID_servicetype_tbl').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('service.package.table.list') }}",
                            columns: [
                                {data: 'service_name', name: 'service_name'},
                                {data: 'price', name: 'price', sClass: 'text-right'},
                                {data: 'type_name', name: 'type_name'},
                                {data: 'created_at', name: 'created_at'},
                                // {data: 'status', name: 'status'},
                                // {data: 'dob', name: 'dob'},
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
                        try {
                            $("#add_item").autocomplete({
                                source: function (request, response) {
                                    if (!self.woCustomerId()) {
                                        $('#add_item').val('').removeClass('ui-autocomplete-loading');
                                        sweetAlertMsg('Info', '{{ __('messages.select_above') }}', 'warning')
                                        $('#add_item').focus();
                                        return false;
                                    }
                                    $.ajax({
                                        type: 'get',
                                        url: '{{route('all.product.suggestion.list')}}',
                                        dataType: "json",
                                        data: {
                                            term: request.term,
                                            warehouse_id: site.settings.default_warehouse,
                                            customer_id: self.woCustomerId()
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
                        }catch (e) {
                            console.log(e);
                        }
                        $('#serv_type_mod').modal('show');
                    }

                    //service package edit
                    self.getServPkgDetails = (id) => {
                        try {
                            self.service_pkg_id(id);
                            if (typeof self.service_pkg_id() === "undefined") return;

                            StartLoading();
                            $.ajax({
                                url: '{{route('service.package.selected.data')}}',
                                type: 'GET',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                dataType: 'json',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "ItemId": self.service_pkg_id()
                                },
                                beforeSend: (jqXHR, settings) => { StartLoading(); },
                                success: (result) => {
                                    StopLoading();
                                    if (result.status) {
                                        self.isNew(false);
                                        self.serviceName(result.data.head_data.service_name);
                                        self.serviceDescript(result.data.head_data.description);
                                        self.price(formatMoney(result.data.head_data.price));
                                        self.VehicleTypeNo(result.data.head_data.vehicle_type_id);
                                        // console.log(result.data.head_data.vehicle_type_id);
                                        // console.log(self.VehicleTypeNo());
                                        select2Dropdown("ID_vehicleType","{{route('vehicletype.drop.list')}}", "{{route('vehicletype.byid.drop.list')}}", result.data.head_data.vehicle_type_id);

                                        $.each(result.data.item_data, function (i, item) {
                                            self.grnItemList.push(new gridItemListRows(item,'up'));
                                        })
                                        // $('#serv_type_mod').modal('show');
                                        self.addNew();
                                    }
                                },
                                error: (jqXHR, textStatus, errorThrown) => {
                                    StopLoading();

                                    if (jqXHR.status == 403) {
                                        sweetAlertMsg('Permission Error', 'User does not have the right permissions.', 'warning');
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
                            console.log(e)
                        }
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
                            self.grnItemList.push(new gridItemListRows(item));
                        })

                        self.calculatePartsTblTot();
                    }

                    self.setItemGridPrice = (indexNo, rowData) => {
                        let item_grid_price = rowData.item_grid_price() != "" ? rowData.item_grid_price().replace(/[^0-9-.]/g, '') : 0;
                        self.grnItemList()[indexNo].item_price_raw(item_grid_price);
                        self.grnItemList()[indexNo].item_price(item_grid_price);
                        self.grnItemList()[indexNo].real_unit_price(item_grid_price);
                        self.calculateInvenTblLineTotal(indexNo, rowData);
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

                        self.grnItemList()[indexNo].base_quantity(new_qty);
                        rowData.base_quantity(new_qty)

                        if (self.grnItemList()[indexNo].product_unit() != self.grnItemList()[indexNo].base_unit()) {
                            if (typeof self.grnItemList()[indexNo].units === "function") {
                                $.each(self.grnItemList()[indexNo].units(), function () {
                                    if (this.id == self.grnItemList()[indexNo].product_unit()) {
                                        self.grnItemList()[indexNo].base_quantity(unitToBaseQty(new_qty, this));
                                        rowData.base_quantity = unitToBaseQty(new_qty, this);
                                    }
                                });
                            } else {
                                console.error("units is not a function or undefined:", self.grnItemList()[indexNo]);
                            }
                        }
                        self.grnItemList()[indexNo].item_qty(new_qty);
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
                        console.log("ds"+ds);
                        console.log("item_discount"+item_discount);

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

                        console.log("product_tax: "+product_tax);
                        console.log("item_price: "+item_price);
                        console.log("unit_price: "+unit_price);
                        console.log("product_discount: "+product_discount);
                        console.log("tax_val: "+tax_val);
                        console.log("tax_rate: "+tax_rate);
                        console.log("total"+total);

                        console.log(rowData)

                        rowData.item_grid_sub_tot_raw(parseFloat(total));
                        rowData.item_grid_sub_tot(formatMoney(parseFloat(total)));
                        rowData.item_grid_price(formatMoney((isNaN(unit_price) ? 0 : unit_price) - item_discount));
                        rowData.item_qty(item_qty);
                        // self.grnItemList()[self.table_index_id()].item_ds(self.mod_pdiscount());
                        rowData.item_discount_raw(item_discount);
                        rowData.item_discount(formatMoney(item_discount));
                        rowData.tax_val_raw(product_tax);
                        rowData.tax_val(formatMoney(product_tax));
                        rowData.tax_rate(tax_rate)

                        self.calculatePartsTblTot();
                    }

                    self.editItemRow = (indexNo, rowData) => {

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
                            uopt = $('<select id="punit" name="punit" class="form-control select" onchange="serviceTypeList.ivm.setPUnit()" />');
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
                            opt = $('<select id="poption" name="poption" class="form-control select" onchange="serviceTypeList.ivm.setPOption()" />');

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
                        self.grnItemList()[self.table_index_id()].product_unit = self.mod_punit();
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
                                        sweetAlertMsg('Permission Error', 'User does not have the right permissions.', 'warning');
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

                                        self.grnItemList()[self.table_index_id()].item_options(result.data[0]);
                                        self.grnItemList()[self.table_index_id()].item_option_name(result.data[0].name);
                                        self.grnItemList()[self.table_index_id()].item_option_price(result.data[0].price);
                                        self.grnItemList()[self.table_index_id()].item_option(result.data[0].id);
                                        self.mod_poption(result.data[0].id);
                                        self.mod_poption_price(result.data[0].price);
                                        self.setModelAmountChange();
                                    }
                                },
                                error: (jqXHR, textStatus, errorThrown) => {
                                    StopLoading();

                                    if (jqXHR.status == 403) {
                                        sweetAlertMsg('Permission Error', 'User does not have the right permissions.', 'warning');
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

                        let base_quantity = self.mod_base_quantity();
                        let base_unit_price = self.mod_base_unit_price();
                        let unit_price = isNaN(self.mod_real_unit_price()) ? 0 : self.mod_real_unit_price();
                        let item_price = self.mod_item_price();
                        let item_qty = self.mod_pquantity();
                        let product_discount = 0, product_tax = 0, total = 0;

                        //checking product has multiple options, if it was itemprice will change to option wise price
                        if (self.mod_poption() !== 0) {
                            item_price = parseFloat(unit_price) + parseFloat(self.mod_poption_price());
                            unit_price = item_price;
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

                        console.log("product_tax: "+product_tax);
                        console.log("item_price: "+item_price);
                        console.log("unit_price: "+unit_price);
                        console.log("product_discount: "+product_discount);
                        console.log("tax_val: "+tax_val);
                        console.log("total"+total);
                        self.mod_pprice_raw(isNaN(unit_price) ? 0 : unit_price);
                        self.mod_pprice(formatMoney(isNaN(unit_price) ? 0 : unit_price));

                        self.pro_tax(formatMoney(product_tax));
                        self.net_price(formatMoney(parseFloat(item_price)));
                        self.mod_psubt_raw(parseFloat(total));
                        self.mod_psubt(formatMoney(parseFloat(total)));

                        self.grnItemList()[self.table_index_id()].item_grid_sub_tot_raw(self.mod_psubt_raw());
                        self.grnItemList()[self.table_index_id()].item_grid_sub_tot(formatMoney(self.mod_psubt_raw()));
                        self.grnItemList()[self.table_index_id()].item_grid_price(formatMoney(self.mod_pprice_raw() - item_discount));
                        self.grnItemList()[self.table_index_id()].item_qty(self.mod_pquantity());
                        self.grnItemList()[self.table_index_id()].item_ds(self.mod_pdiscount());
                        self.grnItemList()[self.table_index_id()].item_discount_raw(item_discount);
                        self.grnItemList()[self.table_index_id()].item_discount(formatMoney(item_discount));
                        self.grnItemList()[self.table_index_id()].item_option(self.mod_poption());

                        self.grnItemList()[self.table_index_id()].tax(self.mod_pr_tax());
                        self.grnItemList()[self.table_index_id()].tax_val_raw(product_tax);
                        self.grnItemList()[self.table_index_id()].tax_val(formatMoney(product_tax));
                        self.grnItemList()[self.table_index_id()].tax_rate(tax_rate);

                        self.calculatePartsTblTot();
                        console.log(self.grnItemList()[self.table_index_id()])
                    }
                    //End: setModelAmountChange

                    self.calculatePartsTblTot = () => {

                        var lt_arr_length = self.grnItemList().length;
                        var subtotal = 0;

                        for (var i = 0; i < lt_arr_length; i++) {

                            subtotal += parseFloat(self.grnItemList()[i].item_grid_sub_tot_raw());
                        }
                        self.tot_parts_amnt(accounting.formatNumber(subtotal, 2));
                        self.price(accounting.formatNumber(subtotal, 2));
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

                        if (typeof obj === "undefined") {

                        } else{
                            item.item_status = ko.observable(state);
                            item.item_id = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
                            item.db_item_id = ko.observable("");
                            if(state == "up") {
                                item.db_item_id((typeof obj.row.db_item_id === "undefined" || obj.row.db_item_id == null) ? "" : obj.row.db_item_id);
                            }
                            item.order = (typeof obj.order === "undefined" || obj.order == null) ? ko.observable("") : ko.observable(obj.order ? obj.order : new Date().getTime());
                            item.product_id = (typeof obj.row.id === "undefined" || obj.row.id == null) ? ko.observable("") : ko.observable(obj.row.id);
                            item.item_type = (typeof obj.row.type === "undefined" || obj.row.type == null) ? ko.observable("") : ko.observable(obj.row.type);
                            item.combo_items = (typeof obj.combo_items === "undefined" || obj.combo_items == null) ? ko.observable("") : ko.observable(obj.combo_items);
                            item.item_price_raw = (typeof obj.row.price === "undefined" || obj.row.price == null) ? ko.observable("") : ko.observable(obj.row.price);
                            item.item_price = (typeof obj.row.price === "undefined" || obj.row.price == null) ? ko.observable("") : ko.observable(obj.row.price);
                            item.item_price_old = (typeof obj.row.price === "undefined" || obj.row.price == null) ? ko.observable("") : ko.observable(obj.row.price);
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
                            item.real_unit_price_old = (typeof obj.row.real_unit_price === "undefined" || obj.row.real_unit_price == null) ? ko.observable("") : ko.observable(obj.row.real_unit_price);
                            item.unit_price = (typeof obj.row.real_unit_price === "undefined" || obj.row.real_unit_price == null) ? ko.observable("") : ko.observable(obj.row.real_unit_price);
                            item.unit_price_old = (typeof obj.row.real_unit_price === "undefined" || obj.row.real_unit_price == null) ? ko.observable("") : ko.observable(obj.row.real_unit_price);
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
                        }
                        /*end of else clause on joborder item array filling*/
                    }

                    //remove service grid item row
                    self.removeItemGridRow = (indexNo, rowData) => {
                        item_id = rowData.item_id()

                        if(rowData.db_item_id() && rowData.db_item_id()!== "") {
                            self.deleteItemList.push(rowData.db_item_id())
                        }
                        self.grnItemList.remove(rowData);
                        self.calculatePartsTblTot();
                    }

                    //form validation before submit the form and add items to table
                    self.submitValidation = function () {

                        if(self.serviceName() === ""){

                            sweetAlertMsg(MsgHead.FVE,"Service Name "+MsgContent.CBE,'warning')
                            ErrorValidationColor('ID_serviceName',1);
                            return false;
                        }
                        ErrorValidationColor('ID_serviceName',0);

                        if(self.price() === ""){

                            sweetAlertMsg(MsgHead.FVE,"Price "+MsgContent.CBE,'warning')
                            ErrorValidationColor('ID_price',1);
                            return false;
                        }
                        ErrorValidationColor('ID_price',0);

                        if(self.VehicleTypeNo() === ""){

                            sweetAlertMsg(MsgHead.FVE,"Vehicle Type "+MsgContent.CBE,'warning')
                            ErrorValidationColor('ID_vehicleType',1);
                            return false;
                        }
                        ErrorValidationColor('ID_vehicleType',0);
                        return true;
                    };

                    //submit service package data
                    self.insertItemData = function () {

                        if(!self.submitValidation()) return;

                        let url = '{{route('service.package.create.submit')}}';
                        if(!self.isNew()){
                            url = '{{ route('service.package.edit.submit') }}';
                        }
                        $.ajax({
                            url: url,
                            type: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "service_id": self.service_pkg_id(),
                                "service_name": self.serviceName(),
                                "service_description": self.serviceDescript(),
                                "price": self.price(),
                                "vehicle_type": self.VehicleTypeNo(),
                                "grid_item_rows": ko.toJSON(self.grnItemList),
                                "delete_grid_item_rows": ko.toJSON(self.deleteItemList)
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading(); },
                            success: function (responseData) {
                                StopLoading();
                                sweetAlertMsg('Success',responseData.message,'success')
                                $('#ID_servicetype_tbl').DataTable().ajax.reload();
                                $('#serv_type_mod').modal('hide');
                                self.clearForm();
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                StopLoading();

                                if(jqXHR.status == 403){
                                    sweetAlertMsg('Permission Error','User does not have the right permissions.','warning');
                                }else {
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
                    }

                    self.clearForm = function () {
                        self.service_pkg_id("");
                        self.serviceName("");
                        self.serviceDescript("");
                        self.price("");
                        self.VehicleTypeNo("");
                        // appSelectEmpty('ID_vehicleType');
                        self.grnItemList.removeAll();
                        self.deleteItemList.removeAll();
                    }




                    //clearing the table fields
                    self.clearTblRowEditFields = function () {

                        self.table_index_id("");
                        self.tableRowData("");
                        self.mod_edit_tax("");
                        self.mod_pr_tax("");
                        self.mod_tax_method("");
                        self.mod_pro_serial("");
                        self.mod_pquantity("");
                        self.mod_punit("");
                        self.mod_poption("");
                        self.mod_poption_price("");
                        self.mod_pdiscount("");
                        self.mod_pprice("");
                        self.mod_real_unit_price("");
                        self.net_price("");
                        self.pro_tax("");
                        self.mod_psubt("");
                        self.mod_padiscount("");

                        self.old_item_qty("");
                    };

                    //when selecting get vehicle type value
                    self.onClickGetVehicleTypeValue = function () {

                        self.VehicleTypeNo(GetSelect2Val('ID_vehicleType'));
                    };


                    /*not used function and thinking implement in future*/
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
                                    url: "{{ route('service.package.destroy.submit') }}",
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

                                            $('#ID_servicetype_tbl').DataTable().ajax.reload();
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

                                sweetAlertMsg('Canceled!','Record deletion canceled.','info');
                            }
                        })
                    }
                }
            }
        </script>
    @endpush
@endsection

