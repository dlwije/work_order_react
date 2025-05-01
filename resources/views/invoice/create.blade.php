@extends('template.master')
@section('title','New Invoice')

@push('css')
    <link href="{{ asset('assets/plugins/DateTimePicker/jquery.datetimepicker.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-ui/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush

@section('content')
    <div class="container-fluid" id="jobOrder_add_view">
        <div class="row">

            <div class="col-md-12">
                @include('errors.errors-forms')
            </div>

            <div class="col-md-12">
                <!-- Default box -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Create Invoice</h3>
                        <div class="card-tools">
                            <a class="btn btn-sm btn-secondary no-shadow" href="{{ route('invoice.list') }}"> <i
                                    class="fas fa-hand-o-left"></i>Go Back</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Job No<span class="text-danger">*</span></label>
                                    <select name="jo_serial" id="id_jo_serial_drop" class="form-control"
                                            onchange="jobOrder.ivm.setJOAppSerialNoId()"></select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Invoice Date</label>
                                    <input type="text" class="form-control" id="id_invdate" data-bind="value: inv_date"
                                           readonly>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Job Date</label>
                                    <input type="text" class="form-control" id="id_jodate" data-bind="value: jo_date"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Workorder Date</label>
                                    <input type="text" class="form-control" id="id_wodate" data-bind="value: wo_date"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Customer<span class="text-danger">*</span></label>
                                    <select name="customer" id="ID_cus_name" class="form-control" disabled></select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Vehicle</label>
                                    <select name="vehicle" id="ID_vehi_name" class="form-control" disabled></select>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                        </div>

                        <div class="row mb-3">
                            <!-- Remarks -->
                            <div class="col-md-12">
                                <label class="control-label">Remarks</label>
                                <textarea class="form-control" rows="2" data-bind="value: woRemark" readonly></textarea>
                            </div>
                        </div>

                        <!-- Table Filling fields -->
                        <div class="callout callout-info">

                            <!-- End Table Filling fields -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-condensed table-bordered table-hover mb-0">
                                            <thead>
                                            <tr>
                                                <th style="" class="text-center">Labor Task</th>
                                                <th style="" class="text-center">Hours</th>
                                                <th style="" class="text-center">Act: Hours</th>
                                                <th class="text-center" style="">Hour Rate</th>
                                                <th class="text-center" style="">Labor Cost</th>
                                                <th class="text-center" style="">
                                                    Subtotal(<span data-bind="html: default_currenty"></span>)
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody data-bind="foreach: laborTaskList">
                                            <tr>
                                                <td data-bind="text: col1" class="text-center"></td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control text-center" data-bind="value: col3, event: { blur: $parent.calculateTblLineTotal.bind($data, $index()) }">
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" class="form-control text-center" data-bind="value: col4">
                                                </td>
                                                <td class="">
                                                    <input type="text" class="form-control text-right" data-bind="value: col5, event: { blur: $parent.calculateTblLineTotal.bind($data, $index()) }">
                                                </td>
                                                <td class="">
                                                    <input type="text" class="form-control text-right" data-bind="value: col6">
                                                </td>
                                                <td data-bind="text: col7" class="text-right ">DisAmount</td>
                                            </tr>
                                            <!-- items -->

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="offset-md-10 col-md-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control text-right"
                                               data-bind="value: tot_labor_tsk_amnt" readonly disabled>
                                    </div>
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
                            <div class="row mt-3">
                                <div class="offset-md-10 col-md-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control text-right"
                                               data-bind="value: tot_serv_pkg_amnt" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End: Service package Table -->

                        <!-- Start:Inventory item adding area -->
                        <div class="callout callout-success">

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
                                                    data-bind="style: { display: jobOrder.ivm.is_product_discount() == 1 ? '' : 'none' }">
                                                    Discount
                                                </th>
                                                <th class="text-center"
                                                    data-bind="style: { display: jobOrder.ivm.is_tax1() == 1 ? '' : 'none' }">
                                                    Product Tax
                                                </th>
                                                <th class="text-center" style="">Subtotal(<span
                                                        data-bind="html: default_currenty"></span>)
                                                </th>
                                                <th class="text-center" style=""></th>
                                            </tr>
                                            </thead>
                                            <tbody data-bind="foreach: jbItemList">
                                            <tr data-bind="attr: { data_item_id: item_id, id: 'row_'+item_id(), class: validateClass1(), title: item_row_tooltip() }" data-toggle="tooltip" data-placement="left">
                                                <td class="text-center">
                                                    <span data-bind="html: item_code"></span> -
                                                    <span data-bind="html: item_name"></span>
                                                    <span data-bind="style: { display: item_option_name() == 0 ? 'none' : '' }">
                                                    (<span data-bind="html: item_option_name"></span>)
                                                </span>
                                                    <span style="cursor: pointer;" class="fa-pull-right fas fa-edit" data-bind="style: { display: db_item_id() == '' && item_status() != 'serv' ? '' : 'none' }, event: { click: $parent.editItemRow.bind($data, $index()) }"></span>
                                                </td>
                                                <td class="text-right">
                                                    <span data-bind="style: { display: item_status() == 'serv' ? 'none' : '' }, html: item_grid_price"></span>
                                                    <span data-bind="style: { display: item_status() == 'serv' ? '' : 'none' },html: 0.00"></span>
                                                </td>
                                                <td class="text-center">
                                                    <span data-bind="html: item_qty,style: { display: db_item_id() == '' && item_status() != 'serv' ? 'none' : '' }"></span>
                                                    <input type="text" class="form-control rquantity text-center" data-bind="value: item_qty, style: { display: db_item_id() == '' && item_status() != 'serv' ? '' : 'none' }, event: { blur: $parent.calculateRQuantity.bind($data, $index()), click: $parent.setOldItemQty.bind($data, $index()) }">
                                                </td>

                                                <td class="text-right" data-bind="style: { display: jobOrder.ivm.is_product_discount() == 1 ? '' : 'none' }">
                                                    <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? 'none' : '' }, text: item_discount"></span>
                                                    <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? '' : 'none' }, text: 0.00"></span>
                                                </td>
                                                <td class="text-right" data-bind="style: { display: jobOrder.ivm.is_tax1() == 1 ? '' : 'none' }">
                                                    <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? 'none' : '' }, text: '('+tax_rate()+') '+tax_val()"></span>
                                                    <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? '' : 'none' }, text: 0.00"></span>
                                                </td>
                                                <td class="text-right" >
                                                    <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? 'none' : '' }, text: item_grid_sub_tot"></span>
                                                    <span class="text-right" data-bind="style: { display: item_status() == 'serv' ? '' : 'none' }, text: 0.00"></span>
                                                </td>
                                                <td class="text-center">
                                                    <i class="fa fa-trash-alt" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Remove this row" data-bind="style: { display: db_item_id() == '' && item_status() != 'serv' ? '' : 'none' },click: $parent.removeItemGridRow.bind($data, $index())"></i>
                                                </td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="offset-md-1 col-md-3">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Sale Status :</label>
                                        <?php $sale_state = array('completed'=> 'Completed', 'pending' => 'Pending') ?>
                                        <div class="col-md-7">
                                            <select id="sale_status" class="form-control" onchange="jobOrder.ivm.setSaleStatus()">
                                                @foreach($sale_state AS $ss => $val)
                                                    <option value="{{ $ss }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Total Parts:
                                            <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Item prices which are belongs to a specific Service Package will not added to this amount."></i>
                                        </label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: tot_parts_amnt" readonly disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Total Labor:</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: tot_labor_tsk_amnt" readonly disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Total Other:</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: tot_other_amnt" readonly disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Sub Total :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: sub_tot_amnt" readonly disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="offset-md-1 col-md-3">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Payment Status :</label>
                                        <?php $payment_state = array('pending'=> 'Pending', 'due' => 'due', 'partial' => 'Partial', 'paid' => 'paid') ?>
                                        <div class="col-md-7">
                                            <select id="paid_status" class="form-control" onchange="jobOrder.ivm.setPaymentDetails()">
                                                @foreach($payment_state AS $ss => $val)
                                                    <option value="{{ $ss }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Tax1 :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: tax1_amnt" readonly disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Supply Charge :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: sup_charge_amnt"
                                                   onblur="jobOrder.ivm.calculateTot();">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Shipping :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: shipping_amnt"
                                                   onblur="jobOrder.ivm.calculateTot();">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Total :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: tot_amnt" readonly disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="offset-md-4 col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Discount :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: discount_amnt"
                                                   onblur="jobOrder.ivm.calculateDiscount();">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Fee :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: fee_amnt">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Diag. Fee :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: diag_fee_amnt">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Grand Total :</label>
                                        <div class="col-md-7">
                                            <input type="text" class="form-control text-right"
                                                   data-bind="value: grand_tot_amnt" readonly disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="">

                            <div class="form-group">

                                <button type="button" id="add_joborder" class="btn btn-success btn-sm float-right" onclick="jobOrder.ivm.formSubmit();">
                                    <i class="fas fa-save"></i> Submit
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card -->
            </div>
        </div>

        <!--Labor Task List Table model -->
        <div class="modal fade" id="labor_task_mod">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Select labor task to the job order</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="id_lbr_task_tbl" class="table table-bordered table-hover responsive"
                                           style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Task Code</th>
                                            <th>Task Name</th>
                                            <th>Source</th>
                                            <th>Category</th>
                                            <th>Model/Class</th>
                                            <th>Hour</th>
                                            <th>Rate</th>
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
                                 data-bind="style: { display: jobOrder.ivm.is_tax1() == 1 ? '' : 'none' }">
                                <label for="ID_mod_edit_tax" class="control-label">Product Tax</label>
                                <select class="form-control " id="ID_mod_edit_tax" onchange="jobOrder.ivm.setEditTax()">
                                    <option></option>

                                </select>
                            </div>
                            <div class="form-group col-md-12"
                                 data-bind="style: { display: jobOrder.ivm.is_product_serial() == 1 ? '' : 'none' }">
                                <label for="ID_mod_pro_serial" class="control-label">Serial No</label>
                                <input type="text" class="form-control" id="ID_mod_pro_serial"
                                       data-bind="value: mod_pro_serial">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="ID_mod_pquantity" class="control-label">Quantity</label>
                                <input type="text" class="form-control text-center" id="ID_mod_pquantity"
                                       data-bind="value: mod_pquantity" onblur="jobOrder.ivm.setModelAmountChange()">
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
                                 data-bind="style: { display: jobOrder.ivm.is_product_discount() == 1 ? '' : 'none' }">
                                <label for="ID_mod_pdiscount" class="control-label">Product Discount <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="You can use discount as a percentage wise or as a fixed amount."></i></label>
                                <input type="text" class="form-control text-right" id="ID_mod_pdiscount"
                                       data-bind="value: mod_pdiscount" onblur="jobOrder.ivm.setModelAmountChange();">
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
                                 data-bind="style: { display: jobOrder.ivm.is_product_discount() == 1 ? '' : 'none' }">
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
        <!-- /.modal -->

        <!-- Payment adding model -->
        <div class="modal fade" id="payment_update_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Finalize payment</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label for="inputEmail3" class="col-form-label">Payment Reference No</label>
                                    <input type="text" class="form-control text-right" data-bind="value: payment_reference_no">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ">
                                    <label for="inputEmail3" class="col-form-label">Amount</label>
                                    <input type="text" class="form-control text-right" data-bind="value: amount_paid">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-form-label">Paying by</label>
                                    <?php $paying_by = array('' => '','cash'=> 'Cash', 'CC' => 'Credit Card', 'Cheque' => 'Cheque', 'other' => 'Other', 'deposit' => 'Deposit') ?>
                                    <select id="paying_by" class="form-control" onchange="jobOrder.ivm.setPayingBy()">
                                        @foreach($paying_by AS $ss => $val)
                                            <option value="{{ $ss }}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Credit card fields -->
                        <div id="credit_card_row" data-bind="style: { display: paying_by() == 'CC' ? '' : 'none' }">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter last four digit of the card..." class="form-control" data-bind="value: pcc_no">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" placeholder="Card holder name" class="form-control" data-bind="value: pcc_holder">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php $card_type = array('Visa'=> 'Visa', 'MasterCard' => 'MasterCard', 'Amex' => 'Amex', 'Discover' => 'Discover') ?>
                                        <select title="Select card type here." id="card_type" class="form-control" onchange="jobOrder.ivm.setCardType()">
                                            @foreach($card_type AS $ss => $val)
                                                <option value="{{ $ss }}">{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter expire month on the card..." class="form-control" data-bind="value: pcc_month">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" placeholder="Enter expire year on the month" class="form-control" data-bind="value: pcc_year">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Check fields -->
                        <div class="row" data-bind="style: { display: paying_by() == 'Cheque' ? '' : 'none' }">
                            <div class="col-md-12">
                                <label for="inputEmail3" class="col-form-label">Cheque No</label>
                                <div class="form-group">
                                    <input type="text" placeholder="" class="form-control" data-bind="value: cheque_no">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for="inputEmail3" class="col-form-label">Payment Note</label>
                                <div class="form-group">
                                    <textarea rows="2" placeholder="" class="form-control" data-bind="value: payment_note"></textarea>
                                </div>
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

    </div>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/DateTimePicker/jquery.datetimepicker.full.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
{{--    <script src="{{asset('assets/plugins/accounting.min.js')}}"></script>--}}
    <script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            jobOrder.init();
            // $('#id_jodate').datetimepicker({mask: '9999-19-39', format: 'Y-m-d'});

            $('#labor_task_mod').on('hide.bs.modal', function () {
                var table = $('#id_lbr_task_tbl').DataTable();
                table.destroy();
                $('#id_lbr_task_tbl').find('tr:eq(1)').remove();
                // $('#id_lbr_task_tbl').empty(); // empty in case the columns change
            })
        });

        jobOrder = {
            ivm: null,
            init: function () {
                jobOrder.ivm = new jobOrder.jobOrderViewModel();
                ko.applyBindings(jobOrder.ivm, $('#jobOrder_add_view')[0]);
                jobOrder.ivm.designInitialize();
            },
            jobOrderViewModel: function () {

                var self = this;

                slitems = {};

                self.isNew = ko.observable(true);
                self.itemID = ko.observable("");

                self.default_currenty = ko.observable(site.settings.default_currency);
                self.is_product_discount = ko.observable(site.settings.product_discount);
                self.is_tax1 = ko.observable(site.settings.tax1);
                self.is_product_serial = ko.observable(site.settings.product_serial);

                self.inv_date = ko.observable("");

                self.jo_serial = ko.observable("");
                self.jo_id = ko.observable("");
                self.jo_date = ko.observable("");

                self.wo_serial = ko.observable("");
                self.woRemark = ko.observable("");
                self.woCustomerId = ko.observable("");
                self.woVehicle = ko.observable("");
                self.wo_date = ko.observable("");

                //table filling fields
                self.task_drop_id = ko.observable("");
                self.task_drop_name = ko.observable("");
                self.task_description = ko.observable("");
                self.task_hours = ko.observable("");
                self.task_act_hours = ko.observable("");
                self.task_hour_rates = ko.observable("");
                self.task_labor_cost = ko.observable("");
                self.task_labor_total = ko.observable("");

                self.laborTaskList = ko.observableArray();
                self.jbItemList = ko.observableArray();

                self.deleteJbItemList = ko.observableArray();
                self.tot_serv_pkg_amnt = ko.observable("");
                self.servicePkgList = ko.observableArray();
                self.deleteServicePkgList = ko.observableArray();

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

                //payment variables
                self.amount_paid = ko.observable(0);
                self.gift_card_no = ko.observable("");
                self.payment_reference_no = ko.observable("");
                self.amount_paying = ko.observable(0);
                self.cheque_no = ko.observable("");
                self.pcc_no = ko.observable("");
                self.pcc_holder = ko.observable("");
                self.pcc_month = ko.observable("");
                self.pcc_year = ko.observable("");
                self.pcc_type = ko.observable("");
                self.payment_note = ko.observable("");
                self.sale_status = ko.observable("");
                self.payment_status = ko.observable("");

                self.paying_by = ko.observable("");

                self.itemValidateCls = ko.observable("");

                self.designInitialize = () => {
                    // $('#sale_status').select2();
                    select2Dropdown('id_jo_serial_drop', '{{ route('approved.jo.serialno.drop.list') }}')

                    var id = App_GetParameterByName();
                    if(id != null && id!=='' ){
                        self.jo_id(id);
                        select2Dropdown('id_jo_serial_drop','{{ route('approved.jo.serialno.drop.list') }}','{{ route('approved.jo.serialno.byid.drop.list') }}', self.jo_id())
                        // self.getJODetails();
                    }

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
                                url: '{{route('product.suggestion.list')}}',
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
                    $('#id_invdate').datetimepicker({ mask:'9999-19-39', format:'Y-m-d' });
                    self.getToday();
                }
                self.getToday = function () {

                    let today = new Date();
                    let dd = today.getDate();
                    let mm = today.getMonth()+1; //January is 0!
                    let yyyy = today.getFullYear();

                    if(dd<10) { dd = '0'+dd } if(mm<10) { mm = '0'+mm }
                    today = yyyy + '-' + mm + '-' + dd;
                    self.inv_date(today);
                };

                self.showLaborTaskListMod = () => {

                    if ($.fn.DataTable.isDataTable('#id_lbr_task_tbl')) {
                        $('#id_lbr_task_tbl').DataTable().destroy();
                    }

                    // $('#id_lbr_task_tbl tbody').empty();

                    $('#id_lbr_task_tbl thead tr').clone(true).appendTo('#id_lbr_task_tbl thead');
                    $('#id_lbr_task_tbl thead tr:eq(1) th').each(function (i) {
                        if (i != (8) && i != 0) {
                            var title = $(this).text();
                            if (i == 6)
                                $(this).html('<input type="text" class="form-control" placeholder=" Search ' + title + '" />');
                            else if (i == 7)
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
                    let te = 0;
                    table = $('#id_lbr_task_tbl').DataTable({
                        orderCellsTop: true,
                        fixedHeader: true,
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('labortask.table.list',['page_type'=>'jb']) }}",
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
                            $('#labor_task_mod').modal('show');
                        }
                    });

                }
                self.clearAutoCompleteInput = () => {
                    $('#add_item').focus();
                    $('#add_item').removeClass('ui-autocomplete-loading');
                    $('#add_item').val('');
                }

                self.getJODetails = function () {
                    if(self.jo_id() != "") {
                        $.ajax({
                            url: "{{ route('joborder.approved.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                item_id: self.jo_id(),
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading(); },
                            success: function (data, textStatus, jQxhr) {
                                StopLoading();
                                if (data.status) {

                                    self.jo_serial(data.data['jo_data'].serial_no);
                                    self.jo_date(data.data['jo_data'].jo_date);

                                    self.wo_serial(data.data['wo_data'].serial_no);
                                    self.woRemark(data.data['wo_data'].reported_defect);
                                    self.woCustomerId(data.data['wo_data'].customer_id);
                                    self.woVehicle(data.data['wo_data'].vehicle_id);
                                    self.wo_date(data.data['wo_data'].wo_date);

                                    select2Dropdown('ID_cus_name', '{{ route('customer.drop.list') }}', '{{ route('customer.byid.drop.list') }}', self.woCustomerId());
                                    select2Dropdown('ID_vehi_name', '{{ route('customer.vehicle.drop.list') }}', '{{ route('customer.vehicle.byid.drop.list') }}', self.woVehicle(), self.woCustomerId());

                                    self.jbItemList.removeAll();
                                    self.laborTaskList.removeAll();
                                    $.each(data.data.task_data, (i,item) => {
                                        self.laborTaskList.push(new gridLaborTaskListRows(item));
                                    })

                                    self.servicePkgList.removeAll();
                                    $.each(data.data.serv_pkg_data, (i,item) => {
                                        self.servicePkgList.push(new gridServPkgListRows(item));
                                    })

                                    $.each(data.data.item_data, function (i, item) {
                                        self.jbItemList.push(new gridItemListRows(item,"up"));
                                    })

                                    $('[data-toggle="tooltip"]').tooltip()

                                    self.calculateLaborTaskTblTot();
                                    self.calculateServPkgTblTot();
                                } else
                                    CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE, MsgContent.FSE, 'warning');
                            },
                            error: function (jqXhr, textStatus, errorThrown) { StopLoading(); }
                        });
                    }
                }

                self.getTaskDetails = (id) => {
                    try {
                        self.task_drop_id(id);
                        if (typeof self.task_drop_id() === "undefined") return;

                        StartLoading();

                        $.ajax({
                            url: '{{route('labortask.single.detail')}}',
                            type: 'GET',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "ItemId": self.task_drop_id()
                            },
                            beforeSend: (jqXHR, settings) => {
                                StartLoading();
                            },
                            success: (result) => {
                                StopLoading();

                                self.task_hours(0);
                                if (result.count > 0) {

                                    self.task_drop_name(result.data[0].task_name);
                                    self.task_description(result.data[0].task_description);
                                    self.task_hours(result.data[0].task_hour);
                                    self.task_hour_rates(accounting.formatNumber(result.data[0].normal_rate, 2));

                                    self.calculateLineTotal();
                                    self.laborTaskList.push(new gridLaborTaskListRows());
                                    self.calculateLaborTaskTblTot();
                                    self.clearTableFields();
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

                /**service pkg*/
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
                        item.col1 = (typeof obj.service_name === "undefined" || obj.service_name == null) ? ko.observable("") : ko.observable(obj.service_name);
                        item.col2 = (typeof obj.description === "undefined" || obj.description == null) ? ko.observable("") : ko.observable(obj.description);
                        item.col3 = (typeof obj.type_name === "undefined" || obj.type_name == null) ? ko.observable("") : ko.observable(obj.type_name);
                        item.col4 = (typeof obj.price === "undefined" || obj.price == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.price, 2));
                        item.col5 = (typeof obj.price === "undefined" || obj.price == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.price, 2));
                        item.col6 = ko.observable(obj.service_pkg_id);
                        item.col7 = ko.observable(obj.id);
                    }
                }
                /*end service pkg*/

                /** not used **/
                self.addItemsToGrid = (rawData, te) => {
                    try {
                        console.log(te);
                        self.task_drop_id(rawData.id);
                        $('[data-toggle="tooltip"]').tooltip()
                        var div = $('<div/>')
                            .css('margin-top', '0px')
                            .addClass('text-success')
                            .text('Added...');
                        console.log('addItemTo');
                        let ste = false;
                        let lt_arr_length = parseInt(self.laborTaskList().length);
                        for (let i = 0; i < lt_arr_length; i++) {
                            if (self.task_drop_id() === self.laborTaskList()[i].col8()) {
                                console.log('found');
                                ste = true;
                                break;
                            } else {
                                ste = false;
                            }
                        }

                        if (!ste) {
                            self.getTaskDetails();
                        }

                        return div;
                    } catch (e) {
                        console.log(e)
                    }
                }

                self.setJOAppSerialNoId = () => {
                    self.jo_id(getSelect2Val('id_jo_serial_drop'));
                    self.getJODetails();
                }

                self.calculateLineTotal = () => {
                    if (isNaN(self.task_hours())) self.task_hours(0);
                    var amount = (self.task_hours() * parseFloat(self.task_hour_rates().replace(/[^0-9-.]/g, '')));
                    self.task_labor_total(accounting.formatNumber(amount, 2));
                }

                self.calculateTblLineTotal = (indexNo, rowData) => {

                    if (!is_numeric(rowData.col3()) && !is_numeric(rowData.col5())) {
                        sweetAlertMsg('Info', '{{ __('messages.unexpected_value') }}', 'warning')
                        return;
                    }
                    if (isNaN(rowData.col3())) rowData.col3(0);
                    var amount = (rowData.col3() * parseFloat(rowData.col5().replace(/[^0-9-.]/g, '')));
                    rowData.col7(accounting.formatNumber(amount, 2));
                    self.calculateLaborTaskTblTot();
                }

                self.calculateLaborTaskTblTot = () => {

                    var lt_arr_length = self.laborTaskList().length;
                    var subtotal = 0;

                    for (var i = 0; i < lt_arr_length; i++) {

                        subtotal += parseFloat(self.laborTaskList()[i].col7().replace(/[^0-9-.]/g, ''));
                    }
                    self.tot_labor_tsk_amnt(accounting.formatNumber(subtotal, 2))
                    self.calculatePartsTblTot();
                }

                let gridLaborTaskListRows = function (obj) {
                    let item = this;

                    if (typeof obj === "undefined") {

                        item.col1 = (typeof self.task_drop_name() === "undefined" || self.task_drop_name() == null) ? ko.observable("") : ko.observable(self.task_drop_name());
                        item.col2 = ko.observable("");
                        item.col3 = (typeof self.task_hours() === "undefined" || self.task_hours() == null) ? ko.observable("") : ko.observable(self.task_hours());
                        item.col4 = (typeof self.task_act_hours() === "undefined" || self.task_act_hours() == null) ? ko.observable("") : ko.observable(self.task_act_hours());
                        item.col5 = (typeof self.task_hour_rates() === "undefined" || self.task_hour_rates() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.task_hour_rates(), 2));
                        item.col6 = (typeof self.task_labor_cost() === "undefined" || self.task_labor_cost() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.task_labor_cost(), 2));
                        item.col7 = (typeof self.task_labor_total() === "undefined" || self.task_labor_total() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.task_labor_total(), 2));
                        item.col8 = (typeof self.task_drop_id() === "undefined" || self.task_drop_id() == null) ? ko.observable("") : ko.observable(self.task_drop_id());
                        item.col9 = ko.observable("");
                        item.col10 = ko.observable("");
                        item.col11 = ko.observable("");
                        item.task_item = ko.observable("");
                    }else{
                        item.col1 = (typeof obj.task.task_name === "undefined" || obj.task.task_name == null) ? ko.observable("") : ko.observable(obj.task.task_name);
                        item.col2 = (typeof obj.task.task_description === "undefined" || obj.task.task_description == null) ? ko.observable("") : ko.observable(obj.task.task_description);
                        item.col3 = (typeof obj.task.hours === "undefined" || obj.task.hours == null) ? ko.observable("") : ko.observable(obj.task.hours);
                        item.col4 = (typeof obj.task.taskTotRawTime === "undefined" || obj.task.taskTotRawTime == null) ? ko.observable("") : ko.observable(obj.task.taskTotRawTime);
                        item.col5 = (typeof obj.task.hourly_rate === "undefined" || obj.task.hourly_rate == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.task.hourly_rate,2));
                        item.col6 = (typeof obj.task.labor_cost === "undefined" || obj.task.labor_cost == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.task.labor_cost,2));
                        item.col7 = (typeof obj.task.total === "undefined" || obj.task.total == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.task.total,2));
                        item.col8 = (typeof obj.task.task_id === "undefined" || obj.task.task_id == null) ? ko.observable("") : ko.observable(obj.task.task_id);
                        item.col9 = (typeof obj.task.id === "undefined" || obj.task.id == null) ? ko.observable("") : ko.observable(obj.task.id);
                        item.col10 = (typeof obj.task.is_approve_cost === "undefined" || obj.task.is_approve_cost == null) ? ko.observable("") : ko.observable(obj.task.is_approve_cost);
                        item.col11 = (typeof obj.task.is_approve_work === "undefined" || obj.task.is_approve_work == null) ? ko.observable("") : ko.observable(obj.task.is_approve_work);
                        item.task_item = (typeof obj.task_item === "undefined" || obj.task_item == null) ? ko.observable("") : ko.observable(obj.task_item);

                        $.each(item.task_item(), function (i,t_item){
                            self.jbItemList.push(new gridTaskItemListRows(t_item, "up", item.col1()));
                        });
                    }
                }

                self.clearTableFields = () => {
                    self.task_drop_id("");
                    self.task_drop_name("");
                    self.task_description("");
                    self.task_hours("");
                    self.task_act_hours("");
                    self.task_hour_rates("");
                    self.task_labor_cost("");
                    self.task_labor_total("");

                    // APP_selectEmpty('id_labor_task_drop');
                }

                self.removeGridRow = (indexNo, rowData) => {
                    self.laborTaskList.remove(rowData);
                    self.calculateLaborTaskTblTot();
                }
                self.removeItemGridRow = (indexNo, rowData) => {
                    item_id = rowData.item_id()
                    delete slitems[item_id];
                    if (slitems.hasOwnProperty(item_id)) {
                    } else {
                        localStorage.setItem('slitems', JSON.stringify(slitems));
                    }

                    self.jbItemList.remove(rowData);
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
                    // self.jbItemList()[self.table_index_id()].item_ds(self.mod_pdiscount());
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
                        uopt = $('<select id="punit" name="punit" class="form-control select" onchange="jobOrder.ivm.setPUnit()" />');
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
                        opt = $('<select id="poption" name="poption" class="form-control select" onchange="jobOrder.ivm.setPOption()" />');

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

                                    self.jbItemList()[self.table_index_id()].item_options(result.data[0]);
                                    self.jbItemList()[self.table_index_id()].item_option_name(result.data[0].name);
                                    self.jbItemList()[self.table_index_id()].item_option_price(result.data[0].price);
                                    self.jbItemList()[self.table_index_id()].item_option(result.data[0].id);
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

                    self.jbItemList()[self.table_index_id()].item_grid_sub_tot_raw(self.mod_psubt_raw());
                    self.jbItemList()[self.table_index_id()].item_grid_sub_tot(formatMoney(self.mod_psubt_raw()));
                    self.jbItemList()[self.table_index_id()].item_grid_price(formatMoney(self.mod_pprice_raw() - item_discount));
                    self.jbItemList()[self.table_index_id()].item_qty(self.mod_pquantity());
                    self.jbItemList()[self.table_index_id()].item_ds(self.mod_pdiscount());
                    self.jbItemList()[self.table_index_id()].item_discount_raw(item_discount);
                    self.jbItemList()[self.table_index_id()].item_discount(formatMoney(item_discount));
                    self.jbItemList()[self.table_index_id()].item_option(self.mod_poption());

                    self.jbItemList()[self.table_index_id()].tax(self.mod_pr_tax());
                    self.jbItemList()[self.table_index_id()].tax_val_raw(product_tax);
                    self.jbItemList()[self.table_index_id()].tax_val(formatMoney(product_tax));
                    self.jbItemList()[self.table_index_id()].tax_rate(tax_rate);

                    self.calculatePartsTblTot();
                    console.log(self.jbItemList()[self.table_index_id()])
                }
                //End: setModelAmountChange

                self.calculatePartsTblTot = () => {

                    var lt_arr_length = self.jbItemList().length;
                    var subtotal = 0;

                    for (var i = 0; i < lt_arr_length; i++) {

                        if(self.jbItemList()[i].item_status() != "serv" && self.jbItemList()[i].service_pkg_id() == ""){

                            subtotal += parseFloat(self.jbItemList()[i].item_grid_sub_tot_raw());
                        }
                    }
                    self.tot_parts_amnt(accounting.formatNumber(subtotal, 2));
                    self.calculateSubTot();
                }

                self.calculateSubTot = () => {
                    let labor_tot = 0;
                    if(self.tot_labor_tsk_amnt() != ""){
                        labor_tot = parseFloat(self.tot_labor_tsk_amnt().replace(/[^0-9-.]/g, ''));
                    }
                    let serv_pkg_tot = 0;
                    if(self.tot_serv_pkg_amnt() != ""){
                        serv_pkg_tot = parseFloat(self.tot_serv_pkg_amnt().replace(/[^0-9-.]/g, ''));
                    }
                    var subTot = parseFloat(self.tot_parts_amnt().replace(/[^0-9-.]/g, '')) + labor_tot + serv_pkg_tot;

                    self.tot_other_amnt(accounting.formatNumber(serv_pkg_tot, 2));
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

                    // console.log(obj);
                    if (state == "up") {

                        item.service_pkg_id = (typeof obj.row.service_pkg_id === "undefined" || obj.row.service_pkg_id == null) ? ko.observable("") : ko.observable(obj.row.service_pkg_id);
                        let itemType = '';
                        if(item.service_pkg_id() != "" && item.service_pkg_id() != 0) itemType = 'serv';

                        item.item_status = ko.observable(itemType);
                        item.validateClass1 = ko.observable('');
                        item.item_row_tooltip = ko.observable('');
                        if(item.item_status() == "serv") { item.validateClass1('bg-gray-light'); item.service_pkg_id(item.service_pkg_id()); item.item_row_tooltip('Item of '+obj.row.service_name)}

                        item.item_id = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id); // auto generated unique hash
                        item.db_item_id = (typeof obj.row.id === "undefined" || obj.row.id == null) ? ko.observable("") : ko.observable(obj.row.id);// db auto incremented id
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

                        item.service_pkg_id = (typeof obj.row.service_pkg_id === "undefined" || obj.row.service_pkg_id == null) ? ko.observable("") : ko.observable(obj.row.service_pkg_id);
                        let itemType = '';
                        if(item.service_pkg_id() != "" && item.service_pkg_id() != 0) itemType = 'serv';

                        item.item_status = ko.observable(itemType);
                        item.validateClass1 = ko.observable('');
                        item.item_row_tooltip = ko.observable('');

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
                    }
                    /*end of else clause on joborder item array filling*/
                }

                /** use for automatic filling of item when job order seleted */
                let gridTaskItemListRows = function (obj, state, taskName) {
                    let item = this;

                    // console.log(obj)
                    // console.log(state)
                    if (state == "up") {

                        item.service_pkg_id = (typeof obj.row.service_pkg_id === "undefined" || obj.row.service_pkg_id == null) ? ko.observable("") : ko.observable(obj.row.service_pkg_id);
                        let itemType = '';
                        if(item.service_pkg_id() != "" && item.service_pkg_id() != 0) itemType = 'serv';

                        item.item_status = ko.observable(itemType);
                        item.validateClass1 = ko.observable('');
                        item.item_row_tooltip = ko.observable('');
                        item.item_row_tooltip('Item of '+taskName)

                        item.item_id = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id); // auto generated unique hash
                        item.db_item_id = (typeof obj.row.id === "undefined" || obj.row.id == null) ? ko.observable("") : ko.observable(obj.row.id);// db auto incremented id
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

                        self.itemValidateCls('');
                        if (item.item_type() == 'standard' && item.item_options() !== false) {
                            console.log("if1")
                            $.each(item.item_options(), function (i, option) {
                                if (option.id == item.item_option() && parseFloat(item.base_quantity()) > parseFloat(option.quantity)) {
                                    console.log("if1.1")
                                    item.validateClass1('bg-warning');
                                    self.itemValidateCls('bg-warning');
                                    if (site.settings.overselling != 1) {
                                        $('#add_joborder, #edit_joborder').attr('disabled', true);
                                    }
                                }
                            });
                        } else if (item.item_type() == 'standard' && item.base_quantity() > item.item_aqty()) {
                            console.log('if2')
                            self.itemValidateCls('bg-warning');
                            if (site.settings.overselling != 1) {
                                console.log("if2.1")
                                $('#add_joborder, #edit_joborder').attr('disabled', true);
                            }
                        }else if (item.item_type() == 'combo') {
                            console.log("if4");
                            if (item.combo_items() === false) {
                                console.log("if4.1")
                                self.itemValidateCls('bg-warning');
                                $('#row_' + item.item_id()).addClass('bg-warning');
                                if (site.settings.overselling != 1) {
                                    $('#add_joborder, #edit_joborder').attr('disabled', true);
                                }
                            } else {
                                console.log('else');
                                $.each(item.combo_items(), function () {
                                    if (parseFloat(this.quantity) < parseFloat(this.qty) * item.base_quantity() && this.type == 'standard') {
                                        console.log('else1.1')
                                        self.itemValidateCls('bg-warning');
                                        $('#row_' + item.item_id()).addClass('bg-warning');
                                        if (site.settings.overselling != 1) {
                                            $('#add_joborder, #edit_joborder').attr('disabled', true);
                                        }
                                    }
                                });
                            }
                        }

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
                    }
                    /*end of else clause on joborder item array filling*/
                }

                //setting payment status
                self.setPaymentDetails = function () {
                    self.payment_status($('#paid_status option:selected').val());
                    if(self.payment_status() == 'partial' || self.payment_status() == 'paid'){

                        self.amount_paid(self.grand_tot_amnt());
                        self.amount_paying(self.grand_tot_amnt());
                        $('#payment_update_mod').modal('show');
                    }else{
                        self.amount_paid(0);
                        self.amount_paying(0);
                        $('#payment_update_mod').modal('hide');
                    }
                }

                //paying by cash or card or cheque
                self.setPayingBy = function () { self.paying_by($('#paying_by option:selected').val()); }
                self.setCardType = function () { self.pcc_type($('#card_type option:selected').val()); }
                self.setSaleStatus = function () { self.sale_status($('#sale_status option:selected').val()); }

                self.formSubmit = function () {

                    if (self.isNew()) {
                        self.insertJobOrder();
                    }
                };

                self.submitValidation = () => {
                    var lt_arr_length = self.jbItemList().length;

                    if(lt_arr_length < 1) {
                        sweetAlertMsg('Required!',"Please add items first.",'warning');
                        return false;
                    }

                    for (var i =0; i < lt_arr_length; i++){

                        if(self.jbItemList()[i].item_type()  == 'standard' && (parseFloat(self.jbItemList()[i].base_quantity()) > parseFloat(self.jbItemList()[i].item_aqty()))){

                            let item_name = self.jbItemList()[i].item_code()+"-"+ self.jbItemList()[i].item_name()+"("+ self.jbItemList()[i].item_option_name()+")";
                            sweetAlertMsg('Error!',item_name+"("+parseFloat(self.jbItemList()[i].item_aqty())+")"+ "Don't have sufficient System Qty.",'warning');
                            return false;
                        }
                    }

                    self.itemValidateCls('');
                    let same_it_qty = 0;
                    $.each(self.jbItemList(), function (i, item) {

                        if(i > 0 && self.jbItemList()[i-1].item_code() == item.item_code()) {
                            same_it_qty += parseFloat(self.jbItemList()[i-1].base_quantity());
                            same_it_qty += parseFloat(item.base_quantity());

                            if(same_it_qty > item.item_aqty()) {

                                console.log(item.item_code()+" / "+same_it_qty)
                                item.validateClass1('bg-warning');
                                self.itemValidateCls('bg-warning');
                                sweetAlertMsg('Error!','The item '+item.item_name()+"("+parseFloat(item.item_aqty())+"), "+ "don't have sufficient System Qty.",'warning');
                                return false;
                            }
                        }
                    })
                    return true;
                }

                self.insertJobOrder = function () {
                    if(!self.submitValidation()){ return; }
                    if(self.itemValidateCls() == "bg-warning") return;

                    self.payment_status($('#paid_status option:selected').val());
                    self.sale_status($('#sale_status option:selected').val());
                    $.ajax({
                        url: '{{route('invoice.create.submit')}}',
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "jo_id": self.jo_id(),
                            "wo_order_serial": self.wo_serial(),
                            "inv_date": self.inv_date(),
                            "customer_id": self.woCustomerId(),
                            "sup_charge_amnt": self.sup_charge_amnt(),
                            "shipping_amnt": self.shipping_amnt(),
                            "order_discount": self.discount_amnt(),
                            "order_tax": '',
                            "fee_amnt": self.fee_amnt(),
                            "diag_fee_amnt": self.diag_fee_amnt(),

                            "paid_by": self.paying_by(),
                            "amount_paid": self.amount_paid(),
                            "gift_card_no": self.gift_card_no(),

                            "payment_reference_no": self.payment_reference_no(),
                            "amount_paying": self.amount_paying(),
                            "cheque_no": self.cheque_no(),
                            "pcc_no": self.pcc_no(),
                            "pcc_holder": self.pcc_holder(),
                            "pcc_month": self.pcc_month(),
                            "pcc_year": self.pcc_year(),
                            "pcc_type": self.pcc_type(),
                            "payment_note": self.payment_note(),
                            "sale_status": self.sale_status(),
                            "payment_status": self.payment_status(),

                            "grid_task_rows": ko.toJSON(self.laborTaskList),
                            "grid_item_rows": ko.toJSON(self.jbItemList),
                            "serv_pkg_rows": ko.toJSON(self.servicePkgList),
                        },
                        beforeSend: (jqXHR, settings) => { StartLoading(); },
                        success: (responseData) => {
                            StopLoading();
                            self.clearForm();
                            sweetAlertMsg('Success', responseData.message, 'success')
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
                }

                self.clearPayFields = () => {
                    self.amount_paid(0);
                    self.gift_card_no("");
                    self.payment_reference_no("");
                    self.amount_paying(0);
                    self.cheque_no("");
                    self.pcc_no("");
                    self.pcc_holder("");
                    self.pcc_month("");
                    self.pcc_year("");
                    self.pcc_type("");
                    self.payment_note("");
                    self.sale_status("");
                    self.payment_status("");

                    self.paying_by("");
                    document.getElementById('sale_status').value = '';
                    document.getElementById('paid_status').value = '';
                }

                self.clearForm = function () {

                    self.wo_serial("");
                    self.jo_serial("");
                    self.jo_date("");
                    self.wo_date("");
                    self.woRemark("");

                    self.laborTaskList.removeAll();
                    self.jbItemList.removeAll();
                    self.servicePkgList.removeAll();

                    self.tot_labor_tsk_amnt(0);
                    self.tot_parts_amnt(0);
                    self.tot_other_amnt(0);
                    self.sub_tot_amnt("");
                    self.tax1_amnt(0);
                    self.sup_charge_amnt(0);
                    self.shipping_amnt(0);
                    self.tot_amnt(0);
                    self.discount_amnt(0);
                    self.fee_amnt(0);
                    self.diag_fee_amnt(0);
                    self.grand_tot_amnt(0);
                    self.clearPayFields();

                    APP_selectEmpty('id_wo_serial_drop');
                    APP_selectEmpty('ID_cus_name');
                    APP_selectEmpty('ID_vehi_name');
                    self.calculateLaborTaskTblTot();
                };
            }
        }
    </script>
@endpush
