<?php

namespace App\Http\Controllers;

use App\Models\CustomerSMA;
use App\Models\JobOrder;
use App\Models\JobOrderTask;
use App\Models\Vehicle;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class EstimateController extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('permission:estimate-list', ['only' =>['index','getDataTableList']]);
//        $this->middleware('permission:estimate-view', ['only' =>['show']]);
//        $this->middleware('permission:estimate-edit', ['only' =>['edit', 'update']]);
//        $this->middleware('permission:estimate-add', ['only' =>['create', 'store']]);
//        $this->middleware('permission:estimate-delete', ['only' =>['destroy']]);
    }

    public function index(){
        return view('estimate/estimate_list');
    }

    public function getDataTableList(Request $request){

        if ($request->ajax()) {
            $wo_tbl_name = (new WorkOrder())->getTable();
            $cus_tbl_name = (new CustomerSMA())->getTable();
            $vehi_tbl_name = (new Vehicle())->getTable();

            $data = WorkOrder::query()
                ->select($wo_tbl_name.'.*',$cus_tbl_name.'.name',$vehi_tbl_name.'.vehicle_no')
                ->leftJoin($cus_tbl_name,$cus_tbl_name.'.id','=',$wo_tbl_name.'.customer_id')
                ->leftJoin($vehi_tbl_name,$vehi_tbl_name.'.id','=',$wo_tbl_name.'.vehicle_id')
                ->where($wo_tbl_name.'.wo_type',2)
                ->where($wo_tbl_name.'.is_active',1);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
                        if(auth()->user()->hasPermissionTo('workorder-view') )
                            $actionBtn .= '<span onclick="workOrderList.ivm.showDetails('.$row->id.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="View workorder."><i class="fas fa-eye"></i></span>';
                        if(auth()->user()->hasPermissionTo('estimate-trans-wo') )
                            $actionBtn .= '<span onclick="workOrderList.ivm.transferToEstimate(' . $row->id . ')" class="btn btn-warning btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Transfer estimate to workorder."><i class="fas fa-exchange-alt"></i></span>';
                        if(auth()->user()->hasPermissionTo('workorder-edit') )
                            $actionBtn .= '<a href="' . route('workorder.edit', $row->id) . '" class="delete btn btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Edit workorder."><i class="fas fa-edit"></i></a>';
                        if(auth()->user()->hasPermissionTo('workorder-delete') )
                            $actionBtn .= '<span onclick="workOrderList.ivm.deleteRecord(' . $row->id . ')" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete workorder."><i class="fas fa-trash-alt"></i></span>';

                        return $actionBtn;
                    })
                    ->addColumn('woStatus', function ($row){
                        if($row->wo_status == 0)
                            $state = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Just created.">Pending</span>';// Just started WO by collecting data from customer
                        elseif($row->wo_status == 1)
                            $state = '<span class="text-white badge badge-light badge-primary">APRVDEST</span>';// Approved WO estimate before work commencement
                        elseif($row->wo_status == 2)
                            $state = '<span class="text-white badge badge-light badge-success">APRVEDCTMR</span>';// Estimate approved by the customer
                        elseif($row->wo_status == 3)
                            $state = '<span class="text-white badge badge-light badge-info">Ongoing</span>';// WO assigned to labor
                        elseif($row->wo_status == 4)
                            $state = '<span class="text-white badge badge-light badge-warning">Halted</span>';// WO halt after assigned to labor lack of parts etc...
                        elseif($row->wo_status == 5)
                            $state = '<span class="text-white badge badge-info">CMLTDLBR</span>';// WO completed by labor
                        elseif($row->wo_status == 6)
                            $state = '<span class="text-white badge badge-primary">APRVEDMNGR</span>';// WO approved by service manager after completed by labor
                        elseif($row->wo_status == 7)
                            $state = '<span class="text-white badge badge-warning">DAPRVEDMNGR</span>';// WO invoice paid by the customer
                        else
                            $state = '<span class="text-white badge badge-secondary">-</span>';// no=8, WO has no state, and it is an estimate
                        return $state;
                    })
                    ->addColumn('woType', function ($row){
                        if($row->wo_type == 0)
                            $state = '<span class="text-black badge badge-secondary" data-toggle="tooltip" data-placement="top" title="Workorder">WO</span>';// Just WO
                        elseif($row->wo_type == 1)
                            $state = '<span class="text-black badge badge-primary" data-toggle="tooltip" data-placement="top" title="Warranty Workorder">WWO</span>';// Warranty WO
                        elseif($row->wo_type == 2)
                            $state = '<span class="text-black badge badge-success" data-toggle="tooltip" data-placement="top" title="Estimate">EST</span>';// Estimate
                        else
                            $state = '<span class="text-white badge badge-secondary">-</span>';// WO has no state
                        return $state;
                    })
                    ->rawColumns(['action', 'woStatus', 'woType'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(".(new WorkOrder())->getTable().".updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]); })
                    ->filterColumn('name', function ($query, $keyword) { $query->whereRaw((new CustomerSMA())->getTable().".name like ?", ["%$keyword%"]); })
                    ->filterColumn('vehicle_no', function ($query, $keyword) { $query->whereRaw((new Vehicle())->getTable().".vehicle_no like ?", ["%$keyword%"]); })
                    ->make(true);
            } catch (\Exception $e) {
                Log::error("getWorkOrderError:".$e->getMessage());
                return $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function transferToWorkorder(Request $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {

            $wo_id = $request->emp_id;
            $employee = WorkOrder::findOrFail($wo_id);
            $jo_data = JobOrder::where('wo_order_id', $wo_id)->get();

            foreach ($jo_data as $jo_item) {

                $jo_tsk_data = JobOrderTask::where('jo_id', $jo_item->id)->get();

                foreach ($jo_tsk_data as $jo_tsk_it) {

                    JobOrderTask::where('id', $jo_tsk_it->id)->update(['is_estimate' => 0]);
                }
                JobOrder::where('id', $jo_item->id)->update(['is_estimate' => 0]);
            }

            $is_warranty = $employee->is_warranty ?? 0;
            $is_contract = $employee->is_contract ?? 0;
            $is_insurance = $employee->is_insurance ?? 0;

            if($is_warranty || $is_contract || $is_insurance)
                $dataArray = ['wo_type' => 1];
            else
                $dataArray = ['wo_type' => 0];
            $resp = $employee->update($dataArray);

            $message = "Estimate has been successfully transferred!";
            if ($resp) {

                DB::commit();
                return $this->responseDataJson(true, $message, 200);
            } else {
                DB::rollBack();
                return $this->responseDataJson(true, ["Something went wrong!"], 401);
            }
        }catch(\Exception $e){
            DB::rollBack();
            Log::error("TransferToWorkorderError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }
}
