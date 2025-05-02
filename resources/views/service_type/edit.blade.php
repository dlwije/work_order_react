@extends('template.master')
@section('title','Service Packages')

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
                        <h3 class="card-title">Edit Item</h3>
                        <div class="card-tools">
                            <a class="btn btn-sm btn-secondary no-shadow" href="{{ route('item.list') }}">  <i class="fas fa-hand-o-left"></i>Go Back</a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form id="form">
                            <div class="row">
                                <div class="col-sm-4 col-md-4 hide">
                                    <div class="">
                                        <label class="control-label">Item Code</label>
                                        <input type="text" id="ID_itemcode" class="form-control " data-bind="value:manufacNo">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Item Name</label><span style="color: red;">*</span>
                                        <input type="text" id="ID_itemname" class="form-control " data-bind="value:itemName">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Item Type</label><span style="color: red;">*</span>
                                        <select class="form-control " id="ID_itemtype" onchange="javascript:Item.ivm.onClickGetItemTypeValue();">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Item Unit</label>
                                        <select class="form-control" id="ID_uofm" onchange="javascript:Item.ivm.onClickGetUOMValue();">
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Item Description</label>
                                        <textarea id="ID_itemdesc" class="form-control " rows="1" data-bind="value:itemDescription"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Item Cost</label>
                                        <input type="text" id="ID_itemcost" class="form-control  text-right" data-bind="value:itemCost" onblur="APP_FormatNumber('ID_itemcost')">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Sales Price</label>
                                        <input type="text" id="ID_salesprice" class="form-control  text-right" data-bind="value:salesPrice" onblur="APP_FormatNumber('ID_salesprice')">
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Sales Description</label>
                                        <textarea id="ID_salesdesc" class="form-control" rows="1" data-bind="value:salesDescription"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Item Reorder(Min)</label>
                                        <input type="text" id="ID_reordermin" class="form-control  text-right" data-bind="value:itemReorderMin">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="">
                                        <label class="control-label">Item Reorder(Max)</label>
                                        <input type="text" id="ID_reordermax" class="form-control  text-right" data-bind="value:itemReorderMax">
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Item Supplier</label><span style="color: red;">*</span>
                                        <select class="form-control " id="ID_prefVen" onchange="javascript:Item.ivm.onClickGetPrefVenValue();">

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">Service Mileage</label>
                                        <select class="form-control " id="ID_serviceMileage" onchange="javascript:Item.ivm.onClickSetSmileage();">

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

                                <button type="button" class="btn btn-success btn-sm float-right" onclick="Item.ivm.formSubmit();"><i class="fas fa-save"></i> Submit</button>

                                <button id="ID_Delete" class="btn btn-danger btn-sm float-right" style="margin-right: 5px" onclick="Item.ivm.DeleteConfirm();"><i class="icon-cancel-square"></i> Delete</button>
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
            Item.init();
        });
        Item = {
            ivm: null,
            init: function (){
                Item.ivm = new Item.ItemViewModel();
                ko.applyBindings(Item.ivm,$('#Item_add_view')[0]);
                Item.ivm.designInitiate();
            },
            ItemViewModel: function (){

                var self = this;

                //declaring the knockout variables and binding id specific data relatively variables
                self.isNew = ko.observable(false);
                self.itemID = ko.observable("{{ $editData->id }}");

                self.manufacNo = ko.observable("{{ $editData->manufacNo }}");
                self.itemName = ko.observable("{{ $editData->itemName }}");
                self.itemNameOld = ko.observable("{{ $editData->itemName }}");
                self.itemType = ko.observable("{{ $editData->itemType }}");
                self.unitOfMesureId = ko.observable("{{ $editData->unitOfMesureId }}");
                self.itemDescription = ko.observable("{{ $editData->itemDescription }}");
                self.itemCost = ko.observable("{{ $editData->itemCost }}");
                self.salesDescription = ko.observable("{{ $editData->salesDescription }}");
                self.salesPrice = ko.observable("{{ $editData->salesPrice }}");
                self.itemReorderMin = ko.observable("{{ $editData->itemReorderMin }}");
                self.itemReorderMax = ko.observable("{{ $editData->itemReorderMax }}");
                self.prefVen = ko.observable("{{ $editData->prefVenid }}");
                self.itemserviceMileage = ko.observable("");
                self.isActive = ko.observable(true);

                //getting predefined msg strings from common file
                var MsgHead = CommonMsg.ivm.toastMsgHead;
                var MsgContent = CommonMsg.ivm.toastMsgContent;

                //design initialization for filling dropdown etc and selected already added values to the dropdown
                self.designInitiate = function () {
                    select2Dropdown("ID_uofm","{{ route('uofm.drop.list') }}","{{route('uofm.byid.drop.list')}}",self.itemID());
                    select2Dropdown("ID_itemtype","{{route('itemtype.drop.list')}}", "{{route('itemtype.byid.drop.list')}}", self.itemType());
                    select2Dropdown("ID_prefVen","{{route('supplier.drop.list')}}", "{{route('supplier.byid.drop.list')}}", self.prefVen());
                    self.setServiceMileageOptions();
                    self.designChange(false);
                }

                //function for set dropdown selected value for knockout variable
                self.onClickSetSmileage = function (){

                    self.itemserviceMileage($('#ID_serviceMileage').val());
                };

                //set service mileage options to drop down
                self.setServiceMileageOptions = function (){

                    var strOption = "<option></option>";
                    var SMArray = ['5000','9000'];

                    $.each(SMArray, function (i,item) {

                        strOption += "<option value='"+item+"'>"+item+"</option>";
                    });

                    $('#ID_serviceMileage').html(strOption);
                    self.itemserviceMileage("{{ $editData->serviceMileadge }}");
                    $('#ID_serviceMileage').val(self.itemserviceMileage());

                    $('#ID_serviceMileage').select2();
                };

                //form submit function for relatively form condition like new or not. via that identify update or create.
                self.formSubmit = function () {

                    if(self.isNew()){ self.insertItemData(); }else{ self.updateItemData(); }
                };

                //item insert function but no need it here. just put it.
                self.insertItemData = function () {

                    $.ajax({
                        url: '{{route('item.createsubmit')}}',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "manufacNo": self.manufacNo(),
                            "itemName": self.itemName(),
                            "itemType": self.itemType(),
                            "unitOfMesureId": (typeof self.unitOfMesureId() === "undefined" || self.unitOfMesureId() === "" ) ? null : self.unitOfMesureId(),
                            "itemDescription": self.itemDescription(),
                            "itemCost": self.itemCost(),
                            "salesDescription": self.salesDescription(),
                            "salesPrice": self.salesPrice(),
                            "itemReorderMin": self.itemReorderMin(),
                            "itemReorderMax": self.itemReorderMax(),
                            "itemserviceMileage": self.itemserviceMileage(),
                            "prefVen": self.prefVen()
                        },
                        beforeSend: function (jqXHR, settings) { StartLoading(); },
                        success: function (responseData) {
                            StopLoading();
                            sweetAlertMsg('Success',responseData.message,'success')
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
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

                //item update function
                self.updateItemData = function () {
                    $.ajax({
                        url: '{{route('item.update')}}',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "id": self.itemID(),
                            "manufacNo": self.manufacNo(),
                            "itemName": self.itemName(),
                            "itemNameOld": self.itemNameOld(),
                            "itemType": self.itemType(),
                            "unitOfMesureId": (typeof self.unitOfMesureId() === "undefined" || self.unitOfMesureId() === "" ) ? null :self.unitOfMesureId(),
                            "itemDescription": self.itemDescription(),
                            "itemCost": self.itemCost(),
                            "salesDescription": self.salesDescription(),
                            "salesPrice": self.salesPrice(),
                            "itemReorderMin": self.itemReorderMin(),
                            "itemReorderMax": self.itemReorderMax(),
                            "itemserviceMileage": self.itemserviceMileage(),
                            "prefVen": self.prefVen(),
                            "isActive": self.isActive()
                        },
                        beforeSend: function (jqXHR, settings) { StartLoading(); },
                        success: function (responseData) {
                            StopLoading();
                            //showing sweet alert message via common file function by passing parameters
                            sweetAlertMsg('Success',responseData.message,'success')

                            //time out for auto matically redirect after update done.
                            setTimeout(function () {
                                window.top.location = "{{ route('item.list') }}";
                            }, 1000);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
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
                            $.post("{{ route('item.destroy') }}", { "_token": "{{ csrf_token() }}",'iID': self.itemID() }, function (res) {
                                StopLoading();
                                var json_val = (res);

                                if (json_val['status']) {

                                    CommonMsg.ivm.sweetAlertMsg('Deleted!','Your record has been deleted.','success');
                                    setTimeout(function () {
                                        window.top.location = "{{ route('item.list') }}";
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

                    self.itemType(GetSelect2Val('ID_itemtype'));
                };

                //function for set dropdown selected value for knockout variable
                self.onClickGetUOMValue = function () {

                    self.unitOfMesureId(GetSelect2Val('ID_uofm'));
                };

                //function for set dropdown selected value for knockout variable
                self.onClickGetPrefVenValue = function () {

                    self.prefVen(GetSelect2Val('ID_prefVen'));
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
