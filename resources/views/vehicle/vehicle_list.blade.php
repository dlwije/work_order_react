@extends('template.master')
@section('title','Vehicles')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@section('content')
    <div class="card" id="labortask_list_view">
        <div class="card-header">
            <h3 class="card-title">Vehicles</h3>
            <div class="card-tools">
                @can('vehicle-add')
                    <a class="btn btn-sm btn-success no-shadow" href="javascript:0" onclick="vehicleList.ivm.showVehicleAddNewModal()">  <i class="glyphicon glyphicon-plus myicon-right"></i> Add New</a>
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
                                <th>Customer Name</th>
                                <th>Vehicle Type</th>
                                <th>Is Trailer</th>
                                <th>License</th>
                                <th>Year</th>
                                <th>Make</th>
                                <th>Vir/Ser Number</th>
                                <th>Model</th>
                                <th>Unit Number</th>
                                <th>Odo Meter</th>
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
                                    <select class="form-control " id="ID_mod_cus_name" onchange="vehicleList.ivm.setModCusId()">
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
                                    <select class="form-control " id="ID_mod_vehi_type" onchange="vehicleList.ivm.setModVehiTypeId()">
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
                                    <label class="control-label">VIN</label><span style="color: red;">*</span>
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
                        <button type="button" class="btn btn-success" onclick="vehicleList.ivm.addNewVehicle()">Submit</button>
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
                vehicleList.init();
                //start top progress bar
                // Pace.restart();

                $('#task_edit_mod').on('hide.bs.modal', function () {
                    vehicleList.ivm.clearVehicleModalForm();
                })
            })
            vehicleList = {
                ivm: null,
                init: function () {
                    vehicleList.ivm = new vehicleList.vehicleListViewModel();

                    //binding the knockout to the dom
                    ko.applyBindings(vehicleList.ivm, $('#labortask_list_view')[0]);

                    //initialize the page design needs when page loads
                    vehicleList.ivm.designInitialize();
                },
                vehicleListViewModel: function () {
                    let self = this;

                    self.vehi_id = ko.observable("");

                    //vehicle Model variable
                    self.mod_cus_name = ko.observable("");
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
                            ajax: "{{ route('vehicle.table.list') }}",
                            columns: [
                                {data: 'name', name: 'name'},
                                {data: 'type_name', name: 'type_name'},
                                {data: 'vehiTypeState', name: 'vehiTypeState'},
                                {data: 'vehicle_no', name: 'vehicle_no'},
                                {data: 'make_year', name: 'make_year'},
                                {data: 'vehicle_make', name: 'vehicle_make'},
                                {data: 'engine_no', name: 'engine_no'},
                                {data: 'vehicle_model', name: 'vehicle_model'},
                                {data: 'unit_number', name: 'unit_number'},
                                {data: 'odometer', name: 'odometer'},
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ]
                        });
                    }

                    //vehicle model functions start
                    self.showVehicleAddNewModal = () => {

                        select2Dropdown('ID_mod_cus_name', '{{ route('customer.drop.list') }}')
                        select2Dropdown('ID_mod_vehi_type', '{{ route('vehicletype.drop.list') }}')
                        select2Dropdown("ID_mod_vehi_type","{{route('vehicletype.drop.list')}}", "{{route('vehicletype.byid.drop.list')}}", 1);
                        $('#vehi_add_mod').modal('show');
                    }

                    self.setModVehiTypeId = () => { self.mod_vehi_type(getSelect2Val('ID_mod_vehi_type')) }
                    self.setModCusId = () => { self.mod_cus_name(getSelect2Val('ID_mod_cus_name')) }

                    self.addNewVehicle = () => {
                        console.log(self.mod_vehi_vin().length)
                        if(self.mod_cus_name() == "") {sweetAlertMsg('Required!','Customer name is required!','warning'); ErrorValidationColor('ID_mod_cus_name',1); return;}
                        ErrorValidationColor('ID_mod_cus_name',0);
                        if(self.mod_vehi_vin() == "") {sweetAlertMsg('Required!','Vehicle VIN number is required!','warning'); ErrorValidationColor('id_mod_vehi_vin',1); return;}
                        ErrorValidationColor('id_mod_vehi_vin',0);


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

                                    sweetAlertMsg('Canceled!','Record submission canceled.','info');
                                }
                            })
                        }else if(self.mod_vehi_vin().length === 17){
                            self.submitVehicleData();
                        } else { sweetAlertMsg('Required!','Vehicle VIN number cannot be empty!','warning'); return; }
                    }

                    self.submitVehicleData = () => {

                        let url = "{{ route('vehicle.edit.submit') }}";
                        if (self.vehi_id() == "")
                            url = '{{ route('vehicle.create.submit') }}';

                        $.ajax({
                            url: url,
                            type: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "item_id": self.vehi_id(),
                                "customer_id": self.mod_cus_name(),
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

                                $('#id_lbr_task_tbl').DataTable().ajax.reload();
                                $('#vehi_add_mod').modal('hide');
                                self.clearVehicleModalForm();
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

                    self.clearVehicleModalForm = () => {
                        self.mod_cus_name("");
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

                    self.editDetails = function (empID) {
                        self.vehi_id(empID);
                        $.ajax({
                            url: "{{ route('vehicle.selected.data') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                item_id: self.vehi_id(),
                            },
                            beforeSend: function (jqXHR, settings) {

                                StartLoading();
                            },
                            success: function( result, textStatus, jQxhr ){

                                StopLoading();
                                if(result.status){

                                    self.mod_cus_name(result.data[0].customer_id);
                                    self.is_trailer((result.data[0].vehicle_type_state ==1 ? 0 : 1));
                                    self.mod_vehi_year(result.data[0].make_year);
                                    self.mod_vehi_make(result.data[0].vehicle_make);
                                    self.mod_vehi_model(result.data[0].vehicle_model);
                                    self.mod_vehi_type(result.data[0].vehicle_type_id);
                                    self.mod_vehi_submodel(result.data[0].sub_model);
                                    self.mod_vehi_body(result.data[0].body);
                                    self.mod_vehi_color(result.data[0].color);
                                    self.mod_vehi_vin(result.data[0].engine_no);
                                    self.mod_vehi_license(result.data[0].vehicle_no);
                                    self.mod_vehi_odometer(result.data[0].odometer);
                                    self.mod_vehi_unit(result.data[0].unit_number);

                                    select2Dropdown('ID_mod_cus_name', '{{ route('customer.drop.list') }}', '{{ route('customer.byid.drop.list') }}', self.mod_cus_name())
                                    select2Dropdown('ID_mod_vehi_type', '{{ route('vehicletype.drop.list') }}', '{{ route('vehicletype.byid.drop.list') }}', self.mod_vehi_type())

                                    $('#vehi_add_mod').modal('show')

                                }else
                                    CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: ( jqXhr, textStatus, errorThrown ) => {
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
                                    url: "{{ route('vehicle.destroy.submit') }}",
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
                }
            }
        </script>
    @endpush
@endsection

