@extends('template.master')
@section('title','Edit Vehicle')

@section('content')
    <link href="{{ asset('assets/plugins/iCheck/all.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
    <div class="container-fluid" id="Item_add_view">
        <div class="row">

            <div class="col-md-12">
                @include('errors.errors-forms')
            </div>

            <div class="offset-md-1 col-md-10">
                <!-- Default box -->
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Edit Vehicle</h3>
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

                                <button type="button" class="btn btn-success btn-sm float-right" onclick="Vehicle.ivm.formSubmit();"><i class="fas fa-save"></i> Submit</button>

                                <button id="ID_Delete" class="btn btn-danger btn-sm float-right" style="margin-right: 5px" onclick="Vehicle.ivm.DeleteConfirm();"><i class="icon-cancel-square"></i> Delete</button>
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
        });
        Vehicle = {
            ivm: null,
            init: function (){
                Vehicle.ivm = new Vehicle.VehicleViewModel();
                ko.applyBindings(Vehicle.ivm,$('#Item_add_view')[0]);
                Vehicle.ivm.designInitiate();
            },
            VehicleViewModel: function (){

                var self = this;

                //declaring the knockout variables and binding id specific data relatively variables
                self.isNew = ko.observable(false);
                self.VehicleID = ko.observable("{{ $editData->id }}");

                self.VehicleMake = ko.observable("{{ $editData->vehiclemake }}");
                self.VehicleModel = ko.observable("{{ $editData->vehiclemodel }}");
                self.VehicleRegNo = ko.observable("{{ $editData->vehicleno }}");
                self.VehicleRegNoOld = ko.observable("{{ $editData->vehicleno }}");
                self.ChassisNo = ko.observable("{{ $editData->chassisno }}");
                self.EngineNo = ko.observable("{{ $editData->engineno }}");
                self.VehicleTypeNo = ko.observable("{{$editData['vehicletypeid']}}");
                self.CustomerID = ko.observable("{{ $editData->customer_id }}");
                self.isActive = ko.observable(true);

                //getting predefined msg strings from common file
                var MsgHead = CommonMsg.ivm.toastMsgHead;
                var MsgContent = CommonMsg.ivm.toastMsgContent;

                //design initialization for filling dropdown etc and selected already added values to the dropdown
                self.designInitiate = function () {

                    console.log(self.VehicleTypeNo());
                    select2Dropdown("ID_customer","{{route('customer.drop.list')}}", "{{route('customer.byid.drop.list')}}", self.VehicleID());
                    select2Dropdown("ID_vehicleType","{{route('vehicletype.drop.list')}}", "{{route('vehicletype.byid.drop.list')}}", self.VehicleTypeNo());

                    self.designChange(false);
                }

                //form submit function for relatively form condition like new or not. via that identify update or create.
                self.formSubmit = function () {

                    if(self.isNew()){ sweetAlertMsg('Warning', 'Wrong form type', 'warning') }else{ self.updateVehicleData(); }
                };

                //item update function
                self.updateVehicleData = function () {
                    $.ajax({
                        url: '{{route('vehicle.edit.submit')}}',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": self.VehicleID(),
                            "customerid": self.CustomerID(),
                            "vehiclemake": self.VehicleMake(),
                            "vehiclemodel": self.VehicleModel(),
                            "vehicleno": self.VehicleRegNo(),
                            "vehiclenoold": self.VehicleRegNoOld(),
                            "chassisno": self.ChassisNo(),
                            "engineno": self.EngineNo(),
                            "vehicletypeid": self.VehicleTypeNo(),
                            "isActive": self.isActive()
                        },
                        beforeSend: function (jqXHR, settings) { StartLoading(); },
                        success: function (responseData) {
                            StopLoading();
                            //showing sweet alert message via common file function by passing parameters
                            sweetAlertMsg('Success',responseData.message,'success')

                            //time out for auto matically redirect after update done.
                            setTimeout(function () {
                                window.top.location = "{{ route('vehicle.list') }}";
                            }, 1000);
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

                //delete function with confirm alert box
                self.DeleteConfirm = function (){

                    const swalWithBootstrapButtons = Swal.mixin({
                        customClass: {
                            confirmButton: 'btn btn-success',
                            cancelButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                        title: MsgHead.confirm,
                        text: MsgContent.confirm,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            StartLoading();
                            $.post("{{ route('vehicle.destroy') }}", { "_token": "{{ csrf_token() }}",'iID': self.VehicleID() }, function (res) {
                                StopLoading();
                                var json_val = (res);

                                if (json_val['status']) {

                                    CommonMsg.ivm.sweetAlertMsg('Deleted!','Your record has been deleted.','success');
                                    setTimeout(function () {
                                        window.top.location = "{{ route('vehicle.list') }}";
                                    }, 1000);
                                } else {

                                    CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE,json_val['message'],'warning');
                                }
                            });

                        } else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            CommonMsg.ivm.sweetAlertMsg('Cancelled','','error');
                        }
                    })
                };

                //function for set dropdown selected value for knockout variable
                self.onClickGetItemTypeValue = function () {

                    self.itemType(getSelect2Val('ID_itemtype'));
                };

                //function for set dropdown selected value for knockout variable
                self.onClickGetUOMValue = function () {

                    self.unitOfMesureId(getSelect2Val('ID_uofm'));
                };

                //function for set dropdown selected value for knockout variable
                self.onClickGetPrefVenValue = function () {

                    self.prefVen(getSelect2Val('ID_prefVen'));
                };

                // this function will show html element based on the form status. like edit and new form
                self.designChange = function (state) {

                    if(state){

                        $('#ID_Delete').attr('style','display:none');
                        // $('#ID_activeCheck').attr('style','display:none');
                    }else{
                        // $('#account_row').attr('style','display:none');
                    }
                }
            }
        }
    </script>
@endpush
