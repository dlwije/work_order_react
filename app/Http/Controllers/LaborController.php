<?php

namespace App\Http\Controllers;

use App\Models\CustomerSMA;
use App\Models\Employee;
use App\Models\JobOrder;
use App\Models\JobOrderItem;
use App\Models\JobOrderServPackage;
use App\Models\JobOrderServPkgItem;
use App\Models\JobOrderServPkgTechnician;
use App\Models\JobOrderServPkgTechTime;
use App\Models\JobOrderTask;
use App\Models\JobOrderTaskItem;
use App\Models\JobOrderTaskTechnician;
use App\Models\JobOrderTaskTechTime;
use App\Models\LaborTask;
use App\Models\ProductSMA;
use App\Models\SaleSMA;
use App\Models\ServicePackage;
use App\Models\ServPkgTechComment;
use App\Models\TaskTechComment;
use App\Models\UnitSMA;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleContract;
use App\Models\VehicleInsurance;
use App\Models\VehicleType;
use App\Models\VehicleWarranty;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LaborController extends Controller
{
    protected $wo_tbl;
    protected $jb_order_tbl;
    protected $jb_order_tsk_tbl;
    protected $jo_tsk_tech_tbl;
    protected $lbr_tsk_tbl;
    protected $jb_order_serv_pkg_tbl;
    protected $serv_pkg_tbl;
    protected $jo_serv_pkg_tech_tbl;
    public function __construct()
    {
        $this->wo_tbl = (new WorkOrder())->getTable();
        $this->jb_order_tbl = (new JobOrder())->getTable();
        $this->jb_order_tsk_tbl = (new JobOrderTask())->getTable();
        $this->jo_tsk_tech_tbl = (new JobOrderTaskTechnician())->getTable();
        $this->lbr_tsk_tbl = (new LaborTask())->getTable();
        $this->jb_order_serv_pkg_tbl = (new JobOrderServPackage())->getTable();
        $this->serv_pkg_tbl = (new ServicePackage())->getTable();
        $this->jo_serv_pkg_tech_tbl = (new JobOrderServPkgTechnician())->getTable();

        $this->middleware('auth');
        $this->middleware('permission:labors-assign-list', ['only' =>['index','show', 'getDataTableList']]);
        $this->middleware('permission:labors-complete-list', ['only' =>['getCompleteTaskDataTableList']]);
//        $this->middleware('permission:laborstask-add', ['only' =>['create', 'store']]);
//        $this->middleware('permission:laborstask-delete', ['only' =>['destroy']]);
    }

    public function dashboard(){
        $logged_tech_id = auth()->user()->emp_id;
        $user_data = User::find(auth()->user()->id);

        $tsk_data = JobOrderTask::query()
            ->select($this->jb_order_tsk_tbl.'.*',$this->jb_order_tbl.'.serial_no', )
            ->leftJoin($this->jb_order_tbl, $this->jb_order_tsk_tbl.'.jo_id','=',$this->jb_order_tbl.'.id')
            ->leftJoin($this->jo_tsk_tech_tbl, $this->jb_order_tsk_tbl.'.id', '=', $this->jo_tsk_tech_tbl.'.jo_task_id')
            ->where($this->jb_order_tsk_tbl.'.is_finished',0)
            ->where($this->jb_order_tbl.'.is_active',1);
        if(!$user_data->hasRole('System Admin'))
            $tsk_data->where($this->jo_tsk_tech_tbl.'.tech_id', $logged_tech_id);

        $tsk_fini_data = JobOrderTask::query()
            ->select($this->jb_order_tsk_tbl.'.*',$this->jb_order_tbl.'.serial_no', )
            ->leftJoin($this->jb_order_tbl, $this->jb_order_tsk_tbl.'.jo_id','=',$this->jb_order_tbl.'.id')
            ->leftJoin($this->jo_tsk_tech_tbl, $this->jb_order_tsk_tbl.'.id', '=', $this->jo_tsk_tech_tbl.'.jo_task_id')
            ->where($this->jb_order_tsk_tbl.'.is_finished',1)
            ->where($this->jb_order_tbl.'.is_active',1);
        if(!$user_data->hasRole('System Admin'))
            $tsk_fini_data->where($this->jo_tsk_tech_tbl.'.tech_id', $logged_tech_id);

        return view('dashboard/dashboard_labor', ['pending_tsk_cnt'=>$tsk_data->count(), 'fini_tsk_cnt' => $tsk_fini_data->count()]);
    }
    public function index(){

        return view('labors_tasks/assigned_labor_task_list');
    }
    public function getDataTableList(Request $request){
        if ($request->ajax()) {

            $cus_tbl = (new CustomerSMA())->getTable();
            $vehi_tbl = (new Vehicle())->getTable();

            $logged_tech_id = auth()->user()->emp_id;
            $user_data = User::find(auth()->user()->id);

            $data = JobOrder::query()
                ->select($this->jb_order_tbl.'.*', DB::raw($this->wo_tbl.'.serial_no AS woSerialNo'), $cus_tbl.'.name', DB::raw($vehi_tbl.'.engine_no AS vinNo'))
                ->leftJoin($this->jo_serv_pkg_tech_tbl, $this->jo_serv_pkg_tech_tbl.'.jo_id', '=', $this->jb_order_tbl.'.id')
                ->leftJoin($this->wo_tbl,$this->wo_tbl.'.id','=',$this->jb_order_tbl.'.wo_order_id')
                ->leftjoin($cus_tbl, $cus_tbl.'.id', '=', $this->wo_tbl.'.customer_id')
                ->leftjoin($vehi_tbl, $vehi_tbl.'.id', '=', $this->wo_tbl.'.vehicle_id');

            if(!$user_data->hasRole('System Admin'))
                $data->where($this->jo_serv_pkg_tech_tbl.'.tech_id', $logged_tech_id);

                $data->where($this->jb_order_tbl.'.jo_status',2)
                    ->orWhere($this->jb_order_tbl.'.jo_status',3)
                    ->orWhere($this->jb_order_tbl.'.jo_status',4)
                    ->groupBy($this->jb_order_tbl.'.id')
                    ->where($this->jb_order_tbl.'.is_active',1);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
                        $actionBtn .= '<span onclick="laborsTaskList.ivm.showJoborderTasks(' . $row->id . ')" class="btn btn-info btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Show task assigned."><i class="fas fa-eye"></i></span>';
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]); })
                    ->filterColumn('woSerialNo', function ($query, $keyword) { $query->whereRaw((new WorkOrder())->getTable().".serial_no like ?", ["%$keyword%"]); })
                    ->filterColumn('name', function ($query, $keyword) { $query->whereRaw((new CustomerSMA())->getTable().".name like ?", ["%$keyword%"]); })
                    ->filterColumn('vinNo', function ($query, $keyword) { $query->whereRaw((new Vehicle())->getTable().".engine_no like ?", ["%$keyword%"]); })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getLaborsJoborderError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    //tech job order assigned task list to technician
    public function getTaskWiseDataTableList(Request $request, $joborder_id){
        if ($request->ajax()) {

            $logged_tech_id = auth()->user()->emp_id;
            $user_data = User::find(auth()->user()->id);

            $data = JobOrderTask::query()
                ->select($this->jb_order_tsk_tbl.'.*',$this->jb_order_tbl.'.serial_no', $this->lbr_tsk_tbl.'.task_code')
                ->leftJoin($this->jb_order_tbl, $this->jb_order_tsk_tbl.'.jo_id','=',$this->jb_order_tbl.'.id')
                ->leftJoin($this->jo_tsk_tech_tbl, $this->jb_order_tsk_tbl.'.id', '=', $this->jo_tsk_tech_tbl.'.jo_task_id')
                ->leftJoin($this->lbr_tsk_tbl, $this->lbr_tsk_tbl.'.id','=',$this->jb_order_tsk_tbl.'.task_id')
                ->where($this->jb_order_tsk_tbl.'.is_finished',0)
                ->where($this->jb_order_tbl.'.id',$joborder_id)
                ->where($this->jb_order_tbl.'.is_active',1);

                if(!$user_data->hasRole('System Admin'))
                    $data->where($this->jo_tsk_tech_tbl.'.tech_id', $logged_tech_id);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })->editColumn('normal_rate', function ($user) {
                        return number_format($user->normal_rate,2);
                    })
                    ->addIndexColumn()
                    ->addColumn('taskDescriptEdited', function ($row) {
                        $tsk_name = $row->task_name;
                        $tsk_name .='<button type="button" style="padding: 7px;" class="btn btn-primary fa-pull-right" data-toggle="tooltip" data-placement="top" title="Add task specific items from here." onclick="laborsTaskList.ivm.showItemAddingView('.$row->id.');">
                                        <i style="cursor: pointer;margin: 0;" class="fa-pull-right fas fa-plus"></i>
                                     </button>';
                        $type = "'task'";
                        $tsk_name .='<button type="button" style="padding: 7px;" class="btn btn-default fa-pull-right mr-1" data-toggle="tooltip" data-placement="top" title="Add your comments and feedbacks to this task from here." onclick="laborsTaskList.ivm.showTaskCommentView('.$row->id.', '.$type.');">
                                        <i style="cursor: pointer;margin: 0;" class="fa-pull-right fas fa-comment-alt"></i>
                                     </button>';

                        return $tsk_name;
                    })
                    ->addColumn('action', function ($row) {

                        $is_started = $is_ended = 0;
                        $task_techs = JobOrderTaskTechTime::where('jo_task_id', $row->id)->where('tech_id', auth()->user()->emp_id)->get();
                        foreach ($task_techs AS $tt){

                            $is_started = $tt->is_started; $is_ended = $tt->is_ended;
                        }

                        $type = "'task'";

                        $u_data = User::find(auth()->user()->id);
                        $actionBtn = '';
                        if(($is_started == 0 ) && !$u_data->hasRole('System Admin')) {
                            $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.startTimer(' . $row->id . ', '.$type.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to start the task"><i class="fas fa-clock"></i></button>';
                        }elseif($is_started == 1 && !$u_data->hasRole('System Admin')){
                            $actionBtn .= '<button type="button" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Task is in progress">Started</button>';
                        }else
                            $actionBtn .= '<button type="button" onclick="" class="btn btn-warning btn-sm mr-1" disabled><i class="fas fa-clock" data-toggle="tooltip" data-placement="top" title="Task start disabled"></i></button>';

                        if($is_ended == 0 && $is_started == 1 && !$u_data->hasRole('System Admin')) { // 0 && 1
                            $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.endTimer(' . $row->id . ', '.$type.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to stop the task"><i class="fas fa-stop"></i></button>';
                        }else
                            $actionBtn .= '<button type="button" onclick="" class="btn btn-warning btn-sm mr-1" disabled><i class="fas fa-stop" data-toggle="tooltip" data-placement="top" title="Task stop disabled"></i></button>';

                        if($is_ended && !$u_data->hasRole('System Admin')) {
                            $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.finishTimer(' . $row->id . ', '.$type.')" class="btn btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to finish the task"><i class="fas fa-check-square"></i></button>';
                        }else
                            $actionBtn .= '<button type="button" onclick="" class="btn btn-primary btn-sm mr-1" disabled><i class="fas fa-check-square" data-toggle="tooltip" data-placement="top" title="Finish disabled"></i></button>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action','taskDescriptEdited'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]); })
                    ->filterColumn('serial_no', function ($query, $keyword) { $query->whereRaw((new JobOrder())->getTable().".serial_no like ?", ["%$keyword%"]); })
                    ->filterColumn('task_code', function ($query, $keyword) { $query->whereRaw((new LaborTask())->getTable().".task_code like ?", ["%$keyword%"]); })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getLaborsTaskError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    //tech job order assigned service package list to technician
    public function getAssignedServPkgsList(Request $request, $joborder_id){

        $logged_tech_id = auth()->user()->emp_id;
        $user_data = User::find(auth()->user()->id);

        $data = JobOrderServPackage::query()
            ->select($this->jb_order_serv_pkg_tbl.'.*',$this->jb_order_tbl.'.serial_no', $this->serv_pkg_tbl.'.service_name', $this->serv_pkg_tbl.'.description')
            ->leftJoin($this->jb_order_tbl, $this->jb_order_serv_pkg_tbl.'.jo_id','=',$this->jb_order_tbl.'.id')
            ->leftJoin($this->jo_serv_pkg_tech_tbl, $this->jb_order_serv_pkg_tbl.'.id', '=', $this->jo_serv_pkg_tech_tbl.'.jo_serv_pkg_id')
            ->leftJoin($this->serv_pkg_tbl, $this->serv_pkg_tbl.'.id','=',$this->jb_order_serv_pkg_tbl.'.service_pkg_id')
            ->where($this->jb_order_serv_pkg_tbl.'.is_finished',0)
            ->where($this->jb_order_tbl.'.id',$joborder_id)
            ->where($this->jb_order_tbl.'.is_active',1);

        if(!$user_data->hasRole('System Admin'))
            $data->where($this->jo_serv_pkg_tech_tbl.'.tech_id', $logged_tech_id);

        try {
            return Datatables::of($data)
                ->editColumn('updated_at', function ($user) {
                    return $user->updated_at->format('Y-m-d');
                })->editColumn('normal_rate', function ($user) {
                    return number_format($user->normal_rate,2);
                })
                ->addIndexColumn()
                ->addColumn('taskDescriptEdited', function ($row) {
                    $tsk_name = $row->service_name;
                    if($row->is_approve_tech == 0) {
                        $tsk_name .= '<button type="button" style="padding: 7px;" class="btn btn-primary fa-pull-right" data-toggle="tooltip" data-placement="top" title="Accept and add service pkg to the inventory." onclick="laborsTaskList.ivm.approveServPkg(' . $row->id . ');">
                                        <i style="cursor: pointer;margin: 0;" class="fa-pull-right fas fa-check"></i>
                                     </button>';
                    }else{
                        $tsk_name .= '<button type="button" style="padding: 7px;" class="btn btn-primary fa-pull-right disabled" data-toggle="tooltip" data-placement="top" title="Service pkg has been accepted and added to the inventory." onclick="">
                                        <i style="cursor: pointer;margin: 0;" class="fa-pull-right fas fa-check"></i>
                                     </button>';
                    }
                    $tsk_name .='<button type="button" style="padding: 7px;" class="btn btn-primary fa-pull-right mr-1" data-toggle="tooltip" data-placement="top" title="Show service pkg items from here." onclick="laborsTaskList.ivm.showServPkgItemsView('.$row->service_pkg_id.');">
                                        <i style="cursor: pointer;margin: 0;" class="fa-pull-right fas fa-plus"></i>
                                     </button>';
                    $type = "'serv'";
                    $tsk_name .='<button type="button" style="padding: 7px;" class="btn btn-default fa-pull-right mr-1" data-toggle="tooltip" data-placement="top" title="Add your comments and feedbacks to this package from here." onclick="laborsTaskList.ivm.showTaskCommentView('.$row->id.','.$type.');">
                                        <i style="cursor: pointer;margin: 0;" class="fa-pull-right fas fa-comment-alt"></i>
                                     </button>';

                    return $tsk_name;
                })
                ->addColumn('action', function ($row) {

                    $is_started = $is_ended = 0;
                    $task_techs = JobOrderServPkgTechTime::where('jo_serv_pkg_id', $row->id)->where('tech_id', auth()->user()->emp_id)->get();
                    foreach ($task_techs AS $tt){

                        $is_started = $tt->is_started; $is_ended = $tt->is_ended;
                    }

                    $type = "'serv'";
                    $u_data = User::find(auth()->user()->id);
                    $actionBtn = '';
                    if(($is_started == 0 ) && !$u_data->hasRole('System Admin')) {
                        if($row->is_approve_tech == 1)
                            $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.startTimer(' . $row->id . ', '.$type.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to start the service"><i class="fas fa-clock"></i></button>';
                        else {
                            $actionBtn .= '<button type="button" class="btn btn-success btn-sm mr-1 disabled" data-toggle="tooltip" data-placement="top" title="Approve & accept to start the service"><i class="fas fa-clock"></i></button>';
                        }
                    }elseif($is_started == 1 && !$u_data->hasRole('System Admin')){
                        $actionBtn .= '<button type="button" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Task is in progress">Started</button>';
                    }else
                        $actionBtn .= '<button type="button" onclick="" class="btn btn-warning btn-sm mr-1" disabled><i class="fas fa-clock" data-toggle="tooltip" data-placement="top" title="Service start disabled"></i></button>';

                    if($is_ended == 0 && $is_started == 1 && !$u_data->hasRole('System Admin')) { // 0 && 1
                        $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.endTimer(' . $row->id . ', '.$type.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to stop the service"><i class="fas fa-stop"></i></button>';
                    }else
                        $actionBtn .= '<button type="button" onclick="" class="btn btn-warning btn-sm mr-1" disabled><i class="fas fa-stop" data-toggle="tooltip" data-placement="top" title="Service stop disabled"></i></button>';

                    if($is_ended && !$u_data->hasRole('System Admin')) {
                        $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.finishTimer(' . $row->id . ', '.$type.')" class="btn btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to finish the service"><i class="fas fa-check-square"></i></button>';
                    }else
                        $actionBtn .= '<button type="button" onclick="" class="btn btn-primary btn-sm mr-1" disabled><i class="fas fa-check-square" data-toggle="tooltip" data-placement="top" title="Finish disabled"></i></button>';

                    return $actionBtn;
                })
                ->rawColumns(['action','taskDescriptEdited'])
                ->removeColumn('created_at')
                ->filterColumn('updated_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]); })
                ->filterColumn('serial_no', function ($query, $keyword) { $query->whereRaw((new JobOrder())->getTable().".serial_no like ?", ["%$keyword%"]); })
                ->filterColumn('task_code', function ($query, $keyword) { $query->whereRaw((new LaborTask())->getTable().".task_code like ?", ["%$keyword%"]); })
                ->make(true);
        } catch (\Exception $e) {

            Log::error("getLaborsServPkgsError:".$e->getMessage());
            $this->responseDataJson(false,['Somethings went wrong'],500);
        }
    }

    public function editData(Request $request){

        $wo_tbl = $this->wo_tbl;
        $vehi_ins = (new VehicleInsurance())->getTable();
        $vehi_con = (new VehicleContract())->getTable();
        $vehi_war = (new VehicleWarranty())->getTable();

        $jo_tsk_tbl = $this->jb_order_tsk_tbl;
        $jo_tsk_tech_tbl = $this->jo_tsk_tech_tbl;
        $tech_tbl = (new Employee())->getTable();
        $cus_tbl = (new CustomerSMA())->getTable();
        $vehi_tbl = (new Vehicle())->getTable();
        $vehi_type_tbl = (new VehicleType())->getTable();

        $jb_head_data = JobOrder::where('id', $request->emp_id)->first();
        $wo_data = [];
        if(isset($jb_head_data->wo_order_id)){

            $wo_data = WorkOrder::select($wo_tbl.'.*',
                $cus_tbl.'.name',
                $vehi_tbl.'.vehicle_make',$vehi_tbl.'.vehicle_model',$vehi_tbl.'.vehicle_no',$vehi_tbl.'.chassis_no',$vehi_tbl.'.engine_no',$vehi_tbl.'.unit_number',
                $vehi_type_tbl.'.type_name',
                $vehi_war.'.war_company_name', $vehi_war.'.warranty_no', $vehi_war.'.date_of_purchase', $vehi_war.'.expire_date', $vehi_war.'.phone_number',
                $vehi_con.'.con_company_name', $vehi_con.'.contract_no', DB::raw($vehi_con.'.expire_date AS con_expire_date'), $vehi_con.'.mileage', DB::raw($vehi_con.'.phone_number AS con_phone_number'),
                $vehi_ins.'.ins_company_name', $vehi_ins.'.policy_no', DB::raw($vehi_ins.'.expire_date AS ins_expire_date'), DB::raw($vehi_ins.'.phone_number AS ins_phone_number'),
            )
                ->leftjoin($vehi_ins, $vehi_ins.'.wo_id', '=', $wo_tbl.'.id')
                ->leftjoin($vehi_con, $vehi_con.'.wo_id', '=', $wo_tbl.'.id')
                ->leftjoin($vehi_war, $vehi_war.'.wo_id', '=', $wo_tbl.'.id')
                ->leftjoin($cus_tbl, $cus_tbl.'.id', '=', $wo_tbl.'.customer_id')
                ->leftjoin($vehi_tbl, $vehi_tbl.'.id', '=', $wo_tbl.'.vehicle_id')
                ->leftJoin($vehi_type_tbl,$vehi_type_tbl.'.id','=',$vehi_tbl.'.vehicle_type_id')
                ->where($wo_tbl.'.id',$jb_head_data->wo_order_id)
                ->first();
        }

        $jb_tsk_tech_data = JobOrderTaskTechnician::query()
            ->select($jo_tsk_tech_tbl.'.*', $tech_tbl.'.full_name', $jo_tsk_tbl.'.task_name')
            ->leftJoin($tech_tbl, $jo_tsk_tech_tbl.'.tech_id', '=', $tech_tbl.'.id')
            ->leftJoin($jo_tsk_tbl, $jo_tsk_tech_tbl.'.jo_task_id', '=', $jo_tsk_tbl.'.id')
            ->where($jo_tsk_tech_tbl.'.jo_id', $request->emp_id)->get();

        $editData = array(
            'head_data' => $jb_head_data,
            'wo_data' => $wo_data,
        );

        return $this->responseSelectedDataJson(true, $editData, 200);
    }

    public function getTaskItemData(Request $request){

        $jo_task_item_tbl = (new JobOrderTaskItem())->getTable();
        $jo_task_tbl = (new JobOrderTask())->getTable();

        $jb_task_data = JobOrderTask::where('id',$request->task_id)->first();
        $jb_item_data = JobOrderTaskItem::query()
            ->select(DB::raw($jo_task_item_tbl.'.id AS jb_task_item_id'), DB::raw($jo_task_item_tbl.'.quantity AS qty'), $jo_task_item_tbl.'.*')
            ->leftJoin($jo_task_tbl, $jo_task_item_tbl.'.jo_task_id','=',$jo_task_tbl.'.id')
            ->where($jo_task_item_tbl.'.jo_task_id',$request->task_id)->get();

        $edit_jb_item = [];
        $r = 0;
        foreach ($jb_item_data AS $jb_item){
            $c = uniqid(mt_rand(), true);

            $options              = ProductSMA::getProductOptions($jb_item->product_id, $jb_item->warehouse_id);

            $pro_data = ProductSMA::getProductByCode($jb_item->product_code);
            $jb_item->tax_method = $pro_data['tax_method'] ?? 0;
            $jb_item->base_unit = $pro_data['unit'];
            $jb_item->base_unit_price = $pro_data['price'];

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
            'task_data' => $jb_task_data,
            'item_data' => ($edit_jb_item),
        );

        return $this->responseSelectedDataJson(true, $editData, 200);
    }

    public function getServPkgItemData(Request $request){

        $jo_item_tbl = (new JobOrderItem())->getTable();

        $jb_serv_pkg_data = JobOrderServPackage::where('jo_id', $request->jo_id)->where('service_pkg_id',$request->serv_pkg_id)->first();
        $jb_item_data = JobOrderItem::query()
            ->select(DB::raw($jo_item_tbl.'.id AS jb_task_item_id'), DB::raw($jo_item_tbl.'.quantity AS qty'), $jo_item_tbl.'.*')
            ->where($jo_item_tbl.'.jo_header_id',$request->jo_id)
            ->where($jo_item_tbl.'.service_pkg_id',$request->serv_pkg_id)->get();

        $edit_jb_item = [];
        $r = 0;
        foreach ($jb_item_data AS $jb_item){
            $c = uniqid(mt_rand(), true);

            $options              = ProductSMA::getProductOptions($jb_item->product_id, $jb_item->warehouse_id);

            $pro_data = ProductSMA::getProductByCode($jb_item->product_code);
            $jb_item->tax_method = $pro_data['tax_method'] ?? 0;
            $jb_item->base_unit = $pro_data['unit'] ?? 0;
            $jb_item->base_unit_price = $pro_data['price'] ?? 0;

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
            'serv_pkg_data' => $jb_serv_pkg_data,
            'item_data' => ($edit_jb_item),
        );

        return $this->responseSelectedDataJson(true, $editData, 200);
    }

    public function getTaxRateByID($id){
        $Q2 = DB::table('sma_tax_rates')->where('id', $id)->get();
        if($Q2->count() > 0){
            return $Q2[0];
        }
        return false;
    }

    public function completedLaborsTaskList(){

        return view('labors_tasks/completed_labor_task_list');
    }

    public function storeTaskItem(Request $request){

        DB::beginTransaction();
        try {
            $jo_id = $request->jo_id;
            $jo_task_id = $request->jo_task_id;

            /** Start: Item insert area **/
            $grid_items = json_decode($request->grid_item_rows);
            $delete_grid_items = json_decode($request->delete_grid_item_rows);

            $itm_array = [];
            foreach ($grid_items AS $task){
                $unit_price = str_replace(',', '', $task->item_grid_price);
                $tax_val = str_replace(',', '', $task->tax_val);
                $item_qty = str_replace(',', '', $task->item_qty);

                if($tax_val > 0) $unit_price += ($tax_val/$item_qty);

                if(empty($task->db_item_id)) {
                    $unit     = UnitSMA::getUnitByID($task->product_unit);
                    $dataGrid = [
                        'jo_id' => $jo_id,
                        'jo_task_id' => $jo_task_id,
                        'product_id' => $task->product_id,
                        'product_code' => $task->item_code,
                        'product_name' => $task->item_name,
                        'product_type' => $task->item_type,
                        'item_option_id' => $task->item_option,//row.option
                        'option_id' => $task->item_option,//row.option for the item costing
                        'net_unit_price' => str_replace(',', '', $task->item_grid_price),//item_price,
                        'unit_price' => $this->formatDecimal($unit_price),
                        'quantity' => $item_qty, // row.qty
                        'warehouse_id' => self::getDefaultValues()['warehouse_default_id'],//default warehouse id
                        'item_tax' => $tax_val,
                        'tax_rate_id' => !$task->tax ? NULL : $task->tax->id,
                        'tax' => str_replace(',', '', $task->tax_rate),//tax_rate
                        'discount' => str_replace(',', '', $task->item_ds),
                        'item_discount' => str_replace(',', '', $task->item_discount),
                        'subtotal' => str_replace(',', '', $task->item_grid_sub_tot),
                        'real_unit_price' => str_replace(',', '', $task->real_unit_price),
                        'sale_item_id' => NULL,
                        'product_unit_id'   => $unit ? $unit->id : null,
                        'product_unit_code' => $unit ? $unit->code : null,
                        'unit_quantity' => str_replace(',', '', $task->base_quantity),
                        'comment' => NULL,
                        'created_by' => auth()->user()->id,
                    ];
                    $itm_array[] = $dataGrid;

                }else{ }

            }
            $this->addTaskItems($itm_array);

            foreach ($delete_grid_items AS $dTask){
                JobOrderTaskItem::where('id',$dTask)->delete();
            }

            DB::commit();
            return $this->responseDataJson(true,'Items submitted successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("storeTaskItemOnLaborError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function addTaskItems($items=[]){

        try {
            Log::info(json_encode($items));
            $cost = $this->costing($items);
            $ids = array();
            foreach ($items as $item){

                $rp = JobOrderTaskItem::create($item);
                $ids[] = $rp->id;
            }
            $this->syncPurchaseItems($cost);

            /** Start: syncing system qty */
            Log::info("tskItemids =".json_encode($ids));
            foreach ($ids as $id) {
                $this->syncQuantity(null, null, null, null, $id);
            }
            /** End: syncing system qty */
//            return true;
        }catch (\Exception $e){
            DB::rollBack();
            Log::error("addTaskItemsOnLaborConError:".$e->getMessage());
//            return false;
        }
    }

    public function getCompleteTaskDataTableList(Request $request){
        if ($request->ajax()) {

            $jb_order_tbl = (new JobOrder())->getTable();
            $jb_order_tsk_tbl = (new JobOrderTask())->getTable();
            $jo_tsk_tech_tbl = (new JobOrderTaskTechnician())->getTable();

            $logged_tech_id = auth()->user()->emp_id;
            $user_data = User::find(auth()->user()->id);


            $data = JobOrderTask::query()
                ->select($jb_order_tsk_tbl.'.*',$jb_order_tbl.'.serial_no', )
                ->leftJoin($jb_order_tbl, $jb_order_tsk_tbl.'.jo_id','=',$jb_order_tbl.'.id')
                ->leftJoin($jo_tsk_tech_tbl, $jb_order_tsk_tbl.'.id', '=', $jo_tsk_tech_tbl.'.jo_task_id')
                ->where($jb_order_tsk_tbl.'.is_finished',1)
                ->where($jb_order_tbl.'.is_active',1);

            if(!$user_data->hasRole('System Admin'))
                $data->where($jo_tsk_tech_tbl.'.tech_id', $logged_tech_id);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->editColumn('normal_rate', function ($user) {
                        return number_format($user->normal_rate,2);
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $type = "'task'";
                        $actionBtn = '';
                        if($row->jo_tsk_status < 6) {
                            if ($row->is_started == 0 && !User::find(auth()->user()->id)->hasRole('System Admin')) {
                                $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.startTimer(' . $row->id . ', '.$type.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to start the task"><i class="fas fa-clock"></i></button>';
                            } elseif ($row->is_started == 1) {
                                $actionBtn .= '<button type="button" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Task is in progress">Started</button>';
                            } else
                                $actionBtn .= '<button type="button" onclick="" class="btn btn-warning btn-sm mr-1" disabled><i class="fas fa-clock" data-toggle="tooltip" data-placement="top" title="Task start disabled"></i></button>';

                            if (!$row->is_ended && $row->is_started && !User::find(auth()->user()->id)->hasRole('System Admin')) {
                                $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.endTimer(' . $row->id . ', '.$type.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to stop the task"><i class="fas fa-stop"></i></button>';
                            } else
                                $actionBtn .= '<button type="button" onclick="" class="btn btn-warning btn-sm mr-1" disabled><i class="fas fa-stop" data-toggle="tooltip" data-placement="top" title="Task stop disabled"></i></button>';

                            if ($row->is_ended && !User::find(auth()->user()->id)->hasRole('System Admin')) {
                                $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.finishTimer(' . $row->id . ', '.$type.')" class="btn btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to finish the task"><i class="fas fa-check-square"></i></button>';
                            } else
                                $actionBtn .= '<button type="button" onclick="" class="btn btn-primary btn-sm mr-1" disabled><i class="fas fa-check-square" data-toggle="tooltip" data-placement="top" title="Finish disabled"></i></button>';
                        }elseif ($row->jo_tsk_status == 6){

                            $actionBtn = '<span class="text-black badge badge-primary" data-toggle="tooltip" data-placement="top" title="Your work has been approved">Approved</span>';
                        }else
                            $actionBtn = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Job order has been invoiced">Invoiced</span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('serial_no', function ($query, $keyword) {
                        $query->whereRaw((new JobOrder())->getTable().".serial_no like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getLaborsTaskError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function startTaskTimer(Request $request){

        $logged_tech_id = auth()->user()->emp_id;
        $logged_user_id = auth()->user()->id;
        $jb_task_id = $request->tsk_id;
        $jb_time_type = $request->time_type;

        DB::beginTransaction();
        try {

            $task_techs = JobOrderTaskTechTime::where('tech_id', $logged_tech_id)->where('is_started',1)->get();
            $serv_techs = JobOrderServPkgTechTime::where('tech_id', $logged_tech_id)->where('is_started',1)->get();

            if($task_techs->count() < 1 && $serv_techs->count() < 1){
                if($jb_time_type == "task") {
                    JobOrderTaskTechTime::create([
                        'jo_task_id' => $jb_task_id,
                        'tech_id' => $logged_tech_id,
                        'date' => date('Y-m-d'),
                        'start_datetime' => date('Y-m-d H:i:s'),
                        'is_started' => 1,
                        'is_finished' => 0,
                    ]);

                    $dataArray = ['jo_tsk_status' => 3, 'is_started' => 1, 'is_ended' => 0, 'is_finished' => 0];
                    $jobTask = JobOrderTask::findOrFail($jb_task_id);
                    $jobTask->update($dataArray);
                    $jobOrder = JobOrder::findOrFail($jobTask->jo_id);
                    $jobOrder->update(['jo_status' => 3]);

                    $this->notifyServiceMana('Task Started!',$jobOrder['serial_no'].' task started by '.auth()->user()->name);
                    DB::commit();
                    return $this->responseDataJson(true, 'Task started successfully', 200);
                }elseif ($jb_time_type == "serv"){
                    JobOrderServPkgTechTime::create([
                        'jo_serv_pkg_id' => $jb_task_id,
                        'tech_id' => $logged_tech_id,
                        'date' => date('Y-m-d'),
                        'start_datetime' => date('Y-m-d H:i:s'),
                        'is_started' => 1,
                        'is_finished' => 0,
                    ]);

                    $dataArray = ['jo_serv_pkg_status' => 3, 'is_started' => 1, 'is_ended' => 0, 'is_finished' => 0];
                    $jobTask = JobOrderServPackage::findOrFail($jb_task_id);
                    $jo_id = $jobTask->jo_id;
                    $jobTask->update($dataArray);
                    $jobOrder = JobOrder::findOrFail($jo_id);
                    $jobOrder->update(['jo_status' => 3]);

                    $this->notifyServiceMana('Service Started!',$jobOrder['serial_no'].' service started by '.auth()->user()->name);
                    DB::commit();
                    return $this->responseDataJson(true, 'Service Package started successfully', 200);
                }
            }else{

                return $this->responseDataJson(false,['You can only start an one task or service at a time. Please finish started work first'],401);
            }
        }catch (\Exception $e){
            DB::rollBack();
            Log::error("TaskStartTimerError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function endTaskTimer(Request $request){

        $logged_tech_id = auth()->user()->emp_id;
        $jb_task_id = $request->tsk_id;
        $jb_time_type = $request->time_type;

        DB::beginTransaction();
        try {
            $startedTime = JobOrderTaskTechTime::where('jo_task_id', $jb_task_id)->where('tech_id', $logged_tech_id)->where('is_started', 1)->get();
            $startedTimeServ = JobOrderServPkgTechTime::where('jo_serv_pkg_id', $jb_task_id)->where('tech_id', $logged_tech_id)->where('is_started', 1)->get();
            if($jb_time_type == "task") {
                if ($startedTime->count() > 0) {

                    $startTime = Carbon::parse($startedTime[0]->start_datetime);
                    $endTime = Carbon::parse(date('Y-m-d H:i:s'));
                    $totalDuration = $startTime->diff($endTime)->format('%H:%I:%S');
                    $up_data = [
                        'date' => date('Y-m-d'),
                        'end_datetime' => date('Y-m-d H:i:s'),
                        'is_started' => 0,
                        'session_tot_time' => $totalDuration,
                        'is_ended' => 1,
                    ];
                    JobOrderTaskTechTime::where('id', $startedTime[0]->id)->update($up_data);
                }
                $dataArray = ['is_started' => 0, 'is_ended' => 1];
                $jobTask = JobOrderTask::findOrFail($jb_task_id);
                $jobTask->update($dataArray);
                $jobOrder = JobOrder::findOrFail($jobTask['jo_id']);

                $this->notifyServiceMana('Task Ended!',$jobOrder['serial_no'].' task ended by '.auth()->user()->name);
                DB::commit();
                return $this->responseDataJson(true, 'Task ended successfully', 200);
            }elseif ($jb_time_type == "serv"){
                if ($startedTimeServ->count() > 0) {

                    $startTime = Carbon::parse($startedTimeServ[0]->start_datetime);
                    $endTime = Carbon::parse(date('Y-m-d H:i:s'));
                    $totalDuration = $startTime->diff($endTime)->format('%H:%I:%S');
                    $up_data = [
                        'date' => date('Y-m-d'),
                        'end_datetime' => date('Y-m-d H:i:s'),
                        'is_started' => 0,
                        'session_tot_time' => $totalDuration,
                        'is_ended' => 1,
                    ];
                    JobOrderServPkgTechTime::where('id', $startedTimeServ[0]->id)->update($up_data);
                }
                $dataArray = ['is_started' => 0, 'is_ended' => 1];
                $jobTask = JobOrderServPackage::findOrFail($jb_task_id);
                $jobTask->update($dataArray);
                $jobOrder = JobOrder::findOrFail($jobTask['jo_id']);

                $this->notifyServiceMana('Service Ended!',$jobOrder['serial_no'].' service ended by '.auth()->user()->name);
                DB::commit();
                return $this->responseDataJson(true, 'Service package ended successfully', 200);
            }

        }catch (\Exception $e){

            DB::rollBack();
            Log::error("TaskEndTimerError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function finishTaskTimer(Request $request){

        $logged_tech_id = auth()->user()->emp_id;
        $jb_task_id = $request->tsk_id;
        $jb_time_type = $request->time_type;

        DB::beginTransaction();
        try {
            if($jb_time_type == "task") {
                $is_finished = 1;
                $startedTime = JobOrderTaskTechTime::where('jo_task_id', $jb_task_id)->where('tech_id', $logged_tech_id)->where('is_ended', 1)->get();
                $jobTask = JobOrderTask::findOrFail($jb_task_id);

                $unsigned_tasks = DB::select('SELECT id, jo_id, task_id FROM job_order_tasks WHERE jo_id= ? AND id NOT IN (SELECT jo_task_id FROM job_order_task_technicians)', [$jobTask->jo_id]);

                //check if there are any unassigned task in this job order. if yes marked as a partially complete
                if (!empty($unsigned_tasks)) {
                    $dataArray = ['jo_tsk_status' => 4, 'is_started' => 0, 'is_ended' => 1, 'is_finished' => 1];
                    $jobTask->update($dataArray);

                    $jobOrder = JobOrder::findOrFail($jobTask->jo_id);
                    $jobOrder->update(['jo_status' => 4]);
                }

                if ($startedTime->count() > 0) {
                    //update the previously started time row to finish state
                    $up_data = ['is_started' => 0, 'is_finished' => 1];
                    foreach ($startedTime as $stt) {
                        JobOrderTaskTechTime::where('id', $stt->id)->update($up_data);
                    }
                }

                $task_techs = JobOrderTaskTechTime::where('jo_task_id', $jb_task_id)->get();
                //check if there are any technicians which don't have finished their work. If it is not finished all, then it will give 0
                foreach ($task_techs as $tt) {

//                Log::info('task_tech_time: jo_task_id = '. ($tt->jo_task_id.",tech_id = ".$tt->tech_id.",is_started = ".$tt->is_started.",is_ended = ".$tt->is_ended.",is_finished = ".$tt->is_finished));
                    switch ($tt->is_finished) {
                        case 0 :
                            $is_finished = 0;
                            break;
                        default:
                            $is_finished = 1;
                            break;
                    }
                }
//            Log::info("is_finish: ".$is_finished."\n\n");

                if (!$is_finished) {
                    $dataArray = ['jo_tsk_status' => 4, 'is_started' => 0, 'is_ended' => 1, 'is_finished' => 1];
                    $jobTask->update($dataArray);

                    $jobOrder = JobOrder::findOrFail($jobTask->jo_id);
                    $jobOrder->update(['jo_status' => 4]);
                }

                //is finished 1 means no unfinished technicians under that task, then this is fully completed.
                if ($is_finished == 1 && empty($unsigned_tasks)) {

                    $dataArray = ['jo_tsk_status' => 5, 'is_started' => 0, 'is_ended' => 1, 'is_finished' => 1];
                    $jobTask->update($dataArray);
                    $jobOrder = JobOrder::findOrFail($jobTask->jo_id);
                    $jobOrder->update(['jo_status' => 5]);

                    $this->notifyServiceMana('Task Finished!',$jobOrder['serial_no'].' task finished by the '.auth()->user()->name);
                    DB::commit();
                    return $this->responseDataJson(true, 'Task finished successfully', 200);
                }

                $jobOrder = JobOrder::findOrFail($jobTask->jo_id);

                $this->notifyServiceMana('Task Partially Finished!',$jobOrder['serial_no'].' task partially finished by the '.auth()->user()->name);
                DB::commit();
                return $this->responseDataJson(true, 'Task partially finished successfully', 200);

            }elseif ($jb_time_type == "serv"){
                $is_finished = 1;
                $startedTime = JobOrderServPkgTechTime::where('jo_serv_pkg_id', $jb_task_id)->where('tech_id', $logged_tech_id)->where('is_ended', 1)->get();
                $jobTask = JobOrderServPackage::findOrFail($jb_task_id);

                $unsigned_tasks = DB::select('SELECT id, jo_id, service_pkg_id FROM job_order_serv_packages WHERE jo_id= ? AND id NOT IN (SELECT jo_serv_pkg_id FROM job_order_serv_pkg_technicians)', [$jobTask->jo_id]);

                //check if there are any unassigned task in this job order. if yes marked as a partially complete
                if (!empty($unsigned_tasks)) {
                    $dataArray = ['jo_serv_pkg_status' => 4, 'is_started' => 0, 'is_ended' => 1, 'is_finished' => 1];
                    $jobTask->update($dataArray);

                    $jobOrder = JobOrder::findOrFail($jobTask->jo_id);
                    $jobOrder->update(['jo_status' => 4]);
                }

                if ($startedTime->count() > 0) {
                    //update the previously started time row to finish state
                    $up_data = ['is_started' => 0, 'is_finished' => 1];
                    foreach ($startedTime as $stt) {
                        JobOrderServPkgTechTime::where('id', $stt->id)->update($up_data);
                    }
                }

                $task_techs = JobOrderServPkgTechTime::where('jo_serv_pkg_id', $jb_task_id)->get();
                //check if there are any technicians which don't have finished their work. If it is not finished all, then it will give 0
                foreach ($task_techs as $tt) {

//                Log::info('task_tech_time: jo_task_id = '. ($tt->jo_task_id.",tech_id = ".$tt->tech_id.",is_started = ".$tt->is_started.",is_ended = ".$tt->is_ended.",is_finished = ".$tt->is_finished));
                    switch ($tt->is_finished) {
                        case 0 :
                            $is_finished = 0;
                            break;
                        default:
                            $is_finished = 1;
                            break;
                    }
                }
//            Log::info("is_finish: ".$is_finished."\n\n");

                if (!$is_finished) {
                    $dataArray = ['jo_serv_pkg_status' => 4, 'is_started' => 0, 'is_ended' => 1, 'is_finished' => 1];
                    $jobTask->update($dataArray);

                    $jobOrder = JobOrder::findOrFail($jobTask->jo_id);
                    $jobOrder->update(['jo_status' => 4]);
                }

                //is finished 1 means no unfinished technicians under that task, then this is fully completed.
                if ($is_finished == 1 && empty($unsigned_tasks)) {

                    $dataArray = ['jo_serv_pkg_status' => 5, 'is_started' => 0, 'is_ended' => 1, 'is_finished' => 1];
                    $jobTask->update($dataArray);
                    $jobOrder = JobOrder::findOrFail($jobTask->jo_id);
                    $jobOrder->update(['jo_status' => 5]);

                    $this->notifyServiceMana('Service Finished!',$jobOrder['serial_no'].' service finished by the '.auth()->user()->name);
                    DB::commit();
                    return $this->responseDataJson(true, 'Service package finished successfully', 200);
                }

                $jobOrder = JobOrder::findOrFail($jobTask->jo_id);

                $this->notifyServiceMana('Service Partially Finished!',$jobOrder['serial_no'].' service partially finished by the '.auth()->user()->name);
                DB::commit();
                return $this->responseDataJson(true, 'Service package partially finished successfully', 200);
            }
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("TaskFinishTimerError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function getTaskCommentData(Request $request){
        $task_id = $request->task_id;
        $comment_type = $request->com_type;
        $tech_tbl_nm = (new Employee())->getTable();
        $tech_tsk_comnt_tbl_nm = (new TaskTechComment())->getTable();
        $tech_serv_comnt_tbl_nm = (new ServPkgTechComment())->getTable();

        if(isset($request->com_type) && $comment_type == "serv"){
            $tsk_cmnt = ServPkgTechComment::query()
                ->select($tech_serv_comnt_tbl_nm . '.*', $tech_tbl_nm . '.full_name', DB::raw("DATE_FORMAT(" . (new ServPkgTechComment())->getTable() . ".created_at, '%H:%i:%s') AS sentTime"))
                ->leftJoin($tech_tbl_nm, $tech_serv_comnt_tbl_nm . '.tech_id', $tech_tbl_nm . '.id')
                ->where('jo_serv_pkg_id', $task_id)
                ->orderBy($tech_serv_comnt_tbl_nm . '.created_at', 'desc')
                ->get();
        }else {
            $tsk_cmnt = TaskTechComment::query()
                ->select($tech_tsk_comnt_tbl_nm . '.*', $tech_tbl_nm . '.full_name', DB::raw("DATE_FORMAT(" . (new TaskTechComment())->getTable() . ".created_at, '%H:%i:%s') AS sentTime"))
                ->leftJoin($tech_tbl_nm, $tech_tsk_comnt_tbl_nm . '.tech_id', $tech_tbl_nm . '.id')
                ->where('jo_task_id', $task_id)
                ->orderBy($tech_tsk_comnt_tbl_nm . '.created_at', 'desc')
                ->get();
        }
        return $this->responseSelectedDataJson(true, $tsk_cmnt, 200);
    }

    public function createTaskComment(Request $request){

        $validateMsgs = [
            'task_message.required' => 'The Message field is required'
        ];

        $validateRequest = Validator::make($request->all(),[
            'task_message'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        $comment_type = $request->com_type;

        $emp_id = auth()->user()->emp_id;
        $user_id = auth()->user()->id;
        $user_role_id = auth()->user()->roles()->get()[0]->id;

        try {

            if($comment_type == "serv"){
                $data = [
                    'tech_id' => $emp_id,
                    'jo_id' => $request->jo_id,
                    'jo_serv_pkg_id' => $request->jo_task_id,
                    'message' => $request->task_message,
                    'user_id' => $user_id,
                    'user_role_id' => $user_role_id,
                ];

                $resp = ServPkgTechComment::create($data);
            }else {
                $data = [
                    'tech_id' => $emp_id,
                    'jo_id' => $request->jo_id,
                    'jo_task_id' => $request->jo_task_id,
                    'message' => $request->task_message,
                    'user_id' => $user_id,
                    'user_role_id' => $user_role_id,
                ];

                $resp = TaskTechComment::create($data);
            }
            return $this->responseDataJson(true,'Task comment/feedback added successfully',200, ['insertedId' => $resp->id]);
        }catch (\Exception $e){
            Log::error('createTaskMessage_LaborCon: '.$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function servPkgTechApprove(Request $request){
        $employee = JobOrderServPackage::findOrFail($request->item_id);
        DB::beginTransaction();
        try {

            $servItems = JobOrderServPkgItem::where('jo_serv_pkg_id', $employee->id)->get();
            $itm_array = [];
            foreach ($servItems AS $task){
                $unit_price = str_replace(',', '', $task->unit_price);
                $tax_val = str_replace(',', '', $task->item_tax);
                $item_qty = str_replace(',', '', $task->quantity);

                if($tax_val > 0) $unit_price += ($tax_val/$item_qty);

                $unit     = UnitSMA::getUnitByID($task->product_unit_id);
                $dataGrid = [
                    'product_id' => $task->product_id,
                    'product_code' => $task->product_code,
                    'product_name' => $task->product_name,
                    'product_type' => $task->product_type,
                    'item_option_id' => $task->item_option_id,//row.option
                    'option_id' => $task->item_option_id,//row.option for the item costing
                    'net_unit_price' => str_replace(',', '', $task->unit_price),//item_price,
                    'unit_price' => $this->formatDecimal($unit_price),
                    'quantity' => $item_qty, // row.qty
                    'warehouse_id' => self::getDefaultValues()['warehouse_default_id'],//default warehouse id
                    'item_tax' => $tax_val,
                    'tax_rate_id' => $task->tax_rate_id,
                    'tax' => str_replace(',', '', $task->tax),//tax_rate
                    'discount' => str_replace(',', '', $task->discount),
                    'item_discount' => str_replace(',', '', $task->item_discount),
                    'subtotal' => str_replace(',', '', $task->subtotal),
                    'real_unit_price' => str_replace(',', '', $task->real_unit_price),
                    'sale_item_id' => NULL,
                    'product_unit_id'   => $unit ? $unit->id : null,
                    'product_unit_code' => $unit ? $unit->code : null,
                    'unit_quantity' => str_replace(',', '', $task->unit_quantity),
                    'comment' => NULL,
                    'created_by' => auth()->user()->id,
                ];
                $itm_array[] = $dataGrid;
            }


            $this->addServiceItems($itm_array);

            $active_state = 1;
            $dataArray = ['is_approve_tech' =>$active_state];
            $resp = $employee->update($dataArray);

            if($active_state)
                $message = "Service package has been added!";
            else
                $message = "Service package has been disapproved!";

            if($resp){
                DB::commit();
                return $this->responseDataJson(true, $message,200);
            }else{

                return $this->responseDataJson(true, "Something went wrong!",401);
            }

        }catch (\Exception $e){

            DB::rollBack();
            Log::error("ServicePkgTechApproveError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function addServiceItems($items=[]){

        try {
            $cost = $this->costing($items);

            $this->syncPurchaseItems($cost);

            /** Start: syncing system qty */
            foreach ($items as $id) {
                $this->syncQuantity(null, null, null, $id['product_id'], null);
            }
            /** End: syncing system qty */
        }catch (\Exception $e){
            DB::rollBack();
            Log::error("addServiceItemsOnLaborConError:".$e->getMessage());
//            return false;
        }
    }

    public function completedServPkgList(){

        return view('labors_tasks/completed_serv_pkg_list');
    }

    public function getCompleteServPkgDataTableList(Request $request){
        if ($request->ajax()) {

            $jb_order_tbl = (new JobOrder())->getTable();
            $jb_order_tsk_tbl = (new JobOrderServPackage())->getTable();
            $jo_tsk_tech_tbl = (new JobOrderServPkgTechnician())->getTable();

            $logged_tech_id = auth()->user()->emp_id;
            $user_data = User::find(auth()->user()->id);


            $data = JobOrderServPackage::query()
                ->select($jb_order_tsk_tbl.'.*',$jb_order_tbl.'.serial_no', )
                ->leftJoin($jb_order_tbl, $jb_order_tsk_tbl.'.jo_id','=',$jb_order_tbl.'.id')
                ->leftJoin($jo_tsk_tech_tbl, $jb_order_tsk_tbl.'.id', '=', $jo_tsk_tech_tbl.'.jo_serv_pkg_id')
                ->where($jb_order_tsk_tbl.'.is_finished',1)
                ->where($jb_order_tbl.'.is_active',1);

            if(!$user_data->hasRole('System Admin'))
                $data->where($jo_tsk_tech_tbl.'.tech_id', $logged_tech_id);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->editColumn('normal_rate', function ($user) {
                        return number_format($user->normal_rate,2);
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
                        if($row->jo_serv_pkg_status < 6) {

                            $type = "'serv'";
                            if ($row->is_started == 0 && !User::find(auth()->user()->id)->hasRole('System Admin')) {
                                $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.startTimer(' . $row->id . ', '.$type.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to start the service"><i class="fas fa-clock"></i></button>';
                            } elseif ($row->is_started == 1) {
                                $actionBtn .= '<button type="button" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Service is in progress">Started</button>';
                            } else
                                $actionBtn .= '<button type="button" onclick="" class="btn btn-warning btn-sm mr-1" disabled><i class="fas fa-clock" data-toggle="tooltip" data-placement="top" title="Service start disabled"></i></button>';

                            if (!$row->is_ended && $row->is_started && !User::find(auth()->user()->id)->hasRole('System Admin')) {
                                $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.endTimer(' . $row->id . ', '.$type.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to stop the service"><i class="fas fa-stop"></i></button>';
                            } else
                                $actionBtn .= '<button type="button" onclick="" class="btn btn-warning btn-sm mr-1" disabled><i class="fas fa-stop" data-toggle="tooltip" data-placement="top" title="Service stop disabled"></i></button>';

                            if ($row->is_ended && !User::find(auth()->user()->id)->hasRole('System Admin')) {
                                $actionBtn .= '<button type="button" onclick="laborsTaskList.ivm.finishTimer(' . $row->id . ', '.$type.')" class="btn btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Click to finish the service"><i class="fas fa-check-square"></i></button>';
                            } else
                                $actionBtn .= '<button type="button" onclick="" class="btn btn-primary btn-sm mr-1" disabled><i class="fas fa-check-square" data-toggle="tooltip" data-placement="top" title="Finish disabled"></i></button>';
                        }elseif ($row->jo_serv_pkg_status == 6){

                            $actionBtn = '<span class="text-black badge badge-primary" data-toggle="tooltip" data-placement="top" title="Your work has been approved">Approved</span>';
                        }else
                            $actionBtn = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Job order has been invoiced">Invoiced</span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('serial_no', function ($query, $keyword) {
                        $query->whereRaw((new JobOrder())->getTable().".serial_no like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getServicePkgError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }
}
