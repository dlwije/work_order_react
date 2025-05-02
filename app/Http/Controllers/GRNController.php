<?php

namespace App\Http\Controllers;

use App\Models\CustomerSMA;
use App\Models\OrderRefSMA;
use App\Models\PaymentSMA;
use App\Models\ProductSMA;
use App\Models\ProductVariantSMA;
use App\Models\PurchaseItemSMA;
use App\Models\PurchaseSMA;
use App\Models\SaleSMA;
use App\Models\SettingSMA;
use App\Models\UnitSMA;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GRNController extends Controller
{
    public function store(Request $request, $quote_id = null)
    {
        $validateMsgs = [
            'warehouse_id.required' => 'The Warehouse field is required',
            'supplier_id.required' => 'The Supplier field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'warehouse_id'      => ['required'],
            'supplier_id'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {


            $reference = $request->reference_no ?? OrderRefSMA::getReference('po');
//            if ($this->Owner || $this->Admin) {
            if (!empty($request->date)) {
                $date = trim($request->date);
            } else {
                $date = date('Y-m-d H:i:s');
            }
            $warehouse_id     = $request->warehouse_id ?? self::getDefaultValues()['warehouse_default_id'];
            $supplier_id      = $request->supplier_id;
            $status           = $request->status;
            $shipping         = $request->shipping ? $request->shipping :0;
            $supplier_details = CustomerSMA::getCompanyByID($supplier_id);
            $supplier         = $supplier_details->company && $supplier_details->company != '-' ? $supplier_details->company : $supplier_details->name;
            $note             = isset($request->note) ? $this->clear_tags($request->note) : '';
            $payment_term     = $request->payment_term;
            $date             = $request->date ? Carbon::parse($request->date) : Carbon::now();
            $due_date         = $payment_term ? $date->copy()->addDays($payment_term)->toDateString() : null;

            $total            = 0;
            $product_tax      = 0;
            $product_discount = 0;
            $i                = isset($request->product) ? sizeof($request->product) : 0;
            $gst_data         = [];
            $total_cgst       = $total_sgst       = $total_igst       = 0;

            $grid_items = json_decode($request->grid_item_rows);
            $total_items = sizeof($grid_items);

            //invoice items array
            foreach ($grid_items AS $task){
                $item_code          = $task->item_code;
                $item_net_cost      = $this->formatDecimal($task->net_cost);
                $unit_cost          = $this->formatDecimal($task->unit_cost);
                $real_unit_cost     = $this->formatDecimal($task->real_unit_cost);
                $item_unit_quantity = str_replace(',','',$task->item_qty);
                $item_option        = isset($task->item_option) && $task->item_option != 'false' && $task->item_option != 'null' ? $task->item_option : "";
                $item_tax_rate      = $task->tax->id ?? null;
                $item_discount      = !empty($task->item_discount) ? str_replace(',', '', $task->item_discount): 0;
                $item_expiry        = (!empty($task->expiry)) ? $task->expiry : null;
                $supplier_part_no   = (!empty($task->part_no)) ? $task->part_no : null;
                $item_unit          = $task->product_unit;
                $item_quantity      = str_replace(',','',$task->base_quantity);

                if (isset($item_code) && isset($real_unit_cost) && isset($unit_cost) && isset($item_quantity)) {
                    $product_details =  ProductSMA::getProductByCode($item_code);
                    if ($item_expiry) {
                        $today = date('Y-m-d');
                        if ($item_expiry <= $today) {
//                            $this->session->set_flashdata('error', lang('product_expiry_date_issue') . ' (' . $product_details->name . ')');
//                            redirect($_SERVER['HTTP_REFERER']);
                            Log::error("GRNCreateExpiryError:ID:".$item_code.'/Name:'.$product_details->name);
                            return $this->responseDataJson(false, ['Product expiry date has issue ('.$product_details->name.')'], 401);
                        }
                    }

                    // $unit_cost = $real_unit_cost;
                    $pr_discount      = $this->calculateDiscount($item_discount, $unit_cost);
                    $unit_cost        = $this->formatDecimal($unit_cost - $pr_discount);
                    $item_net_cost    = $unit_cost;
                    $pr_item_discount = $this->formatDecimal($pr_discount * $item_unit_quantity);
                    $product_discount += $pr_item_discount;
                    $pr_item_tax = $item_tax = 0;
                    $tax         = '';

                    if (isset($item_tax_rate) && $item_tax_rate != 0) {
                        $tax_details = SaleSMA::getTaxRateByID($item_tax_rate);
                        $ctax        = $this->calculateTax($product_details, $tax_details, $unit_cost);
                        $item_tax    = $this->formatDecimal($ctax['amount']);
                        $tax         = $ctax['tax'];
                        if ($product_details->tax_method != 1) {
                            $item_net_cost = $unit_cost - $item_tax;
                        }
                        $pr_item_tax = $this->formatDecimal(($item_tax * $item_unit_quantity), 4);
                        if (SettingSMA::getSettingSma()->indian_gst && $gst_data = $this->calculateIndianGST($pr_item_tax, (SettingSMA::getSettingSma()->state == $supplier_details->state), $tax_details)) {
                            $total_cgst += $gst_data['cgst'];
                            $total_sgst += $gst_data['sgst'];
                            $total_igst += $gst_data['igst'];
                        }
                    }

                    $product_tax += $pr_item_tax;
                    $subtotal = (($item_net_cost * $item_unit_quantity) + $pr_item_tax);
                    $unit     = UnitSMA::getUnitByID($item_unit);

                    $product = [
                        'product_id'        => $product_details->id,
                        'product_code'      => $item_code,
                        'product_name'      => $product_details->name,
                        'option_id'         => $item_option,
                        'net_unit_cost'     => $item_net_cost,
                        'unit_cost'         => $this->formatDecimal($item_net_cost + $item_tax),
                        'quantity'          => $item_quantity,
                        'product_unit_id'   => $item_unit,
                        'product_unit_code' => $unit->code ?? null,
                        'unit_quantity'     => $item_unit_quantity,
                        'quantity_balance'  => $status == 'received' ? $item_quantity : 0,
                        'quantity_received' => $status == 'received' ? $item_quantity : 0,
                        'warehouse_id'      => $warehouse_id,
                        'item_tax'          => $pr_item_tax,
                        'tax_rate_id'       => $item_tax_rate,
                        'tax'               => $tax,
                        'discount'          => $item_discount,
                        'item_discount'     => $pr_item_discount,
                        'subtotal'          => $this->formatDecimal($subtotal),
                        'expiry'            => $item_expiry,
                        'real_unit_cost'    => $real_unit_cost,
                        'date'              => date('Y-m-d', strtotime($date)),
                        'status'            => $status,
                        'supplier_part_no'  => $supplier_part_no,
                    ];

                    if ($unit && $unit->id != $product_details->unit) {
                        $product['base_unit_cost'] = $this->convertToBase($unit, $real_unit_cost);
                    } else {
                        $product['base_unit_cost'] = $real_unit_cost;
                    }

                    $products[] = ($product + $gst_data);
                    $total += $this->formatDecimal(($item_net_cost * $item_unit_quantity), 4);

                }
            }

            if(empty($products)){
                Log::error("GRNCreateItemEmptyError:".json_encode($products));
                return $this->responseDataJson(false, ['GRN items cannot be empty'], 401);
            }else{
                krsort($products);
            }

            $order_discount = $this->calculateDiscount($request->discount ?? 0, ($total + $product_tax), true);
            $total_discount = $this->formatDecimal(($order_discount + $product_discount), 4);
            $order_tax      = $this->calculateOrderTax($request->order_tax ?? 0, ($total + $product_tax - $order_discount));
            $total_tax      = $this->formatDecimal(($product_tax + $order_tax), 4);
            $grand_total    = $this->formatDecimal(($this->formatDecimal($total) + $this->formatDecimal($total_tax) + $this->formatDecimal($shipping) - $this->formatDecimal($order_discount)), 4);
            $data           = ['reference_no' => $reference,
                'date'                        => $date,
                'supplier_id'                 => $supplier_id,
                'supplier'                    => $supplier,
                'warehouse_id'                => $warehouse_id,
                'note'                        => $note,
                'total'                       => $total,
                'product_discount'            => $product_discount,
                'order_discount_id'           => $request->discount ?? NULL,
                'order_discount'              => $order_discount,
                'total_discount'              => $total_discount,
                'product_tax'                 => $product_tax,
                'order_tax_id'                => $request->order_tax ?? NULL,
                'order_tax'                   => $order_tax,
                'total_tax'                   => $total_tax,
                'shipping'                    => $this->formatDecimal($shipping),
                'grand_total'                 => $grand_total,
                'status'                      => $status,
                'created_by'                  => 0,
                'payment_term'                => $payment_term,
                'due_date'                    => $due_date,
            ];
            if (SettingSMA::getSettingSma()->indian_gst) {
                $data['cgst'] = $total_cgst;
                $data['sgst'] = $total_sgst;
                $data['igst'] = $total_igst;
            }

            $attachments        = [];
//            $attachments        = $this->attachments->upload();
//            $data['attachment'] = !empty($attachments);

            if($this->addPurchase($data, $products, $attachments)){

                DB::commit();
                Log::error("GRNCreatedSuccess:".json_encode($data, true));
                return response()->json(['status' => true,'message' => 'GRN successfully added'], 200);
            }else{

                if ($quote_id) {
//                    $this->data['quote'] = $this->purchases_model->getQuoteByID($quote_id);
//                    $supplier_id         = $this->data['quote']->supplier_id;
//                    $items               = $this->purchases_model->getAllQuoteItems($quote_id);
//                    krsort($items);
//                    $c = rand(100000, 9999999);
//                    foreach ($items as $item) {
//                        $row = $this->site->getProductByID($item->product_id);
//                        if (!$row) {
//                            $this->session->set_flashdata('error', sprintf(lang('product_x_found'), $item->product_name . '(' . $item->product_code . ')'));
//                            redirect($_SERVER['HTTP_REFERER']);
//                        }
//                        if ($row->type == 'combo') {
//                            $combo_items = $this->site->getProductComboItems($row->id, $item->warehouse_id);
//                            foreach ($combo_items as $citem) {
//                                $crow = $this->site->getProductByID($citem->id);
//                                if (!$crow) {
//                                    $crow      = json_decode('{}');
//                                    $crow->qty = $item->quantity;
//                                } else {
//                                    unset($crow->details, $crow->product_details, $crow->price);
//                                    $crow->qty = $citem->qty * $item->quantity;
//                                }
//                                $crow->base_quantity  = $item->quantity;
//                                $crow->base_unit      = $crow->unit ? $crow->unit : $item->product_unit_id;
//                                $crow->base_unit_cost = $crow->cost ? $crow->cost : $item->unit_cost;
//                                $crow->unit           = $item->product_unit_id;
//                                $crow->discount       = $item->discount ? $item->discount : '0';
//                                $supplier_cost        = $supplier_id ? $this->getSupplierCost($supplier_id, $crow) : $crow->cost;
//                                $crow->cost           = $supplier_cost ? $supplier_cost : 0;
//                                $crow->tax_rate       = $item->tax_rate_id;
//                                $crow->real_unit_cost = $crow->cost ? $crow->cost : 0;
//                                $crow->expiry         = '';
//                                $options              = $this->purchases_model->getProductOptions($crow->id);
//                                $units                = $this->site->getUnitsByBUID($row->base_unit);
//                                $tax_rate             = $this->site->getTaxRateByID($crow->tax_rate);
//                                $ri                   = $this->Settings->item_addition ? $crow->id : $c;
//
//                                $pr[$ri] = ['id' => $c, 'item_id' => $crow->id, 'label' => $crow->name . ' (' . $crow->code . ')', 'row' => $crow, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options];
//                                $c++;
//                            }
//                        } elseif ($row->type == 'standard') {
//                            if (!$row) {
//                                $row           = json_decode('{}');
//                                $row->quantity = 0;
//                            } else {
//                                unset($row->details, $row->product_details);
//                            }
//
//                            $row->id             = $item->product_id;
//                            $row->code           = $item->product_code;
//                            $row->name           = $item->product_name;
//                            $row->base_quantity  = $item->quantity;
//                            $row->base_unit      = $row->unit ? $row->unit : $item->product_unit_id;
//                            $row->base_unit_cost = $row->cost ? $row->cost : $item->unit_cost;
//                            $row->unit           = $item->product_unit_id;
//                            $row->qty            = $item->unit_quantity;
//                            $row->option         = $item->option_id;
//                            $row->discount       = $item->discount ? $item->discount : '0';
//                            $supplier_cost       = $supplier_id ? $this->getSupplierCost($supplier_id, $row) : $row->cost;
//                            $row->cost           = $supplier_cost ? $supplier_cost : 0;
//                            $row->tax_rate       = $item->tax_rate_id;
//                            $row->expiry         = '';
//                            $row->real_unit_cost = $row->cost ? $row->cost : 0;
//                            $options             = $this->purchases_model->getProductOptions($row->id);
//
//                            $units    = $this->site->getUnitsByBUID($row->base_unit);
//                            $tax_rate = $this->site->getTaxRateByID($row->tax_rate);
//                            $ri       = $this->Settings->item_addition ? $row->id : $c;
//
//                            $pr[$ri] = ['id' => $c, 'item_id' => $row->id, 'label' => $row->name . ' (' . $row->code . ')',
//                                'row'        => $row, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options, ];
//                            $c++;
//                        }
//                    }
                    $this->data['quote_items'] = json_encode([]);
                }

            }

        }catch (\Exception $e){

            DB::rollBack();
            Log::error("GRNCreateError:".$e);
            return response()->json([
                'status' => false,
                'message' => ['Somethings went wrong']
            ], 500);
        }
    }

    public function addPurchase($data, $products, $attachments)
    {
        DB::beginTransaction();
        try {
            $resp_h_grn = PurchaseSMA::create($data);

            if($resp_h_grn) {
                $purchase_id = $resp_h_grn->id;
                if (OrderRefSMA::getReference('po') == $data['reference_no']) {
                    OrderRefSMA::updateReference('po');
                }

                foreach ($products as $item) {
                    $item['purchase_id'] = $purchase_id;
                    $item['option_id']   = !empty($item['option_id']) && is_numeric($item['option_id']) ? $item['option_id'] : null;
                    PurchaseItemSMA::create($item);

                    if (SettingSMA::getSettingSma()->update_cost) {
                        ProductSMA::where(['id' => $item['product_id']])->update(['cost' => $item['base_unit_cost']]);

                        if ($item['option_id']) {
                            ProductVariantSMA::where(['id' => $item['option_id'], 'product_id' => $item['product_id']])->update(['cost' => $item['base_unit_cost']]);
                        }
                    }
                    if ($data['status'] == 'received' || $data['status'] == 'returned') {
                        $this->updateAVCO(['product_id' => $item['product_id'], 'warehouse_id' => $item['warehouse_id'], 'quantity' => $item['quantity'], 'cost' => $item['base_unit_cost'] ?? $item['real_unit_cost']]);
                    }
                }

//                if (!empty($attachments)) {
//                    foreach ($attachments as $attachment) {
//                        $attachment['subject_id']   = $purchase_id;
//                        $attachment['subject_type'] = 'purchase';
//                        $this->db->insert('attachments', $attachment);
//                    }
//                }

                if ($data['status'] == 'returned') {
                    PurchaseSMA::where(['id' => $data['purchase_id']])->update(['return_purchase_ref' => $data['return_purchase_ref'], 'surcharge' => $data['surcharge'], 'return_purchase_total' => $data['grand_total'], 'return_id' => $purchase_id], ['id' => $data['purchase_id']]);
                }

                if ($data['status'] == 'received' || $data['status'] == 'returned') {
                    $this->syncQuantity(null, $purchase_id);
                }

            }
            DB::commit();
            return true;
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("GRNAddGRNError:".$e);
            return false;
        }
    }

    public function updateAVCO($data)
    {
        if ($wp_details = $this->getWarehouseProductQuantity($data['warehouse_id'], $data['product_id'])) {
            $total_cost     = (($wp_details->quantity * $wp_details->avg_cost) + ($data['quantity'] * $data['cost']));
            $total_quantity = $wp_details->quantity + $data['quantity'];
            if (!empty($total_quantity)) {
                $avg_cost = ($total_cost / $total_quantity);
                DB::table('sma_warehouses_products')->where(['product_id' => $data['product_id'], 'warehouse_id' => $data['warehouse_id']])->update(['avg_cost' => $avg_cost]);
            }
        } else {
            DB::table('sma_warehouses_products')->where(['product_id' => $data['product_id'], 'warehouse_id' => $data['warehouse_id']])->update(['avg_cost' => $data['cost'], 'quantity' => 0]);
        }
    }

    public function getWarehouseProductQuantity($warehouse_id, $product_id)
    {
        $product = DB::table('sma_warehouses_products')->where('warehouse_id', $warehouse_id)
            ->where('product_id', $product_id)
            ->first();

        return $product ?: false;
    }

    public function addPayment(Request $request, $id = null)
    {
        $validateMsgs = [
            'amount_paid.required' => 'The Amount paid field is required',
            'paid_by.required' => 'The Paid by field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'amount_paid'      => ['required'],
            'paid_by'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try{

            if (!empty($request->date)) {
                $date = trim($request->date);
            } else {
                $date = date('Y-m-d H:i:s');
            }

            $payment = [
                'date'         => $date,
                'purchase_id'  => $request->purchase_id,
                'reference_no' => $request->reference_no ? $request->reference_no : OrderRefSMA::getReference('ppay'),
                'amount'       => $request->amount_paid,
                'paid_by'      => $request->paid_by ?? 'cash',
                'cheque_no'    => $request->cheque_no ?? null,
                'cc_no'        => $request->pcc_no ?? null,
                'cc_holder'    => $request->pcc_holder ?? null,
                'cc_month'     => $request->pcc_month ?? null,
                'cc_year'      => $request->pcc_year ?? null,
                'cc_type'      => $request->pcc_type ?? null,
                'note'         => $this->clear_tags($request->note),
                'created_by'   => 1,
                'type'         => 'sent',
            ];

//        if ($_FILES['userfile']['size'] > 0) {
//            $this->load->library('upload');
//            $config['upload_path']   = $this->digital_upload_path;
//            $config['allowed_types'] = $this->digital_file_types;
//            $config['max_size']      = $this->allowed_file_size;
//            $config['overwrite']     = false;
//            $config['encrypt_name']  = true;
//            $this->upload->initialize($config);
//            if (!$this->upload->do_upload()) {
//                $error = $this->upload->display_errors();
//                $this->session->set_flashdata('error', $error);
//                redirect($_SERVER['HTTP_REFERER']);
//            }
//            $photo                 = $this->upload->file_name;
//            $payment['attachment'] = $photo;
//        }
            $resp_h_purch = PaymentSMA::create($payment);
            if($resp_h_purch){
                $sale_id = $resp_h_purch->id;
                if (OrderRefSMA::getReference('ppay') == $payment['reference_no']) {
                    OrderRefSMA::updateReference('ppay');
                }

                $this->syncPurchasePayments($payment['purchase_id']);
            }

            DB::commit();
            Log::error("GRNPaymentCreatedSuccess:".json_encode($payment, true));
            return response()->json(['status' => true,'message' => 'GRN successfully added'], 200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("GRNPaymentCreateError:".$e);
            return response()->json([
                'status' => false,
                'message' => ['Somethings went wrong']
            ], 500);
        }
    }
}
