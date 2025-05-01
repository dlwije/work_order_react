@extends('template.master')
@section('title','Work Orders')
@push('css')
    <link href="{{ asset('assets/plugins/DateTimePicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/select2/css/Overide.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@section('content')
    <div class="card" id="workorder_list_view">
        <div class="card-header">
            <h3 class="card-title">Work Orders</h3>
            <div class="card-tools">
                @can('workorder-add')
                    <a class="btn btn-sm btn-success no-shadow" href="{{ route('workorder.create') }}">  <i class="glyphicon glyphicon-plus myicon-right"></i> Add New</a>
                @endcan
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Serial No</th>
                                <th>WO Date</th>
                                <th>Name</th>
                                <th>Vin No</th>
                                <th>WO Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Deposit model -->
        <div class="modal fade" id="deposit_mod">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Deposit</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Customer <span class="text-danger">*</span>(Total: <span data-bind="html: depo_tot_amnt"></span>)</label>
                                    <select class="form-control" id="id_depo_cus_id" onchange="workOrderList.ivm.setDepositCusId()" ></select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Deposit Date</label>
                                    <input type="text" class="form-control text-center" id="id_depo_date" data-bind="value: depo_date" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Amount</label>
                                    <input type="number" class="form-control text-right" id="id_depo_amnt" data-bind="value: depo_amnt" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Note</label>
                                    <textarea class="form-control" rows="2" id="id_depo_note" data-bind="value: depo_note" ></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Paid By</label>
                                    <input type="text" class="form-control text-left" id="id_depo_paid_type" placeholder="EX:cash, cheque" data-bind="value: depo_paid_type" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="workOrderList.ivm.submitDepositData()">Submit</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
    </div>

    @push('scripts')
        <script src="{{asset('assets/plugins/DateTimePicker/jquery.datetimepicker.full.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
        <script>

            $(document).ready(function () {
                workOrderList.init();
                // Pace.restart();
                $('#id_depo_date').datetimepicker({ mask:'9999-19-39 00:00', format:'Y-m-d H:i' });
                $('#deposit_mod').on('hide.bs.modal', function () {
                    workOrderList.ivm.clearForm();
                })
            })
            workOrderList = {
                ivm: null,
                init: function () {
                    workOrderList.ivm = new workOrderList.workOrderListViewModel();
                    ko.applyBindings(workOrderList.ivm, $('#workorder_list_view')[0]);
                    workOrderList.ivm.listTable();
                },
                workOrderListViewModel: function () {
                    let self = this;

                    self.itemID = ko.observable("");

                    self.depo_cus_id = ko.observable("");
                    self.depo_cus_name = ko.observable("");
                    self.depo_date = ko.observable("");
                    self.depo_amnt = ko.observable(0.0);
                    self.depo_paid_type = ko.observable("");
                    self.depo_note = ko.observable("");

                    self.depo_tot_amnt = ko.observable("");

                    self.deposit_amnt_list = ko.observableArray();
                    self.deposit_amnt_delete_list = ko.observableArray();


                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.listTable = function () {

                        var table = $('#example2').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('workorder.table.list') }}",
                            drawCallback: ( settings, json ) =>{ $('[data-toggle="tooltip"]').tooltip() },
                            columns: [
                                {data: 'serial_no', name: 'serial_no'},
                                {data: 'wo_date', name: 'wo_date'},
                                {data: 'name', name: 'name'},
                                {data: 'engine_no', name: 'engine_no'},
                                {data: 'woStatus', name: 'woStatus'},
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

                    self.showDetails = function (empID) {
                        $.ajax({
                            url: "{{ route('workorder.show') }}",
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
                                StopLoading();
                            }
                        });
                    }

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
                                    url: "{{ route('workorder.destroy') }}",
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        emp_id: fbID,
                                    },
                                    beforeSend:  (jqXHR, settings) =>  {
                                        StartLoading();
                                    },
                                    success: ( data, textStatus, jQxhr ) => {
                                        StopLoading();
                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!',data.message,'success');
                                        }else
                                            sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                    },
                                    error: ( jqXhr, textStatus, errorThrown ) => {
                                        StopLoading();
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
                    self.transferToEstimate = function(fbID){

                        Swal.fire({
                            title: 'Are you sure, You want to transfer this to estimate?',
                            icon: 'warning',
                            buttonsStyling: false,
                            showCancelButton: true,
                            confirmButtonText: 'Yes, transfer it!',
                            customClass: {
                                confirmButton: 'btn btn-primary mr-1',
                                cancelButton: 'btn btn-warning'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: "{{ route('transfer.estimate.submit') }}",
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        emp_id: fbID,
                                    },
                                    beforeSend:  (jqXHR, settings) =>  {
                                        StartLoading();
                                    },
                                    success: ( data, textStatus, jQxhr ) => {
                                        StopLoading();
                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            sweetAlertMsg('Success!',data.message,'success');
                                        }else
                                            sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                    },
                                    error: ( jqXhr, textStatus, errorThrown ) => {
                                        StopLoading();
                                        if(jqXhr.status == 403){
                                            sweetAlertMsg(MsgHead.PE,'User does not have the right permissions.','warning');
                                        }
                                    }
                                });

                            } else {

                                CommonMsg.ivm.sweetAlertMsg('Canceled!','Transferring canceled.','info');
                            }
                        })
                    }
                    self.showDepositView = (wo_id) => {
                        self.itemID(wo_id);

                        $.ajax({
                            url: "{{ route('workorder.show') }}",
                            dataType: 'json',
                            type: 'get',
                            contentType: 'application/x-www-form-urlencoded',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                wo_id: self.itemID(),
                            },
                            beforeSend: function (jqXHR, settings) { StartLoading(); },
                            success: function( data, textStatus, jQxhr ){
                                StopLoading();

                                if(data.status){
                                    self.depo_cus_id(data.data.data[0].customer_id)
                                    select2Dropdown('id_depo_cus_id', '{{ route('customer.drop.list') }}', '{{ route('customer.byid.drop.list') }}', self.depo_cus_id())


                                    self.depo_tot_amnt(data.data.depoTot)
                                    $('#deposit_mod').modal('show')

                                }else
                                    sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                            },
                            error: function( jqXhr, textStatus, errorThrown ){
                                StopLoading();
                            }
                        });
                    }

                    self.setDepositCusId = () => {
                        self.depo_cus_id(getSelect2Val('id_depo_cus_id'));
                    }

                    self.formValidation = () => {

                        if(self.depo_cus_id() === "" || typeof self.depo_cus_id() === "undefined")
                        {
                            sweetAlertMsg('Required!','Customer need to be selected to proceed.','warning');
                            ErrorValidationColor('id_depo_cus_id',1)
                            return false;
                        }
                        ErrorValidationColor('id_depo_cus_id',0)

                        return true;
                    }

                    self.submitDepositData = () =>{
                        if(!self.formValidation()) return;

                        $.ajax({
                            url: '{{route('deposit.create.submit')}}',
                            type: 'POST',
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "company_id": self.depo_cus_id(),
                                "date": self.depo_date(),
                                "amount": self.depo_amnt(),
                                "paid_by": self.depo_paid_type(),
                                "note": self.depo_note(),
                            },
                            beforeSend: (jqXHR, settings) => { StartLoading(); },
                            success: (responseData) => {
                                StopLoading();
                                self.clearForm();

                                $('#deposit_mod').modal('hide')
                                sweetAlertMsg('Success',responseData.message,'success')
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

                    self.clearForm = () => {
                        self.itemID("");
                        self.depo_cus_id("");
                        self.depo_cus_name("");
                        self.depo_date("");
                        self.depo_amnt(0.0);
                        self.depo_paid_type("");
                        self.depo_note("");
                        self.depo_tot_amnt("");

                        self.deposit_amnt_list.removeAll();
                        self.deposit_amnt_delete_list.removeAll();
                    }
                }
            }
        </script>
    @endpush
@endsection

