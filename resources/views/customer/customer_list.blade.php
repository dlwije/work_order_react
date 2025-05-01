@extends('template.master')
@section('title','Customers')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@section('content')
    <div class="card" id="labortask_list_view">
        <div class="card-header">
            <h3 class="card-title">Customers</h3>
            <div class="card-tools">
                @can('customer-add')
                    <a class="btn btn-sm btn-success no-shadow" href="javascript:0" onclick="laborTaskList.ivm.showCustomerAddNewModal();">  <i class="glyphicon glyphicon-plus myicon-right"></i> Add New</a>
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
                                <th>Company</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Price Group</th>
                                <th>Customer Group</th>
                                <th>Deposit</th>
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

        <!--Add Customer model -->
        <div class="modal fade" id="cus_add_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" data-bind="text: laborTaskList.ivm.cus_id() == '' ? 'Add Customer' : 'Edit Customer'">Add Customer</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Customer Group</label>
                                    <select class="form-control" id="id_mod_cus_group_drop" onchange="laborTaskList.ivm.setModCustomerGroupId()">

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Price Group</label>
                                    <select class="form-control" id="id_mod_price_group_drop" onchange="laborTaskList.ivm.setModPriceGroupId()">

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
                                        <select class="form-control " id="ID_mod_country" onchange="laborTaskList.ivm.setModCountryId()">
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
                                        <select class="form-control " id="ID_mod_state" onchange="laborTaskList.ivm.setModStateId()">
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
                                        <select class="form-control " id="ID_mod_city" onchange="laborTaskList.ivm.setModCityId()">
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
                        <!-- Start:Customer's vehicle table -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th style="" class="">License</th>
                                            <th style="" class="">Year</th>
                                            <th  class="" style="">Make</th>
                                            <th  class="" style="">Vir/Ser Number</th>
                                            <th  class="" style="">Model</th>
{{--                                            <th  class="" data-bind="style: { display: laborTaskList.ivm.cus_show_id() == '' ? '' : 'none' }">Action</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody data-bind="foreach: customer_vehicle_list">
                                        <tr>
                                            <td data-bind="text: col1" class="text-center"></td>
                                            <td data-bind="text: col2" class="text-right"></td>
                                            <td data-bind="text: col3" class="text-center"></td>
                                            <td data-bind="text: col4" class="text-center"></td>
                                            <td data-bind="text: col5" class="text-center"></td>
                                            {{--<td class="text-center" data-bind="style: { display: laborTaskList.ivm.cus_show_id() == '' ? '' : 'none' }" >
                                                <i class="fa fa-trash-alt" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Remove this row" data-bind="click: $parent.removeGridRow.bind($data, $index())"></i>
                                            </td>--}}
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-bind="style: { display: laborTaskList.ivm.cus_show_id() == '' ? '' : 'none' }" onclick="laborTaskList.ivm.addNewCustomer()">Submit</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

        <!--Deposit Showing model -->
        <div class="modal fade" id="deposit_show_mod">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="deposit_show_title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-condensed table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th style="" class="text-center">Date</th>
                                            <th style="" class="text-right">Amount</th>
                                            <th  class="text-center" style="">Paid by</th>
                                            <th  class="text-center" style="">Note</th>
                                            <th  class="text-center" style="">Created by</th>
                                            <th  class="text-center" style="">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody data-bind="foreach: customer_deposit_list">
                                        <tr>
                                            <td data-bind="text: col1" class="text-center"></td>
                                            <td data-bind="text: col2" class="text-right"></td>
                                            <td data-bind="text: col3" class="text-center"></td>
                                            <td data-bind="text: col4" class="text-center"></td>
                                            <td data-bind="text: col5" class="text-center"></td>
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
{{--                        <button type="button" class="btn btn-success" onclick="jobOrderList.ivm.submitTechAssignData()">Submit</button>--}}
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

                $('#cus_add_mod').on('hide.bs.modal', function () {
                    laborTaskList.ivm.clearCustomerModalForm();
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

                    self.cus_id = ko.observable("");
                    self.cus_show_id = ko.observable("");
                    self.mod_tsk_cate_code = ko.observable("");
                    self.mod_tsk_cate_name = ko.observable("");

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

                    self.customer_deposit_list = ko.observableArray();
                    self.customer_deposit_delete_list = ko.observableArray();

                    self.customer_vehicle_list = ko.observableArray();
                    self.customer_vehicle_delete_list = ko.observableArray();


                    //getting predefined msg strings from common file
                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.designInitialize = () => {
                        self.listTable();
                    }

                    //datatable function
                    self.listTable = function () {

                        var table = $('#id_lbr_task_tbl').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('customer.table.list') }}",
                            drawCallback: ( settings, json ) =>{ $('[data-toggle="tooltip"]').tooltip() },
                            columns: [
                                {data: 'company', name: 'company'},
                                {data: 'name', name: 'name'},
                                {data: 'email', name: 'email'},
                                {data: 'phone', name: 'phone'},
                                {data: 'price_group_name', name: 'price_group_name'},
                                {data: 'customer_group_name', name: 'customer_group_name'},
                                {data: 'deposit_amount', name: 'deposit_amount', sClass: 'text-right'},
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ]
                        });
                    }

                    //customer model functions start
                    self.showCustomerAddNewModal = () => {
                        select2Dropdown('id_mod_cus_group_drop','{{ route('customer.group.drop.list') }}')
                        select2Dropdown('id_mod_price_group_drop','{{ route('price.group.drop.list') }}')
                        select2Dropdown('ID_mod_country','{{ route('country.drop.list') }}')
                        select2Dropdown('ID_mod_state','{{ route('state.drop.list') }}')
                        {{--select2Dropdown('ID_mod_city','{{ route('city.drop.list') }}')--}}
                        $('#cus_add_mod').modal('show');
                    }

                    self.setModCustomerGroupId = () => { self.mod_cus_group(getSelect2Val('id_mod_cus_group_drop')) }
                    self.setModPriceGroupId = () => { self.mod_cus_price_group(getSelect2Val('id_mod_price_group_drop')) }
                    self.setModCountryId = () => { self.mod_cus_country(getSelect2Val('ID_mod_country')) }
                    self.setModStateId = () => {
                        self.mod_cus_state(getSelect2Val('ID_mod_state'));
                        APP_selectEmpty('ID_mod_city');
                        select2Dropdown('ID_mod_city','{{ route('city.by.stateid.drop.list') }}', '', null,self.mod_cus_state())
                    }
                    self.setModCityId = () => { self.mod_cus_city(getSelect2Val('ID_mod_city')) }

                    self.addNewCustomer = () => {

                        if(self.cus_show_id() == "") {
                            let url = "{{ route('customer.edit.submit') }}";
                            if (self.cus_id() == "")
                                url = '{{ route('customer.create.submit') }}';

                            $.ajax({
                                url: url,
                                type: 'POST',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                dataType: 'json',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "item_id": self.cus_id(),
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
                                beforeSend: function (jqXHR, settings) {
                                    StartLoading();
                                },
                                success: function (responseData) {
                                    StopLoading();

                                    $('#id_lbr_task_tbl').DataTable().ajax.reload();
                                    $('#cus_add_mod').modal('hide');
                                    self.clearCustomerModalForm();
                                    sweetAlertMsg('Success', responseData.message, 'success')
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    StopLoading();

                                    if (jqXHR.status == 403) {
                                        sweetAlertMsg('Permission Error!', 'User do not have the right permissions to proceed.', 'warning');
                                    } else {
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
                        }else{ sweetAlertMsg(MsgHead.FVE, 'Please use edit option for edit the customer.', 'warning'); }
                    }

                    /*used function for edit the customer data*/
                    self.editDetails = function (empID) {
                        self.cus_id(empID);

                        $.ajax({
                            url: "{{ route('customer.selected.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                item_id: self.cus_id(),
                            },
                            beforeSend: function (jqXHR, settings) {

                                StartLoading();
                            },
                            success: function( result, textStatus, jQxhr ){

                                StopLoading();
                                if(result.status){

                                    self.mod_cus_group(result.data.cusData[0].customer_group_id);
                                    self.mod_cus_price_group(result.data.cusData[0].price_group_id);
                                    self.mod_cus_company(result.data.cusData[0].company);
                                    self.mod_cus_postal_code(result.data.cusData[0].postal_code);
                                    self.mod_cus_fname(result.data.cusData[0].first_name);
                                    self.mod_cus_lname(result.data.cusData[0].last_name);
                                    self.mod_cus_country(result.data.cusData[0].country_id);
                                    self.mod_cus_spouse_fname(result.data.cusData[0].spouse_first_name);
                                    self.mod_cus_spouse_lname(result.data.cusData[0].spouse_last_name);
                                    self.mod_cus_email(result.data.cusData[0].email);
                                    self.mod_cus_phone(result.data.cusData[0].phone);
                                    self.mod_cus_address(result.data.cusData[0].address);
                                    self.mod_cus_state(result.data.cusData[0].state_id);
                                    self.mod_cus_city(result.data.cusData[0].city_id);

                                    select2Dropdown('id_mod_cus_group_drop','{{ route('customer.group.drop.list') }}', '{{ route('customer.group.byid.drop.list') }}', self.mod_cus_group())
                                    select2Dropdown('id_mod_price_group_drop','{{ route('price.group.drop.list') }}', '{{ route('price.group.byid.drop.list') }}', self.mod_cus_price_group())
                                    select2Dropdown('ID_mod_country','{{ route('country.drop.list') }}', '{{ route('country.byid.drop.list') }}', self.mod_cus_country())
                                    select2Dropdown('ID_mod_state','{{ route('state.drop.list') }}', '{{ route('state.byid.drop.list') }}', self.mod_cus_state())
                                    select2Dropdown('ID_mod_city','{{ route('city.by.stateid.drop.list') }}', '', null,self.mod_cus_state())
                                    select2Dropdown('ID_mod_city','{{ route('city.by.stateid.drop.list') }}', '{{ route('city.byid.drop.list') }}', self.mod_cus_city(), self.mod_cus_state())

                                    $.each(result.data.cusVehiData, function (i, item) {
                                        self.customer_vehicle_list.push(new gridCusVehicleListRows(item));
                                    })

                                    $('#cus_add_mod').modal('show')

                                }else
                                    CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: ( jqXhr, textStatus, errorThrown ) => {
                            }
                        });
                    }

                    self.showDetails = function (empID) {
                        self.cus_show_id(empID);

                        $.ajax({
                            url: "{{ route('customer.selected.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                item_id: self.cus_show_id(),
                            },
                            beforeSend: function (jqXHR, settings) {

                                StartLoading();
                            },
                            success: function( result, textStatus, jQxhr ){

                                StopLoading();
                                if(result.status){

                                    self.mod_cus_group(result.data.cusData[0].customer_group_id);
                                    self.mod_cus_price_group(result.data.cusData[0].price_group_id);
                                    self.mod_cus_company(result.data.cusData[0].company);
                                    self.mod_cus_postal_code(result.data.cusData[0].postal_code);
                                    self.mod_cus_fname(result.data.cusData[0].first_name);
                                    self.mod_cus_lname(result.data.cusData[0].last_name);
                                    self.mod_cus_country(result.data.cusData[0].country_id);
                                    self.mod_cus_spouse_fname(result.data.cusData[0].spouse_first_name);
                                    self.mod_cus_spouse_lname(result.data.cusData[0].spouse_last_name);
                                    self.mod_cus_email(result.data.cusData[0].email);
                                    self.mod_cus_phone(result.data.cusData[0].phone);
                                    self.mod_cus_address(result.data.cusData[0].address);
                                    self.mod_cus_state(result.data.cusData[0].state_id);
                                    self.mod_cus_city(result.data.cusData[0].city_id);

                                    select2Dropdown('id_mod_cus_group_drop','{{ route('customer.group.drop.list') }}', '{{ route('customer.group.byid.drop.list') }}', self.mod_cus_group())
                                    select2Dropdown('id_mod_price_group_drop','{{ route('price.group.drop.list') }}', '{{ route('price.group.byid.drop.list') }}', self.mod_cus_price_group())
                                    select2Dropdown('ID_mod_country','{{ route('country.drop.list') }}', '{{ route('country.byid.drop.list') }}', self.mod_cus_country())
                                    select2Dropdown('ID_mod_state','{{ route('state.drop.list') }}', '{{ route('state.byid.drop.list') }}', self.mod_cus_state())
                                    select2Dropdown('ID_mod_city','{{ route('city.by.stateid.drop.list') }}', '', null,self.mod_cus_state())
                                    select2Dropdown('ID_mod_city','{{ route('city.by.stateid.drop.list') }}', '{{ route('city.byid.drop.list') }}', self.mod_cus_city(), self.mod_cus_state())

                                    $.each(result.data.cusVehiData, function (i, item) {
                                        self.customer_vehicle_list.push(new gridCusVehicleListRows(item));
                                    })

                                    $('#cus_add_mod').modal('show')

                                }else
                                    CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: ( jqXhr, textStatus, errorThrown ) => {
                            }
                        });

                    }

                    self.clearCustomerModalForm = () => {

                        self.cus_id("");
                        self.cus_show_id("");
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
                        self.customer_vehicle_list.removeAll();
                    };

                    //delete Customer function
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
                                    url: "{{ route('customer.destroy.submit') }}",
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
                                            sweetAlertMsg(MsgHead.PE,'User does not have the right permissions.','warning');
                                        }
                                    }
                                });

                            } else {

                                CommonMsg.ivm.sweetAlertMsg('Canceled!','Record deletion canceled.','info');
                            }
                        })
                    }

                    /*show deposit model functions start here*/
                    self.showDeposits = function (empID) {
                        self.cus_id(empID);

                        $.ajax({
                            url: "{{ route('customer.deposit.list.data') }}",
                            dataType: 'json',
                            type: 'post',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                cus_id: self.cus_id(),
                            },
                            beforeSend: function (jqXHR, settings) {

                                StartLoading();
                            },
                            success: function( data, textStatus, jQxhr ){

                                StopLoading();
                                self.customer_deposit_list.removeAll();
                                self.customer_deposit_delete_list.removeAll();

                                if(data.status){

                                    if(data.data.length > 0) {
                                        $.each(data.data, function (i, item) {
                                            self.customer_deposit_list.push(new gridCusDepositListRows(item));
                                        })
                                        if(!$('#deposit_show_mod').hasClass('show')) {
                                            $('#deposit_show_title').html(data.data[0].name + ' deposit list');
                                            $('#deposit_show_mod').modal('show')
                                        }
                                    }else{
                                        CommonMsg.ivm.sweetAlertMsg('No deposits.','Wallet is empty.','warning');
                                    }
                                }else
                                    CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: ( jqXhr, textStatus, errorThrown ) => {
                            }
                        });
                    }

                    let gridCusDepositListRows = function (obj) {
                        let item = this;

                        if(typeof obj === "undefined"){

                        }else{
                            item.col1 = (typeof obj.date === "undefined" || obj.date == null) ? ko.observable("") : ko.observable(obj.date);
                            item.col2 = (typeof obj.amount === "undefined" || obj.amount == null) ? ko.observable("") : ko.observable(formatMoney(obj.amount));
                            item.col3 = (typeof obj.paid_by === "undefined" || obj.paid_by == null) ? ko.observable("") : ko.observable(obj.paid_by);
                            item.col4 = (typeof obj.note === "undefined" || obj.note == null) ? ko.observable("") : ko.observable(obj.note);
                            item.col5 = (typeof obj.uName === "undefined" || obj.uName == null) ? ko.observable("") : ko.observable(obj.uName);
                            item.col6 = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
                        }
                    }

                    let gridCusVehicleListRows = function (obj) {
                        let item = this;

                        if(typeof obj === "undefined"){

                        }else{
                            item.col1 = (typeof obj.vehicle_no === "undefined" || obj.vehicle_no == null) ? ko.observable("") : ko.observable(obj.vehicle_no);
                            item.col2 = (typeof obj.make_year === "undefined" || obj.make_year == null) ? ko.observable("") : ko.observable((obj.make_year));
                            item.col3 = (typeof obj.vehicle_make === "undefined" || obj.vehicle_make == null) ? ko.observable("") : ko.observable(obj.vehicle_make);
                            item.col4 = (typeof obj.engine_no === "undefined" || obj.engine_no == null) ? ko.observable("") : ko.observable(obj.engine_no);
                            item.col5 = (typeof obj.vehicle_model === "undefined" || obj.vehicle_model == null) ? ko.observable("") : ko.observable(obj.vehicle_model);
                            item.col6 = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
                        }
                    }
                    //delete Customer deposit function
                    self.removeGridRow = function(indexNo, rowData){

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
                                if(rowData.col6() && rowData.col6()!== "") {
                                    $.ajax({
                                        url: "{{ route('customer.deposit.destroy.submit') }}",
                                        dataType: 'json',
                                        type: 'post',
                                        contentType: 'application/x-www-form-urlencoded',
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            item_id: rowData.col6(),
                                        },
                                        beforeSend: function (jqXHR, settings) {

                                        },
                                        success: function (data, textStatus, jQxhr) {

                                            if (data.status) {

                                                self.showDeposits();
                                                $('#id_lbr_task_tbl').DataTable().ajax.reload();
                                                sweetAlertMsg('Success!', data.message, 'success');
                                            } else
                                                sweetAlertMsg(MsgHead.FSE, MsgContent.FSE, 'warning');
                                        },
                                        error: function (jqXhr, textStatus, errorThrown) {
                                            if (jqXhr.status == 403) {
                                                sweetAlertMsg(MsgHead.PE, 'User does not have the right permissions.', 'warning');
                                            }
                                        }
                                    });
                                }else
                                    self.customer_deposit_list.remove(rowData);
                            } else {

                                CommonMsg.ivm.sweetAlertMsg('Canceled!','Record deletion canceled.','info');
                            }
                        })
                    }
                }
            }
        </script>
    @endpush
@endsection

