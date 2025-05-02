<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\CustomerGroupSMA;
use App\Models\CustomerSMA;
use App\Models\DepositSMA;
use App\Models\FormSerial;
use App\Models\JobOrder;
use App\Models\JobOrderTask;
use App\Models\PriceGroupSMA;
use App\Models\State;
use App\Models\Vehicle;
use App\Models\VehicleContract;
use App\Models\VehicleInsurance;
use App\Models\VehicleWarranty;
use App\Models\WorkOrder;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class WorkOrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:workorder-view', ['only' =>['index','show', 'getDataTableList']]);
        $this->middleware('permission:workorder-edit', ['only' =>['edit', 'update']]);
        $this->middleware('permission:workorder-add', ['only' =>['create', 'store']]);
        $this->middleware('permission:workorder-delete', ['only' =>['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        //
        return view('work_order/workorder_list');
    }

    public function getDataTableList(Request $request){

        if ($request->ajax()) {
            $wo_tbl_name = (new WorkOrder())->getTable();
            $cus_tbl_name = (new CustomerSMA())->getTable();
            $vehi_tbl_name = (new Vehicle())->getTable();

            $data = WorkOrder::query()
                ->select($wo_tbl_name.'.*',$cus_tbl_name.'.name',$vehi_tbl_name.'.vehicle_no', $vehi_tbl_name.'.engine_no', DB::raw($vehi_tbl_name.'.engine_no as vinNo'))
                ->leftJoin($cus_tbl_name,$cus_tbl_name.'.id','=',$wo_tbl_name.'.customer_id')
                ->leftJoin($vehi_tbl_name,$vehi_tbl_name.'.id','=',$wo_tbl_name.'.vehicle_id')
                ->where($wo_tbl_name.'.wo_type','=',0)
                ->where($wo_tbl_name.'.is_active',1);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
                        if(auth()->user()->hasPermissionTo('deposit') )
                            $actionBtn .= '<span onclick="workOrderList.ivm.showDepositView('.$row->id.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Add deposits."><i class="fas fa-dollar-sign"></i></span>';
//                        if(auth()->user()->hasPermissionTo('workorder-view') )
//                            $actionBtn .= '<span onclick="workOrderList.ivm.showDetails('.$row->id.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="View workorder."><i class="fas fa-eye"></i></span>';
                        if(auth()->user()->hasPermissionTo('workorder-trans-est') )
                            $actionBtn .= '<span onclick="workOrderList.ivm.transferToEstimate(' . $row->id . ')" class="btn btn-warning btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Transfer workorder to estimate."><i class="fas fa-exchange-alt"></i></span>';
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
                            $state = '<span class="text-black badge badge-light badge-success" data-toggle="tooltip" data-placement="top" title="Estimate">EST</span>';// Estimate
                        else
                            $state = '<span class="text-white badge badge-secondary">-</span>';// WO has no state
                        return $state;
                    })
                    ->rawColumns(['action', 'woStatus', 'woType'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) { $query->whereRaw("DATE_FORMAT(".(new WorkOrder())->getTable().".updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]); })
                    ->filterColumn('name', function ($query, $keyword) { $query->whereRaw((new CustomerSMA())->getTable().".name like ?", ["%$keyword%"]); })
                    ->filterColumn('vehicle_no', function ($query, $keyword) { $query->whereRaw((new Vehicle())->getTable().".vehicle_no like ?", ["%$keyword%"]); })
                    ->filterColumn('engine_no', function ($query, $keyword) {
                        $query->whereRaw((new Vehicle())->getTable() . ".engine_no like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {
                Log::error("getWorkOrderError:".$e);
                return $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        //
        return view('work_order/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validateMsgs = [
            'customer_id.required' => 'The Customer name field is required',
            'vehicle_id.required' => 'The Vehicle field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'customer_id'      => ['required'],
            'vehicle_id'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $is_insurance = $request->is_insurance=="true"? 1 : 0;
            $is_contract = $request->is_contract=="true"? 1 : 0;
            $is_warranty = $request->is_warranty=="true"? 1 : 0;
            $ins_id = $cont_id = $warr_id = NULL;

            $wo_type = 0;
            if($is_insurance || $is_contract || $is_warranty) $wo_type = 1;

            $wo_serial_cus =  FormSerial::getTemporaryId('work_order');
            $data = [
                'serial_no'=> $wo_serial_cus,
                'customer_id'=> $request->customer_id,
                'vehicle_id'=> $request->vehicle_id,
                'wo_date'=> empty($request->wo_date) ? date('Y-m-d') : $request->wo_date,
                'appointment_id'=> $request->appointment_id,
                'mileage'=> $request->mileage,
                'reported_defect'=> $request->reported_defect,
                'is_warranty'=> $is_warranty,
                'is_contract'=> $is_contract,
                'is_insurance'=> $is_insurance,
                'wo_type'=> $wo_type,
            ];

            $respWo = WorkOrder::create($data);
            $wo_id = $respWo->id;

            $child_state = true;
            if($respWo){
                if($is_insurance) {
                    $insertInsurance = $this->createVehicleInsurance($request, $wo_id);
                    if(!$insertInsurance) $child_state = false;
                }
                if($is_contract) {
                    $insertContract = $this->createServiceContract($request, $wo_id);
                    if(!$insertContract) $child_state = false;
                }
                if($is_warranty) {
                    $insertWarranty = $this->createVehicleWarranty($request, $wo_id);
                    if(!$insertWarranty) $child_state = false;
                }
            }

            if($wo_type == 1){
                $this->notifyWarrantyMana('New Work order',$wo_serial_cus.' New work order arrived to check.');
            }
            if($child_state){
                DB::commit();
                return $this->responseDataJson(true,'Work order created successfully',200, ['insertedId' => $wo_id]);
            }

            Log::error("WorkorderCreateError: Some errors in child creation parts.");
            return $this->responseDataJson(false,['Work order did not created successfully. Please try again.'],500);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("WorkorderCreateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function createVehicleInsurance($ins_data, $wo_id){
        try {
            $data = [
                'wo_id' => $wo_id,
                'customer_id' => $ins_data->customer_id,
                'vehicle_id' => $ins_data->vehicle_id,
                'ins_company_name'=> $ins_data->ins_company_name,
                'policy_no'=> $ins_data->ins_policy_no,
                'expire_date'=> $ins_data->ins_expire_date,
                'phone_number'=> $ins_data->ins_phone_number,
            ];

            $resp = VehicleInsurance::create($data);

            return true;
        }catch (\Exception $e){
            Log::error('createVehicleInsurance_WOcon: '.$e->getMessage());
            return false;
        }
    }

    public function createServiceContract($cont_data, $wo_id){
        try {
            $data = [
                'wo_id' => $wo_id,
                'customer_id' => $cont_data->customer_id,
                'vehicle_id' => $cont_data->vehicle_id,
                'con_company_name'=> $cont_data->con_company_name,
                'contract_no'=> $cont_data->contract_no,
                'expire_date'=> $cont_data->cont_expire_date,
                'mileage'=> $cont_data->cont_mileage,
                'phone_number'=> $cont_data->cont_phone_number,
            ];

            $resp = VehicleContract::create($data);

            return true;
        }catch (\Exception $e){
            Log::error('createServiceContract_WOcon: '.$e->getMessage());
            return false;
        }
    }

    public function createVehicleWarranty($warr_data, $wo_id){
        try {
            $data = [
                'wo_id' => $wo_id,
                'customer_id' => $warr_data->customer_id,
                'vehicle_id' => $warr_data->vehicle_id,
                'war_company_name'=> $warr_data->war_company_name,
                'warranty_no'=> $warr_data->warranty_no,
                'date_of_purchase'=> $warr_data->date_of_purchase,
                'expire_date'=> $warr_data->war_expire_date,
                'phone_number'=> $warr_data->war_phone_number,
            ];

            $resp = VehicleWarranty::create($data);

            return true;
        }catch (\Exception $e){
            Log::error('createVehicleWarranty_WOcon: '.$e->getMessage());
            return false;
        }
    }

    public function updateVehicleInsurance($ins_data, $wo_id){
        try {
            $data = [
                'customer_id' => $ins_data->customer_id,
                'vehicle_id' => $ins_data->vehicle_id,
                'ins_company_name'=> $ins_data->ins_company_name,
                'policy_no'=> $ins_data->ins_policy_no,
                'expire_date'=> $ins_data->ins_expire_date,
                'phone_number'=> $ins_data->ins_phone_number,
            ];
            $wo_ins_data = VehicleInsurance::findOrFail($ins_data->mod_ins_id);
            $resp = $wo_ins_data->update($data);

            return true;
        }catch (\Exception $e){
            Log::error('updateVehicleInsurance_WOcon: '.$e->getMessage());
            return false;
        }
    }

    public function updateServiceContract($cont_data, $wo_id){
        try {
            $data = [
                'customer_id' => $cont_data->customer_id,
                'vehicle_id' => $cont_data->vehicle_id,
                'con_company_name'=> $cont_data->con_company_name,
                'contract_no'=> $cont_data->contract_no,
                'expire_date'=> $cont_data->cont_expire_date,
                'mileage'=> $cont_data->cont_mileage,
                'phone_number'=> $cont_data->cont_phone_number,
            ];

            $wo_cont_data = VehicleContract::findOrFail($cont_data->mod_con_id);
            $resp = $wo_cont_data->update($data);

            return true;
        }catch (\Exception $e){
            Log::error('updateServiceContract_WOcon: '.$e->getMessage());
            return false;
        }
    }

    public function updateVehicleWarranty($warr_data, $wo_id){
        try {
            $data = [
                'customer_id' => $warr_data->customer_id,
                'vehicle_id' => $warr_data->vehicle_id,
                'war_company_name'=> $warr_data->war_company_name,
                'warranty_no'=> $warr_data->warranty_no,
                'date_of_purchase'=> $warr_data->date_of_purchase,
                'expire_date'=> $warr_data->war_expire_date,
                'phone_number'=> $warr_data->war_phone_number,
            ];
            $wo_warr_data = VehicleWarranty::findOrFail($warr_data->mod_war_id);
            $resp = $wo_warr_data->update($data);

            return true;
        }catch (\Exception $e){
            Log::error('updateVehicleWarranty_WOcon: '.$e->getMessage());
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkOrder  $workOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $resp = WorkOrder::where('id',$request->wo_id)->get();
        $cus_id = $resp[0]->customer_id ?? NULL;

        $deposit_data = DepositSMA::where('company_id', $cus_id)->get();
        $cus_data = CustomerSMA::select('deposit_amount')->where('id', $cus_id)->get();

        $resp_array = array(
            'status' => true,
            'dataCount' => $resp->count(),
            'data' => $resp,
            'depositData' => $deposit_data,
            'depoTot' => isset($cus_data[0]->deposit_amount) ? number_format($cus_data[0]->deposit_amount,2) : "0.00",
        );

        return $this->responseSelectedDataJson(true, $resp_array, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkOrder  $workOrder
     * @return Response
     */
    public function edit($id)
    {
        $wo_tbl_name = (new WorkOrder())->getTable();

        $editData = WorkOrder::select($wo_tbl_name.'.*')->where($wo_tbl_name.'.id',$id)->first();

        $editData['cont_data'] = VehicleContract::where('wo_id', $id)->first();
        $editData['ins_data'] = VehicleInsurance::where('wo_id', $id)->first();
        $editData['war_data'] = VehicleWarranty::where('wo_id', $id)->first();

        return view('work_order/edit', compact('editData'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkOrder  $workOrder
     * @return Response
     */
    public function update(Request $request)
    {
        $validateMsgs = [
            'customer_id.required' => 'The Customer name field is required',
            'vehicle_id.required' => 'The Vehicle field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'customer_id'      => ['required'],
            'vehicle_id'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $wo_id = $request->wo_id;
            $is_insurance = $request->is_insurance=="true"? 1 : 0;
            $is_contract = $request->is_contract=="true"? 1 : 0;
            $is_warranty = $request->is_warranty=="true"? 1 : 0;
            $ins_id = $cont_id = $warr_id = NULL;

            $wo_type = 0;
            if($is_insurance || $is_contract || $is_warranty) $wo_type = 1;


            $data = [
                'customer_id'=> $request->customer_id,
                'vehicle_id'=> $request->vehicle_id,
                'wo_date'=> empty($request->wo_date) ? date('Y-m-d') : $request->wo_date,
                'appointment_id'=> $request->appointment_id,
                'mileage'=> $request->mileage,
                'reported_defect'=> $request->reported_defect,
                'is_warranty'=> $is_warranty,
                'is_contract'=> $is_contract,
                'is_insurance'=> $is_insurance,
                'wo_type'=> $wo_type,
            ];

            $wo_Data = WorkOrder::findOrFail($wo_id);
            $respWo = $wo_Data->update($data);

            $child_state = true;
            if($respWo){
                if($is_insurance) {
                    $insertInsurance = $this->updateVehicleInsurance($request, $wo_id);
                    if(!$insertInsurance) $child_state = false;
                }
                if($is_contract) {
                    $insertContract = $this->updateServiceContract($request, $wo_id);
                    if(!$insertContract) $child_state = false;
                }
                if($is_warranty) {
                    $insertWarranty = $this->updateVehicleWarranty($request, $wo_id);
                    if(!$insertWarranty) $child_state = false;
                }
            }

            if($child_state){
                DB::commit();
                return $this->responseDataJson(true,'Work order updated successfully',200);
            }

            Log::error("WorkorderUpdateError: Some errors in child creation parts.");
            return $this->responseDataJson(false,['Work order did not updated successfully. Please try again.'],500);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("WorkorderUpdateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkOrder  $workOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $wo_id = $request->emp_id;
        $employee = WorkOrder::findOrFail($wo_id);
        $jb_data = JobOrder::where('wo_order_id',$wo_id)->first();

        $active_state = 1;
        if($employee['is_active']) $active_state = 0;

        $dataArray = ['is_active' =>$active_state];

        $resp = $employee->update($dataArray);
        $resp1 = $jb_data->update($dataArray);

        if($active_state)
            $message = "Workorder has been activated!";
        else
            $message = "Workorder has been inactivated!";

        if($resp){

            return $this->responseDataJson(true, $message,200);
        }else{

            return $this->responseDataJson(true, "Something went wrong!",401);
        }
    }

    public function getWorkOrderData(Request $request)
    {
        $wo_tbl = (new WorkOrder())->getTable();
        $vehi_tbl = (new Vehicle())->getTable();
        $vehi_ins = (new VehicleInsurance())->getTable();
        $vehi_con = (new VehicleContract())->getTable();
        $vehi_war = (new VehicleWarranty())->getTable();

        $resp = WorkOrder::select($wo_tbl.'.reported_defect', $wo_tbl.'.customer_id', $wo_tbl.'.vehicle_id', $vehi_tbl.'.vehicle_type_id',
            DB::raw('IF(ISNULL('.$vehi_war.'.war_company_name),"",'.$vehi_war.'.war_company_name) AS war_company_name'), $vehi_war.'.warranty_no', $vehi_war.'.date_of_purchase', $vehi_war.'.expire_date', $vehi_war.'.phone_number',
            DB::raw('IF(ISNULL('.$vehi_con.'.con_company_name),"",'.$vehi_con.'.con_company_name) AS con_company_name'), $vehi_con.'.contract_no', DB::raw($vehi_con.'.expire_date AS con_expire_date'), $vehi_con.'.mileage', DB::raw($vehi_con.'.phone_number AS con_phone_number'),
            DB::raw('IF(ISNULL('.$vehi_ins.'.ins_company_name),"",'.$vehi_ins.'.ins_company_name) AS ins_company_name'), $vehi_ins.'.policy_no', DB::raw($vehi_ins.'.expire_date AS ins_expire_date'), DB::raw($vehi_ins.'.phone_number AS ins_phone_number'),
        )
            ->leftjoin($vehi_tbl, $vehi_tbl.'.id', '=', $wo_tbl.'.vehicle_id')
            ->leftjoin($vehi_ins, $vehi_ins.'.wo_id', '=', $wo_tbl.'.id')
            ->leftjoin($vehi_con, $vehi_con.'.wo_id', '=', $wo_tbl.'.id')
            ->leftjoin($vehi_war, $vehi_war.'.wo_id', '=', $wo_tbl.'.id')
            ->where($wo_tbl.'.id',$request->item_id)
            ->get();

        return $this->responseSelectedDataJson(true, $resp, 200);
    }

    public function getWOSerialNo(Request $request): \Illuminate\Http\JsonResponse
    {
        return FormSerial::getSerialData($request);
    }

    public function getPriceListForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = PriceGroupSMA::where('name', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = PriceGroupSMA::where('name', 'LIKE', '%' . $search_term . '%')->get();

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
            Log::error("select2PriceGroup: ".$e->getMessage());
        }
    }

    public function getPriceByIdForDrop(Request $request){

        $state_data = PriceGroupSMA::where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->name);
        }

        return json_encode($data);
    }

    public function getCountryListForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = Country::where('is_active',1)->where('name_en', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = Country::where('is_active',1)->where('name_en', 'LIKE', '%' . $search_term . '%')->get();

            $count = $item_list_count->count();
            $endCount = $offset + $res_count;
            $morePages = $endCount < $count;

            $data = array();
            foreach ($item_list as $row) {
                $data[] = array("id" => $row->id, "text" => $row->name_en);
            }

            return array(
                "results" => $data,
                "pagination" => array("more" => $morePages)
            );

        }catch (\Exception $e){
            Log::error("select2Country: ".$e->getMessage());
        }
    }

    public function getCountryByIdForDrop(Request $request){

        $state_data = Country::where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->name_en);
        }

        return json_encode($data);
    }

    public function getStateListForDrop(Request $request){
        $page = $request->page;
        $res_count = $request->resCount;
        $search_term = $request->searchTerm;

        $offset = ($page - 1) * $res_count;

        $cities_list = DB::table((new State())->getTable())->where('name_en','LIKE','%'.$search_term.'%')->offset($offset)->limit($res_count)->get();
        $cities_list_count = DB::table((new State())->getTable())->where('name_en','LIKE','%'.$search_term.'%')->get();

        $count = $cities_list_count->count();
        $endCount = $offset + $res_count;
        $morePages = $endCount < $count;

        $data = array();
        foreach($cities_list as $row){

            $data[] = array("id" => $row->id, "text" => $row->name_en);
        }

        return array(
            "results" => $data,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }

    public function getStateByIdForDrop(Request $request){

        $state_data = DB::table((new State())->getTable())->where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->name_en);
        }

        return json_encode($data);
    }

    public function getCityListForDrop(Request $request){
        $page = $request->page;
        $res_count = $request->resCount;
        $search_term = $request->searchTerm;

        $offset = ($page - 1) * $res_count;

        $cities_list = DB::table((new City())->getTable())->where('is_active',1)->where('name_en','LIKE','%'.$search_term.'%')->offset($offset)->limit($res_count)->get();
        $cities_list_count = DB::table((new City())->getTable())->where('is_active',1)->where('name_en','LIKE','%'.$search_term.'%')->get();

        $count = $cities_list_count->count();
        $endCount = $offset + $res_count;
        $morePages = $endCount < $count;

        $data = array();
        foreach($cities_list as $row){

            $data[] = array("id" => $row->id, "text" => $row->name_en);
        }

        return array(
            "results" => $data,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }

    public function getCityByIdForDrop(Request $request){

        $state_data = DB::table((new City())->getTable())->where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->name_en);
        }

        return json_encode($data);
    }

    public function getCitiesByStateIdForDrop(Request $request){

        $page = $request->page;
        $res_count = $request->resCount;
        $search_term = $request->searchTerm;
        $item_id = $request->vendor_id;

        $offset = ($page - 1) * $res_count;

        $cities_list = DB::table((new City())->getTable())->where('is_active',1)->where('state_id',$item_id)->where('name_en','LIKE','%'.$search_term.'%')->offset($offset)->limit($res_count)->get();
        $cities_list_count = DB::table((new City())->getTable())->where('is_active',1)->where('state_id',$item_id)->where('name_en','LIKE','%'.$search_term.'%')->get();

        $count = $cities_list_count->count();
        $endCount = $offset + $res_count;
        $morePages = $endCount < $count;

        $data = array();
        foreach($cities_list as $row){

            $data[] = array("id" => $row->id, "text" => $row->name_en);
        }

        return array(
            "results" => $data,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }

    public function getWOSerialListForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = WorkOrder::where('is_active',1)->where('is_loaded_jo',0)->where('serial_no', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = WorkOrder::where('is_active',1)->where('is_loaded_jo',0)->where('serial_no', 'LIKE', '%' . $search_term . '%')->get();

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
            Log::error("select2WOSerial: ".$e->getMessage());
        }
    }

    public function getWOSerialByIdForDrop(Request $request){

        $state_data = WorkOrder::where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->serial_no);
        }

        return json_encode($data);
    }

    public function transferToEstimate(Request $request): \Illuminate\Http\JsonResponse
    {
        DB::beginTransaction();
        try {

            $wo_id = $request->emp_id;
            $employee = WorkOrder::findOrFail($wo_id);
            $jo_data = JobOrder::where('wo_order_id', $wo_id)->get();

            foreach ($jo_data as $jo_item) {

                $jo_tsk_data = JobOrderTask::where('jo_id', $jo_item->id)->get();

                foreach ($jo_tsk_data as $jo_tsk_it) {

                    JobOrderTask::where('id', $jo_tsk_it->id)->update(['is_estimate' => 1]);
                }
                JobOrder::where('id', $jo_item->id)->update(['is_estimate' => 1]);
            }

            $dataArray = ['wo_type' => 2];
            $resp = $employee->update($dataArray);

            $message = "Workorder has been successfully transferred!";
            if ($resp) {

                DB::commit();
                return $this->responseDataJson(true, $message, 200);
            } else {
                DB::rollBack();
                return $this->responseDataJson(true, ["Something went wrong!"], 401);
            }
        }catch(\Exception $e){
            DB::rollBack();
            Log::error("TransferToEstimateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

}
