@extends('template.master')
@section('title','New Item')

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
                        <h3 class="card-title">Create Service Type</h3>
                        <div class="card-tools">
                            <a class="btn btn-sm btn-secondary no-shadow" href="{{ route('servicetype.list') }}">  <i class="fas fa-hand-o-left"></i>Go Back</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="form">
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Service Name</label><span style="color: red;">*</span>
                                        <input type="text" id="ID_serviceName" class="form-control" data-bind="value: serviceName">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Service Description</label>
                                        <textarea class="form-control" rows="1" data-bind="value: serviceDescript"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Price</label>
                                        <input type="text" id="ID_price" class="form-control text-right" data-bind="value: price" onblur="APP_FormatNumber('ID_price');">
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
                            <!-- start of item table-->
                            <div class="row" style="margin-top: 12px;">
                                <div class="col-md-4">
                                    <label>Item Name</label>
                                    <select class="form-control " id="ID_ItemName" onchange="Service_type.ivm.getItemDetails()">

                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <label>Qty</label>
                                    <input type="number" class="form-control text-center" id="ID_itemQty" data-bind="value: itemQty" onblur="Service_type.ivm.calculateSubTotal();">
                                </div>
                                <div class="col-md-2">
                                    <label>Rate</label>
                                    <input type="text" class="form-control text-right" id="ID_itemRate" data-bind="value: itemRate" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Discounted Amount</label>
                                    <input type="text" class="form-control text-right" id="ID_discount_amount" data-bind="value: discount_amount">
                                </div>
                                <div class="col-md-2">
                                    <label>Amount</label>
                                    <input type="text" class="form-control text-right" id="ID_itemAmount" data-bind="value: itemAmount" readonly>
                                </div>
                                <div class="col-md-1" style="margin-top: 32px;">
                                    <button type="button" class="btn btn-info btn-icon" style="width: 100%" onclick="javascript: Service_type.ivm.addItemsToGrid();"><i class="fa fa-plus"></i> </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12" style="margin-top: 12px;">
                                    <div class=" table-responsive">
                                        <table class="table table-condensed table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th style="width: 20%" class="text-center">Item Name</th>
                                                <th style="width: 40%" class="text-center">Item Description</th>
                                                <th style="width: 8%;" class="text-center">Qty</th>
                                                <th  class="text-center" style="width: 15%;" >Amount</th>
                                                <th  class="text-center" style="width: 15%;" >Discounted Amount</th>
                                                <th  class="text-center" style="width: 8%;">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody data-bind="foreach: grnItemList">
                                            <tr>
                                                <td data-bind="text: col1" class="text-center"></td>
                                                <td data-bind="text: col2" class="text-left"></td>
                                                <td data-bind="text: col3" class="text-center"></td>
                                                <td data-bind="text: col5" class="text-right"></td>
                                                <td data-bind="text: col6" class="text-right ">DisAmount</td>
                                                <td class="text-center" ><i class="fa fa-trash-alt" style="cursor: pointer;" data-bind="click: $parent.RemoveGridRow.bind($data, $index())"></i></td>
                                            </tr>

                                            </tbody>
                                        </table>
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
                                <button type="button" class="btn btn-success btn-sm float-right" onclick="Service_type.ivm.formSubmit();"><i class="fas fa-save"></i> Submit</button>

                                <button id="ID_Delete" class="btn btn-danger pull-right" style="margin-right: 5px" onclick="Service_type.ivm.DeleteConfirm();"><i class="icon-cancel-square"></i> Delete</button>
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
            Service_type.init();

            //activate the Icheck checkbox styles
            $('#ID_itemActive').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%'
            });
        });
        Service_type = {
            ivm: null,
            init: function (){
                Service_type.ivm = new Service_type.Service_typeViewModel();
                //binding the knockout to the dom
                ko.applyBindings(Service_type.ivm,$('#Item_add_view')[0]);

                //initialize the designs when page loads
                Service_type.ivm.designInitiate();
            },
            Service_typeViewModel: function (){

                var self = this;

                //declaring the knockout variables
                self.isNew = ko.observable(true);
                self.Service_typeID = ko.observable("");

                var MsgHead = CommonMsg.ivm.toastMsgHead;
                var MsgContent = CommonMsg.ivm.toastMsgContent;

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

                //design initialization for filling dropdown etc
                self.designInitiate = function () {

                    select2Dropdown("ID_ItemName","{{route('item.inventory.drop.list')}}");
                    select2Dropdown("ID_vehicleType","{{route('vehicletype.drop.list')}}");
                    self.designChange(true);
                }

                //function for set dropdown selected value for knockout variable

                //get item details for selected item
                self.getItemDetails = function (){

                    try {

                        self.ItemNameID(GetSelect2Val('ID_ItemName'));

                        if(typeof self.ItemNameID() === "undefined") return;

                        StartLoading();

                        $.ajax({
                            url: '{{route('item.single.detail')}}',
                            type: 'GET',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "ItemId": self.ItemNameID()
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading(); },
                            success: function (result) {
                                StopLoading();

                                self.itemRate(accounting.formatNumber(0,2));
                                if (result.count > 0){

                                    self.itemDescription(result.data[0].itemDescription);
                                    self.itemRate(accounting.formatNumber(result.data[0].itemCost,2));
                                    self.itemQty(1);
                                    self.itemType(result.data[0].itemType);

                                    if(result.countStock < 1)
                                        self.itemOnhand(0);
                                    else
                                        self.itemOnhand(result.dataStock[0].inStockQty);

                                    self.calculateSubTotal();
                                }
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

                    }catch (e){

                    }
                };

                //form submit function for relatively form condition like new or not. via that identify update or create.
                self.formSubmit = function () {

                    if(self.isNew()){
                        self.insertItemData();
                    }else{

                        self.updateItemData();
                    }
                };

                //form validation before submit the form and add items to table
                self.submitValidation = function () {

                    if(self.serviceName() === ""){

                        sweetAlertMsg(MsgHead.FVE,"Service Name "+MsgContent.CBE,'warning')
                        ErrorValidationColor('ID_serviceName',1);
                        return false;
                    }
                    ErrorValidationColor('ID_serviceName',0);

                    if(self.VehicleTypeNo() === ""){

                        sweetAlertMsg(MsgHead.FVE,"Vehicle Type "+MsgContent.CBE,'warning')
                        ErrorValidationColor('ID_vehicleType',1);
                        return false;
                    }
                    ErrorValidationColor('ID_vehicleType',0);

                    var arr_length = self.grnItemList().length;

                    for (var i =0; i < arr_length; i++){

                        if(parseInt(self.grnItemList()[i].col3()) > parseInt(self.grnItemList()[i].col10()) && self.grnItemList()[i].col7() === '1'){

                            sweetAlertMsg(MsgHead.FVE,self.grnItemList()[i].col1()+" Don't have sufficient System Qty.",'warning')
                            return false;
                        }
                    }

                    return true;
                };

                //calculate table sub total
                self.calculateSubTotal = function () {

                    if(isNaN(self.itemQty())) self.itemQty(0);
                    var amount = (self.itemQty() * parseFloat(self.itemRate().replace(/[^0-9-.]/g, '')));
                    self.itemAmount(accounting.formatNumber(amount,2));

                };

                //item insert function
                self.insertItemData = function () {

                    $.ajax({
                        url: '{{route('servicetype.create.submit')}}',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "service_name": self.serviceName(),
                            "service_description": self.serviceDescript(),
                            "price": self.price(),
                            "vehicle_type": self.VehicleTypeNo(),
                            "grid_item_rows": ko.toJSON(self.grnItemList)
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

                //add selected items to table grid
                self.addItemsToGrid = function () {

                    self.itemName($('#ID_ItemName').select2('data')[0].text);
                    self.grnItemList.push(new gridItemListRows());

                    self.clearTblFields();
                    if(!self.submitValidation()) return;

                    if(typeof self.ItemNameID() === "undefined" || self.ItemNameID() == ""){

                        sweetAlertMsg(MsgHead.FVE,"Please select an Item.",'warning')
                        return;
                    }
                };

                //create table array objects
                var gridItemListRows = function (obj) {
                    var item = this;

                    if(typeof obj ==="undefined"){

                        item.col1 = (typeof self.itemName() === "undefined" || self.itemName() == null) ? ko.observable("") : ko.observable(self.itemName());
                        item.col2 = (typeof self.itemDescription() === "undefined" || self.itemDescription() == null) ? ko.observable("") : ko.observable(self.itemDescription());
                        item.col3 = (typeof self.itemQty() === "undefined" || self.itemQty() == null) ? ko.observable("") : ko.observable(self.itemQty());
                        item.col4 = (typeof self.itemRate() === "undefined" || self.itemRate() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.itemRate(),2));
                        item.col5 = (typeof self.itemAmount() === "undefined" || self.itemAmount() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.itemAmount(),2));
                        item.col6 = (typeof self.discount_amount() === "undefined" || self.discount_amount() == null) ? ko.observable("") : ko.observable(accounting.formatNumber(self.discount_amount(),2));
                        item.col7 = (typeof self.itemType() === "undefined" || self.itemType() == null) ? ko.observable("") : ko.observable(self.itemType());
                        item.col8 = (typeof self.ItemNameID() === "undefined" || self.ItemNameID() == null) ? ko.observable("") : ko.observable(self.ItemNameID());
                        item.col9 = ko.observable("");
                        item.col10 = (typeof self.itemOnhand() === "undefined" || self.itemOnhand() == null) ? ko.observable("") : ko.observable(self.itemOnhand());

                    }else{

                        item.col1 = (typeof obj.itemName === "undefined" || obj.itemName == null) ? ko.observable("") : ko.observable(obj.itemName);
                        item.col2 = (typeof obj.itemDescription === "undefined" || obj.itemDescription == null) ? ko.observable("") : ko.observable(obj.itemDescription);
                        item.col3 = (typeof obj.qty === "undefined" || obj.qty == null) ? ko.observable("") : ko.observable(obj.qty);
                        item.col4 = (typeof obj.rate === "undefined" || obj.rate == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.rate,2));
                        item.col5 = (typeof obj.amount === "undefined" || obj.amount == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.amount,2));
                        item.col6 = (typeof obj.discounted_rate === "undefined" || obj.discounted_rate == null) ? ko.observable("") : ko.observable(accounting.formatNumber(obj.discounted_rate,2));
                        item.col7 = (typeof obj.item_type === "undefined" || obj.item_type == null) ? ko.observable("") : ko.observable(obj.item_type);
                        item.col8 = (typeof obj.item_id === "undefined" || obj.item_id == null) ? ko.observable("") : ko.observable(obj.item_id);
                        item.col9 = (typeof obj.id === "undefined" || obj.id == null) ? ko.observable("") : ko.observable(obj.id);
                        item.col10 = (typeof obj.onHand === "undefined" || obj.onHand == null) ? ko.observable("") : ko.observable(obj.onHand);

                    }

                };

                //remove item row in table grid
                self.RemoveGridRow = function (indexNo,rowData) {

                    self.deleteItemList.push(rowData.col9());
                    self.grnItemList.remove(rowData);
                };

                //clearing the table fields
                self.clearTblFields = function () {

                    self.ItemNameID();
                    self.itemName("");
                    self.itemDescription("");
                    self.itemRate("");
                    self.itemQty("");
                    self.itemType("");
                    self.itemAmount("");
                    self.discount_amount("");
                    appSelectEmpty('ID_ItemName');
                };

                //form clearing function which clears all variables and dropdown values
                self.clearForm = function () {

                    self.serviceName("");
                    self.serviceDescript("");
                    self.price("");
                    self.VehicleTypeNo;
                    self.isActive(true);
                    appSelectEmpty('ID_vehicleType');
                    self.grnItemList.removeAll();
                    self.deleteItemList.removeAll();
                    self.clearTblFields();
                };

                //when selecting get vehicle type value
                self.onClickGetVehicleTypeValue = function () {

                    self.VehicleTypeNo(GetSelect2Val('ID_vehicleType'));
                };

                self.designChange = function (state) {

                    if(state){

                        $('#ID_Delete').attr('style','display:none');
                        $('#ID_activeCheck').attr('style','display:none');
                    }else{
                        // $('#account_row').attr('style','display:none');
                    }
                }
            }
        }
    </script>
@endpush
