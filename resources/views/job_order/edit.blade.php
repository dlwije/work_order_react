@extends('template.master')
@section('title','Edit Job order')

@push('css')
    <link href="{{ asset('assets/plugins/DateTimePicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/jquery-ui/jquery-ui.min.css')}}">
@endpush

@section('content')
    <div class="container-fluid" id="jobOrder_edit_view">
        <div class="row">
            <div class="col-md-12">
                @include('errors.errors-forms')
            </div>

            <div class="col-md-12">
                <!-- Default box -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Edit Job order</h3>
                        <div class="card-tools">
                            <a class="btn btn-sm btn-secondary no-shadow" href="{{ route('joborder.list') }}">  <i class="fas fa-hand-o-left"></i>Go Back</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">WO No</label>
                                    <select name="wo_serial" id="id_wo_serial_drop" class="form-control" disabled></select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Job Type</label>
                                    <select name="wo_serial" id="id_job_type_drop" class="form-control" data-bind="value: jo_type">
                                        <option value=""></option>
                                        <option value="os">On Site</option>
                                        <option value="op">On Premises</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">JO No</label>
                                    <span class="form-control" data-bind="html: jo_serial" readonly></span>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">JO Date</label>
                                    <input type="text" class="form-control" id="id_jodate" data-bind="value: jo_date" readonly>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Customer</label>
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
                            <!-- Remarks -->
                            <div class="col-md-12">
                                <label class="control-label">Remarks</label>
                                <textarea class="form-control" rows="2" data-bind="value: woRemark" readonly></textarea>
                            </div>
                        </div>

                        <!-- Work order Insurance and all 3 data -->
                        <div id="accordion" class="mt-2">
                            {{--data-bind="style: { display: jobOrder.ivm.mod_ins_comp_name() == '' ? 'none' : '' }"
                            data-bind="style: { display: jobOrder.ivm.mod_cont_comp_name() == '' ? 'none' : '' }"
                            data-bind="style: { display: jobOrder.ivm.mod_warr_comp_name() == '' ? 'none' : '' }"--}}
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
                                            Vehicle Insurance
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <!-- Vehicle Insurance details -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Company Name</label>
                                                    <input type="text" class="form-control" id="id_mod_ins_comp_name" data-bind="value: mod_ins_comp_name" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Policy No</label>
                                                    <input type="text" class="form-control" id="id_mod_ins_policy_no" data-bind="value: mod_ins_policy_no" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Expire Date</label>
                                                    <input type="text" class="form-control" id="id_mod_ins_expire_date" data-bind="value: mod_ins_expire_date" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Phone Number</label>
                                                    <input type="text" class="form-control" id="id_mod_ins_phone_no" data-bind="value: mod_ins_phone_no" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                                            Vehicle Contract
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <!-- Vehicle Contract details -->
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Company Name</label>
                                                    <input type="text" class="form-control" id="id_mod_cont_comp_name" data-bind="value: mod_cont_comp_name" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Contract No</label>
                                                    <input type="text" class="form-control" id="id_mod_cont_policy_no" data-bind="value: mod_cont_policy_no" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Expire Date</label>
                                                    <input type="text" class="form-control" id="id_mod_cont_expire_date" data-bind="value: mod_cont_expire_date" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Phone Number</label>
                                                    <input type="text" class="form-control" id="id_mod_cont_phone_no" data-bind="value: mod_cont_phone_no" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Mileage</label>
                                                    <input type="text" class="form-control" id="id_mod_cont_mileage" data-bind="value: mod_cont_mileage" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
                                            Vehicle Warranty
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="collapse" data-parent="#accordion">
                                    <div class="card-body">
                                        <!-- Vehicle Warranty details -->
                                        <h5 class="card-subtitle fa-">Vehicle Warranty</h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Company Name</label>
                                                    <input type="text" class="form-control" id="id_mod_warr_comp_name" data-bind="value: mod_warr_comp_name" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Warranty No</label>
                                                    <input type="text" class="form-control" id="id_mod_warr_warranty_no" data-bind="value: mod_warr_warranty_no" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Date of Purchase</label>
                                                    <input type="text" class="form-control" id="id_mod_warr_dop" data-bind="value: mod_warr_dop" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Expire Date</label>
                                                    <input type="text" class="form-control" id="id_mod_warr_expire_date" data-bind="value: mod_warr_expire_date" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="control-label">Phone Number</label>
                                                    <input type="text" class="form-control" id="id_mod_warr_phone_no" data-bind="value: mod_warr_phone_no" readonly>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Labor Task Table -->
                        <div class="callout callout-info">

                            <!-- Start: Labor task Table -->
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
                                                <th class="text-center" style="">
                                                    <button type="button" class="btn btn-primary"
                                                            onclick="jobOrder.ivm.showLaborTaskListMod()"><i
                                                            class="fas fa-plus"></i></button>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody data-bind="foreach: laborTaskList">
                                            <tr>
                                                <td data-bind="text: col1" class="text-center"></td>
                                                <td class="text-center">
                                                    <input type="text" class="form-control text-center"
                                                           data-bind="value: col3, event: { blur: $parent.calculateTblLineTotal.bind($data, $index()) }">
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" class="form-control text-center"
                                                           data-bind="value: col4">
                                                </td>
                                                <td class="">
                                                    <input type="text" class="form-control text-right"
                                                           data-bind="value: col5, event: { blur: $parent.calculateTblLineTotal.bind($data, $index()) }">
                                                </td>
                                                <td class="">
                                                    <input type="text" class="form-control text-right"
                                                           data-bind="value: col6">
                                                </td>
                                                <td data-bind="text: col7" class="text-right ">DisAmount</td>
                                                <td class="text-center"><i class="fa fa-trash-alt"
                                                                           style="cursor: pointer;"
                                                                           data-toggle="tooltip" data-placement="top"
                                                                           title="Remove this row"
                                                                           data-bind="click: $parent.removeGridRow.bind($data, $index())"></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" style="padding: 0" data-toggle="tooltip"
                                                    data-placement="bottom" title="Edit task description">
                                                    <textarea rows="1" class="form-control"
                                                              data-bind="value: col2"></textarea>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- End: Labor task Table -->
                            <div class="row mt-3">
                                <div class="offset-md-10 col-md-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control text-right"
                                               data-bind="value: tot_labor_tsk_amnt" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End:labor task table -->

                        <!-- Start:service packages table -->
                        <div class="callout border-primary">

                            <!-- Start: Service package Table -->
                            <div class="row">
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
                                                <th class="text-center" style="">
                                                    <button type="button" class="btn btn-primary" onclick="jobOrder.ivm.showServicePkgListMod()"><i class="fas fa-plus"></i></button>
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
                                                <td class="text-center">
                                                    <i class="fa fa-trash-alt" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Remove this row" data-bind="click: $parent.removeServGridRow.bind($data, $index())"></i>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- End: Service package Table -->
                            <div class="row mt-3">
                                <div class="offset-md-10 col-md-2">
                                    <div class="form-group">
                                        <input type="text" class="form-control text-right"
                                               data-bind="value: tot_serv_pkg_amnt" readonly disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End:service packages table -->

                        <!-- Start:Inventory item adding area -->
                        <div class="callout callout-success">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="" style="background-color: #f6f6f6;">
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
                            </div>

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
                                            <tr data-bind="attr: { data_item_id: item_id, id: 'row_'+item_id(), class: validateClass1(), title: item_row_tooltip() }" data-toggle="tooltip" data-placement="top">
                                                <td class="text-center">
                                                    <span data-bind="html: item_code"></span> -
                                                    <span data-bind="html: item_name"></span>
                                                    <span data-bind="style: { display: item_option_name() == 0 ? 'none' : '' }">
                                                    (<span data-bind="html: item_option_name"></span>)
                                                </span>
                                                    <span style="cursor: pointer;" class="fa-pull-right fas fa-edit" data-bind="style: { display: item_status() == 'serv' ? 'none' : '' },event: { click: $parent.editItemRow.bind($data, $index()) }"></span>
                                                </td>
                                                <td class="text-right">
                                                    <span data-bind="style: { display: item_status() == 'serv' ? 'none' : '' }, html: item_grid_price"></span>
                                                    <span data-bind="style: { display: item_status() == 'serv' ? '' : 'none' },html: 0.00"></span>
                                                </td>
                                                <td class="text-center">
                                                    <span data-bind="html: item_qty,style: { display: item_status() == 'serv' ? '' : 'none' }"></span>
                                                    <input type="text" class="form-control rquantity text-center" data-bind="value: item_qty, style: { display: item_status() == 'serv' ? 'none' : '' }, event: { blur: $parent.calculateRQuantity.bind($data, $index()), click: $parent.setOldItemQty.bind($data, $index()) }">
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
                                                    <i class="fa fa-trash-alt" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Remove this row" data-bind="style: { display: item_status() == 'serv' ? 'none' : '' },click: $parent.removeItemGridRow.bind($data, $index())"></i>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="offset-md-4 col-md-2">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-md-5 col-form-label">Total Parts: <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Item prices which are belongs to a specific Service Package will not added to this amount."></i></label>
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
                                <div class="offset-md-4 col-md-2">
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

                                <button type="button" class="btn btn-success btn-sm float-right" onclick="jobOrder.ivm.formSubmit();"><i class="fas fa-save"></i> Submit</button>
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

        <!--Service package List Table model -->
        <div class="modal fade" id="serv_pkg_mod">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Select service packages to the job order</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="id_serv_pkg_tbl" class="table table-bordered table-hover responsive" style="width: 100%;">
                                        <thead>
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Price</th>
                                            <th>Vehicle Type</th>
                                            <th>Created On</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="id_serv_pkg_tbody">
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
        <!-- /.End serve pkg -->

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

    </div>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/DateTimePicker/jquery.datetimepicker.full.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            jobOrder.init();
            $('#labor_task_mod').on('hide.bs.modal', function () {
                var table = $('#id_lbr_task_tbl').DataTable(); table.destroy();
                $('#id_lbr_task_tbl').find('tr:eq(1)').remove();
            });
            $('#serv_pkg_mod').on('hide.bs.modal', function () {
                var table = $('#id_serv_pkg_tbl').DataTable().destroy();
                $('#id_serv_pkg_tbl').find('tr:eq(1)').remove();
            });
        });
        jobOrder = {
            ivm: null,
            init: function (){
                jobOrder.ivm = new jobOrder.jobOrderViewModel();
                ko.applyBindings(jobOrder.ivm,$('#jobOrder_edit_view')[0]);
                jobOrder.ivm.designInitialize();
            },
            jobOrderViewModel: function (){

                var self = this;

                slitems = {};
                self.isNew = ko.observable(false);
                self.itemID = ko.observable("{{ $editData->id }}");

                self.default_currenty = ko.observable(site.settings.default_currency);
                self.is_product_discount = ko.observable(site.settings.product_discount);
                self.is_tax1 = ko.observable(site.settings.tax1);
                self.is_product_serial = ko.observable(site.settings.product_serial);

                self.wo_serial = ko.observable("{{ $editData->wo_order_id }}");
                self.jo_type = ko.observable("{{ $editData->job_type }}");
                self.jo_serial = ko.observable("{{ $editData->serial_no }}");
                self.jo_date = ko.observable("{{ $editData->jo_date }}");
                self.woCustomer = ko.observable("");
                self.woVehicle = ko.observable("");
                self.vehicleTypeId = ko.observable("");
                self.woRemark = ko.observable("");

                //vehicle Insurance variables
                self.mod_ins_comp_name = ko.observable("");
                self.mod_ins_policy_no = ko.observable("");
                self.mod_ins_expire_date = ko.observable("");
                self.mod_ins_phone_no = ko.observable("");

                //service contract variables
                self.mod_cont_comp_name = ko.observable("");
                self.mod_cont_policy_no = ko.observable("");
                self.mod_cont_expire_date = ko.observable("");
                self.mod_cont_phone_no = ko.observable("");
                self.mod_cont_mileage = ko.observable("");

                //vehicle warranty variables
                self.mod_warr_comp_name = ko.observable("");
                self.mod_warr_warranty_no = ko.observable("");
                self.mod_warr_dop = ko.observable("");
                self.mod_warr_expire_date = ko.observable("");
                self.mod_warr_phone_no = ko.observable("");

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
                self.deleteLaborTaskList = ko.observableArray();

                //inventory item adding variables
                self.jbItemList = ko.observableArray();
                self.deleteJbItemList = ko.observableArray();

                /** service package variable */
                self.service_pkg_id = ko.observable("");
                self.service_pkg_name = ko.observable("");
                self.service_pkg_descript = ko.observable("");
                self.service_pkg_v_type = ko.observable("");
                self.service_pkg_price = ko.observable("");
                self.service_pkg_tot = ko.observable("");

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

                self.designInitialize = () => {
                    select2Dropdown('id_labor_task_drop','{{ route('labortask.drop.list') }}')
                    select2Dropdown('id_wo_serial_drop','{{ route('workorder.serialno.drop.list') }}','{{ route('workorder.serialno.byid.drop.list') }}', self.wo_serial())
                    self.setJobTaskList();
                    self.getWODetails();

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

                self.getWODetails = function () {
                    $.ajax({
                        url: "{{ route('workorder.data') }}",
                        dataType: 'json',
                        type: 'get',
                        contentType: 'application/x-www-form-urlencoded',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            item_id: self.wo_serial(),
                        },
                        beforeSend: function (jqXHR, settings) {

                            StartLoading();
                        },
                        success: function( data, textStatus, jQxhr ){

                            StopLoading();
                            if(data.status){
                                self.woCustomer(data.data[0].customer_id);
                                self.woVehicle(data.data[0].vehicle_id);
                                self.vehicleTypeId(data.data[0].vehicle_type_id);
                                self.woRemark(data.data[0].reported_defect);
                                select2Dropdown('ID_cus_name', '{{ route('customer.drop.list') }}', '{{ route('customer.byid.drop.list') }}', self.woCustomer());
                                select2Dropdown('ID_vehi_name', '{{ route('customer.vehicle.drop.list') }}', '{{ route('customer.vehicle.byid.drop.list') }}', self.woVehicle(), self.woCustomer());

                                //set vehicle ins, contract, warranty
                                self.mod_ins_comp_name(data.data[0].ins_company_name);
                                self.mod_ins_policy_no(data.data[0].policy_no);
                                self.mod_ins_expire_date(data.data[0].ins_expire_date);
                                self.mod_ins_phone_no(data.data[0].ins_phone_number);

                                self.mod_cont_comp_name(data.data[0].con_company_name);
                                self.mod_cont_policy_no(data.data[0].contract_no);
                                self.mod_cont_expire_date(data.data[0].con_expire_date);
                                self.mod_cont_phone_no(data.data[0].con_phone_number);
                                self.mod_cont_mileage(data.data[0].mileage);

                                self.mod_warr_comp_name(data.data[0].war_company_name);
                                self.mod_warr_warranty_no(data.data[0].warranty_no);
                                self.mod_warr_dop(data.data[0].date_of_purchase);
                                self.mod_warr_expire_date(data.data[0].expire_date);
                                self.mod_warr_phone_no(data.data[0].phone_number);
                            }else
                                CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                        },
                        error: function( jqXhr, textStatus, errorThrown ){
                            StopLoading();
                        }
                    });
                }

                self.setJobTaskList = () => {
                    $.ajax({
                        url: "{{ route('joborder.edit.data') }}",
                        dataType: 'json',
                        type: 'get',
                        contentType: 'application/x-www-form-urlencoded',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            emp_id: self.itemID(),
                        },
                        beforeSend:  (jqXHR, settings) =>{ StartLoading(); },
                        success: ( data, textStatus, jQxhr ) =>{
                            StopLoading();
                            if(data.status){
                                $.each(data.data.serv_pkg_data, (i,item) => {
                                    self.servicePkgList.push(new gridServPkgListRows(item));
                                })

                                $.each(data.data.task_data, (i,item) => {
                                    self.laborTaskList.push(new gridLaborTaskListRows(item));
                                })

                                $.each(data.data.item_data, (i,item) => {
                                    self.jbItemList.push(new gridItemListRows(item, "up"));
                                })
                                $('[data-toggle="tooltip"]').tooltip()
                                self.calculateLaborTaskTblTot("up");
                                self.calculateServPkgTblTot();
                            }else
                                sweetAlertMsg('Submission Error!','Form submission error.','warning');
                        },
                        error: ( jqXhr, textStatus, errorThrown ) =>{
                        }
                    });
                }

                /** labor task adding function **/
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

                self.getTaskDetails = (id) => {
                    try {
                        self.task_drop_id(id);

                        if(typeof self.task_drop_id() === "undefined") return;

                        StartLoading();

                        $.ajax({
                            url: '{{route('labortask.single.detail')}}',
                            type: 'GET',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "ItemId": self.task_drop_id()
                            },
                            beforeSend: (jqXHR, settings) => { StartLoading(); },
                            success: (result) => {
                                StopLoading();

                                self.task_hours(0);
                                if (result.count > 0){

                                    self.task_drop_name(result.data[0].task_name);
                                    self.task_description(result.data[0].task_description);
                                    self.task_hours(result.data[0].task_hour);
                                    self.task_hour_rates(accounting.formatNumber(result.data[0].normal_rate,2));

                                    self.calculateLineTotal();
                                    self.laborTaskList.push(new gridLaborTaskListRows());
                                    self.calculateLaborTaskTblTot();

                                    self.clearTableFields();
                                }
                            },
                            error: (jqXHR, textStatus, errorThrown) => {
                                StopLoading();

                                if(jqXHR.status == 403){
                                    sweetAlertMsg('Permission Error','Users does not have the right permissions.','warning');
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
                    }catch (e){ }
                }

                self.calculateLaborTaskTblTot = (formState) => {

                    var lt_arr_length = self.laborTaskList().length;
                    var subtotal = 0;

                    for (var i = 0; i < lt_arr_length; i++) {

                        subtotal += parseFloat(self.laborTaskList()[i].col7().replace(/[^0-9-.]/g, ''));
                    }
                    self.tot_labor_tsk_amnt(accounting.formatNumber(subtotal, 2))
                    self.calculatePartsTblTot(formState);
                }

                self.setWOSerialNoId = () => { self.wo_serial(getSelect2Val('id_wo_serial_drop')); }

                self.calculateLineTotal = () => {
                    if(isNaN(self.task_hours())) self.task_hours(0);
                    var amount = (self.task_hours() * parseFloat(self.task_hour_rates().replace(/[^0-9-.]/g, '')));
                    self.task_labor_total(accounting.formatNumber(amount,2));
                }

                self.calculateTblLineTotal = (indexNo, rowData) => {
                    if(isNaN(rowData.col3())) rowData.col3(0);
                    var amount = (rowData.col3() * parseFloat(rowData.col5().replace(/[^0-9-.]/g, '')));
                    rowData.col7(accounting.formatNumber(amount,2));
                    self.calculateLaborTaskTblTot();
                }

                let gridLaborTaskListRows = function (obj) {
                    let item = this;

                    if(typeof obj === "undefined"){

                        item.col1 = (typeof self.task_drop_name() === "undefined" || self.task_drop_name() == null) ? ko.observable("") : ko.observable(self.task_drop_name());
                        item.col2 = ko.observable("");
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

                self.clearTableFields = () => {
                    self.task_drop_id("");
                    self.task_drop_name("");
                    self.task_description("");
                    self.task_hours("");
                    self.task_act_hours("");
                    self.task_hour_rates("");
                    self.task_labor_cost("");
                    self.task_labor_total("");
                }

                self.removeGridRow = (indexNo, rowData) => {
                    self.laborTaskList.remove(rowData);
                    if(rowData.col9() && rowData.col9()!== "") {
                        self.deleteLaborTaskList.push(rowData.col9())
                    }

                    self.calculateLaborTaskTblTot();
                }

                /** start of inventory items adding */
                self.clearAutoCompleteInput = () => {
                    $('#add_item').focus();
                    $('#add_item').removeClass('ui-autocomplete-loading');
                    $('#add_item').val('');
                }

                self.removeItemGridRow = (indexNo, rowData) => {
                    item_id = rowData.item_id()
                    delete slitems[item_id];
                    if (slitems.hasOwnProperty(item_id)) {
                    } else {
                        localStorage.setItem('slitems', JSON.stringify(slitems));
                    }

                    self.jbItemList.remove(rowData);
                    if(rowData.db_item_id() && rowData.db_item_id() !== ""){
                        self.deleteJbItemList.push(rowData.db_item_id());
                    }
                    self.calculatePartsTblTot();
                }

                //service package functions
                self.showServicePkgListMod = () => {

                    if (!self.woCustomer()) {
                        sweetAlertMsg('Info', '{{ __('messages.select_above') }}', 'warning')
                        return;
                    }

                    if ($.fn.DataTable.isDataTable('#id_serv_pkg_tbl')) {
                        $('#id_serv_pkg_tbl').DataTable().destroy();
                    }

                    // $('#id_lbr_task_tbl tbody').empty();

                    $('#id_serv_pkg_tbl thead tr').clone(true).appendTo('#id_serv_pkg_tbl thead');
                    $('#id_serv_pkg_tbl thead tr:eq(1) th').each(function (i) {
                        if (i != (4)) {
                            var title = $(this).text();
                            if (i == 3)
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
                    table = $('#id_serv_pkg_tbl').DataTable({
                        orderCellsTop: true,
                        fixedHeader: true,
                        processing: true,
                        serverSide: true,
                        ajax: "/service-package-vtype-data-list/"+self.vehicleTypeId(),
                        columns: [

                            {data: 'service_name', name: 'service_name'},
                            {data: 'price', name: 'price', sClass: 'text-right'},
                            {data: 'type_name', name: 'type_name'},
                            {data: 'created_at', name: 'created_at'},
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
                            $('#serv_pkg_mod').modal('show');
                        }
                    });
                }

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
                                    self.service_pkg_name(result.data.head_data.service_name);
                                    self.service_pkg_descript(result.data.head_data.description);
                                    self.service_pkg_v_type(result.data.head_data.type_name);
                                    self.service_pkg_price(result.data.head_data.price);
                                    self.service_pkg_tot(result.data.head_data.price);
                                    self.servicePkgList.push(new gridServPkgListRows());

                                    $.each(result.data.item_data, function (i, item) {
                                        self.jbItemList.push(new gridItemListRows(item,'serv'));
                                    })
                                    $('[data-toggle="tooltip"]').tooltip()
                                    self.calculateServPkgTblTot();
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
                        console.log(e)
                    }
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

                self.removeServGridRow = (indexNo, rowData) => {

                    const filteredServItems = self.jbItemList().filter(res=>res.serv_pkg_id() == rowData.col6()).map(ele=>ele);
                    $.each(filteredServItems, function (i, item) {
                        self.jbItemList.remove(item);
                    })
                    self.servicePkgList.remove(rowData);
                    if(rowData.col7() && rowData.col7()!== "") {
                        self.deleteServicePkgList.push(rowData.col7())
                    }
                    self.calculateServPkgTblTot();
                }
                self.calculateServPkgTblTot = () => {

                    var lt_arr_length = self.servicePkgList().length;
                    var subtotal = 0;

                    for (var i = 0; i < lt_arr_length; i++) {

                        subtotal += parseFloat(self.servicePkgList()[i].col5().replace(/[^0-9-.]/g, ''));
                    }
                    self.tot_serv_pkg_amnt(accounting.formatNumber(subtotal, 2))
                    self.calculatePartsTblTot();
                }
                //end

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

                    let base_quantity = self.mod_base_quantity();
                    let base_unit_price = self.mod_base_unit_price();
                    let unit_price = isNaN(self.mod_real_unit_price()) ? 0 : self.mod_real_unit_price();
                    let item_price = self.mod_item_price();
                    let item_qty = self.mod_pquantity();
                    let product_discount = 0, product_tax = 0, total = 0;

                    console.log("item_price"+item_price);
                    console.log("self.mod_poption_price()"+self.mod_poption_price());
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
                    console.log("ds"+ds);
                    console.log("item_discount"+item_discount);

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

                self.calculatePartsTblTot = (formState) => {

                    var lt_arr_length = self.jbItemList().length;
                    var subtotal = 0;

                    for (var i = 0; i < lt_arr_length; i++) {

                        if(self.jbItemList()[i].item_status() != "serv" && self.jbItemList()[i].serv_pkg_id() == ""){

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
                /** End of inventory items adding **/

                self.formSubmit = function () {

                    if(!self.isNew()){ self.submitJobOrder(); }
                };

                self.submitJobOrder = function () {

                    $.ajax({
                        url: '{{route('joborder.edit.submit')}}',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "item_id": self.itemID(),
                            "jo_type": self.jo_type(),
                            "jo_date": self.jo_date(),
                            "vehi_type_id": self.vehicleTypeId(),
                            "grid_task_rows": ko.toJSON(self.laborTaskList),
                            "delete_grid_task_rows": ko.toJSON(self.deleteLaborTaskList),
                            "grid_item_rows": ko.toJSON(self.jbItemList),
                            "delete_grid_item_rows": ko.toJSON(self.deleteJbItemList),
                            "serv_pkg_rows": ko.toJSON(self.servicePkgList),
                            "delete_serv_pkg_rows": ko.toJSON(self.deleteServicePkgList),
                        },
                        beforeSend: (jqXHR, settings) => { StartLoading(); },
                        success: (responseData) => {
                            StopLoading();

                            setTimeout(function () {
                                location.href = "{{ route('joborder.list') }}";
                            }, 200)
                            sweetAlertMsg('Success',responseData.message,'success')
                        },
                        error: (jqXHR, textStatus, errorThrown) => {
                            StopLoading();

                            if(jqXHR.status == 403){
                                sweetAlertMsg('Permission Error','Users does not have the right permissions.','warning');
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

                    self.wo_serial("");self.jo_serial("");self.jo_date("");self.laborTaskList.removeAll();self.deleteLaborTaskList.removeAll();APP_selectEmpty('id_wo_serial_drop');
                };
            }
        }
    </script>
@endpush
