@extends('template.master')
@section('title','Invoices')
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush

@section('content')
    <div class="card" id="employee_list_view">
        <div class="card-header">
            <h3 class="card-title">Invoices</h3>
            <div class="card-tools">
                @can('invoice-add')
                    <a class="btn btn-sm btn-success no-shadow" href="{{ route('invoice.create') }}">  <i class="glyphicon glyphicon-plus myicon-right"></i> Add New</a>
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
                                <th>Date</th>
                                <th>JO No</th>
                                <th>Invoice No</th>
                                <th>Customer Name</th>
                                <th>Status</th>
                                <th>Grand Total</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Paid Status</th>
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

        <!-- Payment adding model -->
        <div class="modal fade" id="payment_update_mod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Payment</h4>
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
                                    <input type="text" class="form-control text-right" id="amount_paid" data-bind="value: amount_paid">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-form-label">Paying by</label>
                                    <?php $paying_by = array('cash'=> 'Cash', 'CC' => 'Credit Card', 'Cheque' => 'Cheque', 'other' => 'Other', 'deposit' => 'Deposit') ?>
                                    <select id="paying_by" class="form-control" onchange="invoiceList.ivm.setPayingBy()">
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
                                        <select title="Select card type here." id="card_type" class="form-control" onchange="invoiceList.ivm.setCardType()">
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
                        <button type="button" class="btn btn-success" data-dismiss="modal" onclick="invoiceList.ivm.insertSalePayment();">Done</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>


    <!--Showing model -->
    <div class="modal fade" id="emp_show_mod">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Details of <span id="show_mod_title" ></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <dl class="row">
                        <dt class="col-sm-3">First Name</dt>
                        <dd class="col-sm-9" id="show_mod_fname"></dd>
                        <dt class="col-sm-3">Last Name</dt>
                        <dd class="col-sm-9" id="show_mod_lname"></dd>
                    </dl>
                </div>
                <div class="modal-footer justify-content-between">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    @push('scripts')
        <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <script>

            $(document).ready(function () {
                invoiceList.init();
                // Pace.restart();
            })
            invoiceList = {
                ivm: null,
                init: function () {
                    invoiceList.ivm = new invoiceList.invoiceListViewModel();
                    ko.applyBindings(invoiceList.ivm, $('#employee_list_view')[0]);
                    invoiceList.ivm.listTable();
                },
                invoiceListViewModel: function () {
                    let self = this;

                    self.inv_id = ko.observable("");

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

                    let MsgHead = CommonMsg.ivm.toastMsgHead;
                    let MsgContent = CommonMsg.ivm.toastMsgContent;

                    self.listTable = function () {

                        var table = $('#example2').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: "{{ route('invoice.table.list') }}",
                            initComplete: ( settings, json ) =>{ $('[data-toggle="tooltip"]').tooltip() },
                            columns: [
                                {data: 'date', name: 'date'},
                                {data: 'joSerialNo', name: 'joSerialNo'},
                                {data: 'reference_no', name: 'reference_no'},
                                {data: 'name', name: 'name'},
                                {data: 'sale_status', name: 'sale_status'},
                                {data: 'grand_total', name: 'grand_total', sClass: 'text-right'},
                                {data: 'paid', name: 'paid', sClass: 'text-right'},
                                {data: 'balance', name: 'balance', sClass: 'text-right'},
                                {data: 'payment_status', name: 'payment_status'},
                                {
                                    data: 'action',
                                    name: 'action',
                                    orderable: false,
                                    searchable: false
                                },
                            ]
                        });
                    }

                    self.showPaymentAddModel = function (id) {
                        try {
                            self.inv_id(id);
                            if (typeof self.inv_id() === "undefined") return;

                            $.ajax({
                                url: '{{route('invoice.head.selected.data')}}',
                                type: 'GET',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                dataType: 'json',
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    "item_id": self.inv_id()
                                },
                                beforeSend: (jqXHR, settings) => { StartLoading(); },
                                success: (result) => {
                                    StopLoading();

                                    if (result.status) {
                                        let paid_amnt = parseFloat(result.data['headData'].grand_total) - parseFloat(result.data['headData'].paid);
                                        self.amount_paid(paid_amnt);

                                        $('#payment_update_mod').modal('show');
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

                    //paying by cash or card or cheque
                    self.setPayingBy = function () { self.paying_by($('#paying_by option:selected').val()); }
                    self.setCardType = function () { self.pcc_type($('#card_type option:selected').val()); }

                    self.submitValidation = () => {
                        if($.trim(self.amount_paid()) == ""){
                            sweetAlertMsg(MsgHead.FVE,"Amount "+MsgContent.CBE,'warning')
                            ErrorValidationColor('amount_paid',1);
                            return false;
                        }
                        return true;
                    }

                    self.insertSalePayment = () => {
                        if(!self.submitValidation()){
                            return;
                        }

                        $.ajax({
                            url: '{{route('invoice.payment.submit')}}',
                            type: 'POST',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            dataType: 'json',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "inv_id": self.inv_id(),
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
                                "payment_status": self.payment_status(),
                            },
                            beforeSend: (jqXHR, settings) => { StartLoading(); },
                            success: (responseData) => {
                                StopLoading();
                                // self.clearForm();
                                $('#example2').DataTable().ajax.reload();
                                sweetAlertMsg('Success', responseData.message, 'success')
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
                    }

                    self.showDetails = function (empID) {

                        $.ajax({
                            url: "{{ route('joborder.show') }}",
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
                                    url: "{{ route('invoice.destroy.submit') }}",
                                    dataType: 'json',
                                    type: 'post',
                                    contentType: 'application/x-www-form-urlencoded',
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        emp_id: fbID,
                                    },
                                    beforeSend: function (jqXHR, settings) {

                                    },
                                    success: function( data, textStatus, jQxhr ){

                                        if(data.status){

                                            $('#example2').DataTable().ajax.reload();
                                            CommonMsg.ivm.sweetAlertMsg('Success!',data.message,'success');
                                        }else
                                            CommonMsg.ivm.sweetAlertMsg(MsgHead.FSE,MsgContent.FSE,'warning');
                                    },
                                    error: function( jqXhr, textStatus, errorThrown ){
                                        if(jqXhr.status == 403){
                                            CommonMsg.ivm.sweetAlertMsg(MsgHead.PE,'Users does not have the right permissions.','warning');
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

