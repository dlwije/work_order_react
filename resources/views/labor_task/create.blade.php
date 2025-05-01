@extends('template.master')
@section('title','New Labor Task')

@section('content')
    <link href="{{ asset('assets/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
    <div class="container-fluid" id="vehicle_add_view">
        <div class="row">

            <div class="col-md-12">
                @include('errors.errors-forms')
            </div>

            <div class="offset-md-1 col-md-10">
                <!-- Default box -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Create Labor Task</h3>
                        <div class="card-tools">
                            <a class="btn btn-sm btn-secondary no-shadow" href="{{ route('vehicle.list') }}">  <i class="fas fa-hand-o-left"></i>Go Back</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="form">
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Customer</label>
                                        <select class="form-control " id="ID_customer" onchange="javascript:Vehicle.ivm.onClickGetCustomerValue();">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Vehicle Make</label>
                                        <input type="text" id="" class="form-control " data-bind="value:VehicleMake">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Vehicle Model</label>
                                        <input type="text" id="" class="form-control " data-bind="value:VehicleModel">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Vehicle RegNo</label><span style="color: red;">*</span>
                                        <input required type="text" id="" class="form-control " data-bind="value:VehicleRegNo">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Chassis No</label>
                                        <input type="text" class="form-control " data-bind="value:ChassisNo">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Engine No</label>
                                        <input type="text" class="form-control " data-bind="value:EngineNo">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Vehicle Type</label>
                                        <select class="form-control " id="ID_vehicleType" data-bind="value:VehicleTypeNo">

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="">

                            <div class="form-group">
                                <div class="" id="ID_activeCheck">
                                    <input type="checkbox" id="ID_itemActive" data-bind="checked:isActive">
                                    <label for="ID_itemActive" class="custom_check">Is Active</label>
                                </div>

                                <button type="button" class="btn btn-success btn-sm float-right" onclick="Item.ivm.formSubmit();"><i class="fas fa-save"></i> Submit</button>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('assets/plugins/iCheck/icheck.min.js')}}"></script>
    <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            //initiate page javascript
            Vehicle.init();

            //activate the Icheck checkbox styles
            $('#ID_itemActive').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%'
            });
        });
        Vehicle = {
            ivm: null,
            init: function (){
                Vehicle.ivm = new Vehicle.VehicleViewModel();
                //binding the knockout to the dom
                ko.applyBindings(Vehicle.ivm,$('#vehicle_add_view')[0]);

                //initialize the designs when page loads
                Vehicle.ivm.designInitiate();
            },
            VehicleViewModel: function (){

                var self = this;

                //declaring the knockout variables
                self.isNew = ko.observable(true);
                self.VehicleID = ko.observable("");

                self.VehicleMake = ko.observable("");
                self.VehicleModel = ko.observable("");
                self.VehicleRegNo = ko.observable("");
                self.ChassisNo = ko.observable("");
                self.EngineNo = ko.observable("");
                self.VehicleTypeNo = ko.observable("");
                self.CustomerID = ko.observable("");
                self.isActive = ko.observable(true);

                //design initialization for filling dropdown etc
                self.designInitiate = function () {
                    select2Dropdown("ID_customer","{{route('customer.drop.list')}}");
                    select2Dropdown("ID_vehicleType","{{route('vehicletype.drop.list')}}");
                    self.designChange(true);
                }

                //form submit function for relatively form condition like new or not. via that identify update or create.
                self.formSubmit = function () {

                    if(self.isNew()){
                        self.insertVehicleData();
                    }
                };

                //item insert function
                self.insertVehicleData = function () {

                    $.ajax({
                        url: '{{route('vehicle.create.submit')}}',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "customerid": self.CustomerID(),
                            "vehiclemake": self.VehicleMake(),
                            "vehiclemodel": self.VehicleModel(),
                            "vehicleno": self.VehicleRegNo(),
                            "chassisno": self.ChassisNo(),
                            "engineno": self.EngineNo(),
                            "vehicletypeid": self.VehicleTypeNo()
                        },
                        beforeSend: function (jqXHR, settings) { StartLoading(); },
                        success: function (responseData) {
                            StopLoading();
                            sweetAlertMsg('Success',responseData.message,'success')
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

                //form clearing function which clears all variables and dropdown values
                self.clearForm = function () {

                    self.VehicleModel("");
                    self.VehicleMake("");
                    self.VehicleRegNo("");
                    self.ChassisNo("");
                    self.EngineNo("");
                    self.VehicleTypeNo("");
                    self.CustomerID("");
                    self.isActive(true);
                    appSelectEmpty('ID_vehicleType');
                    appSelectEmpty('ID_customer');
                };

                //function for set dropdown selected value for knockout variable
                self.onClickGetVehicleTypeValue = function () {

                    self.VehicleTypeNo(getSelect2Val('ID_vehicleType'));
                };

                //function for set dropdown selected value for knockout variable
                self.onClickGetCustomerValue = function () {

                    self.CustomerID(getSelect2Val('ID_customer'));
                };

                self.designChange = function (state) {

                    if(state){

                        $('#ID_Delete').attr('style','display:none');
                        $('#ID_activeCheck').attr('style','display:none');
                    }
                }
            }
        }
    </script>
@endpush
