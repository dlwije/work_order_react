@extends('template.master')
@section('title','New Workorder')

@section('content')
    <link href="{{ asset('assets/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/DateTimePicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
    <style>
        .input-group-text{
            padding: .3rem .3rem .3rem .5rem !important;
        }
        .btn-group-sm>.btn, .btn-sm {
            padding: .25rem .7rem;
        }
    </style>
    <div class="container-fluid" id="wo_add_view">
        <div class="row">

            <div class="col-md-12">
                @include('errors.errors-forms')
            </div>

            <div class="col-md-12">
                <!-- Default box -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Create Work Order</h3>
                        <div class="card-tools">
                            <a class="btn btn-sm btn-secondary no-shadow" href="{{ route('workorder.list') }}">  <i class="fas fa-hand-o-left"></i>Go Back</a>
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <!-- Wo Number -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">WO No</label>
                                    <span class="form-control" data-bind="html:WONo" readonly=""></span>
                                </div>
                            </div>
                            <!-- Customer -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Customer Name</label><span style="color: red;">*</span>
                                    <div class="input-group">
                                        <select class="form-control " id="ID_cus_name" onchange="workOrder.ivm.setWoCustomerId()">
                                            <option></option>

                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Add new customer." onclick="workOrder.ivm.showCustomerAddNewModal()"><i class="fas fa-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Vehicle -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Vehicle</label><span style="color: red;">*</span>
                                    <div class="input-group">
                                        <select class="form-control " id="ID_vehi_name" onchange="workOrder.ivm.setWoVehicleId()">
                                            <option></option>

                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Add new vehicle." onclick="workOrder.ivm.showVehicleAddNewModal()"><i class="fas fa-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Wo Date -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">WO Date</label>
                                    <input type="text" class="form-control" id="id_wodate" data-bind="value: woDate" readonly>
                                </div>
                            </div>
                            <!-- Appointment -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="control-label">Appointment</label>
                                    <input type="text" class="form-control" id="id_appointment" data-bind="value: woAppointment" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Insurance -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Insurance</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" data-bind="value: insurance, enable: is_enableInsurance">
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-toggle="tooltip" data-placement="top" title="Please tick this if have Insurance.">
                                                <div class="" id="id_insurance_div" >
                                                    <input type="checkbox" id="id_insurance_chk"  data-bind="checked:is_insurance">
                                                    <label for="id_insurance_chk" class="custom_check"></label>
                                                </div>
                                            </span>
                                            <span class="input-group btn_group">
                                                <button type="button" id="id_btn_insu" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Add insurance details." data-bind="enable: is_enableInsuranceBtn" onclick="workOrder.ivm.showVehicleInsuranceAddNewModal()"><i class="fas fa-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Contract -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Contract</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" data-bind="value: contract, enable: is_enableContract">
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-toggle="tooltip" data-placement="top" title="Please tick this if have Service Contract.">
                                                <div class="" id="id_contract_div">
                                                    <input type="checkbox" id="id_contract_chk" data-bind="checked:is_contract">
                                                    <label for="id_contract_chk" class="custom_check"></label>
                                                </div>
                                            </span>
                                            <span class="input-group btn_group">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Add service contract details." data-bind="enable: is_enableContractBtn" onclick="workOrder.ivm.showServiceContractAddNewModal()"><i class="fas fa-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Warranty -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Warranty</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" data-bind="value: warranty, enable: is_enableWarranty">
                                        <div class="input-group-append">
                                            <span class="input-group-text" data-toggle="tooltip" data-placement="top" title="Please tick this if have Warranty.">
                                                <div class="" id="id_warranty_div">
                                                    <input type="checkbox" id="id_warranty_chk" data-bind="checked:is_warranty">
                                                    <label for="id_warranty_chk" class="custom_check"></label>
                                                </div>
                                            </span>
                                            <span class="input-group btn_group">
                                                <button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Add vehicle warranty details." data-bind="enable: is_enableWarrantyBtn" onclick="workOrder.ivm.showVehicleWarrantyAddNewModal()"><i class="fas fa-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Current Mileage -->
                            <div class="col-md-3" id="mileage_div">
                                <div class="form-group">
                                    <label class="control-label">Current Mileage</label>
                                    <input type="text" class="form-control" data-bind="value: current_mileage">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Remarks -->
                            <div class="col-md-12">
                                <label class="control-label">Remarks</label>
                                <textarea class="form-control" rows="2" data-bind="value: woRemark"></textarea>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="">
                            <div class="form-group">
                                <button type="button" class="btn btn-success btn-sm float-right" onclick="workOrder.ivm.formSubmit();" id="wo_submit"><i class="fas fa-save"></i> Submit</button>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card -->
            </div>
        </div>

        <!--Add Customer model -->
        <div class="modal fade" id="cus_add_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Customer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Customer Group</label>
                                    <select class="form-control" id="id_mod_cus_group_drop" onchange="workOrder.ivm.setModCustomerGroupId()">

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Price Group</label>
                                    <select class="form-control" id="id_mod_price_group_drop" onchange="workOrder.ivm.setModPriceGroupId()">

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Company</label>
                                    <input type="text" class="form-control" id="id_mod_company" data-bind="value: mod_cus_company">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Postal Code</label>
                                    <input type="text" class="form-control" id="id_mod_postal_code" data-bind="value: mod_cus_postal_code">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Customer First Name</label><span style="color: red;">*</span>
                                    <input type="text" class="form-control" id="id_mod_cus_fname" data-bind="value: mod_cus_fname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Customer Last Name</label>
                                    <input type="text" class="form-control" id="id_mod_cus_lname" data-bind="value: mod_cus_lname">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Spouse First Name</label>
                                    <input type="text" class="form-control" id="id_mod_spouse_fname" data-bind="value: mod_cus_spouse_fname">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Spouse Last Name</label>
                                    <input type="text" class="form-control" id="id_mod_spouse_lname" data-bind="value: mod_cus_spouse_lname">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Email Address</label><span style="color: red;">*</span>
                                    <input type="email" class="form-control" id="id_mod_email" data-bind="value: mod_cus_email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Phone</label><span style="color: red;">*</span>
                                    <input type="number" class="form-control" id="id_mod_cus_phone" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==15) return false;" data-bind="value: mod_cus_phone">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Address</label><span style="color: red;">*</span>
                                    <textarea class="form-control" id="id_mod_cus_address" data-bind="value: mod_cus_address"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Country</label>
                                    <div class="input-group">
                                        <select class="form-control " id="ID_mod_country" onchange="workOrder.ivm.setModCountryId()">
                                            <option></option>

                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group">
                                                <button type="button" class="btn btn-primary btn-sm" onclick=""><i class="fas fa-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">State</label>
                                    <div class="input-group">
                                        <select class="form-control " id="ID_mod_state" onchange="workOrder.ivm.setModStateId()">
                                            <option></option>

                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group">
                                                <button type="button" class="btn btn-primary btn-sm" onclick=""><i class="fas fa-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">City</label><span style="color: red;">*</span>
                                    <div class="input-group">
                                        <select class="form-control " id="ID_mod_city" onchange="workOrder.ivm.setModCityId()">
                                            <option></option>

                                        </select>
                                        <div class="input-group-append">
                                            <span class="input-group">
                                                <button type="button" class="btn btn-primary btn-sm" onclick=""><i class="fas fa-plus"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="workOrder.ivm.addNewCustomer()">Submit</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!--Add Vehicle model -->
        <div class="modal fade" id="vehi_add_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Vehicle</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Selected Customer</label><span style="color: red;">*</span>
                                    <select class="form-control " id="ID_mod_cus_name" onchange="" disabled>
                                        <option></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-4">
                                    <label class="control-label">Is Trailer</label>

                                    <input type="checkbox" id="id_tler_or_mh_chk" data-bind="checked:is_trailer">
                                    <label for="id_tler_or_mh_chk" class="custom_check"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Year</label>
                                    <input type="number" class="form-control" id="id_mod_vehi_year" data-bind="value: mod_vehi_year" maxlength="4">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Make</label>
                                    <input type="text" class="form-control" id="id_mod_vehi_make" data-bind="value: mod_vehi_make">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Model</label>
                                    <input type="text" class="form-control" id="id_mod_vehi_model" data-bind="value: mod_vehi_model">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Vehicle Type</label><span style="color: red;">*</span>
                                    <select class="form-control " id="ID_mod_vehi_type" onchange="workOrder.ivm.setModVehiTypeId()">
                                        <option></option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Sub Model</label>
                                    <input type="text" class="form-control" id="id_mod_vehi_submodel" data-bind="value: mod_vehi_submodel">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Body #</label>
                                    <input type="text" class="form-control" id="id_mod_vehi_body" data-bind="value: mod_vehi_body">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Color</label>
                                    <input type="text" class="form-control" id="id_mod_vehi_color" data-bind="value: mod_vehi_color">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">VIN</label>
                                    <input type="text" class="form-control" onKeyPress="if(this.value.length==17) return false;" id="id_mod_vehi_vin" data-bind="value: mod_vehi_vin">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">License</label>
                                    <input type="text" class="form-control" id="id_mod_vehi_license" data-bind="value: mod_vehi_license">
                                </div>
                            </div>
                            <div class="col-md-6" id="id_mod_odometer_div">
                                <div class="form-group">
                                    <label class="control-label">Odometer</label>
                                    <input type="text" class="form-control" id="id_mod_vehi_odometer" data-bind="value: mod_vehi_odometer">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Unit Number</label>
                                    <input type="text" class="form-control" id="id_mod_vehi_unit" data-bind="value: mod_vehi_unit">
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="workOrder.ivm.addNewVehicle()">Submit</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!--Add Insurance model -->
        <div class="modal fade" id="insu_add_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Vehicle Insurance</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Selected Customer</label><span style="color: red;">*</span>
                                    <select class="form-control " id="ID_mod_ins_cus_name" onchange="" disabled>
                                        <option></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Selected Vehicle</label><span style="color: red;">*</span>
                                    <select class="form-control " id="ID_mod_ins_vehi_name" onchange="" disabled>
                                        <option></option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Company Name</label>
                                    <input type="text" class="form-control" id="id_mod_ins_comp_name" data-bind="value: mod_ins_comp_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Policy No</label>
                                    <input type="text" class="form-control" id="id_mod_ins_policy_no" data-bind="value: mod_ins_policy_no">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Expire Date</label>
                                    <input type="text" class="form-control" id="id_mod_ins_expire_date" data-bind="value: mod_ins_expire_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Phone Number</label>
                                    <input type="text" class="form-control" id="id_mod_ins_phone_no" data-bind="value: mod_ins_phone_no">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="workOrder.ivm.setInsuranceName()">Done</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!--Add Contract model -->
        <div class="modal fade" id="cont_add_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Service Contract</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Selected Customer</label><span style="color: red;">*</span>
                                    <select class="form-control " id="ID_mod_cont_cus_name" onchange="" disabled>
                                        <option></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Selected Vehicle</label><span style="color: red;">*</span>
                                    <select class="form-control " id="ID_mod_cont_vehi_name" onchange="" disabled>
                                        <option></option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Company Name</label>
                                    <input type="text" class="form-control" id="id_mod_cont_comp_name" data-bind="value: mod_cont_comp_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Contract No</label>
                                    <input type="text" class="form-control" id="id_mod_cont_policy_no" data-bind="value: mod_cont_policy_no">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Expire Date</label>
                                    <input type="text" class="form-control" id="id_mod_cont_expire_date" data-bind="value: mod_cont_expire_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Phone Number</label>
                                    <input type="text" class="form-control" id="id_mod_cont_phone_no" data-bind="value: mod_cont_phone_no">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Mileage</label>
                                    <input type="text" class="form-control" id="id_mod_cont_mileage" data-bind="value: mod_cont_mileage">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="workOrder.ivm.setContractName()">Done</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!--Add Warranty model -->
        <div class="modal fade" id="warr_add_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Vehicle Warranty</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Selected Customer</label><span style="color: red;">*</span>
                                    <select class="form-control " id="ID_mod_warr_cus_name" onchange="" disabled>
                                        <option></option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Selected Vehicle</label><span style="color: red;">*</span>
                                    <select class="form-control " id="ID_mod_warr_vehi_name" onchange="" disabled>
                                        <option></option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Company Name</label>
                                    <input type="text" class="form-control" id="id_mod_warr_comp_name" data-bind="value: mod_warr_comp_name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Warranty No</label>
                                    <input type="text" class="form-control" id="id_mod_warr_warranty_no" data-bind="value: mod_warr_warranty_no">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Date of Purchase</label>
                                    <input type="text" class="form-control" id="id_mod_warr_dop" data-bind="value: mod_warr_dop">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Expire Date</label>
                                    <input type="text" class="form-control" id="id_mod_warr_expire_date" data-bind="value: mod_warr_expire_date">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Phone Number</label>
                                    <input type="text" class="form-control" id="id_mod_warr_phone_no" data-bind="value: mod_warr_phone_no">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="workOrder.ivm.setWarrantyName()">Done</button>
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
    <script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('assets/plugins/DateTimePicker/jquery.datetimepicker.full.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function () {

            workOrder.init();
        });
        workOrder = {
            ivm: null,
            init: function (){
                workOrder.ivm = new workOrder.workOrderViewModel();
                ko.applyBindings(workOrder.ivm,$('#wo_add_view')[0]);
                workOrder.ivm.designInitialize();
            },
            workOrderViewModel: function (){

                var self = this;

                self.isNew = ko.observable(true);
                self.itemID = ko.observable("");

                self.WONo = ko.observable("");
                self.woCustomer = ko.observable("");
                self.woVehicle = ko.observable("");
                self.woDate = ko.observable("");
                self.woAppointment = ko.observable("");

                self.insurance = ko.observable("");
                self.contract = ko.observable("");
                self.warranty = ko.observable("");

                self.is_insurance = ko.observable(true);
                self.is_contract = ko.observable(false);
                self.is_warranty = ko.observable(false);

                self.is_enableInsurance = ko.observable(self.is_insurance());
                self.is_enableContract = ko.observable(self.is_contract());
                self.is_enableWarranty = ko.observable(self.is_warranty());
                self.is_enableInsuranceBtn = ko.observable(self.is_insurance());
                self.is_enableContractBtn = ko.observable(self.is_contract());
                self.is_enableWarrantyBtn = ko.observable(self.is_warranty());

                self.current_mileage = ko.observable("");
                self.woRemark = ko.observable("");

                //customer Modal variable
                self.mod_cus_group = ko.observable("");
                self.mod_cus_price_group = ko.observable("");
                self.mod_cus_company = ko.observable("");
                self.mod_cus_postal_code = ko.observable("");
                self.mod_cus_fname = ko.observable("");
                self.mod_cus_lname = ko.observable("");
                self.mod_cus_country = ko.observable("");
                self.mod_cus_spouse_fname = ko.observable("");
                self.mod_cus_spouse_lname = ko.observable("");
                self.mod_cus_email = ko.observable("");
                self.mod_cus_phone = ko.observable("");
                self.mod_cus_address = ko.observable("");
                self.mod_cus_state = ko.observable("");
                self.mod_cus_city = ko.observable("");

                //vehicle Model variable
                self.is_trailer = ko.observable(false);
                self.mod_vehi_year = ko.observable("");
                self.mod_vehi_make = ko.observable("");
                self.mod_vehi_model = ko.observable("");
                self.mod_vehi_type = ko.observable("");
                self.mod_vehi_submodel = ko.observable("");
                self.mod_vehi_body = ko.observable("");
                self.mod_vehi_color = ko.observable("");
                self.mod_vehi_vin = ko.observable("");
                self.mod_vehi_license = ko.observable("");
                self.mod_vehi_odometer = ko.observable("");
                self.mod_vehi_unit = ko.observable("");

                //vehicle Insurance Model variables
                self.mod_ins_comp_name = ko.observable("");
                self.mod_ins_policy_no = ko.observable("");
                self.mod_ins_expire_date = ko.observable("");
                self.mod_ins_phone_no = ko.observable("");

                //service contract Model variables
                self.mod_cont_comp_name = ko.observable("");
                self.mod_cont_policy_no = ko.observable("");
                self.mod_cont_expire_date = ko.observable("");
                self.mod_cont_phone_no = ko.observable("");
                self.mod_cont_mileage = ko.observable("");

                //vehicle warranty Model variables
                self.mod_warr_comp_name = ko.observable("");
                self.mod_warr_warranty_no = ko.observable("");
                self.mod_warr_dop = ko.observable("");
                self.mod_warr_expire_date = ko.observable("");
                self.mod_warr_phone_no = ko.observable("");

                /*var MsgHead = CommonMsg.ivm.toastMsgHead;
                var MsgContent = CommonMsg.ivm.toastMsgContent;*/

                self.designInitialize = () => {

                    self.getSerialNo();
                    select2Dropdown('ID_cus_name', '{{ route('customer.drop.list') }}');
                    {{--select2Dropdown('ID_vehi_name', '{{ route('vehicle.drop.list') }}');--}}

                    let id_ins = "#id_insurance_chk";
                    let id_con = "#id_contract_chk";
                    let id_war = "#id_warranty_chk";
                    let id_trailer = "#id_tler_or_mh_chk";

                    $(id_ins).iCheck({checkboxClass: 'icheckbox_square-green',radioClass: 'iradio_square-green',increaseArea: '20%'});
                    $(id_con).iCheck({checkboxClass: 'icheckbox_square-green',radioClass: 'iradio_square-green',increaseArea: '20%'});
                    $(id_war).iCheck({checkboxClass: 'icheckbox_square-green',radioClass: 'iradio_square-green',increaseArea: '20%'});
                    $(id_trailer).iCheck({checkboxClass: 'icheckbox_square-green',radioClass: 'iradio_square-green',increaseArea: '20%'});

                    $(id_ins).on('ifChecked', function () { workOrder.ivm.is_insurance(true);workOrder.ivm.enableInsurance(true); });
                    $(id_ins).on('ifUnchecked', function () { workOrder.ivm.is_insurance(false);workOrder.ivm.enableInsurance(false); });

                    $(id_con).on('ifChecked', function () { workOrder.ivm.is_contract(true);workOrder.ivm.enableContract(true) });
                    $(id_con).on('ifUnchecked', function () { workOrder.ivm.is_contract(false);workOrder.ivm.enableContract(false) });

                    $(id_war).on('ifChecked', function () { workOrder.ivm.is_warranty(true);workOrder.ivm.enableWarranty(true) });
                    $(id_war).on('ifUnchecked', function () { workOrder.ivm.is_warranty(false);workOrder.ivm.enableWarranty(false) });

                    $(id_trailer).on('ifChecked', function () { workOrder.ivm.is_trailer(true); $('#id_mod_odometer_div').attr('style','display:none') });
                    $(id_trailer).on('ifUnchecked', function () { workOrder.ivm.is_trailer(false); $('#id_mod_odometer_div').removeAttr('style') });

                    $('#id_wodate').datetimepicker({ mask:'9999-19-39', format:'Y-m-d' });
                    $('#id_appointment').datetimepicker({ mask:'9999-19-39', format:'Y-m-d' });
                    $('#id_mod_ins_expire_date').datetimepicker({ mask:'9999-19-39', format:'Y-m-d' });
                    $('#id_mod_cont_expire_date').datetimepicker({ mask:'9999-19-39', format:'Y-m-d' });
                    $('#id_mod_warr_dop').datetimepicker({ mask:'9999-19-39', format:'Y-m-d' });
                    $('#id_mod_warr_expire_date').datetimepicker({ mask:'9999-19-39', format:'Y-m-d' });
                    self.getToday();
                }

                self.getToday = function () {

                    let today = new Date();
                    let dd = today.getDate();
                    let mm = today.getMonth()+1; //January is 0!
                    let yyyy = today.getFullYear();

                    if(dd<10) { dd = '0'+dd } if(mm<10) { mm = '0'+mm }
                    today = yyyy + '-' + mm + '-' + dd;
                    self.woDate(today);
                    // self.woAppointment(today);
                };

                self.enableInsurance = (state) => {

                    if(state){ self.is_enableInsurance(true); self.is_enableInsuranceBtn(true);} else { self.is_enableInsurance(false); self.is_enableInsuranceBtn(false);}
                }

                self.enableContract = (state) => {

                    if(state){ self.is_enableContract(true); self.is_enableContractBtn(true); } else{ self.is_enableContract(false); self.is_enableContractBtn(false); }
                }

                self.enableWarranty = (state) => {

                    if(state){ self.is_enableWarranty(true); self.is_enableWarrantyBtn(true); } else{ self.is_enableWarranty(false); self.is_enableWarrantyBtn(false); }
                }

                //customer model functions start
                self.showCustomerAddNewModal = () => {
                    select2Dropdown('id_mod_cus_group_drop','{{ route('customer.group.drop.list') }}')
                    select2Dropdown('id_mod_price_group_drop','{{ route('price.group.drop.list') }}')
                    select2Dropdown('ID_mod_country','{{ route('country.drop.list') }}')
                    select2Dropdown('ID_mod_state','{{ route('state.drop.list') }}')
                    select2Dropdown('ID_mod_city','{{ route('city.drop.list') }}')
                    $('#cus_add_mod').modal('show');
                }

                self.setModCustomerGroupId = () => { self.mod_cus_group(getSelect2Val('id_mod_cus_group_drop')) }
                self.setModPriceGroupId = () => { self.mod_cus_price_group(getSelect2Val('id_mod_price_group_drop')) }
                self.setModCountryId = () => { self.mod_cus_country(getSelect2Val('ID_mod_country')) }
                self.setModStateId = () => { self.mod_cus_state(getSelect2Val('ID_mod_state')) }
                self.setModCityId = () => { self.mod_cus_city(getSelect2Val('ID_mod_city')) }

                self.addNewCustomer = () => {

                    $.ajax({
                        url: '{{route('customer.create.submit')}}',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "customer_group_id": self.mod_cus_group(),
                            "price_group_id": self.mod_cus_price_group(),
                            "company": self.mod_cus_company(),
                            "postal_code": self.mod_cus_postal_code(),
                            "first_name": self.mod_cus_fname(),
                            "last_name": self.mod_cus_lname(),
                            "spouse_first_name": self.mod_cus_spouse_fname(),
                            "spouse_last_name": self.mod_cus_spouse_lname(),
                            "email": self.mod_cus_email(),
                            "phone": self.mod_cus_phone(),
                            "address": self.mod_cus_address(),
                            "country_id": self.mod_cus_country(),
                            "state_id": self.mod_cus_state(),
                            "city_id": self.mod_cus_city(),
                        },
                        beforeSend: function (jqXHR, settings) { StartLoading(); },
                        success: function (responseData) {
                            StopLoading();

                            $('#cus_add_mod').modal('hide');
                            self.clearCustomerModalForm();
                            self.woCustomer(responseData.data.inserted_id);
                            select2Dropdown('ID_cus_name', '{{ route('customer.drop.list') }}', '{{ route('customer.byid.drop.list') }}', self.woCustomer());
                            sweetAlertMsg('Success',responseData.message,'success')
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            StopLoading();

                            if(jqXHR.status == 403){
                                sweetAlertMsg('Permission Error!','User do not have the right permissions to proceed.','warning');
                            }else {
                                var errors = jqXHR.responseJSON.message;

                                var errorList = "<ul class='list-group'>";

                                $.each(errors, function (i, error) {
                                    errorList += '<li class="text-center text-danger list-group-item">' + error + '</li>';
                                })

                                errorList += "</ul>"

                                sweetAlertMsg('Form submission error!', errorList, 'warning');
                            }
                        }
                    });
                }

                //vehicle model functions start
                self.showVehicleAddNewModal = () => {
                    if(self.woCustomer() === "" || typeof self.woCustomer() === "undefined")
                    {
                        sweetAlertMsg('Required!','Please select customer first.','warning');
                        ErrorValidationColor('ID_cus_name',1)
                        return false;
                    }
                    ErrorValidationColor('ID_cus_name',0)

                    select2Dropdown('ID_mod_cus_name', '{{ route('customer.drop.list') }}', '{{ route('customer.byid.drop.list') }}', self.woCustomer())
                    select2Dropdown('ID_mod_vehi_type', '{{ route('vehicletype.drop.list') }}')
                    select2Dropdown("ID_mod_vehi_type","{{route('vehicletype.drop.list')}}", "{{route('vehicletype.byid.drop.list')}}", 1);
                    $('#vehi_add_mod').modal('show');
                }

                self.setModVehiTypeId = () => { self.mod_vehi_type(getSelect2Val('ID_mod_vehi_type')) }

                self.addNewVehicle = () => {

                    if(self.mod_vehi_vin().length > 17){ sweetAlertMsg('Required!','Vehicle VIN number must be 17 characters.','warning'); return; }
                    else if(self.mod_vehi_vin().length > 1 && self.mod_vehi_vin().length < 17){

                        Swal.fire({
                            title: 'Are you sure?',
                            text: "Is this Vehicle VIN number less than 17 characters?",
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
                                self.submitVehicleData();
                            } else {

                                // CommonMsg.ivm.sweetAlertMsg('Canceled!','Record deletion canceled.','info');
                            }
                        })

                    }else if(self.mod_vehi_vin().length === 17){
                        self.submitVehicleData();
                    }  else { sweetAlertMsg('Required!','Vehicle VIN number cannot be empty!','warning'); return; }
                }

                self.submitVehicleData = () => {
                    $.ajax({
                        url: '{{route('vehicle.create.submit')}}',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "customer_id": self.woCustomer(),
                            "vehicle_type_state": self.is_trailer()==true ? 0 : 1,
                            "make_year": self.mod_vehi_year(),
                            "vehicle_make": self.mod_vehi_make(),
                            "vehicle_model": self.mod_vehi_model(),
                            "vehicle_type_id": self.mod_vehi_type(),
                            "sub_model": self.mod_vehi_submodel(),
                            "body": self.mod_vehi_body(),
                            "color": self.mod_vehi_color(),
                            "engine_no": self.mod_vehi_vin(),
                            "vehicle_no": self.mod_vehi_license(),
                            "odometer": self.mod_vehi_odometer(),
                            "unit_number": self.mod_vehi_unit(),
                        },
                        beforeSend: (jqXHR, settings) => { StartLoading(); },
                        success: (responseData) => {
                            StopLoading();

                            $('#vehi_add_mod').modal('hide');
                            self.clearVehicleModalForm();
                            self.woVehicle(responseData.data.inserted_id);
                            select2Dropdown('ID_vehi_name', '{{ route('customer.vehicle.drop.list') }}', '{{ route('customer.vehicle.byid.drop.list') }}', self.woVehicle(), self.woCustomer());
                            self.showVehiDetails();
                            sweetAlertMsg('Success',responseData.message,'success')
                        },
                        error: (jqXHR, textStatus, errorThrown) => {
                            StopLoading();

                            if(jqXHR.status == 403){
                                sweetAlertMsg('Permission Error!','User do not have the right permissions to proceed.','warning');
                            }else {
                                var errors = jqXHR.responseJSON.message;

                                var errorList = "<ul class='list-group'>";

                                $.each(errors, function (i, error) {
                                    errorList += '<li class="text-center text-danger list-group-item">' + error + '</li>';
                                })

                                errorList += "</ul>"

                                sweetAlertMsg('Form submission error!', errorList, 'warning');
                            }
                        }
                    });
                }

                self.showVehiDetails = function () {
                    if(self.woVehicle() == ""){

                    }else {
                        $.ajax({
                            url: "{{ route('vehicle.show') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                item_id: self.woVehicle(),
                            },
                            beforeSend: function (jqXHR, settings) {

                                StartLoading();
                            },
                            success: function (data, textStatus, jQxhr) {

                                StopLoading();
                                if (data.status) {
                                    let vehi_type_state = 1;
                                    if (typeof data.data[0].vehicle_type_state == "undefined") vehi_type_state = 0; else vehi_type_state = parseInt(data.data[0].vehicle_type_state);
                                    if (vehi_type_state === 0) {

                                        $('#mileage_div').hide();
                                    } else {

                                        $('#mileage_div').show();
                                        const {value: color} = Swal.fire({
                                            title: 'Add Vehicle Mileage',
                                            input: 'number',
                                            inputValidator: (value) => {
                                                if (!value) {
                                                    return 'You need to fill mileage'
                                                } else {

                                                    self.current_mileage($.trim(value));
                                                }
                                            }
                                        })
                                    }
                                } else
                                    CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE, MsgContent.FSE, 'warning');
                            },
                            error: function (jqXhr, textStatus, errorThrown) {
                                StopLoading();
                            }
                        });
                    }
                }

                //vehicle insurance function start
                self.showVehicleInsuranceAddNewModal = () => {
                    if(self.woVehicle() === "" || typeof self.woVehicle() === "undefined" || self.woCustomer() === "" || typeof self.woCustomer() === "undefined")
                    {
                        sweetAlertMsg('Required!','Please select vehicle and customer first.','warning');
                        ErrorValidationColor('ID_vehi_name',1)
                        ErrorValidationColor('ID_cus_name',1)
                        return false;
                    }
                    ErrorValidationColor('ID_vehi_name',0)
                    ErrorValidationColor('ID_cus_name',0)

                    select2Dropdown('ID_mod_ins_cus_name', '{{ route('customer.drop.list') }}', '{{ route('customer.byid.drop.list') }}', self.woCustomer())
                    select2Dropdown('ID_mod_ins_vehi_name', '{{ route('vehicle.drop.list') }}', '{{ route('vehicle.byid.drop.list') }}', self.woVehicle())
                    $('#insu_add_mod').modal('show');
                }
                self.setInsuranceName = () => { self.insurance(self.mod_ins_comp_name()); }

                //service contract function start
                self.showServiceContractAddNewModal = () => {
                    if(self.woVehicle() === "" || typeof self.woVehicle() === "undefined" || self.woCustomer() === "" || typeof self.woCustomer() === "undefined")
                    {
                        sweetAlertMsg('Required!','Please select vehicle and customer first.','warning');
                        ErrorValidationColor('ID_vehi_name',1)
                        ErrorValidationColor('ID_cus_name',1)
                        return false;
                    }
                    ErrorValidationColor('ID_vehi_name',0)
                    ErrorValidationColor('ID_cus_name',0)

                    select2Dropdown('ID_mod_cont_cus_name', '{{ route('customer.drop.list') }}', '{{ route('customer.byid.drop.list') }}', self.woCustomer())
                    select2Dropdown('ID_mod_cont_vehi_name', '{{ route('vehicle.drop.list') }}', '{{ route('vehicle.byid.drop.list') }}', self.woVehicle())
                    $('#cont_add_mod').modal('show');
                }
                self.setContractName = () => { self.contract(self.mod_cont_comp_name()); }

                //vehicle warranty function start
                self.showVehicleWarrantyAddNewModal = () => {
                    if(self.woVehicle() === "" || typeof self.woVehicle() === "undefined" || self.woCustomer() === "" || typeof self.woCustomer() === "undefined")
                    {
                        sweetAlertMsg('Required!','Please select vehicle and customer first.','warning');
                        ErrorValidationColor('ID_vehi_name',1)
                        ErrorValidationColor('ID_cus_name',1)
                        return false;
                    }
                    ErrorValidationColor('ID_vehi_name',0)
                    ErrorValidationColor('ID_cus_name',0)

                    select2Dropdown('ID_mod_warr_cus_name', '{{ route('customer.drop.list') }}', '{{ route('customer.byid.drop.list') }}', self.woCustomer())
                    select2Dropdown('ID_mod_warr_vehi_name', '{{ route('vehicle.drop.list') }}', '{{ route('vehicle.byid.drop.list') }}', self.woVehicle())
                    $('#warr_add_mod').modal('show');
                }
                self.setWarrantyName = () => { self.warranty(self.mod_warr_comp_name()); }

                //work order functions start
                self.setWoCustomerId = () => {
                    self.woCustomer(getSelect2Val('ID_cus_name'));
                    APP_selectEmpty('ID_vehi_name');
                    select2Dropdown('ID_vehi_name', '{{ route('customer.vehicle.drop.list') }}', '{{ route('customer.vehicle.byid.drop.list') }}', self.woVehicle(), self.woCustomer());
                }
                self.setWoVehicleId = () => { self.woVehicle(getSelect2Val('ID_vehi_name'));self.showVehiDetails(); }

                self.formSubmit = function () {

                    if(self.isNew()){ self.insertWorkorderData(); }
                };

                self.getSerialNo = () => {

                    $.ajax({
                        url: "{{ route('workorder.serialno') }}",
                        dataType: 'json',
                        type: 'get',
                        contentType: 'application/x-www-form-urlencoded',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            form_type: 'work_order',
                        },
                        beforeSend: function (jqXHR, settings) { StartLoading(); },
                        success: function( data, textStatus, jQxhr ){

                            StopLoading();
                            if(data.status){
                                let prefix = data.data[0].start;
                                let serino = data.data[0].num;
                                let fullNo = prefix+serino;
                                self.WONo(fullNo);
                            }else
                                sweetAlertMsg('Server Error!',data.message,'warning');
                        },
                        error: function( jqXhr, textStatus, errorThrown ){
                        }
                    });
                }

                self.formValidation = () => {
                    if(self.woVehicle() === "" || typeof self.woVehicle() === "undefined" || self.woCustomer() === "" || typeof self.woCustomer() === "undefined")
                    {
                        sweetAlertMsg('Required!','Vehicle and Customer need to be selected to proceed.','warning');
                        ErrorValidationColor('ID_vehi_name',1)
                        ErrorValidationColor('ID_cus_name',1)
                        return false;
                    }
                    ErrorValidationColor('ID_vehi_name',0)
                    ErrorValidationColor('ID_cus_name',0)

                    if(self.is_insurance() && self.insurance()=== ""){ sweetAlertMsg('Required!','Insurance details are required.','warning'); return false; }

                    if(self.is_contract() && self.contract()=== ""){ sweetAlertMsg('Required!','Service contract details are required.','warning'); return false; }

                    if(self.is_warranty() && self.warranty()=== ""){ sweetAlertMsg('Required!','Warranty details are required.','warning'); return false; }

                    return true;
                }

                self.insertWorkorderData = () => {

                    if(!self.formValidation()) return;

                    $.ajax({
                        url: '{{route('workorder.create.submit')}}',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "customer_id": self.woCustomer(),
                            "vehicle_id": self.woVehicle(),
                            "wo_date": self.woDate(),
                            "appointment_id": self.woAppointment(),
                            "ins_company_name": self.mod_ins_comp_name(),
                            "ins_policy_no": self.mod_ins_policy_no(),
                            "ins_expire_date": self.mod_ins_expire_date(),
                            "ins_phone_number": self.mod_ins_phone_no(),
                            "con_company_name": self.mod_cont_comp_name(),
                            "contract_no": self.mod_cont_policy_no(),
                            "cont_expire_date": self.mod_cont_expire_date(),
                            "cont_mileage": self.mod_cont_mileage(),
                            "cont_phone_number": self.mod_cont_phone_no(),
                            "war_company_name": self.mod_warr_comp_name(),
                            "warranty_no": self.mod_warr_warranty_no(),
                            "date_of_purchase": self.mod_warr_dop(),
                            "war_expire_date": self.mod_warr_expire_date(),
                            "war_phone_number": self.mod_warr_phone_no(),
                            "mileage": self.current_mileage(),
                            "reported_defect": self.woRemark(),
                            "is_warranty": self.is_warranty(),
                            "is_contract": self.is_contract(),
                            "is_insurance": self.is_insurance(),
                        },
                        beforeSend: (jqXHR, settings) => { StartLoading(); },
                        success: (responseData) => {
                            StopLoading();
                            self.clearForm();
                            sweetAlertMsg('Success',responseData.message,'success')

                            $('#wo_submit').attr('target',"_blank");
                            window.top.location = "{{ url('/wo-joborder-create') }}/"+responseData.data['insertedId'];
                        },
                        error: (jqXHR, textStatus, errorThrown) => {
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

                self.clearCustomerModalForm = () => {

                    self.mod_cus_group("");
                    self.mod_cus_price_group("");
                    self.mod_cus_company("");
                    self.mod_cus_postal_code("");
                    self.mod_cus_fname("");
                    self.mod_cus_lname("");
                    self.mod_cus_country("");
                    self.mod_cus_spouse_fname("");
                    self.mod_cus_spouse_lname("");
                    self.mod_cus_email("");
                    self.mod_cus_phone("");
                    self.mod_cus_address("");
                    self.mod_cus_state("");
                    self.mod_cus_city("");
                    APP_selectEmpty('id_mod_cus_group_drop');
                    APP_selectEmpty('id_mod_price_group_drop');
                    APP_selectEmpty('ID_mod_country');
                    APP_selectEmpty('ID_mod_state');
                    APP_selectEmpty('ID_mod_city');
                };

                self.clearVehicleModalForm = () => {
                    self.is_trailer(false);
                    self.mod_vehi_year("");
                    self.mod_vehi_make("");
                    self.mod_vehi_model("");
                    self.mod_vehi_type("");
                    self.mod_vehi_submodel("");
                    self.mod_vehi_body("");
                    self.mod_vehi_color("");
                    self.mod_vehi_vin("");
                    self.mod_vehi_license("");
                    self.mod_vehi_odometer("");
                    self.mod_vehi_unit("");
                    APP_selectEmpty('ID_mod_cus_name');
                    APP_selectEmpty('ID_mod_vehi_type');
                }

                self.clearInsuranceModalForm = () => {
                    self.mod_ins_comp_name("");
                    self.mod_ins_policy_no("");
                    self.mod_ins_expire_date("");
                    self.mod_ins_phone_no("");
                }

                self.clearContractModalForm = () => {
                    self.mod_cont_comp_name("");
                    self.mod_cont_policy_no("");
                    self.mod_cont_expire_date("");
                    self.mod_cont_phone_no("");
                    self.mod_cont_mileage("");
                }

                self.clearWarrantyModalForm = () => {
                    self.mod_warr_comp_name("");
                    self.mod_warr_warranty_no("");
                    self.mod_warr_dop("");
                    self.mod_warr_expire_date("");
                    self.mod_warr_phone_no("");
                }

                self.clearForm = () => {

                    self.WONo("");
                    self.woCustomer("");
                    self.woVehicle("");
                    self.woDate("");
                    self.woAppointment("");
                    self.current_mileage("");
                    self.woRemark("");

                    self.insurance("");
                    self.contract("");
                    self.warranty("");

                    self.is_insurance(false);
                    self.is_contract(false);
                    self.is_warranty(false);

                    self.is_enableInsurance(false);
                    self.is_enableContract(false);
                    self.is_enableWarranty(false);
                    self.is_enableInsuranceBtn(false);
                    self.is_enableContractBtn(false);
                    self.is_enableWarrantyBtn(false);

                    // uncheckIcheck('id_insurance_chk');
                    // uncheckIcheck('id_contract_chk');
                    // uncheckIcheck('id_warranty_chk');
                    // uncheckIcheck('id_tler_or_mh_chk');

                    APP_selectEmpty('ID_cus_name');
                    APP_selectEmpty('ID_vehi_name');

                    self.clearCustomerModalForm();
                    self.clearVehicleModalForm();
                    self.clearInsuranceModalForm();
                    self.clearContractModalForm();
                    self.clearWarrantyModalForm();
                    self.getSerialNo();
                };
            }
        }
    </script>
@endpush
