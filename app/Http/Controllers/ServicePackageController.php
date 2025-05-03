<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroupSMA;
use App\Models\CustomerSMA;
use App\Models\JobOrder;
use App\Models\ProductSMA;
use App\Models\SaleSMA;
use App\Models\ServicePackage;
use App\Models\ServicePackageItem;
use App\Models\ServiceType;
use App\Models\VehicleType;
use App\Models\WarehouseSMA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ServicePackageController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('permission:service_pkg-list', ['only' =>['index','show', 'getDataTableList']]);
//        $this->middleware('permission:service_pkg-view', ['only' =>['show']]);
//        $this->middleware('permission:service_pkg-edit', ['only' =>['edit', 'editData', 'update']]);
//        $this->middleware('permission:service_pkg-add', ['only' =>['create', 'store']]);
//        $this->middleware('permission:service_pkg-delete', ['only' =>['destroy']]);
    }

    public function index(){
        return view('service_type/service_type_list');
    }

    public function getDataTableList(Request $request){
        if ($request->ajax()) {

            //get table names from model class
            $vehicle_type_tbl_nm = (new VehicleType())->getTable();
            $service_type_tbl_nm = (new ServicePackage())->getTable();

            $data = ServicePackage::query()
                ->select($service_type_tbl_nm.'.*',$vehicle_type_tbl_nm.'.type_name')
                ->leftJoin($vehicle_type_tbl_nm,$service_type_tbl_nm.'.vehicle_type_id','=',$vehicle_type_tbl_nm.'.id')
                ->where($service_type_tbl_nm.'.is_active',1);

            try {
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y-m-d');
                    })
                    ->editColumn('price', function ($user) {
                        return number_format($user->price,2);
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';

                        if(auth()->user()->hasPermissionTo('service_pkg-edit') )
                            $actionBtn .= '<span onclick="serviceTypeList.ivm.getServPkgDetails(' . $row->id . ')" class="btn btn-primary btn-sm mr-1"><i class="fas fa-edit"></i></span>';
                        if(auth()->user()->hasPermissionTo('service_pkg-delete') )
                            $actionBtn .= '<span onclick="serviceTypeList.ivm.deleteRecord(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action', 'joStatus'])
                    ->removeColumn('updated_at')
                    ->filterColumn('created_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(".(new ServicePackage())->getTable().".created_at, '%Y-%m-%d') like ?", ["%$keyword%"]); })
                    ->filterColumn('type_name', function ($query, $keyword) { $query->whereRaw((new VehicleType())->getTable().".type_name like ?", ["%$keyword%"]); })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getServicePackageError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function create(){
        return view('job_order/create');
    }

    public function store(Request $request){
        $validateMsgs = [
            'service_name.required' => 'The Service Name field is required',
            'price.required' => 'The Price field is required',
            'vehicle_type.required' => 'The Vehicle Type field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'service_name'      => ['required'],
            'price'      => ['required'],
            'vehicle_type'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $data = [
                'service_name'=> $request->service_name,
                'description'=> $request->service_description,
                'price'=> str_replace(',','',$request->price),
                'vehicle_type_id'=> $request->vehicle_type,
            ];

            $resServ = ServicePackage::create($data);
            $wo_id = $resServ->id;

            $grid_tasks = json_decode($request->grid_item_rows);

            foreach ($grid_tasks AS $task){
                $unit_price = str_replace(',', '', $task->item_grid_price);
                $unit_price_old = str_replace(',', '', $task->item_price_old);
                $tax_val = str_replace(',', '', $task->tax_val);
                $item_qty = str_replace(',', '', $task->item_qty);

                if($tax_val > 0) {$unit_price += ($tax_val/$item_qty); $unit_price_old += ($tax_val/$item_qty);}

                $dataGrid = [
                    'header_id' => $wo_id,
                    'product_id' => $task->product_id,
                    'product_code' => $task->item_code,
                    'product_name' => $task->item_name,
                    'product_type' => $task->item_type,
                    'item_option_id' => $task->item_option,//row.option
                    'net_unit_price' => str_replace(',', '', $task->item_grid_price),//item_price,
                    'net_unit_price_old' => str_replace(',', '', $task->item_price_old),//item_price_old,
                    'unit_price' =>  $unit_price,
                    'unit_price_old' =>  $unit_price_old,
                    'quantity' => $item_qty, // row.qty
                    'warehouse_id' => 1,//default warehouse id
                    'item_tax' => $tax_val,
                    'tax_rate_id' => !$task->tax ? NULL : $task->tax->id,
                    'tax' => str_replace(',', '', $task->tax_rate),//tax_rate
                    'discount' => str_replace(',', '', $task->item_ds),
                    'item_discount' => str_replace(',', '', $task->item_discount),
                    'subtotal' => str_replace(',', '', $task->item_grid_sub_tot),
                    'real_unit_price' => str_replace(',', '', $task->real_unit_price),
                    'real_unit_price_old' => str_replace(',', '', $task->real_unit_price_old),
                    'sale_item_id' => NULL,
                    'product_unit_id' => $task->product_unit,
                    'product_unit_code' => NULL,
                    'unit_quantity' => str_replace(',', '', $task->base_quantity),
                    'comment' => NULL,
                ];
                ServicePackageItem::create($dataGrid);
            }
            DB::commit();
            return response()->json(['status' => false,'message' => 'Service type created successfully'], 200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("ServiceTypeCreateError:".$e->getMessage());
            return response()->json([
                'status' => false,
                'message' => ['Somethings went wrong']
            ], 500);
        }
    }

    public function addJobOrderItem(){

    }

    public function updateWOLoadState($wo_id){

        $w_res = DB::table((new WorkOrder())->getTable())->where('id',$wo_id)->update(['is_loaded_jo' => 1]);
        if($w_res) return true; else return false;
    }

    public function show(){

    }

    public function edit($id){

        $editData = JobOrder::where('id', $id)->first();
        return view('job_order/edit', compact('editData'));
    }

    public function editData(Request $request){
        $vehicle_type_tbl_nm = (new VehicleType())->getTable();
        $serv_type_tbl = (new ServicePackage())->getTable();
        $jb_head_data = ServicePackage::query()
            ->select($serv_type_tbl.'.*',$vehicle_type_tbl_nm.'.type_name')
            ->where($serv_type_tbl.'.id', $request->ItemId)
            ->leftJoin($vehicle_type_tbl_nm,$serv_type_tbl.'.vehicle_type_id','=',$vehicle_type_tbl_nm.'.id')
            ->first();
        $jb_item_data = ServicePackageItem::where('header_id', $request->ItemId)->get();

        $edit_jb_item = [];
        $r = 0;
        foreach ($jb_item_data AS $jb_item){
            $c = uniqid(mt_rand(), true);

            $options              = ProductSMA::getProductOptions($jb_item->product_id, $jb_item->warehouse_id);

            $pro_data = ProductSMA::getProductByCode($jb_item->product_code);
            $jb_item->tax_method = $pro_data['tax_method'] ?? 0;
            $jb_item->base_unit = $pro_data['unit'] ?? 0;
            $jb_item->base_unit_price = $pro_data['price'] ?? 0;

            //match to front common variable name
            $jb_item->db_item_id = $jb_item->id;
            $jb_item->id = $jb_item->product_id;
            $jb_item->type = $jb_item->product_type;
            $jb_item->price = $jb_item->net_unit_price;
            $jb_item->qty = $jb_item->quantity;
            $jb_item->option = $jb_item->item_option_id;
            $jb_item->name = $jb_item->product_name;
            $jb_item->code = $jb_item->product_code;
            $jb_item->unit = $jb_item->product_unit_id;

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
        $editData = array(
            'head_data' => $jb_head_data,
            'item_data' => ($edit_jb_item),
        );

        return $this->responseSelectedDataJson(true, $editData, 200);
    }

    public function update(Request $request){

        DB::beginTransaction();
        try {

            $serv_data = ServicePackage::query()->findOrFail($request->service_id);
            $dataHeadUp = [
                'service_name'=> $request->service_name,
                'description'=> $request->service_description,
                'price'=> str_replace(',','',$request->price),
                'vehicle_type_id'=> $request->vehicle_type,
            ];
            $serv_data->update($dataHeadUp);

            $grid_tasks = json_decode($request->grid_item_rows);
            $delete_grid_tasks = json_decode($request->delete_grid_item_rows);

            foreach ($grid_tasks AS $task){
                $unit_price = str_replace(',', '', $task->item_grid_price);
                $unit_price_old = str_replace(',', '', $task->item_price_old);
                $tax_val = str_replace(',', '', $task->tax_val);
                $item_qty = str_replace(',', '', $task->item_qty);

                if($tax_val > 0) {$unit_price += ($tax_val/$item_qty); $unit_price_old += ($tax_val/$item_qty);}

                if(empty($task->db_item_id)) {

                    ServicePackageItem::create([
                        'header_id' => $request->service_id,
                        'product_id' => $task->product_id,
                        'product_code' => $task->item_code,
                        'product_name' => $task->item_name,
                        'product_type' => $task->item_type,
                        'item_option_id' => $task->item_option,//row.option
                        'net_unit_price' => str_replace(',', '', $task->item_grid_price),//item_price,
                        'net_unit_price_old' => str_replace(',', '', $task->item_price_old),//item_price_old,
                        'unit_price' =>  $unit_price,
                        'unit_price_old' =>  $unit_price_old,
                        'quantity' => $item_qty, // row.qty
                        'warehouse_id' => 1,//default warehouse id
                        'item_tax' => $tax_val,
                        'tax_rate_id' => !$task->tax ? NULL : $task->tax->id,
                        'tax' => str_replace(',', '', $task->tax_rate),//tax_rate
                        'discount' => str_replace(',', '', $task->item_ds),
                        'item_discount' => str_replace(',', '', $task->item_discount),
                        'subtotal' => str_replace(',', '', $task->item_grid_sub_tot),
                        'real_unit_price' => str_replace(',', '', $task->real_unit_price),
                        'real_unit_price_old' => str_replace(',', '', $task->real_unit_price_old),
                        'sale_item_id' => NULL,
                        'product_unit_id' => $task->product_unit,
                        'product_unit_code' => NULL,
                        'unit_quantity' => str_replace(',', '', $task->base_quantity),
                        'comment' => NULL,
                    ]);
                }else{

                    $res_up = ServicePackageItem::findOrFail($task->db_item_id);
                    $up_data = [
                        'product_type' => $task->item_type,
                        'item_option_id' => $task->item_option,//row.option
                        'net_unit_price' => str_replace(',', '', $task->item_grid_price),//item_price,
                        'net_unit_price_old' => str_replace(',', '', $task->item_price_old),//item_price_old,
                        'unit_price' =>  $unit_price,
                        'unit_price_old' =>  $unit_price_old,
                        'quantity' => $item_qty, // row.qty
                        'warehouse_id' => 1,//default warehouse id
                        'item_tax' => $tax_val,
                        'tax_rate_id' => !$task->tax ? NULL : $task->tax->id,
                        'tax' => str_replace(',', '', $task->tax_rate),//tax_rate
                        'discount' => str_replace(',', '', $task->item_ds),
                        'item_discount' => str_replace(',', '', $task->item_discount),
                        'subtotal' => str_replace(',', '', $task->item_grid_sub_tot),
                        'real_unit_price' => str_replace(',', '', $task->real_unit_price),
                        'real_unit_price_old' => str_replace(',', '', $task->real_unit_price_old),
                        'sale_item_id' => NULL,
                        'product_unit_id' => $task->product_unit,
                        'product_unit_code' => NULL,
                        'unit_quantity' => str_replace(',', '', $task->base_quantity),
                        'comment' => NULL,
                    ];
                    $res_up->update($up_data);
                }
            }

            foreach ($delete_grid_tasks AS $dTask){
                ServicePackageItem::where('id',$dTask)->delete();
            }

            DB::commit();
            return $this->responseDataJson(true,'Service package updated successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("ServPkgUpdateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function destroy(Request $request){
        $employee = ServicePackage::findOrFail($request->emp_id);

        $active_state = 1;
        if($employee['is_active']) $active_state = 0;

        $dataArray = ['is_active' =>$active_state];

        $resp = $employee->update($dataArray);

        if($active_state)
            $message = "Service package has been activated!";
        else
            $message = "Service package has been inactivated!";

        if($resp){

            return $this->responseDataJson(true, $message,200);
        }else{

            return $this->responseDataJson(true, "Something went wrong!",401);
        }
    }

    public function getVTypeWiseServPkgsList(Request $request, $vTypeId){
        if ($request->ajax()) {

            //get table names from model class
            $vehicle_type_tbl_nm = (new VehicleType())->getTable();
            $service_type_tbl_nm = (new ServicePackage())->getTable();

            $data = ServicePackage::query()
                ->select($service_type_tbl_nm.'.*',$vehicle_type_tbl_nm.'.type_name')
                ->leftJoin($vehicle_type_tbl_nm,$service_type_tbl_nm.'.vehicle_type_id','=',$vehicle_type_tbl_nm.'.id')
                ->where($service_type_tbl_nm.'.is_active',1)->where($service_type_tbl_nm.'.vehicle_type_id', $vTypeId);

            try {
                return Datatables::of($data)
                    ->editColumn('created_at', function ($user) {
                        return $user->created_at->format('Y-m-d');
                    })
                    ->editColumn('price', function ($user) {
                        return number_format($user->price,2);
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';

                        $actionBtn .= '<span onclick="jobOrder.ivm.getServPkgDetails(' . $row->id . ')" class="btn btn-primary btn-sm mr-1"><i class="fas fa-plus"></i></span>';
                        return $actionBtn;
                    })
                    ->rawColumns(['action', 'joStatus'])
                    ->removeColumn('updated_at')
                    ->filterColumn('created_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') like ?", ["%$keyword%"]); })
                    ->filterColumn('type_name', function ($query, $keyword) { $query->whereRaw((new VehicleType())->getTable().".type_name like ?", ["%$keyword%"]); })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getVTypeWiseServicePackageError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function suggestions(Request $request, $pos=0){
        $term         = $request->term;
        $warehouse_id = $request->warehouse_id;
        $customer_id  = $request->customer_id;

        $analyzed  = JobOrder::analyze_term($term);

        $sr        = $analyzed['term'];
        $option_id = $analyzed['option_id'];
        $sr        = addslashes($sr);
        $strict    = $analyzed['strict']                    ?? false;
        $qty       = $strict ? null : $analyzed['quantity'] ?? null;
        $bprice    = $strict ? null : $analyzed['price']    ?? null;

        $warehouse      = WarehouseSMA::where('id', $warehouse_id)->first();
        $customer       = CustomerSMA::where('id', $customer_id)->first();
        $customer_group = CustomerGroupSMA::where('id', $customer->customer_group_id)->first();

        $rows           = ServicePackage::getProductNames($sr, $warehouse_id);

        if ($rows) {
            $r = 0;
            foreach ($rows as $row) {
                $c = uniqid(mt_rand(), true);
                unset($row->cost, $row->details, $row->product_details, $row->image, $row->barcode_symbology, $row->cf1, $row->cf2, $row->cf3, $row->cf4, $row->cf5, $row->cf6, $row->supplier1price, $row->supplier2price, $row->cfsupplier3price, $row->supplier4price, $row->supplier5price, $row->supplier1, $row->supplier2, $row->supplier3, $row->supplier4, $row->supplier5, $row->supplier1_part_no, $row->supplier2_part_no, $row->supplier3_part_no, $row->supplier4_part_no, $row->supplier5_part_no);
                $option               = false;
                $row->quantity        = 0;
                $row->item_tax_method = $row->tax_method;
                $row->qty             = 1;
                $row->discount        = '0';
                $row->serial          = '';
                $options              = ServicePackage::getProductOptions($row->id, $warehouse_id);
                if ($options) {
                    $opt = $option_id && $r == 0 ? ProductSMA::getProductOptionByID($option_id) : $options[0];
                    if (!$option_id || $r > 0) {
                        $option_id = $opt->id;
                    }
                } else {
                    $opt        = json_decode('{}');
                    $opt->price = 0;
                    $option_id  = false;
                }
                $row->option = $option_id;
                $pis         = ProductSMA::getPurchasedItems($row->id, $warehouse_id, $row->option);
                if ($pis) {
                    $row->quantity = 0;
                    foreach ($pis as $pi) {
                        $row->quantity += $pi->quantity_balance;
                    }
                }
                if ($options) {
                    $option_quantity = 0;
                    foreach ($options as $option) {
                        $pis = ProductSMA::getPurchasedItems($row->id, $warehouse_id, $row->option);
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
                if ($row->promotion) {
                    $row->price = $row->promo_price;
                } elseif ($customer->price_group_id) {
                    if ($pr_group_price = ProductSMA::getProductGroupPrice($row->id, $customer->price_group_id)) {
                        $row->price = $pr_group_price->price;
                    }
                } elseif ($warehouse->price_group_id) {
                    if ($pr_group_price = ProductSMA::getProductGroupPrice($row->id, $warehouse->price_group_id)) {
                        $row->price = $pr_group_price->price;
                    }
                }
                if ($customer_group->discount && $customer_group->percent < 0) {
                    $row->discount = (0 - $customer_group->percent) . '%';
                } else {
                    $row->price = $row->price + (($row->price * $customer_group->percent) / 100);
                }
                $row->real_unit_price = $row->price;
                $row->base_quantity   = 1;
                $row->base_unit       = $row->unit;
                $row->base_unit_price = $row->price;
                $row->unit            = $row->sale_unit ? $row->sale_unit : $row->unit;
                $row->comment         = '';
                $combo_items          = false;
                if ($row->type == 'combo') {
                    $combo_items = ServicePackage::getProductComboItems($row->id, $warehouse_id);
                }
                if ($qty) {
                    $row->qty           = $qty;
                    $row->base_quantity = $qty;
                } else {
                    $row->qty = ($bprice ? $bprice / $row->price : 1);
                }
                $units    = ProductSMA::getUnitsByBUID($row->base_unit);
                $tax_rate = SaleSMA::getTaxRateByID($row->tax_rate);

                $pr[] = ['id' => sha1($c . $r), 'item_id' => $row->id, 'label' => $row->name . ' (' . $row->code . ')', 'category' => $row->category_id,
                    'row'     => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options, ];
                $r++;
            }

            $this->send_json_sma($pr);
        } else {
            $this->send_json_sma([['id' => 0, 'label' => __('messages.no_match_found'), 'value' => $term]]);
        }
    }
}
