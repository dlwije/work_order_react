<?php

namespace App\Http\Controllers;

use App\Models\CustomerSMA;
use App\Models\Vehicle;
use App\Models\VehicleType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class VehicleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('permission:vehicle-view', ['only' => ['index', 'show', 'getDataTableList']]);
//        $this->middleware('permission:vehicle-edit', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:vehicle-add', ['only' => ['create', 'store']]);
//        $this->middleware('permission:vehicle-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('vehicle/vehicle_list');
    }

    public function getDataTableList(Request $request)
    {
        if ($request->ajax()) {

            $vehi_tbl_nm = (new Vehicle())->getTable();
            $vehi_type_tbl_nm = (new VehicleType())->getTable();
            $cus_tbl_nm = (new CustomerSMA())->getTable();

            $data = Vehicle::active($vehi_tbl_nm)
                ->select($vehi_tbl_nm . '.*', $vehi_type_tbl_nm . '.type_name', $cus_tbl_nm . '.name')
                ->leftJoin($vehi_type_tbl_nm, $vehi_type_tbl_nm . '.id', '=', $vehi_tbl_nm . '.vehicle_type_id')
                ->leftJoin($cus_tbl_nm, $cus_tbl_nm . '.id', '=', $vehi_tbl_nm . '.customer_id');

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
//                        if(auth()->user()->hasPermissionTo('vehicle-view') )
//                            $actionBtn .= '<span onclick="vehicleList.ivm.showDetails('.$row->id.')" class="btn btn-success btn-sm mr-1"><i class="fas fa-eye"></i></span>';
                        if (auth()->user()->hasPermissionTo('vehicle-edit'))
                            $actionBtn .= '<span onclick="vehicleList.ivm.editDetails(' . $row->id . ')" class="btn btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Edit vehicle."><i class="fas fa-edit"></i></span>';
                        if (auth()->user()->hasPermissionTo('vehicle-delete'))
                            $actionBtn .= '<span onclick="vehicleList.ivm.deleteRecord(' . $row->id . ')" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete vehicle."><i class="fas fa-trash-alt"></i></span>';

                        return $actionBtn;
                    })
                    ->addColumn('vehiTypeState', function ($row) {

                        if ($row->vehicle_type_state)
                            $actionBtn = "Motor Home";
                        else
                            $actionBtn = "Trailer";

                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('type_name', function ($query, $keyword) {
                        $query->whereRaw((new VehicleType())->getTable() . ".type_name like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('name', function ($query, $keyword) {
                        $query->whereRaw((new CustomerSMA())->getTable() . ".name like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getVehicleError:" . $e->getMessage());
                $this->responseDataJson(false, ['Somethings went wrong'], 500);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validateMsgs = [
            'customer_id.required' => 'The Customer name field is required',
            'vehicle_type_id.required' => 'The Vehicle type field is required',
            'engine_no.required' => 'The Vehicle VIN number field is required',
        ];

        $validateRequest = Validator::make($request->all(), [
            'customer_id' => ['required'],
            'vehicle_type_id' => ['required'],
            'engine_no' => ['required'],
        ], $validateMsgs);

        if ($validateRequest->fails()) {

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        try {

            $data = [
                'customer_id' => $request->customer_id,
                'make_year' => $request->make_year,
                'vehicle_make' => $request->vehicle_make,
                'vehicle_model' => $request->vehicle_model,
                'engine_no' => $request->engine_no,
                'vehicle_type_id' => $request->vehicle_type_id,
                'vehicle_type_state' => $request->vehicle_type_state,
                'sub_model' => $request->sub_model,
                'body' => $request->body,
                'color' => $request->color,
                'vehicle_no' => $request->vehicle_no,
                'odometer' => $request->odometer,
                'unit_number' => $request->unit_number,
            ];

            $vehi_data = Vehicle::create($data);

            $data = [
                'inserted_id' => $vehi_data->id
            ];

            return $this->responseDataJson(true, 'Vehicle created successfully', 200, $data);
        } catch (\Exception $e) {

            Log::error("VehicleCreateError:" . $e->getMessage());
            return $this->responseDataJson(false, ['Somethings went wrong'], 500);
        }
    }

    public function show(Request $request)
    {
        $resp = Vehicle::where('id', $request->item_id)->select('vehicle_type_state')->get();

        return $this->responseSelectedDataJson(true, $resp, 200);
    }

    public function getSelectedData(Request $request)
    {
        $resp = Vehicle::where('id', $request->item_id)->select('*')->get();

        return $this->responseSelectedDataJson(true, $resp, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param \App\Models\CustomerSMA $customerSMA
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $validateMsgs = [
            'customer_id.required' => 'The Customer name field is required',
            'vehicle_type_id.required' => 'The Vehicle type field is required',
            'engine_no.required' => 'The Vehicle VIN number field is required',
        ];

        $validateRequest = Validator::make($request->all(), [
            'customer_id' => ['required'],
            'vehicle_type_id' => ['required'],
            'engine_no' => ['required'],
        ], $validateMsgs);

        if ($validateRequest->fails()) {

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        try {

            $data = [
                'customer_id' => $request->customer_id,
                'make_year' => $request->make_year,
                'vehicle_make' => $request->vehicle_make,
                'vehicle_model' => $request->vehicle_model,
                'engine_no' => $request->engine_no,
                'vehicle_type_id' => $request->vehicle_type_id,
                'vehicle_type_state' => $request->vehicle_type_state,
                'sub_model' => $request->sub_model,
                'body' => $request->body,
                'color' => $request->color,
                'vehicle_no' => $request->vehicle_no,
                'odometer' => $request->odometer,
                'unit_number' => $request->unit_number,
            ];

            $vehi_data = Vehicle::where('id', $request->item_id)->update($data);

            $data = [
                'updated_id' => $request->item_id
            ];

            return $this->responseDataJson(true, 'Vehicle updated successfully', 200, $data);
        } catch (\Exception $e) {

            Log::error("VehicleUpdateError:" . $e->getMessage());
            return $this->responseDataJson(false, ['Somethings went wrong'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CustomerSMA $customerSMA
     * @return JsonResponse
     */
    public function destroy(Request $request)
    {
        $employee = Vehicle::findOrFail($request->item_id);

        $active_state = 1;
        if ($employee['is_active']) $active_state = 0;

        $dataArray = ['is_active' => $active_state];

        $resp = $employee->update($dataArray);

        if ($active_state)
            $message = "Vehicle has been activated!";
        else
            $message = "Vehicle has been inactivated!";

        if ($resp) {

            return $this->responseDataJson(true, $message, 200);
        } else {

            return $this->responseDataJson(true, "Something went wrong!", 401);
        }

        /*$resp = Vehicle::where('id',$id)->delete();
        if($resp){ return $this->responseDataJson(true,'Vehicle deleted successfully',200); }
        else{ $resp_msg = ['status' => false,['message' => "Something went wrong!"]]; return response()->json($resp_msg,401); }*/
    }

    public function getVehicleList(Request $request): array
    {
        $page = $request->page;
        $res_count = $request->resCount;
        $search_term = $request->searchTerm;

        $offset = ($page - 1) * $res_count;

        $item_list = Vehicle::active()->where('vehicle_no', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
        $item_list_count = Vehicle::active()->where('vehicle_no', 'LIKE', '%' . $search_term . '%')->get();

        $count = $item_list_count->count();
        $endCount = $offset + $res_count;
        $morePages = $endCount < $count;

        $data = array();
        foreach ($item_list as $row) {
            $data[] = array("id" => $row->id, "text" => $row->vehicle_no);
        }

        return array(
            "results" => $data,
            "pagination" => array("more" => $morePages)
        );
    }

    public function getVehicleByIdForDrop(Request $request)
    {

        $state_data = Vehicle::where('id', $request->id)->get();

        $data = array();
        foreach ($state_data as $row) {

            $data[] = array("id" => $row->id, "text" => $row->vehicle_no);
        }

        return json_encode($data);
    }

    public function getCustomerVehicleList(Request $request): array
    {
        $page = $request->page;
        $res_count = $request->resCount;
        $search_term = $request->searchTerm;
        $item_id = $request->vendor_id;

        $offset = ($page - 1) * $res_count;

        if (!empty($search_term)) {
            $item_list = Vehicle::active()->where('customer_id', $item_id)
                ->where('vehicle_no', 'LIKE', '%' . $search_term . '%')
                ->offset($offset)->limit($res_count)->get();
            $item_list_count = Vehicle::where('customer_id', $item_id)
                ->where('vehicle_no', 'LIKE', '%' . $search_term . '%')->get();
        }else{
            $item_list = Vehicle::active()->where('customer_id', $item_id)
                ->offset($offset)->limit($res_count)->get();
            $item_list_count = Vehicle::where('customer_id', $item_id)->get();
        }

        $count = $item_list_count->count();
        $endCount = $offset + $res_count;
        $morePages = $endCount < $count;

        $data = array();
        foreach ($item_list as $row) {
            $data[] = array("id" => $row->id, "text" => $row->vehicle_no);
        }

        return array(
            "results" => $data,
            "pagination" => array("more" => $morePages)
        );
    }

    public function getCustomerVehicleByIdForDrop(Request $request)
    {

        $state_data = Vehicle::where('id', $request->id)->get();

        $data = array();
        foreach ($state_data as $row) {

            $data[] = array("id" => $row->id, "text" => $row->vehicle_no);
        }

        return json_encode($data);
    }

    public function getVehicleTypeList(Request $request): array
    {
        $page = $request->page;
        $res_count = $request->resCount;
        $search_term = $request->searchTerm;

        $offset = ($page - 1) * $res_count;

        $item_list = VehicleType::where('is_active', 1)->where('type_name', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
        $item_list_count = VehicleType::where('is_active', 1)->where('type_name', 'LIKE', '%' . $search_term . '%')->get();

        $count = $item_list_count->count();
        $endCount = $offset + $res_count;
        $morePages = $endCount < $count;

        $data = array();
        foreach ($item_list as $row) {
            $data[] = array("id" => $row->id, "text" => $row->type_name);
        }

        return array(
            "results" => $data,
            "pagination" => array("more" => $morePages)
        );
    }

    public function getVehicleTypeByIdForDrop(Request $request)
    {

        $state_data = VehicleType::where('id', $request->id)->get();

        $data = array();
        foreach ($state_data as $row) {

            $data[] = array("id" => $row->id, "text" => $row->type_name);
        }

        return json_encode($data);
    }

    /** Vehicle type functions start here... **/

    public function vehicleTypeView()
    {
        return view('vehicle/vehicle_type_list');
    }

    public function getVehicleTypeDataTableList(Request $request)
    {
        if ($request->ajax()) {

            $data = VehicleType::query()->where('is_active', 1);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
//                        if(auth()->user()->hasPermissionTo('labortask-view') )
//                            $actionBtn .= '<span onclick="laborTaskList.ivm.editDetails('.$row->id.')" class="btn btn-success btn-sm mr-1"><i class="fas fa-eye"></i></span>';
                        if (auth()->user()->hasPermissionTo('vehicle-type-edit'))
                            $actionBtn .= '<span onclick="vehicleTypeList.ivm.editDetails(' . $row->id . ')" class="btn btn-primary btn-sm mr-1"><i class="fas fa-edit"></i></span>';
                        if (auth()->user()->hasPermissionTo('vehicle-type-delete'))
                            $actionBtn .= '<span onclick="vehicleTypeList.ivm.deleteRecord(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getVehicleTypeError:" . $e->getMessage());
                $this->responseDataJson(false, ['Somethings went wrong'], 500);
            }
        }
    }

    public function storeType(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'tsk_type_name.required' => 'The Vehicle type name field is required',
        ];

        $validateRequest = Validator::make($request->all(), [
            'tsk_type_name' => ['required'],
        ], $validateMsgs);

        if ($validateRequest->fails()) {

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $data = ['type_name' => $request->tsk_type_name,];
            $wo_Data = VehicleType::create($data);

            DB::commit();

            return $this->responseDataJson(true, 'Vehicle Type created successfully', 200);
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error("VehicleTypeCreateError:" . $e->getMessage());
            return $this->responseDataJson(false, ['Somethings went wrong'], 500);
        }
    }

    public function getVehicleTypeSelectedData(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = ($request->item_id);
        $resp = VehicleType::where('id', '=', $id)->get();
        $resp_array = array("data" => $resp, "count" => $resp->count());

        return response()->json($resp_array);
    }

    public function updateType(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'tsk_type_name.required' => 'The Vehicle type name field is required',
        ];

        $validateRequest = Validator::make($request->all(), [
            'tsk_type_name' => ['required'],
        ], $validateMsgs);

        if ($validateRequest->fails()) {

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $wo_id = $request->item_id;
            $data = ['type_name' => $request->tsk_type_name,];
            $wo_Data = VehicleType::where('id', $wo_id)->update($data);

            DB::commit();

            return $this->responseDataJson(true, 'Vehicle type updated successfully', 200);
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error("VehicleTypeUpdateError:" . $e->getMessage());
            return $this->responseDataJson(false, ['Somethings went wrong'], 500);
        }
    }

    public function destroyType(Request $request): \Illuminate\Http\JsonResponse
    {
        $employee = TaskType::findOrFail($request->item_id);

        $active_state = 1;
        $resp = $employee->delete();

        if ($active_state)
            $message = "Labor task type has been deleted!";
        else
            $message = "Labor task category has been inactivated!";

        if ($resp) {

            return $this->responseDataJson(true, $message, 200);
        } else {

            return $this->responseDataJson(true, "Something went wrong!", 401);
        }
    }
}
