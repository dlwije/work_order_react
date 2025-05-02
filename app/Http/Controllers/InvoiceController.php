<?php

namespace App\Http\Controllers;

use App\Models\CustomerSMA;
use App\Models\Employee;
use App\Models\FormSerial;
use App\Models\InvoiceServPackage;
use App\Models\InvoiceTask;
use App\Models\JobOrder;
use App\Models\JobOrderItem;
use App\Models\JobOrderServPackage;
use App\Models\JobOrderTask;
use App\Models\JobOrderTaskItem;
use App\Models\JobOrderTaskTechnician;
use App\Models\JobOrderTaskTechTime;
use App\Models\OrderRefSMA;
use App\Models\PaymentSMA;
use App\Models\ProductSMA;
use App\Models\ProductVariantSMA;
use App\Models\SaleItemSMA;
use App\Models\SaleSMA;
use App\Models\ServicePackage;
use App\Models\SettingSMA;
use App\Models\UnitSMA;
use App\Models\VehicleType;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class InvoiceController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:invoice-list', ['only' => ['index', 'getDataTableList']]);
//        $this->middleware('permission:invoice-view', ['only' => ['index', 'getDataTableList']]);
        $this->middleware('permission:invoice-add', ['only' => ['create', 'store']]);
        $this->middleware('permission:invoice-edit', ['only' => ['edit', 'update', 'editData']]);
