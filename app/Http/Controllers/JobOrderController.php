<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroupSMA;
use App\Models\CustomerSMA;
use App\Models\Employee;
use App\Models\FormSerial;
use App\Models\JobOrder;
use App\Models\JobOrderItem;
use App\Models\JobOrderServPackage;
use App\Models\JobOrderServPkgItem;
use App\Models\JobOrderServPkgTechnician;
use App\Models\JobOrderServPkgTechTime;
use App\Models\JobOrderTask;
use App\Models\JobOrderTaskTechnician;
use App\Models\JobOrderTaskTechTime;
use App\Models\ProductSMA;
use App\Models\ProductVariantSMA;
use App\Models\SaleSMA;
use App\Models\ServicePackage;
use App\Models\ServicePackageItem;
use App\Models\SettingSMA;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleContract;
use App\Models\VehicleInsurance;
use App\Models\VehicleType;
use App\Models\VehicleWarranty;
use App\Models\WarehouseSMA;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use PDF;

class JobOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:joborder-view', ['only' =>['index','show', 'getDataTableList']]);
        $this->middleware('permission:joborder-edit', ['only' =>['edit', 'editData', 'update']]);
        $this->middleware('permission:joborder-add', ['only' =>['create', 'store']]);
        $this->middleware('permission:joborder-delete', ['only' =>['destroy']]);
        $this->middleware('permission:jo-cost-unapprove-list', ['only' =>['getTBCostApproveDataTableList']]);
        $this->middleware('permission:jo-cost-approve', ['only' =>['joborderApproveCost']]);
        $this->middleware('permission:jo-approved-list', ['only' =>['joborderApproveCost']]);
    }

    public function index(){
        return view('job_order/joborder_list');
    }

    public function getDataTableList(Request $request){
        if ($request->ajax()) {

            $cus_tbl = (new CustomerSMA())->getTable();
            $jb_order_tbl = (new JobOrder())->getTable();
            $wo_tbl = (new WorkOrder())->getTable();

            $data = JobOrder::query()
                ->select($jb_order_tbl.'.*', DB::raw($wo_tbl.'.serial_no AS woSerialNo'), $cus_tbl.'.name')
                ->leftJoin($wo_tbl,$wo_tbl.'.id','=',$jb_order_tbl.'.wo_order_id')
                ->leftJoin($cus_tbl,$cus_tbl.'.id','=',$wo_tbl.'.customer_id')
                ->where($jb_order_tbl.'.is_estimate',0)
                ->where($wo_tbl.'.is_active',1)
                ->where($jb_order_tbl.'.is_active',1);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->addIndexColumn()
                    ->addColumn('joStatus', function ($row){
                        if($row->jo_status == 0)
                            $state = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Pending joborder">Pending</span>';// Just created JO
                        elseif($row->jo_status == 1)
                            $state = '<span class="text-black badge badge-primary" data-toggle="tooltip" data-placement="top" title="Cost approved by service manager">APRVDCST</span>';// Approved WO cost before work commencement
                        elseif($row->jo_status == 2)
                            $state = '<span class="text-black badge badge-info" data-toggle="tooltip" data-placement="top" title="Assigned to labor">ASSIGNED</span>';// waiting in the labor assigned task queue to proceed in specific labor list
                        elseif($row->jo_status == 3)
                            $state = '<span class="text-black badge badge-warning" data-toggle="tooltip" data-placement="top" title="Work in progress">WIP</span>';// Task is in work in progress
                        elseif($row->jo_status == 4)
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Work is completed partially by technician (s)">WRKPCTD</span>';// task is done by the technicians partially and pending work approve
                        elseif($row->jo_status == 5)
                            $state = '<span class="text-black badge badge-light badge-success" data-toggle="tooltip" data-placement="top" title="All task are completed by technician (s)">WRKCTD</span>';// Job order all task are done by the technicians and pending work approve
                        elseif($row->jo_status == 6)
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="All task are approved and sent to cashier">APRVD</span>';// approved task after completing the work
                        /*elseif($row->jo_status == 7)
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Task sent to cashier">STC</span>';// after completing all tasks sent to cashier*/
                        elseif($row->jo_status == 7)
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Job order has been invoiced">Invoiced</span>';// Paid the tot amount and completed
                        else
                            $state = '<span class="text-white badge badge-secondary">-</span>';// Task has no state
                        return $state;
                    })
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
//                        if(auth()->user()->hasPermissionTo('joborder-view') )
//                            $actionBtn .= '<span onclick="jobOrderList.ivm.showDetails('.$row->id.')" class="edit btn btn-success btn-sm mr-1"><i class="fas fa-eye"></i></span>';
                        if(auth()->user()->hasPermissionTo('joborder-edit') && ($row->jo_status <= 6))
                            $actionBtn .= '<a href="' . route('joborder.edit', $row->id) . '" class="delete btn btn-primary btn-sm mr-1"><i class="fas fa-edit"></i></a>';
                        if(auth()->user()->hasPermissionTo('joborder-delete') && ($row->jo_status <= 6))
                            $actionBtn .= '<span onclick="jobOrderList.ivm.deleteRecord(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action', 'joStatus'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);})
                    ->filterColumn('woSerialNo', function ($query, $keyword) { $query->whereRaw((new WorkOrder())->getTable().".serial_no like ?", ["%$keyword%"]);})
                    ->filterColumn('name', function ($query, $keyword) { $query->whereRaw((new CustomerSMA())->getTable().".name like ?", ["%$keyword%"]);})
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getJobOrderError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function create(){
        return view('job_order/create');
    }

    public function createWoJo(Request $request, $wo_id){

        $wo_id = $wo_id;
        return view('job_order/create', compact('wo_id'));
    }

    public function store(Request $request){
        $validateMsgs = [
            'wo_order_id.required' => 'The WO No field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'wo_order_id'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        if($request->wo_order_id == "joborder-create"){
            return $this->responseDataJson(false, ["WO No is required!"], 401);
        }

        DB::beginTransaction();
        try {

            $jo_serial_cus = FormSerial::getTemporaryId('job_order');
            $data = [
                'serial_no'=> $jo_serial_cus,
                'wo_order_id'=> $request->wo_order_id,
                'job_type'=> $request->jo_type,
                'jo_date'=> empty($request->jo_date) ? date('Y-m-d') : $request->jo_date,
            ];

            $respJOrder = JobOrder::create($data);
            $wo_id = $respJOrder['id'];

            $up_res = $this->updateWOLoadState($request->wo_order_id);
            if(!$up_res) { return $this->responseDataJson(false,['Somethings went wrong'], 500); }

            $grid_tasks = json_decode($request->grid_task_rows);

            foreach ($grid_tasks AS $task){
                JobOrderTask::create([
                    'jo_id' => $wo_id,
                    'task_id' => $task->col8,
                    'task_name' => $task->col1,
                    'task_description' => $task->col2,
                    'hours' => !empty($task->col3) ? str_replace(',','',$task->col3): 0.0,
                    'hourly_rate' => !empty($task->col5) ? str_replace(',','',$task->col5): 0.0,
                    'actual_hours' => !empty($task->col4) ? str_replace(',','',$task->col4): 0.0,
                    'labor_cost' => !empty($task->col6) ? str_replace(',','',$task->col6): 0.0,
                    'total' => !empty($task->col7) ? str_replace(',','',$task->col7): 0.0,
                ]);
            }

            $grid_items = json_decode($request->grid_item_rows);

            foreach ($grid_items AS $task){
                $unit_price = str_replace(',', '', $task->item_grid_price);
                $tax_val = str_replace(',', '', $task->tax_val);
                $item_qty = str_replace(',', '', $task->item_qty);

                if($tax_val > 0) $unit_price += ($tax_val/$item_qty);

                $dataGrid = [
                    'jo_header_id' => $wo_id,
                    'service_pkg_id' => $task->serv_pkg_id,
                    'product_id' => $task->product_id,
                    'product_code' => $task->item_code,
                    'product_name' => $task->item_name,
                    'product_type' => $task->item_type,
                    'item_option_id' => $task->item_option,//row.option
                    'net_unit_price' => str_replace(',', '', $task->item_grid_price),//item_price,
                    'unit_price' =>  $unit_price,
                    'quantity' => $item_qty, // row.qty
                    'warehouse_id' => 1,//default warehouse id
                    'item_tax' => $tax_val,
                    'tax_rate_id' => !$task->tax ? NULL : $task->tax->id,
                    'tax' => str_replace(',', '', $task->tax_rate),//tax_rate
                    'discount' => str_replace(',', '', $task->item_ds),
                    'item_discount' => str_replace(',', '', $task->item_discount),
                    'subtotal' => str_replace(',', '', $task->item_grid_sub_tot),
                    'real_unit_price' => str_replace(',', '', $task->real_unit_price),
                    'sale_item_id' => NULL,
                    'product_unit_id' => $task->product_unit,
                    'product_unit_code' => NULL,
                    'unit_quantity' => str_replace(',', '', $task->base_quantity),
                    'comment' => NULL,
                ];
                JobOrderItem::create($dataGrid);
            }

            $serv_pkgs = json_decode($request->serv_pkg_rows);
            foreach ($serv_pkgs AS $task){
                $serv_items = ServicePackageItem::where('header_id', $task->col6)->get();
                $dataS = [
                    'jo_id' => $wo_id,
                    'service_pkg_id' => $task->col6,
                    'service_name' => $task->col1,
                    'description' => $task->col2,
                    'vehi_type_id' => $request->vehi_type_id,
                    'cost' => 0,
                    'price' => str_replace(',', '', $task->col4),
                    'sub_total' => str_replace(',', '', $task->col5),
                ];
                $resp = JobOrderServPackage::create($dataS);

                foreach ($serv_items AS $s_item){
                    $jo_s_items = [
                        'jo_serv_pkg_id'=> $resp['id'],
                        'service_pkg_id'=> $task->col6,
                        'product_id' => $s_item->product_id,
                        'product_code' => $s_item->product_code,
                        'product_name' => $s_item->product_name,
                        'product_type' => $s_item->product_type,
                        'item_option_id' => $s_item->item_option_id,
                        'net_unit_price' => $s_item->net_unit_price,
                        'net_unit_price_old' => $s_item->net_unit_price_old,
                        'unit_price' => $s_item->unit_price,
                        'unit_price_old' => $s_item->unit_price_old,
                        'quantity' => $s_item->quantity,
                        'warehouse_id' => $s_item->warehouse_id,
                        'item_tax' => $s_item->item_tax,
                        'tax_rate_id' => $s_item->tax_rate_id,
                        'tax' => $s_item->tax,
                        'discount' => $s_item->discount,
                        'item_discount' => $s_item->item_discount,
                        'subtotal' => $s_item->subtotal,
                        'real_unit_price' => $s_item->real_unit_price,
                        'real_unit_price_old' => $s_item->real_unit_price_old,
                        'sale_item_id' => $s_item->sale_item_id,
                        'product_unit_id' => $s_item->product_unit_id,
                        'product_unit_code' => $s_item->product_unit_code,
                        'unit_quantity' => $s_item->unit_quantity,
                        'comment' => $s_item->comment,
                    ];

                    JobOrderServPkgItem::create($jo_s_items);
                }
            }

            $this->notifyServiceMana('Job order Approve',$jo_serial_cus.' Needs cost approval');

            DB::commit();
            return $this->responseDataJson(true,'Job order created successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("JoborderCreateError:".$e);
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function updateWOLoadState($wo_id){

        $w_res = DB::table((new WorkOrder())->getTable())->where('id',$wo_id)->update(['is_loaded_jo' => 1]);
        if($w_res) return true; else return false;
    }

    public function show(){ }

    public function edit($id){

        $editData = JobOrder::where('id', $id)->first();
        return view('job_order/edit', compact('editData'));
    }

    /** Assign technician view data list */
    public function editData(Request $request){

        $job_id = $request->emp_id ?? $request->item_id;

        if(empty($job_id)) $job_id = $request->item_id;

        $wo_tbl = (new WorkOrder())->getTable();
        $vehi_ins = (new VehicleInsurance())->getTable();
        $vehi_con = (new VehicleContract())->getTable();
        $vehi_war = (new VehicleWarranty())->getTable();

        $tax_rate_tbl = "sma_tax_rates";
        $pro_opt_tbl = (new ProductVariantSMA())->getTable();

        $vehicle_type_tbl_nm = (new VehicleType())->getTable();
        $serv_pkg_tbl = (new ServicePackage())->getTable();
        $jo_serv_pkg_tbl = (new JobOrderServPackage())->getTable();
        $jo_item_tbl = (new JobOrderItem())->getTable();
        $jo_tsk_tbl = (new JobOrderTask())->getTable();
        $jo_tsk_tech_tbl = (new JobOrderTaskTechnician())->getTable();
        $jo_serv_pkg_tech_tbl = (new JobOrderServPkgTechnician())->getTable();
        $tech_tbl = (new Employee())->getTable();

        $jb_head_data = JobOrder::where('id', $job_id)->first();
        /** getting joborder service packages data*/
        $jb_serv_pkg_data = JobOrderServPackage::query()
            ->select($jo_serv_pkg_tbl.'.*', $serv_pkg_tbl.'.service_name', $serv_pkg_tbl.'.description', $vehicle_type_tbl_nm.'.type_name')
            ->leftJoin($serv_pkg_tbl, $jo_serv_pkg_tbl.'.service_pkg_id', '=', $serv_pkg_tbl.'.id')
            ->leftJoin($vehicle_type_tbl_nm,$serv_pkg_tbl.'.vehicle_type_id','=',$vehicle_type_tbl_nm.'.id')
            ->where($jo_serv_pkg_tbl.'.jo_id', $job_id)->get();

        $jb_s = [];
        foreach ($jb_serv_pkg_data AS $jb_task){
            $jb_tsk_techs = JobOrderServPkgTechnician::query()
                ->select($jo_serv_pkg_tech_tbl.'.*', $tech_tbl.'.full_name')
                ->leftJoin($tech_tbl, $jo_serv_pkg_tech_tbl.'.tech_id', '=', $tech_tbl.'.id')
                ->where($jo_serv_pkg_tech_tbl.'.jo_serv_pkg_id', $jb_task->id)->get();

            foreach ($jb_tsk_techs AS $jbtt){
                $jb_tsk_tch_time = JobOrderServPkgTechTime::query()
                    ->select('is_started', 'is_ended', 'is_finished',DB::raw("IF(ISNULL(time((TIMEDIFF( end_datetime, start_datetime )))),'00:00:00', time((TIMEDIFF( end_datetime, start_datetime )))) as total"))
                    ->where('jo_serv_pkg_id', $jb_task->id)->where('tech_id', $jbtt->tech_id)
                    ->get();

                $seconds = 0;
                $jbState = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Service Package is in queue to be proceed by technician">Pending</span>';
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

            $jb_s[] = ['serv_pkg' => $jb_task, 'pkg_techs' => $jb_tsk_techs];
        }
        /** getting joborder task data*/
        $jb_task_data = JobOrderTask::where('jo_id', $job_id)->get();
        $jb_t = [];
        foreach ($jb_task_data AS $jb_task){

            $jb_tsk_techs = JobOrderTaskTechnician::query()
                ->select($jo_tsk_tech_tbl.'.*', $tech_tbl.'.full_name')
                ->leftJoin($tech_tbl, $jo_tsk_tech_tbl.'.tech_id', '=', $tech_tbl.'.id')
                ->where($jo_tsk_tech_tbl.'.jo_task_id', $jb_task->id)->get();

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

            }

            $jb_t[] = ['task' => $jb_task, 'task_techs' => $jb_tsk_techs];
        }

        /** getting joborder item data*/
        $jb_item_data = JobOrderItem::query()
            ->select($jo_item_tbl.'.*', DB::raw($pro_opt_tbl.'.name AS option_name'), $serv_pkg_tbl.'.service_name')
            ->leftjoin($tax_rate_tbl, $jo_item_tbl.'.tax_rate_id','=',$tax_rate_tbl.'.id')
            ->leftJoin($pro_opt_tbl, $jo_item_tbl.'.item_option_id', '=', $pro_opt_tbl.'.id')
            ->leftJoin($serv_pkg_tbl, $jo_item_tbl.'.service_pkg_id', '=', $serv_pkg_tbl.'.id')
            ->where('jo_header_id', $job_id)->get();

        $edit_jb_item = [];
        $r = 0;
        foreach ($jb_item_data AS $jb_item){
            $c = uniqid(mt_rand(), true);

            $options              = ProductSMA::getProductOptions($jb_item->product_id, $jb_item->warehouse_id);

            $pro_data = ProductSMA::getProductByCode($jb_item->product_code);
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

        /** getting joborder workorder data*/
        $wo_data = [];
        if(isset($jb_head_data->wo_order_id)){

            $wo_data = WorkOrder::select($wo_tbl.'.*',
                $vehi_war.'.war_company_name', $vehi_war.'.warranty_no', $vehi_war.'.date_of_purchase', $vehi_war.'.expire_date', $vehi_war.'.phone_number',
                $vehi_con.'.con_company_name', $vehi_con.'.contract_no', DB::raw($vehi_con.'.expire_date AS con_expire_date'), $vehi_con.'.mileage', DB::raw($vehi_con.'.phone_number AS con_phone_number'),
                $vehi_ins.'.ins_company_name', $vehi_ins.'.policy_no', DB::raw($vehi_ins.'.expire_date AS ins_expire_date'), DB::raw($vehi_ins.'.phone_number AS ins_phone_number'),
            )
                ->leftjoin($vehi_ins, $vehi_ins.'.wo_id', '=', $wo_tbl.'.id')
                ->leftjoin($vehi_con, $vehi_con.'.wo_id', '=', $wo_tbl.'.id')
                ->leftjoin($vehi_war, $vehi_war.'.wo_id', '=', $wo_tbl.'.id')
                ->where($wo_tbl.'.id',$jb_head_data->wo_order_id)
                ->first();
        }

        $jb_tsk_tech_data = JobOrderTaskTechnician::query()
            ->select($jo_tsk_tech_tbl.'.*', $tech_tbl.'.full_name', $jo_tsk_tbl.'.task_name')
            ->leftJoin($tech_tbl, $jo_tsk_tech_tbl.'.tech_id', '=', $tech_tbl.'.id')
            ->leftJoin($jo_tsk_tbl, $jo_tsk_tech_tbl.'.jo_task_id', '=', $jo_tsk_tbl.'.id')
            ->where($jo_tsk_tech_tbl.'.jo_id', $job_id)->get();

        $jb_serv_tech_data = JobOrderServPkgTechnician::query()
            ->select($jo_serv_pkg_tech_tbl.'.*', $tech_tbl.'.full_name', $jo_serv_pkg_tbl.'.service_name')
            ->leftJoin($tech_tbl, $jo_serv_pkg_tech_tbl.'.tech_id', '=', $tech_tbl.'.id')
            ->leftJoin($jo_serv_pkg_tbl, $jo_serv_pkg_tech_tbl.'.jo_serv_pkg_id', '=', $jo_serv_pkg_tbl.'.id')
            ->where($jo_serv_pkg_tech_tbl.'.jo_id', $job_id)->get();

        $editData = array(
            'head_data' => $jb_head_data,
            'serv_pkg_data' => ($jb_s),
            'serv_pkg_tech' => ($jb_serv_tech_data),
            'task_data' => ($jb_t),
            'tsk_tech' => ($jb_tsk_tech_data),
            'item_data' => ($edit_jb_item),
            'wo_data' => $wo_data,
        );

        return $this->responseSelectedDataJson(true, $editData, 200);
    }

    public function update(Request $request){

        DB::beginTransaction();
        try {

            $grid_tasks = json_decode($request->grid_task_rows);
            $delete_grid_tasks = json_decode($request->delete_grid_task_rows);
            $job_id = $request->item_id;

            $headDataUp = [
                'job_type'=> $request->jo_type
            ];
            $resHeadResp = JobOrder::findOrFail($job_id);
            $resHeadResp->update($headDataUp);

            /** Start: Labor task insert area **/
            foreach ($grid_tasks AS $task){
                if(empty($task->col9)) {
                    JobOrderTask::create([
                        'jo_id' => $job_id,
                        'task_id' => $task->col8,
                        'task_name' => $task->col1,
                        'task_description' => $task->col2,
                        'hours' => !empty($task->col3) ? str_replace(',', '', $task->col3) : 0.0,
                        'hourly_rate' => !empty($task->col5) ? str_replace(',', '', $task->col5) : 0.0,
                        'actual_hours' => !empty($task->col4) ? str_replace(',', '', $task->col4) : 0.0,
                        'labor_cost' => !empty($task->col6) ? str_replace(',', '', $task->col6) : 0.0,
                        'total' => !empty($task->col7) ? str_replace(',', '', $task->col7) : 0.0,
                    ]);
                }else{

                    $res_up = JobOrderTask::findOrFail($task->col9);
                    $up_data = [
                        'task_id' => $task->col8,
                        'task_name' => $task->col1,
                        'task_description' => $task->col2,
                        'hours' => !empty($task->col3) ? str_replace(',', '', $task->col3) : 0.0,
                        'hourly_rate' => !empty($task->col5) ? str_replace(',', '', $task->col5) : 0.0,
                        'actual_hours' => !empty($task->col4) ? str_replace(',', '', $task->col4) : 0.0,
                        'labor_cost' => !empty($task->col6) ? str_replace(',', '', $task->col6) : 0.0,
                        'total' => !empty($task->col7) ? str_replace(',', '', $task->col7) : 0.0,
                    ];
                    $res_up->update($up_data);
                }
            }
            foreach ($delete_grid_tasks AS $dTask){
                JobOrderTask::where('id',$dTask)->delete();
            }
            /** End: Labor task insert area **/

            /** Start: Item insert area **/
            $grid_items = json_decode($request->grid_item_rows);
            $delete_grid_items = json_decode($request->delete_grid_item_rows);
            foreach ($grid_items AS $task){
                $unit_price = str_replace(',', '', $task->item_grid_price);
                $tax_val = str_replace(',', '', $task->tax_val);
                $item_qty = str_replace(',', '', $task->item_qty);

                if($tax_val > 0) $unit_price += ($tax_val/$item_qty);

                if(empty($task->db_item_id)) {
                    //if db_item_id is empty then insert a new record
                    $dataGrid = [
                        'jo_header_id' => $job_id,
                        'service_pkg_id' => $task->serv_pkg_id,
                        'product_id' => $task->product_id,
                        'product_code' => $task->item_code,
                        'product_name' => $task->item_name,
                        'product_type' => $task->item_type,
                        'item_option_id' => $task->item_option,//row.option
                        'net_unit_price' => str_replace(',', '', $task->item_grid_price),//item_price,
                        'unit_price' => $unit_price,
                        'quantity' => $item_qty, // row.qty
                        'warehouse_id' => 1,//default warehouse id
                        'item_tax' => $tax_val,
                        'tax_rate_id' => !$task->tax ? NULL : $task->tax->id,
                        'tax' => str_replace(',', '', $task->tax_rate),//tax_rate
                        'discount' => str_replace(',', '', $task->item_ds),
                        'item_discount' => str_replace(',', '', $task->item_discount),
                        'subtotal' => str_replace(',', '', $task->item_grid_sub_tot),
                        'real_unit_price' => str_replace(',', '', $task->real_unit_price),
                        'sale_item_id' => NULL,
                        'product_unit_id' => $task->product_unit,
                        'product_unit_code' => NULL,
                        'unit_quantity' => str_replace(',', '', $task->base_quantity),
                        'comment' => NULL,
                    ];
                    JobOrderItem::create($dataGrid);
                }else{
                    //if db_item_id is not empty then update the current record
                    $jb_it_data = JobOrderItem::findOrFail($task->db_item_id);
                    $dataGridUp = [
                        'product_id' => $task->product_id,
                        'product_code' => $task->item_code,
                        'product_name' => $task->item_name,
                        'product_type' => $task->item_type,
                        'item_option_id' => $task->item_option,//row.option
                        'net_unit_price' => str_replace(',', '', $task->item_grid_price),//item_price,
                        'unit_price' => $unit_price,
                        'quantity' => $item_qty, // row.qty
                        'warehouse_id' => 1,//default warehouse id
                        'item_tax' => $tax_val,
                        'tax_rate_id' => !$task->tax ? NULL : $task->tax->id,
                        'tax' => str_replace(',', '', $task->tax_rate),//tax_rate
                        'discount' => str_replace(',', '', $task->item_ds),
                        'item_discount' => str_replace(',', '', $task->item_discount),
                        'subtotal' => str_replace(',', '', $task->item_grid_sub_tot),
                        'real_unit_price' => str_replace(',', '', $task->real_unit_price),
                        'sale_item_id' => NULL,
                        'product_unit_id' => $task->product_unit,
                        'product_unit_code' => NULL,
                        'unit_quantity' => str_replace(',', '', $task->base_quantity),
                        'comment' => NULL,
                    ];

                    $jb_it_data->update($dataGridUp);
                }
            }
            foreach ($delete_grid_items AS $dTask){
                JobOrderItem::where('id',$dTask)->delete();
            }
            /** End: Item insert area **/

            /** Start:Service package adding area **/
            $serv_pkgs = json_decode($request->serv_pkg_rows);
            $delete_serv_pkgs = json_decode($request->delete_serv_pkg_rows);
            foreach ($serv_pkgs AS $task){
                if(empty($task->col7)) {
                    $serv_items = ServicePackageItem::where('header_id', $task->col6)->get();
                    $dataS = [
                        'jo_id' => $job_id,
                        'service_pkg_id' => $task->col6,
                        'service_name' => $task->col1,
                        'description' => $task->col2,
                        'vehi_type_id' => $request->vehi_type_id,
                        'cost' => 0,
                        'price' => str_replace(',', '', $task->col4),
                        'sub_total' => str_replace(',', '', $task->col5),
                    ];
                    $resp = JobOrderServPackage::create($dataS);

                    foreach ($serv_items AS $s_item){
                        $jo_s_items = [
                            'jo_serv_pkg_id'=> $resp->id,
                            'product_id' => $s_item->product_id,
                            'product_code' => $s_item->product_code,
                            'product_name' => $s_item->product_name,
                            'product_type' => $s_item->product_type,
                            'item_option_id' => $s_item->item_option_id,
                            'net_unit_price' => $s_item->net_unit_price,
                            'net_unit_price_old' => $s_item->net_unit_price_old,
                            'unit_price' => $s_item->unit_price,
                            'unit_price_old' => $s_item->unit_price_old,
                            'quantity' => $s_item->quantity,
                            'warehouse_id' => $s_item->warehouse_id,
                            'item_tax' => $s_item->item_tax,
                            'tax_rate_id' => $s_item->tax_rate_id,
                            'tax' => $s_item->tax,
                            'discount' => $s_item->discount,
                            'item_discount' => $s_item->item_discount,
                            'subtotal' => $s_item->subtotal,
                            'real_unit_price' => $s_item->real_unit_price,
                            'real_unit_price_old' => $s_item->real_unit_price_old,
                            'sale_item_id' => $s_item->sale_item_id,
                            'product_unit_id' => $s_item->product_unit_id,
                            'product_unit_code' => $s_item->product_unit_code,
                            'unit_quantity' => $s_item->unit_quantity,
                            'comment' => $s_item->comment,
                        ];

                        JobOrderServPkgItem::create($jo_s_items);
                    }
                }
            }
            foreach ($delete_serv_pkgs AS $dTask){
                $jb_serv_pkg_data = JobOrderServPackage::where('id',$dTask)->first();
                if(isset($jb_serv_pkg_data->service_pkg_id) && !empty($jb_serv_pkg_data->service_pkg_id)) {
                    JobOrderServPackage::where('id', $dTask)->delete();
                    JobOrderItem::where('jo_header_id', $job_id)->where('service_pkg_id', $jb_serv_pkg_data->service_pkg_id)->delete();
                    JobOrderItem::where('jo_header_id', $job_id)->where('service_pkg_id', $jb_serv_pkg_data->service_pkg_id)->delete();
                }
            }
            /** End:Service package adding area **/

            DB::commit();
            return $this->responseDataJson(true,'Job order updated successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("JoborderUpdateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function storeReview(Request $request){

        DB::beginTransaction();
        try {

            $grid_tasks = json_decode($request->grid_task_rows);

            foreach ($grid_tasks AS $task){
                if(empty($task->col9)) {
                    JobOrderTask::create([
                        'jo_id' => $request->item_id,
                        'task_id' => $task->col8,
                        'task_name' => $task->col1,
                        'task_description' => $task->col2,
                        'hours' => !empty($task->col3) ? str_replace(',', '', $task->col3) : 0.0,
                        'hourly_rate' => !empty($task->col5) ? str_replace(',', '', $task->col5) : 0.0,
                        'actual_hours' => !empty($task->col4) ? str_replace(',', '', $task->col4) : 0.0,
                        'labor_cost' => !empty($task->col6) ? str_replace(',', '', $task->col6) : 0.0,
                        'total' => !empty($task->col7) ? str_replace(',', '', $task->col7) : 0.0,
                    ]);
                }else{

                    $res_up = JobOrderTask::findOrFail($task->col9);
                    $up_data = [
                        'task_id' => $task->col8,
                        'task_name' => $task->col1,
                        'task_description' => $task->col2,
                        'hours' => !empty($task->col3) ? str_replace(',', '', $task->col3) : 0.0,
                        'hourly_rate' => !empty($task->col5) ? str_replace(',', '', $task->col5) : 0.0,
                        'actual_hours' => !empty($task->col4) ? str_replace(',', '', $task->col4) : 0.0,
                        'labor_cost' => !empty($task->col6) ? str_replace(',', '', $task->col6) : 0.0,
                        'total' => !empty($task->col7) ? str_replace(',', '', $task->col7) : 0.0,
                    ];
                    $res_up->update($up_data);
                }
            }

            /** Start: Item insert area **/
            $grid_items = json_decode($request->grid_item_rows);
            $delete_grid_items = json_decode($request->delete_grid_item_rows);

            foreach ($grid_items AS $task){
                $unit_price = str_replace(',', '', $task->item_grid_price);
                $tax_val = str_replace(',', '', $task->tax_val);
                $item_qty = str_replace(',', '', $task->item_qty);

                if($tax_val > 0) $unit_price += ($tax_val/$item_qty);

                if(empty($task->db_item_id)) {
                    $dataGrid = [
                        'jo_header_id' => $request->item_id,
                        'product_id' => $task->product_id,
                        'product_code' => $task->item_code,
                        'product_name' => $task->item_name,
                        'product_type' => $task->item_type,
                        'item_option_id' => $task->item_option,//row.option
                        'net_unit_price' => str_replace(',', '', $task->item_grid_price),//item_price,
                        'unit_price' => $unit_price,
                        'quantity' => $item_qty, // row.qty
                        'warehouse_id' => 1,//default warehouse id
                        'item_tax' => $tax_val,
                        'tax_rate_id' => !$task->tax ? NULL : $task->tax->id,
                        'tax' => str_replace(',', '', $task->tax_rate),//tax_rate
                        'discount' => str_replace(',', '', $task->item_ds),
                        'item_discount' => str_replace(',', '', $task->item_discount),
                        'subtotal' => str_replace(',', '', $task->item_grid_sub_tot),
                        'real_unit_price' => str_replace(',', '', $task->real_unit_price),
                        'sale_item_id' => NULL,
                        'product_unit_id' => $task->product_unit,
                        'product_unit_code' => NULL,
                        'unit_quantity' => str_replace(',', '', $task->base_quantity),
                        'comment' => NULL,
                    ];
                    JobOrderItem::create($dataGrid);
                }else{
                    $jb_it_data = JobOrderItem::findOrFail($task->db_item_id);
                    $dataGridUp = [
                        'product_id' => $task->product_id,
                        'product_code' => $task->item_code,
                        'product_name' => $task->item_name,
                        'product_type' => $task->item_type,
                        'item_option_id' => $task->item_option,//row.option
                        'net_unit_price' => str_replace(',', '', $task->item_grid_price),//item_price,
                        'unit_price' => $unit_price,
                        'quantity' => $item_qty, // row.qty
                        'warehouse_id' => 1,//default warehouse id
                        'item_tax' => $tax_val,
                        'tax_rate_id' => !$task->tax ? NULL : $task->tax->id,
                        'tax' => str_replace(',', '', $task->tax_rate),//tax_rate
                        'discount' => str_replace(',', '', $task->item_ds),
                        'item_discount' => str_replace(',', '', $task->item_discount),
                        'subtotal' => str_replace(',', '', $task->item_grid_sub_tot),
                        'real_unit_price' => str_replace(',', '', $task->real_unit_price),
                        'sale_item_id' => NULL,
                        'product_unit_id' => $task->product_unit,
                        'product_unit_code' => NULL,
                        'unit_quantity' => str_replace(',', '', $task->base_quantity),
                        'comment' => NULL,
                    ];

                    $jb_it_data->update($dataGridUp);
                }
            }

            foreach ($delete_grid_items AS $dTask){
                JobOrderItem::where('id',$dTask)->delete();
            }

            DB::commit();
            return $this->responseDataJson(true,'Job order reviewed successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("JoborderReviewError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function destroy(Request $request){
        $employee = JobOrder::findOrFail($request->emp_id);

        $active_state = 1;
        if($employee['is_active']) $active_state = 0;

        $dataArray = ['is_active' =>$active_state];

        $resp = $employee->update($dataArray);

        if($active_state)
            $message = "Joborder has been activated!";
        else
            $message = "Joborder has been inactivated!";

        if($resp){

            return $this->responseDataJson(true, $message,200);
        }else{

            return $this->responseDataJson(true, "Something went wrong!",401);
        }
    }

    public function tBCostApprove(){

        return view('job_order/joborder_unapprove_cost_list');
    }

    public function getTBCostApproveDataTableList(Request $request){
        if ($request->ajax()) {

            $jb_order_tbl = (new JobOrder())->getTable();
            $wo_tbl = (new WorkOrder())->getTable();
            $cus_tbl = (new CustomerSMA())->getTable();
            $vehi_tbl = (new Vehicle())->getTable();

            $data = JobOrder::query()
                ->select($jb_order_tbl.'.*',DB::raw($wo_tbl.'.serial_no AS woSerialNo'), DB::raw($vehi_tbl.'.engine_no AS vinNo'), $cus_tbl.'.name')
                ->leftJoin($wo_tbl,$wo_tbl.'.id','=',$jb_order_tbl.'.wo_order_id')
                ->leftJoin($cus_tbl,$cus_tbl.'.id','=',$wo_tbl.'.customer_id')
                ->leftJoin($vehi_tbl,$vehi_tbl.'.id','=',$wo_tbl.'.vehicle_id')
                ->where($jb_order_tbl.'.is_active',1)
                ->where($wo_tbl.'.is_active',1)
                ->where($jb_order_tbl.'.is_estimate',0)
                ->where($jb_order_tbl.'.is_approve_cost',0);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->addIndexColumn()
                    ->addColumn('taskStatus', function ($row){
                        if($row->is_approve_cost == 0)
                            $state = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Workorder">Pending</span>';// Just created JO
                        else
                            $state = '<span class="text-white badge badge-success">-</span>';// cost approved
                        return $state;
                    })
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
                        if(auth()->user()->hasPermissionTo('jo-cost-approve') )
                            $actionBtn .= '<span onclick="jobOrderList.ivm.reviewJoborder(' . $row->id . ')" class="btn btn-info btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Review job order before approve."><i class="fas fa-eye"></i></span>';
                        if(auth()->user()->hasPermissionTo('jo-cost-approve') )
                            $actionBtn .= '<span onclick="jobOrderList.ivm.approveTask(' . $row->id . ')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Approve Workorder Task"><i class="fas fa-check"></i></span>';

                        $actionBtn .= '<a target="_blank" href="'.route('joborder.generate.pdf', ['job_id' => $row->id]).'" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="top" title="Generate pdf to print"><i class="fas fa-file-pdf text-danger"></i></a>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action', 'taskStatus'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]); })
                    ->filterColumn('woSerialNo', function ($query, $keyword) { $query->whereRaw((new WorkOrder())->getTable().".serial_no like ?", ["%$keyword%"]); })
                    ->filterColumn('serial_no', function ($query, $keyword) { $query->whereRaw((new JobOrder())->getTable().".serial_no like ?", ["%$keyword%"]); })
                    ->filterColumn('vinNo', function ($query, $keyword) { $query->whereRaw((new Vehicle())->getTable().".engine_no like ?", ["%$keyword%"]); })
                    ->filterColumn('name', function ($query, $keyword) { $query->whereRaw((new CustomerSMA())->getTable().".name like ?", ["%$keyword%"]); })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getTBCostJobOrderError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function joborderApproveCost(Request $request){
        $employee = JobOrder::findOrFail($request->emp_id);
        DB::beginTransaction();
        try {
            $active_state = 1;
            if($employee['is_approve_cost']) $active_state = 0;
            $dataArray = ['is_approve_cost' =>$active_state, 'jo_status' => 1];
            $resp = $employee->update($dataArray);

            $jb_tsks = JobOrderTask::where('jo_id', $request->emp_id)->get();
            foreach ($jb_tsks AS $jo_tk) {

                $dataArray = ['is_approve_cost' => $active_state, 'jo_tsk_status' => 1];
                $resp = JobOrderTask::where('id',$jo_tk->id)->update($dataArray);
            }

            if($active_state)
                $message = "Joborder has been approved!";
            else
                $message = "Joborder has been disapproved!";

            $this->notifyServiceWriter('Job order Approved!', $employee['serial_no']." job order has been approved by ".auth()->user()->name);

            if($resp){
                DB::commit();
                return $this->responseDataJson(true, $message,200);
            }else{

                return $this->responseDataJson(true, "Something went wrong!",401);
            }

        }catch (\Exception $e){

            DB::rollBack();
            Log::error("JoborderCostApproveError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function joborderApproveWork(Request $request){
        $employee = JobOrder::findOrFail($request->emp_id);
        DB::beginTransaction();
        try {
            $active_state = 1;
            if($employee['is_approve_work']) $active_state = 0;
            $dataArray = ['is_approve_work' =>$active_state, 'jo_status' => 6];
            $resp = $employee->update($dataArray);

            $jb_tsks = JobOrderTask::where('jo_id', $request->emp_id)->get();
            $jb_serv = JobOrderServPackage::where('jo_id', $request->emp_id)->get();
            foreach ($jb_tsks AS $jo_tk) {

                $dataArray = ['is_approve_work' => $active_state, 'jo_tsk_status' => 6];
                $resp = JobOrderTask::where('id',$jo_tk->id)->update($dataArray);
            }

            foreach ($jb_serv AS $jo_tk) {

                $dataArray = ['is_approve_work' => $active_state, 'jo_serv_pkg_status' => 6];
                $resp = JobOrderServPackage::where('id',$jo_tk->id)->update($dataArray);
            }
            if($active_state)
                $message = "Joborder has been approved!";
            else
                $message = "Joborder has been disapproved!";

            if($resp){
                DB::commit();
                return $this->responseDataJson(true, $message,200);
            }else{

                return $this->responseDataJson(true, "Something went wrong!",401);
            }

        }catch (\Exception $e){

            DB::rollBack();
            Log::error("JoborderWorkApproveError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }


    /** Start: use for joborder task wise to be approve and assign **/
    public function tBTaskCostApprove(){

        return view('job_order/joborder_task_unapprove_cost_list');
    }
    public function getTBTaskCostApproveDataTableList(Request $request){
        if ($request->ajax()) {

            $jb_order_tbl = (new JobOrder())->getTable();
            $jb_order_tsk_tbl = (new JobOrderTask())->getTable();
            $wo_tbl = (new WorkOrder())->getTable();

            $data = JobOrderTask::query()
                ->select($jb_order_tsk_tbl.'.*', $jb_order_tbl.'.serial_no', DB::raw($wo_tbl.'.serial_no AS woSerialNo'))
                ->join($jb_order_tbl, $jb_order_tbl.'.id','=',$jb_order_tsk_tbl.'.jo_id')
                ->leftJoin($wo_tbl,$wo_tbl.'.id','=',$jb_order_tbl.'.wo_order_id')
                ->where($jb_order_tbl.'.is_active',1)
                ->where($jb_order_tsk_tbl.'.is_estimate',0)
                ->where($jb_order_tsk_tbl.'.is_approve_cost',0);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->editColumn('hourly_rate', function ($user) {
                        return number_format($user->hourly_rate,2);
                    })
                    ->editColumn('total', function ($user) {
                        return number_format($user->total,2);
                    })
                    ->addIndexColumn()
                    ->addColumn('taskStatus', function ($row){
                        if($row->is_approve_cost == 0)
                            $state = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Workorder">Pending</span>';// Just created JO
                        else
                            $state = '<span class="text-white badge badge-success">-</span>';// cost approved
                        return $state;
                    })
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
                        if(auth()->user()->hasPermissionTo('jo-cost-approve') )
                            $actionBtn .= '<span onclick="jobOrderList.ivm.reviewTask(' . $row->id . ')" class="btn btn-info btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Review task before approve."><i class="fas fa-eye"></i></span>';
                        if(auth()->user()->hasPermissionTo('jo-cost-approve') )
                            $actionBtn .= '<span onclick="jobOrderList.ivm.approveTask(' . $row->id . ')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="Approve Workorder Task"><i class="fas fa-check"></i></span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action', 'taskStatus'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('woSerialNo', function ($query, $keyword) {
                        $query->whereRaw((new WorkOrder())->getTable().".serial_no like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('serial_no', function ($query, $keyword) {
                        $query->whereRaw((new JobOrder())->getTable().".serial_no like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getTBCostJobOrderError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }
    public function joborderTaskApproveCost(Request $request){
        $employee = JobOrderTask::findOrFail($request->emp_id);

        $active_state = 1;
        if ($employee['is_approve_cost']) $active_state = 0;

        $dataArray = ['is_approve_cost' => $active_state, 'jo_tsk_status' => 1];

        $resp = $employee->update($dataArray);

        if ($active_state)
            $message = "Joborder task has been approved!";
        else
            $message = "Joborder task has been disapproved!";

        if ($resp) {

            return $this->responseDataJson(true, $message, 200);
        } else {

            return $this->responseDataJson(true, "Something went wrong!", 401);
        }

    }
    public function joborderTaskEditData(Request $request){

        $jb_tsk_data = JobOrderTask::where('id', $request->emp_id)->first();
        $jo_id = $jb_tsk_data->jo_id ?? '';

        $jb_order_tbl = (new JobOrder())->getTable();
        $wo_tbl = (new WorkOrder())->getTable();

        $jo_tsk_tbl = (new JobOrderTask())->getTable();
        $jo_tsk_tech_tbl = (new JobOrderTaskTechnician())->getTable();
        $tech_tbl = (new Employee())->getTable();

        $jb_h_data = JobOrder::query()
            ->select($jb_order_tbl.'.*', DB::raw($wo_tbl.'.serial_no AS woSerialNo'))
            ->leftJoin($wo_tbl,$wo_tbl.'.id','=',$jb_order_tbl.'.wo_order_id')
            ->where($jb_order_tbl.'.id', $jo_id)->first();
        $jb_tsk_tech_data = JobOrderTaskTechnician::query()
            ->select($jo_tsk_tech_tbl.'.*', $tech_tbl.'.full_name')
            ->leftJoin($tech_tbl, $jo_tsk_tech_tbl.'.tech_id', '=', $tech_tbl.'.id')
            ->where($jo_tsk_tech_tbl.'.jo_task_id', $request->emp_id)->get();

        $jb_task_data = JobOrderTask::where('jo_id', $jo_id)->get();

        $data_array = [
            'head' => $jb_h_data,
            'tsks' => $jb_tsk_data,
            'task_data' => ($jb_task_data),
            'tsk_tech' => $jb_tsk_tech_data,
        ];
        return $this->responseSelectedDataJson(true, $data_array, 200);
    }
    public function joborderTaskEdit(Request $request){
        $employee = JobOrderTask::findOrFail($request->emp_id);

        $dataArray = [
            'task_name' => $request->task_name,
            'task_description' => $request->task_description,
            'hours' => str_replace(',','',$request->hours),
            'hourly_rate' => str_replace(',','',$request->hourly_rate),
            'actual_hours' => str_replace(',','',$request->actual_hours),
            'labor_cost' => str_replace(',','',$request->labor_cost),
            'total' => str_replace(',','',$request->total),
        ];

        $resp = $employee->update($dataArray);

        $message = "Joborder task updated successfully!";

        if($resp){

            return $this->responseDataJson(true, $message,200);
        }else{

            return $this->responseDataJson(true, "Something went wrong!",401);
        }
    }
    /** End: use for joborder task wise to be approve and assign **/


    public function jobOrderApproved(){

        return view('job_order/joborder_approved_list');
    }

    public function getApprovedDataTableList(Request $request){
        if ($request->ajax()) {

            $jb_order_tbl = (new JobOrder())->getTable();
            $wo_tbl = (new WorkOrder())->getTable();
            $cus_tbl = (new CustomerSMA())->getTable();
            $vehi_tbl = (new Vehicle())->getTable();

            $data = JobOrder::query()
                ->select($jb_order_tbl.'.*',DB::raw($wo_tbl.'.serial_no AS woSerialNo'), DB::raw($vehi_tbl.'.engine_no AS vinNo'), $cus_tbl.'.name')
                ->leftJoin($wo_tbl,$wo_tbl.'.id','=',$jb_order_tbl.'.wo_order_id')
                ->leftJoin($cus_tbl,$cus_tbl.'.id','=',$wo_tbl.'.customer_id')
                ->leftJoin($vehi_tbl,$vehi_tbl.'.id','=',$wo_tbl.'.vehicle_id')
                ->where($wo_tbl.'.is_active',1)
                ->where($jb_order_tbl.'.is_active',1)
                ->where($jb_order_tbl.'.is_estimate',0)
                ->where($jb_order_tbl.'.is_approve_cost',1);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->addIndexColumn()
                    ->addColumn('taskStatus', function ($row){
                        if($row->jo_status == 0)
                            $state = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Pending joborder">Pending</span>';// Just created JO
                        elseif($row->jo_status == 1)
                            $state = '<span class="text-black badge badge-primary" data-toggle="tooltip" data-placement="top" title="Cost approved by service manager">APRVDCST</span>';// Approved WO cost before work commencement
                        elseif($row->jo_status == 2)
                            $state = '<span class="text-black badge badge-info" data-toggle="tooltip" data-placement="top" title="Assigned to labor">ASSIGNED</span>';// waiting in the labor assigned task queue to proceed in specific labor list
                        elseif($row->jo_status == 3)
                            $state = '<span class="text-black badge badge-warning" data-toggle="tooltip" data-placement="top" title="Work in progress by technician">WIP</span>';// Task is in work in progress
                        elseif($row->jo_status == 4)
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Work is completed partially by technician (s)">WRKPCTD</span>';// task is done by the technicians partially and pending work approve
                        elseif($row->jo_status == 5)
                            $state = '<span class="text-black badge badge-light badge-success" data-toggle="tooltip" data-placement="top" title="All task are completed by technician (s)">WRKCTD</span>';// Job order all task are done by the technicians and pending work approve
                        elseif($row->jo_status == 6)
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="All task are approved and sent to cashier">APRVD</span>';// approved task after completing the work
                        /*elseif($row->jo_status == 7)
                            $state = '<span class="text-black badge badge-light badge-success" data-toggle="tooltip" data-placement="top" title="Task sent to cashier">STC</span>';// after completing all tasks sent to cashier*/
                        elseif($row->jo_status == 7)
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Job order has been invoiced">Invoiced</span>';// Paid the tot amount and completed
                        else
                            $state = '<span class="text-white badge badge-secondary">-</span>';// Task has no state
                        return $state;
                    })
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
                        if(auth()->user()->hasPermissionTo('jo-cost-approve') && $row->jo_status <= 6)
                            $actionBtn .= '<span onclick="jobOrderList.ivm.showJoborderFullView(' . $row->id . ')" class="btn btn-info btn-sm mr-1" data-toggle="tooltip" data-placement="right" title="Assigne task technicians and view."><i class="fas fa-eye"></i></span>';

                        if($row->jo_status == 5)
                            $actionBtn .= '<span onclick="jobOrderList.ivm.approveJobOrderWork(' . $row->id . ')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="right" title="Approve all tasks done by technicians."><i class="fas fa-check"></i></span>';
                        return $actionBtn;
                    })
                    ->rawColumns(['action', 'taskStatus'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]); })
                    ->filterColumn('woSerialNo', function ($query, $keyword) { $query->whereRaw((new WorkOrder())->getTable().".serial_no like ?", ["%$keyword%"]); })
                    ->filterColumn('serial_no', function ($query, $keyword) { $query->whereRaw((new JobOrder())->getTable().".serial_no like ?", ["%$keyword%"]); })
                    ->filterColumn('vinNo', function ($query, $keyword) { $query->whereRaw((new Vehicle())->getTable().".engine_no like ?", ["%$keyword%"]); })
                    ->filterColumn('name', function ($query, $keyword) { $query->whereRaw((new CustomerSMA())->getTable().".name like ?", ["%$keyword%"]); })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getApprovedJobOrderError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    /** Start: use for joborder task wise approve and assign **/
    public function jobOrderTaskApproved(){

        return view('job_order/joborder_task_approved_list');
    }
    public function getApprovedTaskDataTableList(Request $request){
        if ($request->ajax()) {

            $jb_order_tbl = (new JobOrder())->getTable();
            $jb_order_tsk_tbl = (new JobOrderTask())->getTable();
            $wo_tbl = (new WorkOrder())->getTable();

            $data = JobOrderTask::query()
                ->select($jb_order_tsk_tbl.'.*', $jb_order_tbl.'.serial_no', DB::raw($wo_tbl.'.serial_no AS woSerialNo'))
                ->join($jb_order_tbl, $jb_order_tbl.'.id','=',$jb_order_tsk_tbl.'.jo_id')
                ->leftJoin($wo_tbl,$wo_tbl.'.id','=',$jb_order_tbl.'.wo_order_id')
                ->where($jb_order_tbl.'.is_active',1)
                ->where($jb_order_tsk_tbl.'.is_approve_cost',1);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->editColumn('hourly_rate', function ($user) {
                        return number_format($user->hourly_rate,2);
                    })
                    ->editColumn('total', function ($user) {
                        return number_format($user->total,2);
                    })
                    ->addIndexColumn()
                    ->addColumn('taskStatus', function ($row){
                        if($row->jo_tsk_status == 0)
                            $state = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Pending task">Pending</span>';// Just created JO
                        elseif($row->jo_tsk_status == 1)
                            $state = '<span class="text-black badge badge-primary" data-toggle="tooltip" data-placement="top" title="Cost approved by service manager">APRVDCST</span>';// Approved WO cost before work commencement
                        elseif($row->jo_tsk_status == 2)
                            $state = '<span class="text-black badge badge-info" data-toggle="tooltip" data-placement="top" title="Assigned to labor">ASSIGNED</span>';// waiting in the labor assigned task queue to proceed in specific labor list
                        elseif($row->jo_tsk_status == 3)
                            $state = '<span class="text-black badge badge-warning" data-toggle="tooltip" data-placement="top" title="Work in progress">WIP</span>';// Task is in work in progress
                        elseif($row->jo_tsk_status == 4)
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Work is completed by technician (s)">WRKCTD</span>';// task is done by the technician and pending work approve
                        elseif($row->jo_tsk_status == 5)
                            $state = '<span class="text-black badge badge-light badge-success" data-toggle="tooltip" data-placement="top" title="Work is done and approved">APRVD</span>';// approved task after completing the work
                        elseif($row->jo_tsk_status == 6)
                            $state = '<span class="text-black badge badge-light badge-success" data-toggle="tooltip" data-placement="top" title="Task sent to cashier">STC</span>';// after completing all tasks sent to cashier
                        elseif($row->jo_tsk_status == 7)
                            $state = '<span class="text-black badge badge-light badge-success" data-toggle="tooltip" data-placement="top" title="Task paid">PAID</span>';// Paid the tot amount and completed
                        else
                            $state = '<span class="text-white badge badge-secondary">-</span>';// Task has no state
                        return $state;
                    })
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
                        if(auth()->user()->hasPermissionTo('jo-cost-approve') )
                            $actionBtn .= '<span onclick="jobOrderList.ivm.showTaskFullView(' . $row->id . ')" class="btn btn-info btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Assigne task technicians and view."><i class="fas fa-eye"></i></span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action', 'taskStatus'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('woSerialNo', function ($query, $keyword) {
                        $query->whereRaw((new WorkOrder())->getTable().".serial_no like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('serial_no', function ($query, $keyword) {
                        $query->whereRaw((new JobOrder())->getTable().".serial_no like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getApprovedJobOrderError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }
    /** End: use for joborder task wise approve and assign **/

    public function getTechnicianListForDrop(Request $request): array
    {
        try {

            $user_role_tbl = "model_has_roles";
            $user_tbl = (new User())->getTable();
            $emp_tbl = (new Employee())->getTable();
            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = Employee::query()
                ->select($emp_tbl.'.*')
                ->leftJoin($user_tbl, $user_tbl.'.emp_id', '=', $emp_tbl.'.id')
                ->leftJoin($user_role_tbl, $user_role_tbl.'.model_id', $user_tbl.'.id')
                ->where('role_id', self::getDefaultValues()['technician_role_id'])
                ->where($emp_tbl.'.is_active',1)
                ->where($emp_tbl.'.full_name', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();

            $item_list_count = Employee::query()
                ->select($emp_tbl.'.*')
                ->leftJoin($user_tbl, $user_tbl.'.emp_id', '=', $emp_tbl.'.id')
                ->leftJoin($user_role_tbl, $user_role_tbl.'.model_id', $user_tbl.'.id')
                ->where('role_id', self::getDefaultValues()['technician_role_id'])
                ->where($emp_tbl.'.is_active',1)
                ->where($emp_tbl.'.full_name', 'LIKE', '%' . $search_term . '%')->get();

            $count = $item_list_count->count();
            $endCount = $offset + $res_count;
            $morePages = $endCount < $count;

            $data = array();
            foreach ($item_list as $row) {
                $data[] = array("id" => $row->id, "text" => $row->full_name);
            }

            return array(
                "results" => $data,
                "pagination" => array("more" => $morePages)
            );

        }catch (\Exception $e){
            Log::error("select2employee: ".$e->getMessage());
        }
    }

    public function getTechnicianByIdForDrop(Request $request){

        $state_data = WorkOrder::where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->full_name);
        }

        return json_encode($data);
    }

    public function submitTaskTechnicians(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'jo_id.required' => 'The Job order id field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'jo_id'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $jb_id = $request->jo_id;
            $grid_serv_pkg = json_decode($request->serv_pkg_tech_list);
            $grid_serv_pkg_delete = json_decode($request->serv_pkg_tech_delete_list);
            $joborder_data = JobOrder::findOrFail($jb_id);

            if(!empty($grid_serv_pkg_delete)) {
                foreach ($grid_serv_pkg_delete as $task_tech_del) {
                    JobOrderServPkgTechnician::where('id', $task_tech_del)->delete();
                }
            }

            /** Start:service package technician adding*/
            foreach ($grid_serv_pkg AS $task_tech){
                if(empty($task_tech->col3)) {
                    $jb_task_id = $task_tech->col4;
                    $jb_tsk_tech = JobOrderServPkgTechnician::where('jo_serv_pkg_id', $jb_task_id)->where('tech_id', $task_tech->col2)->get();
                    if ($jb_tsk_tech->count() < 1) {
                        JobOrderServPkgTechnician::create([
                            'jo_id' => $jb_id,
                            'jo_serv_pkg_id' => $jb_task_id,
                            'tech_id' => $task_tech->col2,
                        ]);

                        $usr_data = User::where('emp_id',$task_tech->col2)->first();
                        $this->notifyTechnician($usr_data['id'],'Job order service Received!', $joborder_data['serial_no'].' job order service assigned to you.');
                    }

                    $tsk_tech_list = JobOrderServPkgTechnician::where('jo_serv_pkg_id', $jb_task_id)->get();
                    if ($tsk_tech_list->count() >= 1)
                        $dataArray = ['is_approve_work' => 0,'jo_tsk_status' => 2, 'is_ended' => 0, 'is_finished' => 0];
                    else
                        $dataArray = ['is_approve_work' => 0,'jo_tsk_status' => 1, 'is_ended' => 0, 'is_finished' => 0];

                    $employee = JobOrderServPackage::findOrFail($jb_task_id);
                    $employee->update($dataArray);
                }
            }
            /** End:service package technician adding*/

            $grid_tasks = json_decode($request->task_tech_list);
            $grid_tasks_delete = json_decode($request->task_tech_delete_list);

            if(!empty($grid_tasks_delete)) {
                foreach ($grid_tasks_delete as $task_tech_del) {
                    JobOrderTaskTechnician::where('id', $task_tech_del)->delete();
                }
                /** tried to auto update all task finish state if other tasks are already finished */
                /*$jb_t_is_finished = true;
                $jb_t_data = JobOrderTask::where('jo_id', $jb_id)->get();

                foreach ($jb_t_data as $jb_t){
                    Log::info("tsk_id_: ".$jb_t->id);
                    Log::info("tsk_name: ".$jb_t->task_name);
                    Log::info("tsk_is_finished: ".$jb_t->is_finished."\n");
                    if(!$jb_t->is_finished) {$jb_t_is_finished = false; break;}
                }
                Log::info("finish_state: ".$jb_t_is_finished);
                if($jb_t_is_finished){
                    $dataArrayUp = ['jo_status' => 5];
                    $joborder = JobOrder::findOrFail($jb_id);
                    $joborder->update($dataArrayUp);
                }*/
            }

            foreach ($grid_tasks AS $task_tech){
                if(empty($task_tech->col3)) {
                    $jb_task_id = $task_tech->col4;
                    $jb_tsk_tech = JobOrderTaskTechnician::where('jo_task_id', $jb_task_id)->where('tech_id', $task_tech->col2)->get();
                    if ($jb_tsk_tech->count() < 1) {
                        JobOrderTaskTechnician::create([
                            'jo_id' => $jb_id,
                            'jo_task_id' => $jb_task_id,
                            'tech_id' => $task_tech->col2,
                        ]);

                        $usr_data = User::where('emp_id',$task_tech->col2)->first();
                        $this->notifyTechnician($usr_data['id'],'Job order task Received!', $joborder_data['serial_no'].' job order task assigned to you.');
                    }

                    $tsk_tech_list = JobOrderTaskTechnician::where('jo_task_id', $jb_task_id)->get();
                    if ($tsk_tech_list->count() >= 1)
                        $dataArray = ['is_approve_work' => 0,'jo_tsk_status' => 2, 'is_ended' => 0, 'is_finished' => 0];
                    else
                        $dataArray = ['is_approve_work' => 0,'jo_tsk_status' => 1, 'is_ended' => 0, 'is_finished' => 0];

                    $employee = JobOrderTask::findOrFail($jb_task_id);
                    $employee->update($dataArray);
                }
            }

            $tsk_tech_list = JobOrderTaskTechnician::where('jo_id', $jb_id)->get();
            $srv_pkg_tech_list = JobOrderServPkgTechnician::where('jo_id', $jb_id)->get();
            if($tsk_tech_list->count() >= 1 || $srv_pkg_tech_list->count() >= 1)
                $dataArrayJb = ['jo_status' => 2];
            else
                $dataArrayJb = ['jo_status' => 1];

            $jb_state = JobOrder::findOrFail($jb_id);
            $jb_state->update($dataArrayJb);

            DB::commit();
            return $this->responseDataJson(true,'Technicians assigned successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("TechAssignedError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
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

        $rows           = ProductSMA::getProductNames($sr, $warehouse_id, $pos);

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
                $options              = ProductSMA::getProductOptions($row->id, $warehouse_id);
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
                if (ProductSMA::isPromo($row)) {
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
                    $combo_items = ProductSMA::getProductComboItems($row->id, $warehouse_id);
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

    public function getTaxRateByID($id){
        $Q2 = DB::table('sma_tax_rates')->where('id', $id)->get();
        if($Q2->count() > 0){
            return $Q2[0];
        }
        return false;
    }

    public function getSelectedTaxRateData(Request $request)
    {
        $resp = DB::table('sma_tax_rates')->where('id', $request->ItemId)->get();

        return $this->responseSelectedDataJson(true, $resp, 200);
    }

    public function getSelectedProductVariantData(Request $request)
    {
        $resp = ProductVariantSMA::where('id', $request->ItemId)->get();

        return $this->responseSelectedDataJson(true, $resp, 200);
    }

    public function getSelectedProductData(Request $request): \Illuminate\Http\JsonResponse
    {
        $resp = ProductSMA::where('id', $request->ItemId)->get();

        return $this->responseSelectedDataJson(true, $resp, 200);
    }

    public function getAllTaxRateForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = DB::table('sma_tax_rates')->where('name', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = DB::table('sma_tax_rates')->where('name', 'LIKE', '%' . $search_term . '%')->get();

            $count = $item_list_count->count();
            $endCount = $offset + $res_count;
            $morePages = $endCount < $count;

            $data = array();
            foreach ($item_list as $row) {
                $data[] = array("id" => $row->id, "text" => $row->name);
            }

            return array(
                "results" => $data,
                "pagination" => array("more" => $morePages)
            );

        }catch (\Exception $e){
            Log::error("select2AllTaxRates: ".$e->getMessage());
        }
    }

    public function getAllTaxRateByIdForDrop(Request $request){

        $state_data = DB::table('sma_tax_rates')->where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->name);
        }

        return json_encode($data);
    }

    public function sendToRework(Request $request){

        $work_type = $request->com_type;

        if($work_type == 'tsk') {
            $employee = JobOrderTask::findOrFail($request->jo_tsk_serv_id);
            $active_state = 1;
            if ($employee['is_approve_work']) $active_state = 0;

            $dataArray = ['is_approve_work' => $active_state, 'jo_tsk_status' => 2];

            $resp = $employee->update($dataArray);
        }else{
            $employee = JobOrderServPackage::findOrFail($request->jo_tsk_serv_id);
            $active_state = 1;
            if ($employee['is_approve_work']) $active_state = 0;

            $dataArray = ['is_approve_work' => $active_state, 'jo_serv_pkg_status' => 2];

            $resp = $employee->update($dataArray);
        }
        if ($active_state)
            $message = "Joborder rework has been sent!";
        else
            $message = "Joborder rework has been sent!";

        if ($resp) {

            return $this->responseDataJson(true, $message, 200);
        } else {

            return $this->responseDataJson(true, "Something went wrong!", 401);
        }
    }

    public function exportToPDF($job_id){

        $selected_biller = SettingSMA::getSettingSma()->default_biller;
        $biller_data = CustomerSMA::getCompanyByID($selected_biller);
        $data['biller_data'] = $biller_data;

        $jb_order_tbl = (new JobOrder())->getTable();
        $wo_tbl = (new WorkOrder())->getTable();
        $cus_tbl = (new CustomerSMA())->getTable();
        $vehi_tbl = (new Vehicle())->getTable();
        $vehi_type_tbl = (new VehicleType())->getTable();
        $jb_order_serv_pkg_tbl = (new JobOrderServPackage())->getTable();
        $serv_pkg_tbl = (new ServicePackage())->getTable();
        $jo_serv_pkg_tech_tbl = (new JobOrderServPkgTechnician())->getTable();

        $data['jobData']['headData'] = JobOrder::query()
            ->select(
                $jb_order_tbl.'.*',
                DB::raw($wo_tbl.'.serial_no AS woSerialNo'), $wo_tbl.'.reported_defect', $wo_tbl.'.appointment_id', $wo_tbl.'.mileage',
                $cus_tbl.'.name', $cus_tbl.'.address',$cus_tbl.'.phone',
                $vehi_tbl.'.vehicle_no', $vehi_tbl.'.vehicle_make', $vehi_tbl.'.vehicle_model', $vehi_tbl.'.chassis_no', $vehi_tbl.'.engine_no',
                $vehi_type_tbl.'.type_name'
            )
            ->leftJoin($wo_tbl,$wo_tbl.'.id','=',$jb_order_tbl.'.wo_order_id')
            ->leftJoin($cus_tbl,$cus_tbl.'.id','=',$wo_tbl.'.customer_id')
            ->leftJoin($vehi_tbl,$vehi_tbl.'.id','=',$wo_tbl.'.vehicle_id')
            ->leftJoin($vehi_type_tbl,$vehi_type_tbl.'.id','=',$vehi_tbl.'.vehicle_type_id')
            ->where($jb_order_tbl.'.id', $job_id)->get();

        $data['jobData']['serviceData'] = JobOrderServPackage::query()
            ->select($jb_order_serv_pkg_tbl.'.*', $serv_pkg_tbl.'.service_name', $serv_pkg_tbl.'.description')
            ->leftJoin($jb_order_tbl, $jb_order_serv_pkg_tbl.'.jo_id','=',$jb_order_tbl.'.id')
            ->leftJoin($serv_pkg_tbl, $serv_pkg_tbl.'.id','=',$jb_order_serv_pkg_tbl.'.service_pkg_id')
            ->where($jb_order_tbl.'.id',$job_id)->get();

        $dompdf = PDF::loadView('pdf_temps/job_pdf', $data);
        $dompdf->render();

        $x          = 505;
        $y          = 790;
        $text       = "Page {PAGE_NUM} of {PAGE_COUNT}";
        $font       = $dompdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $size       = 10;
        $color      = array(0,0,0);
        $word_space = 0.0;
        $char_space = 0.0;
        $angle      = 0.0;

        $dompdf->getCanvas()->page_text(
            $x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle
        );

        $canvas = $dompdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->set_opacity(.2,"Multiply");

        $canvas->set_opacity(.2);
        $img_width = 596;
        $img_height = 200;
        //horizontal (X) position from top left
        $x = 0;
        $canvas->image(public_path('img/companyLogo.jpg'), $x, ($height/2)-80, $img_width, $img_height);

        /*$canvas->page_text($width/5, $height/2, asset('img/companyLogo.jpg'), null,
            55, array(0,0,0),2,2,-30);*/

        // download PDF file with download method
        return $dompdf->stream("CustomerJob-".time().".pdf");
    }
}