//        $this->middleware('permission:invoice-delete', ['only' => ['index', 'getDataTableList']]);
    }

    public function index(){
        return view('invoice/invoice_list');
    }

    public function getDataTableList(Request $request){
        if ($request->ajax()) {

            $cus_tbl = (new CustomerSMA())->getTable();
            $sale_tbl = (new SaleSMA())->getTable();
            $jb_order_tbl = (new JobOrder())->getTable();

            $data = SaleSMA::query()
                ->select($sale_tbl.'.*', DB::raw($jb_order_tbl.'.serial_no AS joSerialNo'), $cus_tbl.'.name')
                ->leftJoin($jb_order_tbl,$jb_order_tbl.'.id','=',$sale_tbl.'.jo_id')
                ->leftJoin($cus_tbl,$cus_tbl.'.id','=',$sale_tbl.'.customer_id')
                ->where($sale_tbl.'.workorder',1);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return !empty($user->updated_at) ? $user->updated_at->format('Y-m-d') : '';
                    })->editColumn('sale_status', function ($row) {
                        if($row->sale_status == "completed")
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Invoice completed">Completed</span>';
                        elseif ($row->sale_status == "pending")
                            $state = '<span class="text-black badge badge-warning" data-toggle="tooltip" data-placement="top" title="Invoice pending">Pending</span>';
                        else $state = '';
                        return $state;
                    })->editColumn('payment_status', function ($row) {
                        if($row->payment_status == "pending")
                            $state = '<span class="text-black badge badge-warning" data-toggle="tooltip" data-placement="top" title="Invoice pending payment">Pending</span>';
                        elseif ($row->payment_status == "due")
                            $state = '<span class="text-black badge badge-danger" data-toggle="tooltip" data-placement="top" title="Invoice due">Due</span>';
                        elseif ($row->payment_status == "partial")
                            $state = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Invoice partially paid">Partially</span>';
                        elseif ($row->payment_status == "paid")
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Invoice fully paid">Paid</span>';
                        else $state = '';
                        return $state;
                    })
                    ->editColumn('grand_total', function ($row) { return number_format($row->grand_total, SettingSMA::getSettingSma()->decimals); })
                    ->editColumn('paid', function ($row) { return number_format($row->paid, SettingSMA::getSettingSma()->decimals); })
                    ->addIndexColumn()
                    ->addColumn('balance', function ($row) {
                        $balance = $row->grand_total - $row->paid;
                        return number_format($balance,SettingSMA::getSettingSma()->decimals);
                    })
                    ->addColumn('action', function ($row) {
                        $actionBtn = '';
                        $actionListBtn = '<div class="btn-group-sm">';
                            $actionListBtn .= '<button type="button" class="btn btn-default">Actions</button>';
                            $actionListBtn .= '<button type="button" class="btn btn-default dropdown-toggle dropdown-hover dropdown-icon" data-toggle="dropdown"> <span class="sr-only">Toggle Dropdown</span></button>';
                            $actionListBtn .= '<div class="dropdown-menu" role="menu">';

                                $actionListBtn .= '<span onclick="invoiceList.ivm.showPaymentAddModel(' . $row->id . ')" class="dropdown-item" style="cursor: pointer"><i class="fas fa-dollar-sign"></i> &nbsp;&nbsp;&nbsp;Add Payment</span>';

                                if(auth()->user()->hasPermissionTo('invoice-edit') )
                                    $actionListBtn .= '<a href="' . route('invoice.edit', $row->id) . '" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>';
                                if(auth()->user()->hasPermissionTo('invoice-delete') )
                                    $actionListBtn .= '<span onclick="invoiceList.ivm.deleteRecord(' . $row->id . ')" class="dropdown-item" style="cursor: pointer"><i class="fas fa-trash-alt"></i> Delete</span>';
                            $actionListBtn .= '</div>';
                        $actionListBtn .= '</div>';

                        $actionBtn = $actionListBtn;
                        return $actionBtn;
                    })
                    ->rawColumns(['action', 'sale_status', 'payment_status'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);})
                    ->filterColumn('joSerialNo', function ($query, $keyword) { $query->whereRaw((new JobOrder())->getTable().".serial_no like ?", ["%$keyword%"]);})
                    ->filterColumn('name', function ($query, $keyword) { $query->whereRaw((new CustomerSMA())->getTable().".name like ?", ["%$keyword%"]);})
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getInvoiceError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function create(){
        return view('invoice/create');
    }

    public function store(Request $request){

        $validateMsgs = [
            'customer_id.required' => 'The Customer name field is required',
            'jo_id.required' => 'The Job No field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'customer_id'      => ['required'],
            'jo_id'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $reference = $request->reference_no ?? OrderRefSMA::getReference('so');
            $date = date('Y-m-d H:i:s');
            if(!empty($request->inv_date)) $date = $request->inv_date;

            $warehouse_id = self::getDefaultValues()['warehouse_default_id'];
            $customer_id = $request->customer_id;
            $biller_id = self::getDefaultValues()['biller_default_id'];
            $total_items = 0;//no need tot count here
            $sale_status = empty($request->sale_status) ? 'pending' : $request->sale_status;
            $payment_status = empty($request->payment_status) ? 'due' : $request->payment_status;
            $payment_term = "";
            $due_date         = $payment_term ? date('Y-m-d', strtotime('+' . $payment_term . ' days', strtotime($date))) : null;
            $shipping         = $request->shipping_amnt ? str_replace(',','',$request->shipping_amnt) : 0;
            $customer_details = CustomerSMA::getCompanyByID($customer_id);
            $customer         = !empty($customer_details->company) && $customer_details->company != '-' ? $customer_details->company : $customer_details->name;
            $biller_details   = CustomerSMA::getCompanyByID($biller_id);
            $biller           = !empty($biller_details->company) && $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
            $note             = isset($request->note) ? $this->clear_tags($request->note) : '';
            $staff_note = '';
            $quote_id         = $request->quote_id ?? null;

            $total            = 0;
            $product_tax      = 0;
            $product_discount = 0;
            $digital          = false;
            $gst_data         = [];
            $total_cgst       = $total_sgst       = $total_igst       = 0;
            $i                = isset($request->product_code) ? sizeof($request->product_code) : 0;

            $grid_items = json_decode($request->grid_item_rows);
            $total_items = sizeof($grid_items);

            //invoice labor tasks array
            $grid_tasks = json_decode($request->grid_task_rows);
            $tasks = array();
            foreach ($grid_tasks AS $task){

                $task = [
                    'jo_id' => $request->jo_id,
                    'task_id' => $task->col8,
                    'task_name' => $task->col1,
                    'task_description' => $task->col2,
                    'hours' => !empty($task->col3) ? str_replace(',','',$task->col3): 0.0,
                    'hourly_rate' => !empty($task->col5) ? str_replace(',','',$task->col5): 0.0,
                    'actual_hours' => !empty($task->col4) ? str_replace(',','',$task->col4): 0.0,
                    'labor_cost' => !empty($task->col6) ? str_replace(',','',$task->col6): 0.0,
                    'total' => !empty($task->col7) ? str_replace(',','',$task->col7): 0.0,
                ];
                $tasks[] = $task;
            }

            //invoice service pkgs array
            $serv_pkgs = json_decode($request->serv_pkg_rows);
            $servPkgs = array();
            $serv_total = 0;
            $serv_total_discount = 0;
            foreach ($serv_pkgs AS $task){
                $servPkg = [
                    'service_pkg_id' => $task->col6,
                    'vehi_type_id' => $request->vehi_type_id ?? null,
                    'cost' => str_replace(',', '', $task->cost),
                    'discount' => str_replace(',', '', $task->col3),
                    'tot_discount' => str_replace(',', '', $task->col3),
                    'price' => str_replace(',', '', $task->col4),
                    'sub_total' => str_replace(',', '', $task->col5),
                ];
                $serv_total += $servPkg['sub_total'];
                $serv_total_discount += $servPkg['tot_discount'];
                $servPkgs[] = $servPkg;
            }

            //invoice items array
            foreach ($grid_items AS $task){

                $service_pkg_id        = $task->service_pkg_id;
                $item_id            = $task->product_id;
                $item_type          = $task->item_type;
                $item_code          = $task->item_code;
                $item_name          = $task->item_name;
                $item_option        = isset($task->item_option) && $task->item_option != 'false' && $task->item_option != 'null' ? $task->item_option : "";
                $real_unit_price    = $this->formatDecimal(str_replace(',','',$task->real_unit_price));
                $unit_price         = $this->formatDecimal(str_replace(',','',$task->unit_price));
                $item_unit_quantity = str_replace(',','',$task->item_qty);
                $item_serial        = $task->item_serial           ?? '';
                $item_tax_rate      = $task->tax->id      ?? null;
                $item_discount      = !empty($task->item_discount) ? str_replace(',', '', $task->item_discount): 0;
                $item_unit          = $task->product_unit;
                $item_quantity      = str_replace(',','',$task->base_quantity);

                if(!empty($task->item_discount) && $task->item_discount > 0){
                    Log::info("Old_inv_master_id: ".$task->old_invoice_master_id);
                    Log::info("real_unit_price: ".$real_unit_price);
                    Log::info("unit_price: ".$unit_price);
                    Log::info("item_discount: ".$item_discount);
                    Log::info("item_unit_qty: ".$item_unit_quantity);
                }

                if (isset($item_code) && isset($real_unit_price) && isset($unit_price) && isset($item_quantity)) {
                    $product_details = $item_type != 'manual' ? ProductSMA::getProductByCode($item_code) : null;
                    // $unit_price = $real_unit_price;
                    if ($item_type == 'digital') {
                        $digital = true;
                    }

                    $pr_discount      = $this->calculateDiscount($item_discount, $unit_price);
                    $unit_price       = $unit_price > 0 ? $this->formatDecimal(max($unit_price - $pr_discount, 0)) : $unit_price;
                    $item_net_price   = $unit_price;
                    $pr_item_discount = $this->formatDecimal($pr_discount * $item_unit_quantity);
                    $product_discount += $pr_item_discount;
                    $pr_item_tax = $item_tax = 0;
                    $tax         = '';

                    if(!empty($task->item_discount) && $task->item_discount > 0){
                        Log::info("pr_discount: ".$pr_discount);
                        Log::info("after_discount_unit_price: ".$unit_price);
                        Log::info("after_discount_pr_item_discount: ".$pr_item_discount);
                    }

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $tax_details = SaleSMA::getTaxRateByID($item_tax_rate);
                        $ctax        = $this->calculateTax($product_details, $tax_details, $unit_price);
                        $item_tax    = $this->formatDecimal($ctax['amount']);
                        $tax         = $ctax['tax'];
                        if (!$product_details || (!empty($product_details) && $product_details->tax_method != 1)) {
                            $item_net_price = $unit_price - $item_tax;
                        }
                        $pr_item_tax = $this->formatDecimal(($item_tax * $item_unit_quantity), 4);
                        if (SettingSMA::getSettingSma()->indian_gst && $gst_data = $this->calculateIndianGST($pr_item_tax, ($biller_details->state == $customer_details->state), $tax_details)) {
                            $total_cgst += $gst_data['cgst'];
                            $total_sgst += $gst_data['sgst'];
                            $total_igst += $gst_data['igst'];
                        }
                    }

                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_price * $item_unit_quantity) + $pr_item_tax);
                    $unit     = UnitSMA::getUnitByID($item_unit);

                    if(!empty($task->item_discount) && $task->item_discount > 0){
                        Log::info("after_tax_sub_total: ".$subtotal);
                        Log::info("after_tax_unit: ".json_encode($unit));
                        Log::info("after_tax_item_net_price: ".$item_net_price);
                    }

                    $product = [
                        'service_pkg_id'       => $service_pkg_id,
                        'old_invoice_master_id'       => $task->old_invoice_master_id,
                        'product_id'        => $item_id,
                        'product_code'      => $item_code,
                        'product_name'      => $item_name,
                        'product_type'      => $item_type,
                        'option_id'         => $item_option,
                        'net_unit_price'    => $item_net_price,
                        'unit_price'        => $this->formatDecimal($item_net_price + $item_tax),
                        'quantity'          => $item_quantity,
                        'product_unit_id'   => $unit ? $unit->id : null,
                        'product_unit_code' => $unit ? $unit->code : null,
                        'unit_quantity'     => $item_unit_quantity,
                        'warehouse_id'      => $warehouse_id,
                        'item_tax'          => $pr_item_tax,
                        'tax_rate_id'       => $item_tax_rate,
                        'tax'               => $tax,
                        'discount'          => $item_discount,
                        'item_discount'     => $pr_item_discount,
                        'subtotal'          => $this->formatDecimal($subtotal),
                        'serial_no'         => $item_serial,
                        'real_unit_price'   => $real_unit_price,
                    ];

                    $products[] = ($product + $gst_data);
                    $total += $this->formatDecimal(($item_net_price * $item_unit_quantity), 4);
                }
            }
            if(empty($products)){
                return $this->responseDataJson(false, ['Invoice items cannot be empty'], 401);
            }else{
                krsort($products);
            }

            $order_discount = $this->calculateDiscount(str_replace(',','',$request->order_discount), ($total + $product_tax), true);
            $total_discount = $this->formatDecimal(($order_discount + $product_discount + $serv_total_discount), 4);
            $order_tax      = $this->calculateOrderTax(empty($request->order_tax) ? self::getDefaultValues()['no_tax'] : str_replace(',','',$request->order_tax), ($total + $product_tax - $order_discount));
            $total_tax      = $this->formatDecimal(($product_tax + $order_tax), 4);
            $grand_total    = $this->formatDecimal(($this->formatDecimal($total) + $this->formatDecimal($serv_total) + $this->formatDecimal($total_tax) + $this->formatDecimal($shipping) - $this->formatDecimal($order_discount)), 4);

            $data           = ['date' => $date,
                'reference_no'        => $reference,
                'customer_id'         => $customer_id,
                'customer'            => $customer,
                'biller_id'           => $biller_id,
                'biller'              => $biller,
                'warehouse_id'        => $warehouse_id,
                'note'                => $note,
                'staff_note'          => $staff_note,
                'total_serv'          => $serv_total,
                'total'               => $total,
                'total_serv_discount' => $serv_total_discount,
                'product_discount'    => $product_discount,
                'order_discount_id'   => isset($request->order_discount) ? str_replace(',','',$request->order_discount) : "",
                'order_discount'      => $order_discount,
                'total_discount'      => $total_discount,
                'product_tax'         => $product_tax,
                'order_tax_id'        => isset($request->order_tax) && empty($request->order_tax) ? self::getDefaultValues()['no_tax'] : str_replace(',','',$request->order_tax),
                'order_tax'           => $order_tax,
                'total_tax'           => $total_tax,
                'shipping'            => $this->formatDecimal($shipping),
                'grand_total'         => $grand_total,
                'total_items'         => $total_items,
                'sale_status'         => $sale_status,
                'payment_status'      => $payment_status,
                'payment_term'        => $payment_term,
                'due_date'            => $due_date,
                'paid'                => 0,
                'created_by'          => 1,
                'hash'                => hash('sha256', microtime() . mt_rand()),
                'workorder'           => 1,
                'jo_id'               => $request->jo_id,
            ];
            if (SettingSMA::getSettingSma()->indian_gst) {
                $data['cgst'] = $total_cgst;
                $data['sgst'] = $total_sgst;
                $data['igst'] = $total_igst;
            }

            $payments = []; // initialize array

            if ($payment_status == 'partial' || $payment_status == 'paid') {
                if (isset($request->payment) && is_array($request->payment)) {
                    foreach ($request->payment as $paymentData) {
                        // process each payment
                        if ($paymentData['paid_by'] == 'deposit') {
                            if (!$this->check_customer_deposit($customer_id, str_replace(',', '', $paymentData['amount']))) {
                                return $this->responseDataJson(false, [trans('messages.amount_greater_than_deposit')], 401);
                            }
                        }

                        if ($paymentData['paid_by'] == 'gift_card') {
                            $gc = SaleSMA::getGiftCardByNO($paymentData['cc_no']);
                            $amount_paying = $grand_total >= $gc->balance ? $gc->balance : $grand_total;
                            $gc_balance = $gc->balance - $amount_paying;

                            $payments[] = [
                                'date'         => !empty($paymentData['date']) ? $paymentData['date'] : $date,
                                'reference_no' => empty($paymentData['reference_no']) ? '' : $paymentData['reference_no'],
                                'amount'       => str_replace(',', '', $amount_paying),
                                'paid_by'      => $paymentData['paid_by'],
                                'cheque_no'    => empty($paymentData['cheque_no']) ? '' : $paymentData['cheque_no'],
                                'cc_no'        => $paymentData['cc_no'],
                                'cc_holder'    => $paymentData['cc_holder'] ?? null,
                                'cc_month'     => $paymentData['cc_month'] ?? null,
                                'cc_year'      => $paymentData['cc_year'] ?? null,
                                'cc_type'      => $paymentData['cc_type'] ?? null,
                                'created_by'   => 1,
                                'note'         => $paymentData['note'] ?? null,
                                'type'         => 'received',
                                'gc_balance'   => $gc_balance,
                            ];
                        } else {
                            $payments[] = [
                                'date'         => $paymentData['date'] ?? $date,
                                'reference_no' => empty($paymentData['reference_no']) ? '' : $paymentData['reference_no'],
                                'amount'       => $this->formatDecimal(str_replace(',', '', $paymentData['amount'])),
                                'paid_by'      => $paymentData['paid_by'] ?? 'cash',
                                'cheque_no'    => empty($paymentData['cheque_no']) ? '' : $paymentData['cheque_no'],
                                'cc_no'        => $paymentData['cc_no'] ?? null,
                                'cc_holder'    => $paymentData['cc_holder'] ?? null,
                                'cc_month'     => $paymentData['cc_month'] ?? null,
                                'cc_year'      => $paymentData['cc_year'] ?? null,
                                'cc_type'      => $paymentData['cc_type'] ?? null,
                                'created_by'   => 1,
                                'note'         => $paymentData['note'] ?? null,
                                'type'         => 'received',
                            ];
                        }
                    }
                }
            } else {
                $payments = [];
            }
            if($this->addSale($data, $products, $payments, $servPkgs, $tasks,[])){

                //update the job order status from 6 to 7
                $employee = JobOrder::findOrFail($request->jo_id);
                $dataArray = ['jo_status' => 7];
                $resp = $employee->update($dataArray);

                $jb_tsks = JobOrderTask::where('jo_id', $request->jo_id)->get();
                $jb_serv = JobOrderServPackage::where('jo_id', $request->jo_id)->get();
                foreach ($jb_tsks AS $jo_tk) {

                    $dataArray = ['jo_tsk_status' => 7];
                    $resp = JobOrderTask::where('id',$jo_tk->id)->update($dataArray);
                }
                foreach ($jb_serv AS $jo_tk) {

                    $dataArray = ['jo_serv_pkg_status' => 7];
                    $resp = JobOrderServPackage::where('id',$jo_tk->id)->update($dataArray);
                }

                DB::commit();
                return response()->json(['status' => true,'message' => 'Invoice created successfully'], 200);
            }else{
//                exit();
                DB::rollBack();
                Log::error("InvoiceAddSaleError:");
                return response()->json(['status' => false, 'message' => ['Somethings went wrong']], 500);
            }
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("InvoiceCreateError:".$e);
            return response()->json([
                'status' => false,
                'message' => ['Somethings went wrong']
            ], 500);
        }
    }

    public function addSale($data=[], $items=[], $payments=[], $serv_pkgs = [], $tasks = [],$si_return=[])
    {
        DB::beginTransaction();
        try {

            /*if(empty($si_return)){
                $cost = $this->costing($items);
            }*/

            //invoice(sale) header insert
            $resp_h_sale = SaleSMA::create($data);
            if($resp_h_sale){
                $sale_id = $resp_h_sale->id;
                if (OrderRefSMA::getReference('so') == $data['reference_no']) {
                    OrderRefSMA::updateReference('so');
                }

                //serv pkg insert
                foreach ($serv_pkgs as $pkg) {
                    $pkg['sale_id'] = $sale_id;
                    InvoiceServPackage::create($pkg);
                }
                //invoice(sale) tasks inserted here
                foreach ($tasks as $task) {
                    $task['sale_id'] = $sale_id;
                    InvoiceTask::create($task);
                }

                //invoice(sale) items inserted here
                foreach ($items as $item) {
                    $item['sale_id'] = $sale_id;
                    $sale_item = SaleItemSMA::create($item);
                    $sale_item_id = $sale_item->id;
                    if ($data['sale_status'] == 'completed' && empty($si_return)) {
                        $item_costs = $this->item_costing($item);
//                    Log::info("items after cost: ".json_encode($item_costs));
//                        Log::info(json_encode($item_costs));
                        foreach ($item_costs as $item_cost) {
                            if (isset($item_cost['date']) || isset($item_cost['pi_overselling'])) {
                                $item_cost['sale_item_id'] = $sale_item_id;
                                $item_cost['sale_id'] = $sale_id;
                                $item_cost['date'] = date('Y-m-d', strtotime($data['date']));
                                $item_cost['inventory'] = 1;
                                if (!isset($item_cost['pi_overselling'])) {
                                    DB::table('sma_costing')->insertGetId($item_cost);
                                }
                            } else {
                                foreach ($item_cost as $ic) {
                                    $ic['sale_item_id'] = $sale_item_id;
                                    $ic['sale_id'] = $sale_id;
                                    $ic['date'] = date('Y-m-d', strtotime($data['date']));
                                    $ic['inventory'] = 1;
                                    if (!isset($ic['pi_overselling'])) {
                                        DB::table('sma_costing')->insertGetId($ic);
                                    }
                                }
                            }
                        }
                    }
                }

                //if we have attachments it will be added here
                /*if ($data['sale_status'] == 'completed') {
                    $this->syncPurchaseItems($cost);
                }*/

                if (!empty($si_return)) {
                    foreach ($si_return as $return_item) {
                        $product = ProductSMA::getProductByID($return_item['product_id']);
                        if ($product->type == 'combo') {
                            $combo_items = ProductSMA::getProductComboItems($return_item['product_id'], $return_item['warehouse_id']);
                            foreach ($combo_items as $combo_item) {
                                $this->updateCostingAndPurchaseItem($return_item, $combo_item->id, ($return_item['quantity'] * $combo_item->qty));
                            }
                        } elseif ($product->type != 'service') {
                            $this->updateCostingAndPurchaseItem($return_item, $return_item['product_id'], $return_item['quantity']);
                        }
                    }
                    $ress = SaleSMA::findOrFail($data['sale_id']);
                    $ress->update(['return_sale_ref' => $data['return_sale_ref'], 'surcharge' => $data['surcharge'], 'return_sale_total' => $data['grand_total'], 'return_id' => $sale_id]);
                }

                if ($data['payment_status'] == 'partial' || (($data['payment_status'] == 'paid') && !empty($payments))) {
                    foreach ($payments as $payment) {
                        if (empty($payment['reference_no'])) {
                            $payment['reference_no'] = OrderRefSMA::getReference('pay');
                        }

                        $payment['sale_id'] = $sale_id;
                        Log::info('paid_by: '.json_encode($payment));

                        if ($payment['paid_by'] == 'gift_card') {
                            DB::table('sma_gift_cards')->where(['card_no' => $payment['cc_no']])->update(['balance' => $payment['gc_balance']]);
                            unset($payment['gc_balance']);
                            PaymentSMA::create($payment);
                        } else {
                            if ($payment['paid_by'] == 'deposit') {
                                $customer = CustomerSMA::getCompanyByID($data['customer_id']);
                                CustomerSMA::where(['id' => $customer->id])->update(['deposit_amount' => ($customer->deposit_amount - $payment['amount'])]);
                            }
                            PaymentSMA::create($payment);
                        }

                        if (OrderRefSMA::getReference('pay') == $payment['reference_no']) {
                            OrderRefSMA::updateReference('pay');
                        }

                        SaleSMA::syncSalePayments($sale_id);
                    }
                }

                $this->syncQuantity($sale_id);
                SaleSMA::update_award_points($data['grand_total'], $data['customer_id'], $data['created_by']);
            }

            DB::commit();
            return true;
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("InvoiceAddSaleError:".$e);
            return false;
        }

    }

    public function getSaleByID(Request $request){

        $saleData = SaleSMA::getSaleByID($request->ItemId);
        return $this->responseSelectedDataJson(true, $saleData, 200);
    }

    public function addPayment(Request $request){

        $inv_id = $request->inv_id;
        $validateMsgs = [
            'amount_paid.required' => 'The Amount field is required',
            'paid_by.required' => 'The Paying by field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'amount_paid'      => ['required'],
            'paid_by'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        try {

            $saleData = SaleSMA::getSaleByID($inv_id);

            if($request->paid_by == "deposit"){

                $customer_id = $saleData->customer_id;
                if(!$this->check_customer_deposit($customer_id, str_replace(',', '', $request->amount_paid))){

                    return $this->responseDataJson(false, [trans('messages.amount_greater_than_deposit')], 401);
                }
            }else{
                $customer_id = null;
            }
            $date = date('Y-m-d H:i:s');
            $payment = [
                'date'         => $date,
                'sale_id'      => $inv_id,
                'reference_no' => $request->reference_no ? $request->reference_no : OrderRefSMA::getReference('pay'),
                'amount'       => str_replace(',','',$request->amount_paid),
                'paid_by'      => $request->paid_by,
                'cheque_no'    => $request->cheque_no,
                'cc_no'        => $request->paid_by == 'gift_card' ? $request->gift_card_no : $request->pcc_no,
                'cc_holder'    => $request->pcc_holder,
                'cc_month'     => $request->pcc_month,
                'cc_year'      => $request->pcc_year,
                'cc_type'      => $request->pcc_type,
                'note'         => $request->note,
                'created_by'   => 1,
                'type'         => $request->sale_status == 'returned' ? 'returned' : 'received',
            ];

            if(SaleSMA::addPayment($payment, $customer_id)){

                return $this->responseDataJson(true,'Payment submitted successfully',200);
            }else{
                DB::rollBack();
                return $this->responseDataJson(false,['Payment submission unsuccessful!'], 500);
            }
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("InvoiceAddPaymentError:".$e);
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function getApprovedJoSerialListForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = JobOrder::where('is_active',1)->where('jo_status',6)->where('serial_no', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = JobOrder::where('is_active',1)->where('jo_status',6)->where('serial_no', 'LIKE', '%' . $search_term . '%')->get();

            $count = $item_list_count->count();
            $endCount = $offset + $res_count;
            $morePages = $endCount < $count;

            $data = array();
            foreach ($item_list as $row) {
                $data[] = array("id" => $row->id, "text" => $row->serial_no);
            }

            return array(
                "results" => $data,
                "pagination" => array("more" => $morePages)
            );

        }catch (\Exception $e){
            Log::error("select2JOApprovedSerial: ".$e);
        }
    }

    public function getJoApprovedSerialByIdForDrop(Request $request){

        $state_data = JobOrder::where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->serial_no);
        }

        return json_encode($data);
    }

    public function getApprovedJobOrderData(Request $request){
        $jo_id = $request->item_id;

        $wo_tbl = (new WorkOrder())->getTable();

        $tax_rate_tbl = "sma_tax_rates";
        $pro_opt_tbl = (new ProductVariantSMA())->getTable();

        $jo_tsk_item_tbl = (new JobOrderTaskItem())->getTable();
        $jo_tsk_tech_tbl = (new JobOrderTaskTechnician())->getTable();
        $tech_tbl = (new Employee())->getTable();

        $serv_pkg_tbl = (new ServicePackage())->getTable();
        $jo_serv_pkg_tbl = (new JobOrderServPackage())->getTable();
        $vehicle_type_tbl_nm = (new VehicleType())->getTable();

        $jo_item_tbl = (new JobOrderItem())->getTable();

        $jb_head_data = JobOrder::where('id', $jo_id)->first();

        //getting job order service packages
        $jb_serv_pkg_data = JobOrderServPackage::query()
            ->select($jo_serv_pkg_tbl.'.*', $serv_pkg_tbl.'.service_name', $serv_pkg_tbl.'.description', $vehicle_type_tbl_nm.'.type_name')
            ->leftJoin($serv_pkg_tbl, $jo_serv_pkg_tbl.'.service_pkg_id', '=', $serv_pkg_tbl.'.id')
            ->leftJoin($vehicle_type_tbl_nm,$serv_pkg_tbl.'.vehicle_type_id','=',$vehicle_type_tbl_nm.'.id')
            ->where($jo_serv_pkg_tbl.'.jo_id', $jo_id)->where('is_approve_tech', 1)->get();

        $jb_task_data = JobOrderTask::where('jo_id', $jo_id)->get();

        //getting job order tasks and its items
        $jb_t = [];
        foreach ($jb_task_data AS $jb_task){

            $jb_tsk_techs = JobOrderTaskTechnician::query()
                ->select($jo_tsk_tech_tbl.'.*', $tech_tbl.'.full_name')
                ->leftJoin($tech_tbl, $jo_tsk_tech_tbl.'.tech_id', '=', $tech_tbl.'.id')
                ->where($jo_tsk_tech_tbl.'.jo_task_id', $jb_task->id)->get();

            $taskTotTime = 0;
            foreach ($jb_tsk_techs AS $jbtt){
                $jb_tsk_tch_time = JobOrderTaskTechTime::query()
                    ->select('is_started', 'is_ended', 'is_finished',DB::raw("IF(ISNULL(time((TIMEDIFF( end_datetime, start_datetime )))),'00:00:00', time((TIMEDIFF( end_datetime, start_datetime )))) as total"))
                    ->where('jo_task_id', $jb_task->id)->where('tech_id', $jbtt->tech_id)
                    ->get();

                $seconds = 0;
                $jbState = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Task is in queue to be proceed by technician">Pending</span>';
                foreach ($jb_tsk_tch_time AS $jbttt){
                    list($hour,$minute,$second) = explode(':', $jbttt->total);
                    $seconds += $hour*3600;
                    $seconds += $minute*60;
                    $seconds += $second;

                    if($jbttt->is_started)
                        $jbState = '<span class="text-black badge badge-info" data-toggle="tooltip" data-placement="top" title="Task is started by the technician and work is going">Started</span>';
                    elseif ($jbttt->is_ended && !$jbttt->is_finished)
                        $jbState = '<span class="text-black badge badge-primary" data-toggle="tooltip" data-placement="top" title="Task is ended by the technician but not finished">Ended</span>';
                    elseif ($jbttt->is_ended && $jbttt->is_finished)
                        $jbState = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Task is finished by the technician">Finished</span>';
                    else
                        $jbState = "-";
                }
                $jbtt->totTime = $this->format_the_time($seconds)['formatTime'];
                $jbtt->jbState = $jbState;
                $taskTotTime += $seconds;
            }
            $jb_task->taskTotTime = $this->format_the_time($taskTotTime)['formatTime'];
            $jb_task->taskTotRawTime = $this->format_the_time($taskTotTime)['rawTime'];

            //function for getting task specific items
            $jb_tsk_item_data = JobOrderTaskItem::query()
                ->select($jo_tsk_item_tbl.'.*', DB::raw($pro_opt_tbl.'.name AS option_name'), DB::raw($jo_tsk_item_tbl.'.quantity AS qty'))
                ->leftjoin($tax_rate_tbl, $jo_tsk_item_tbl.'.tax_rate_id','=',$tax_rate_tbl.'.id')
                ->leftJoin($pro_opt_tbl, $jo_tsk_item_tbl.'.item_option_id', '=', $pro_opt_tbl.'.id')
                ->where('jo_task_id', $jb_task->id)->get();

            //modify items which added for task according the procedural standard
            $edit_jb_item = [];
            $r = 0;
            foreach ($jb_tsk_item_data AS $jb_item){
                $c = uniqid(mt_rand(), true);

                $pro_data = ProductSMA::getProductByCode($jb_item->product_code);

                $jb_item->quantity        = 0;
                $options              = ProductSMA::getProductOptions($jb_item->product_id, $jb_item->warehouse_id);

                $pis         = ProductSMA::getPurchasedItems($jb_item->product_id, $jb_item->warehouse_id, $jb_item->item_option_id);
                if ($pis) {
                    $jb_item->quantity = 0;
                    foreach ($pis as $pi) {
//                        Log::info(json_encode($pi)."\n");
                        $jb_item->quantity += $pi->quantity_balance;
                    }
                }

//                Log::info('Warehouse: '.$jb_item->warehouse_id.'/'.$jb_item->quantity.'/'.$pro_data['name']."(option: ".$jb_item->item_option_id.")"."\n\n");
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = ProductSMA::getPurchasedItems($jb_item->product_id, $jb_item->warehouse_id, $jb_item->item_option_id);
                        if ($pis) {
                            foreach ($pis as $pi) {
                                $option_quantity += $pi->quantity_balance;
                            }
                        }
                        if ($option->quantity > $option_quantity) {
                            $option->quantity = $option_quantity;
                        }
                    }
                }

                $jb_item->tax_method = $pro_data['tax_method'] ?? 0;
                $jb_item->base_unit = $pro_data['unit'];
                $jb_item->base_unit_price = $pro_data['price'];

                $combo_items          = false;
                if ($jb_item->product_type == 'combo') {
                    $combo_items = ProductSMA::getProductComboItems($jb_item->product_id, $jb_item->warehouse_id);
                }

                $units    = ProductSMA::getUnitsByBUID($jb_item->product_unit_id);
                $tax_rate = SaleSMA::getTaxRateByID($jb_item->tax_rate_id);

                $edit_jb_item[] = ['id' => sha1($c . $r), 'item_id' => $jb_item->product_id, 'label' => $jb_item->product_name . ' (' . $jb_item->product_code . ')', 'category' => '',
                    'row'     => $jb_item, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options, ];
                $r++;
            }

            $jb_t[] = ['task' => $jb_task, 'task_item' => $edit_jb_item];
        }

        //getting joborder wise added items
        $jb_item_data = JobOrderItem::query()
            ->select($jo_item_tbl.'.*', DB::raw($pro_opt_tbl.'.name AS option_name'), $serv_pkg_tbl.'.service_name', DB::raw($jo_item_tbl.'.quantity AS qty'))
            ->leftjoin($tax_rate_tbl, $jo_item_tbl.'.tax_rate_id','=',$tax_rate_tbl.'.id')
            ->leftJoin($pro_opt_tbl, $jo_item_tbl.'.item_option_id', '=', $pro_opt_tbl.'.id')
            ->leftJoin($serv_pkg_tbl, $jo_item_tbl.'.service_pkg_id', '=', $serv_pkg_tbl.'.id')
            ->leftJoin($jo_serv_pkg_tbl, function ($join) use ($jo_item_tbl, $jo_serv_pkg_tbl) {
                $join->on($jo_item_tbl.'.service_pkg_id', '=', $jo_serv_pkg_tbl.'.service_pkg_id')
                    ->where($jo_serv_pkg_tbl.'.is_approve_tech', 1);
            })
            ->where(function ($query) use ($jo_id, $jo_item_tbl) {
                $query->where('jo_header_id', $jo_id)
                    ->orWhere($jo_item_tbl.'.service_pkg_id', 0);
            })->get();

        //modifying items according the standard way
        $edit_jb_item = [];
        $r = 0;
        foreach ($jb_item_data AS $jb_item){
            $c = uniqid(mt_rand(), true);

            $pro_data = ProductSMA::getProductByCode($jb_item->product_code);

            $jb_item->quantity        = 0;
            $jb_item->unit_quantity        = $jb_item->qty;
            $options              = ProductSMA::getProductOptions($jb_item->product_id, $jb_item->warehouse_id);

            $pis         = ProductSMA::getPurchasedItems($jb_item->product_id, $jb_item->warehouse_id, $jb_item->item_option_id);
            if ($pis) {
                $jb_item->quantity = 0;
                foreach ($pis as $pi) {
                    $jb_item->quantity += $pi->quantity_balance;
                }
            }

            if ($options) {
                $option_quantity = 0;
                foreach ($options as $option) {
                    $pis = ProductSMA::getPurchasedItems($jb_item->product_id, $jb_item->warehouse_id, $jb_item->item_option_id);
                    if ($pis) {
                        foreach ($pis as $pi) {
                            $option_quantity += $pi->quantity_balance;
                        }
                    }
                    if ($option->quantity > $option_quantity) {
                        $option->quantity = $option_quantity;
                    }
                }
            }

            $jb_item->tax_method = $pro_data['tax_method'] ?? 0;
            $jb_item->base_unit = $pro_data['unit'];
            $jb_item->base_unit_price = $pro_data['price'];

            $combo_items          = false;
            if ($jb_item->product_type == 'combo') {
                $combo_items = ProductSMA::getProductComboItems($jb_item->product_id, $jb_item->warehouse_id);
            }

            $units    = ProductSMA::getUnitsByBUID($jb_item->product_unit_id);
            $tax_rate = SaleSMA::getTaxRateByID($jb_item->tax_rate_id);

            $edit_jb_item[] = ['id' => sha1($c . $r), 'item_id' => $jb_item->product_id, 'label' => $jb_item->product_name . ' (' . $jb_item->product_code . ')', 'category' => '',
                'row'     => $jb_item, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options, ];
            $r++;
        }

        //getting Workorder data according to selected joborder
        $wo_data = [];
        if(isset($jb_head_data->wo_order_id)){

            $wo_data = WorkOrder::select($wo_tbl.'.*') ->where($wo_tbl.'.id',$jb_head_data->wo_order_id) ->first();
        }

        $jobOrderData = array(
            'jo_data' => $jb_head_data,
            'serv_pkg_data' => ($jb_serv_pkg_data),
            'task_data' => ($jb_t),
            'item_data' => ($edit_jb_item),
            'wo_data' => $wo_data,
        );

        return $this->responseSelectedDataJson(true, $jobOrderData, 200);
    }

    public function edit($id){
        $editData = SaleSMA::findOrFail($id);
        return view('invoice/edit', compact('editData'));
    }

    public function editData(){

    }

    public function update(Request $request){

    }

    public function getSaleHeadData(Request $request){
        $inv_id = $request->item_id;
        $inv_head_data = SaleSMA::findOrFail($inv_id);
        $invData = [
            'headData' => $inv_head_data
        ];
        return $this->responseSelectedDataJson(true, $invData, 200);
    }
    public function getSelectedInvoiceData(Request $request){

        $inv_id = $request->item_id;

        $inv_item_tbl = (new SaleItemSMA())->getTable();
        $tax_rate_tbl = "sma_tax_rates";
        $pro_opt_tbl = (new ProductVariantSMA())->getTable();
        $serv_pkg_tbl = (new ServicePackage())->getTable();
        $wo_tbl = (new WorkOrder())->getTable();
        $inv_serv_pkg_tbl = (new InvoiceServPackage())->getTable();
        $vehicle_type_tbl_nm = (new VehicleType())->getTable();
        $jo_tsk_tech_tbl = (new JobOrderTaskTechnician())->getTable();
        $tech_tbl = (new Employee())->getTable();

        $inv_head_data = SaleSMA::findOrFail($inv_id);

        $inv_item_data = SaleItemSMA::query()
            ->select($inv_item_tbl.'.*', DB::raw($pro_opt_tbl.'.name AS option_name'), $serv_pkg_tbl.'.service_name', DB::raw($inv_item_tbl.'.quantity AS qty'))
            ->leftjoin($tax_rate_tbl, $inv_item_tbl.'.tax_rate_id','=',$tax_rate_tbl.'.id')
            ->leftJoin($pro_opt_tbl, $inv_item_tbl.'.option_id', '=', $pro_opt_tbl.'.id')
            ->leftJoin($serv_pkg_tbl, $inv_item_tbl.'.service_pkg_id', '=', $serv_pkg_tbl.'.id')
            ->where($inv_item_tbl.'.sale_id', $inv_id)->get();

        //modifying items according the standard way
        $edit_inv_item = [];
        $r = 0;
        foreach ($inv_item_data AS $jb_item){
            $c = uniqid(mt_rand(), true);

            $pro_data = ProductSMA::getProductByCode($jb_item->product_code);

            $jb_item->quantity        = 0;
            $jb_item->unit_quantity        = $jb_item->qty;
            $options              = ProductSMA::getProductOptions($jb_item->product_id, $jb_item->warehouse_id);

            $pis         = ProductSMA::getPurchasedItems($jb_item->product_id, $jb_item->warehouse_id, $jb_item->item_option_id);
            if ($pis) {
                $jb_item->quantity = 0;
                foreach ($pis as $pi) {
                    $jb_item->quantity += $pi->quantity_balance;
                }
            }

            if ($options) {
                $option_quantity = 0;
                foreach ($options as $option) {
                    $pis = ProductSMA::getPurchasedItems($jb_item->product_id, $jb_item->warehouse_id, $jb_item->item_option_id);
                    if ($pis) {
                        foreach ($pis as $pi) {
                            $option_quantity += $pi->quantity_balance;
                        }
                    }
                    if ($option->quantity > $option_quantity) {
                        $option->quantity = $option_quantity;
                    }
                }
            }

            $jb_item->tax_method = $pro_data['tax_method'] ?? 0;
            $jb_item->base_unit = $pro_data['unit'];
            $jb_item->base_unit_price = $pro_data['price'];

            $combo_items          = false;
            if ($jb_item->product_type == 'combo') {
                $combo_items = ProductSMA::getProductComboItems($jb_item->product_id, $jb_item->warehouse_id);
            }

            $units    = ProductSMA::getUnitsByBUID($jb_item->product_unit_id);
            $tax_rate = SaleSMA::getTaxRateByID($jb_item->tax_rate_id);

            $edit_inv_item[] = ['id' => sha1($c . $r), 'item_id' => $jb_item->product_id, 'label' => $jb_item->product_name . ' (' . $jb_item->product_code . ')', 'category' => '',
                'row'     => $jb_item, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options, ];
            $r++;
        }


        $jb_head_data = JobOrder::where('id', $inv_head_data->jo_id)->first();

        //getting Workorder data according to the selected invoice
        $wo_data = [];
        if(isset($jb_head_data->wo_order_id)){

            $wo_data = WorkOrder::select('*')
                ->where('id',$jb_head_data->wo_order_id)
                ->first();
        }
        //invoice service package data
        $inv_serv_pkg_data = InvoiceServPackage::query()
            ->select($inv_serv_pkg_tbl.'.*', $serv_pkg_tbl.'.service_name', $serv_pkg_tbl.'.description', $vehicle_type_tbl_nm.'.type_name')
            ->leftJoin($serv_pkg_tbl, $inv_serv_pkg_tbl.'.service_pkg_id', '=', $serv_pkg_tbl.'.id')
            ->leftJoin($vehicle_type_tbl_nm,$serv_pkg_tbl.'.vehicle_type_id','=',$vehicle_type_tbl_nm.'.id')
            ->where($inv_serv_pkg_tbl.'.sale_id', $inv_id)->get();

        $inv_task_data = InvoiceTask::where('sale_id', $inv_id)->get();

        $inv_t = [];
        foreach ($inv_task_data AS $jb_task){

            $inv_tsk_techs = JobOrderTaskTechnician::query()
                ->select($jo_tsk_tech_tbl.'.*', $tech_tbl.'.full_name')
                ->leftJoin($tech_tbl, $jo_tsk_tech_tbl.'.tech_id', '=', $tech_tbl.'.id')
                ->where($jo_tsk_tech_tbl.'.jo_task_id', $jb_task->id)->get();

            foreach ($inv_tsk_techs AS $jbtt){
                $jb_tsk_tch_time = JobOrderTaskTechTime::query()
                    ->select('is_started', 'is_ended', 'is_finished',DB::raw("IF(ISNULL(time((TIMEDIFF( end_datetime, start_datetime )))),'00:00:00', time((TIMEDIFF( end_datetime, start_datetime )))) as total"))
                    ->where('jo_task_id', $jb_task->id)->where('tech_id', $jbtt->tech_id)
                    ->get();

                $seconds = 0;
                $jbState = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Task is in queue to be proceed by technician">Pending</span>';
                foreach ($jb_tsk_tch_time AS $jbttt){
                    list($hour,$minute,$second) = explode(':', $jbttt->total);
                    $seconds += $hour*3600;
                    $seconds += $minute*60;
                    $seconds += $second;

                    if($jbttt->is_started)
                        $jbState = '<span class="text-black badge badge-info" data-toggle="tooltip" data-placement="top" title="Task is started by the technician and work is going">Started</span>';
                    elseif ($jbttt->is_ended && !$jbttt->is_finished)
                        $jbState = '<span class="text-black badge badge-primary" data-toggle="tooltip" data-placement="top" title="Task is ended by the technician but not finished">Ended</span>';
                    elseif ($jbttt->is_ended && $jbttt->is_finished)
                        $jbState = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Task is finished by the technician">Finished</span>';
                    else
                        $jbState = "-";
                }
                $jbtt->totTime = $this->format_the_time($seconds)['formatTime'];
                $jbtt->jbState = $jbState;

            }

            $inv_t[] = ['task' => $jb_task, 'task_techs' => $inv_tsk_techs];
        }

        $invData = array(
            'jo_data' => $jb_head_data,
            'serv_pkg_data' => ($inv_serv_pkg_data),
            'task_data' => $inv_t,
            'item_data' => ($edit_inv_item),
            'wo_data' => $wo_data,
        );

        return $this->responseSelectedDataJson(true, $invData, 200);
    }


}
