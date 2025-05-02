<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\CustomerGroupSMA;
use App\Models\CustomerSMA;
use App\Models\DepositSMA;
use App\Models\PriceGroupSMA;
use App\Models\State;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CustomerController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:customer-list', ['only' =>['index','show', 'getDataTableList']]);
        $this->middleware('permission:customer-view', ['only' =>['show']]);
        $this->middleware('permission:customer-edit', ['only' =>['edit', 'update']]);
        $this->middleware('permission:customer-add', ['only' =>['create', 'store']]);
        $this->middleware('permission:customer-delete', ['only' =>['destroy']]);

        $this->middleware('permission:deposit', ['only' =>['addDeposit']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|View
     */
    public function index()
    {
        return view('customer/customer_list');
    }

    public function getDataTableList(Request $request){

        if ($request->ajax()) {
            $data = CustomerSMA::query()->where('group_name', 'customer');

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->editColumn('deposit_amount', function ($user) {
                        return number_format($user->deposit_amount,2);
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $actionBtn = '';
                        if(auth()->user()->hasPermissionTo('customer-deposit-view') )
                            $actionBtn .= '<span onclick="laborTaskList.ivm.showDeposits('.$row->id.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="See customer deposit list."><i class="fas fa-dollar-sign"></i></span>';
                        if(auth()->user()->hasPermissionTo('customer-view') )
                            $actionBtn .= '<span onclick="laborTaskList.ivm.showDetails('.$row->id.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="See customer."><i class="fas fa-eye"></i></span>';
                        if(auth()->user()->hasPermissionTo('customer-edit') )
                            $actionBtn .= '<span onclick="laborTaskList.ivm.editDetails('.$row->id.')" class="btn btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Edit customer."><i class="fas fa-edit"></i></span>';
                        if(auth()->user()->hasPermissionTo('customer-delete') )
                            $actionBtn .= '<span onclick="laborTaskList.ivm.deleteRecord(' . $row->id . ')" class="edit btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete customer."><i class="fas fa-trash-alt"></i></span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {
                Log::error("getCustomerTableError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
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
            'email.required' => 'The Email address is required',
            'email.email' => 'The Email should be a valid email address',
            'email.unique' => 'The Email address already used by other user.',
            'first_name.required' => 'The First name is required',
            'phone.required' => 'The Phone number is required',
            'city_id.required' => 'The City field is required',
            'address.required' => 'The Address field is required',
//            'company.required' => 'The Company field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'email'      => ['required', 'email', 'unique:sma_companies,email'],
            'first_name'      => ['required'],
            'address'      => ['required'],
            'phone'      => ['required'],
            'city_id'      => ['required'],
//            'company'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        try {
            $cus_grp_id = !empty($request->customer_group_id)? $request->customer_group_id : 1;
            $prc_grp_id = !empty($request->price_group_id) ? $request->price_group_id : 1;
            $country_id = !empty($request->country_id) ? $request->country_id : 1;
            $state_id = !empty($request->state_id) ? $request->state_id : 1;

            $cus_grp_name = $this->getCustomerGroupById($cus_grp_id);
            $prc_grp_name = $this->getPriceGroupById($prc_grp_id);
            $country_name = $this->getCountryById($country_id);
            $state_name = $this->getStateById($state_id);
            $city_name = $this->getCityById($request->city_id);

            $data = [
                'group_id'=>3,
                'group_name'=>'customer',
                'customer_group_id'=> $cus_grp_id,
                'customer_group_name'=> $cus_grp_name['status'] ? $cus_grp_name['data']['name'] : NULL,
                'price_group_id'=> $prc_grp_id,
                'price_group_name'=> $prc_grp_name['status'] ? $prc_grp_name['data']['name'] : NULL,
                'company'=> !empty($request->company)? $request->company : $request->first_name.' '.$request->last_name,
                'postal_code'=> $request->postal_code,
                'name'=> $request->first_name.' '.$request->last_name,
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name,
                'spouse_first_name'=> $request->spouse_first_name,
                'spouse_last_name'=> $request->spouse_last_name,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'address'=> $request->address,
                'country'=> $country_name['status'] ? $country_name['data']['name_en'] : NULL,
                'country_id'=> $country_id,
                'state'=> $state_name['status'] ? $state_name['data']['name_en'] : NULL,
                'state_id'=> $state_id,
                'city'=> $city_name['status'] ? $city_name['data']['name_en'] : NULL,
                'city_id'=> $request->city_id,
                /*'vat_no','cf1','cf2','cf3','cf4','cf5','cf6','invoice_footer','payment_term','logo','award_points','deposit_amount',*/

            ];

            $ins_data = CustomerSMA::create($data);

            $data = [
                'inserted_id' => $ins_data->id
            ];
            return $this->responseDataJson(true,'Customer created successfully',200, $data);
        }catch (\Exception $e){

            Log::error("CustomerCreateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function getCustomerGroupById($id){

        $respData = CustomerGroupSMA::where('id', $id)->first();
        if($respData->count() > 0){ $resp = ['status' => true,'data' => $respData]; } else { $resp = ['status' => false, 'data' => []]; }
        return $resp;
    }

    public function getPriceGroupById($id){

        $respData = PriceGroupSMA::where('id', $id)->first();
        if($respData->count() > 0){ $resp = ['status' => true,'data' => $respData]; } else { $resp = ['status' => false, 'data' => []]; }
        return $resp;
    }

    public function getCountryById($id){

        $respData = Country::where('id', $id)->first();
        if($respData->count() > 0){ $resp = ['status' => true,'data' => $respData]; } else { $resp = ['status' => false, 'data' => []]; }
        return $resp;
    }

    public function getStateById($id){

        $respData = State::where('id', $id)->first();
        if($respData->count() > 0){ $resp = ['status' => true,'data' => $respData]; } else { $resp = ['status' => false, 'data' => []]; }
        return $resp;
    }

    public function getCityById($id){

        $respData = City::where('id', $id)->first();
        if($respData->count() > 0){ $resp = ['status' => true,'data' => $respData]; } else { $resp = ['status' => false, 'data' => []]; }
        return $resp;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerSMA  $customerSMA
     * @return JsonResponse
     */
    public function getSelectedData(Request $request)
    {
        $resp = CustomerSMA::where('id',$request->item_id)->select('*')->get();
        $cus_vehi_list = Vehicle::where('is_active',1)->where('customer_id',$request->item_id)->get();

        $d_arr = [
            'cusData' => $resp,
            'cusVehiData' => $cus_vehi_list
        ];

        return $this->responseSelectedDataJson(true, $d_arr, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \App\Models\CustomerSMA  $customerSMA
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        $validateMsgs = [
            'email.required' => 'The Email address is required',
            'email.email' => 'The Email should be a valid email address',
            'email.unique' => 'The Email address already used by other user.',
            'first_name.required' => 'The First name is required',
            'phone.required' => 'The Phone number is required',
            'city_id.required' => 'The City field is required',
            'address.required' => 'The Address field is required',
            'company.required' => 'The Company field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'email'      => ['required', 'email', 'unique:sma_companies,email,'.$request->item_id.',id'],
            'first_name'      => ['required'],
            'address'      => ['required'],
            'phone'      => ['required'],
            'city_id'      => ['required'],
            'company'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        try {
            $cus_grp_id = !empty($request->customer_group_id)? $request->customer_group_id : 1;
            $prc_grp_id = !empty($request->price_group_id) ? $request->price_group_id : 1;
            $country_id = !empty($request->country_id) ? $request->country_id : 1;
            $state_id = !empty($request->state_id) ? $request->state_id : 1;

            $cus_grp_name = $this->getCustomerGroupById($cus_grp_id);
            $prc_grp_name = $this->getPriceGroupById($prc_grp_id);
            $country_name = $this->getCountryById($country_id);
            $state_name = $this->getStateById($state_id);
            $city_name = $this->getCityById($request->city_id);

            $data = [
                'group_id'=>3,
                'group_name'=>'customer',
                'customer_group_id'=> $cus_grp_id,
                'customer_group_name'=> $cus_grp_name['status'] ? $cus_grp_name['data']['name'] : NULL,
                'price_group_id'=> $prc_grp_id,
                'price_group_name'=> $prc_grp_name['status'] ? $prc_grp_name['data']['name'] : NULL,
                'company'=> $request->company,
                'postal_code'=> $request->postal_code,
                'name'=> $request->first_name.' '.$request->last_name,
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name,
                'spouse_first_name'=> $request->spouse_first_name,
                'spouse_last_name'=> $request->spouse_last_name,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'address'=> $request->address,
                'country'=> $country_name['status'] ? $country_name['data']['name_en'] : NULL,
                'country_id'=> $country_id,
                'state'=> $state_name['status'] ? $state_name['data']['name_en'] : NULL,
                'state_id'=> $state_id,
                'city'=> $city_name['status'] ? $city_name['data']['name_en'] : NULL,
                'city_id'=> $request->city_id,
                /*'vat_no','cf1','cf2','cf3','cf4','cf5','cf6','invoice_footer','payment_term','logo','award_points','deposit_amount',*/

            ];

            $ins_data = CustomerSMA::where('id', $request->item_id)->update($data);

            $data = [
                'updated_id' => $request->item_id
            ];
            return $this->responseDataJson(true,'Customer updated successfully',200, $data);
        }catch (\Exception $e){

            Log::error("CustomerUpdateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerSMA  $customerSMA
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $resp = CustomerSMA::where('id',$id)->delete();

        if($resp){

            return $this->responseDataJson(true,'Customer deleted successfully',200);
        }else{

            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }

    public function getCustomerListForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = CustomerSMA::where('group_name', 'customer')->where('name', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = CustomerSMA::where('group_name', 'customer')->where('name', 'LIKE', '%' . $search_term . '%')->get();

            $count = $item_list_count->count();
            $endCount = $offset + $res_count;
            $morePages = $endCount < $count;

            $data = array();
            foreach ($item_list as $row) {
                $data[] = array("id" => $row->id, "old_id" => $row->old_db_id, "text" => $row->name);
            }

            return array(
                "results" => $data,
                "pagination" => array("more" => $morePages)
            );

        }catch (\Exception $e){
            Log::error("select2Customer: ".$e->getMessage());
        }
    }

    public function getCustomerByIdForDrop(Request $request){

        $state_data = CustomerSMA::where('id',$request->id)->get();

        if ($state_data->isEmpty()) {
            // If $state_data is empty, add the where clause for 'id'
            $state_data = CustomerSMA::query()->where('id', $request->id)->get();
        }

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->name);
        }

        return json_encode($data);
    }

    public function getCustomerGroupListForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = CustomerGroupSMA::where('name', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = CustomerGroupSMA::where('name', 'LIKE', '%' . $search_term . '%')->get();

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
            Log::error("select2CustomerGroup: ".$e->getMessage());
        }
    }

    public function getCustomerGroupByIdForDrop(Request $request){

        $state_data = CustomerGroupSMA::where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->name);
        }

        return json_encode($data);
    }

    public function addDeposit(Request $request): JsonResponse
    {
        $validateMsgs = [
            'company_id.required' => 'The Customer field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'company_id'      => ['required'],
            'amount'      => ['required', 'numeric', 'min:1'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $cus_data = CustomerSMA::where('id', $request->company_id)->get();

            $data = [
                'date'=>empty($request->date) ? $request->date : date('Y-m-d H:i'),
                'company_id'=> $request->company_id,
                'amount'=> (double)str_replace(',','',$request->amount),
                'paid_by'=> $request->paid_by,
                'note'=> $request->note,
                'sys_type'=> 'W',
                'created_by'=> auth()->user()->id,
                'updated_by'=> 0,
            ];
            $resp = DepositSMA::create($data);

            if($cus_data->count() > 0 ){
                $depo_amnt = $cus_data[0]->deposit_amount ?? 0;

                $nw_depo_amnt = (double)$depo_amnt + (double)str_replace(',','',$request->amount);
                CustomerSMA::where('id', $request->company_id)->update(['deposit_amount' => $nw_depo_amnt]);
            }

            DB::commit();
            return $this->responseDataJson(true,'Amount deposited successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("CustomerDepositCreateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function getCustomerDepositList(Request $request)
    {
        $cus_tbl = (new CustomerSMA())->getTable();
        $cus_depo_tbl = (new DepositSMA())->getTable();
        $user_tbl = (new User())->getTable();

//        $depo_data = DepositSMA::where('company_id', $request->cus_id);

        $deposit_data = DepositSMA::select($cus_depo_tbl.'.*', $cus_tbl.'.name', DB::raw($user_tbl.'.name AS uName'))
            ->leftjoin($cus_tbl, $cus_tbl.'.id','=',$cus_depo_tbl.'.company_id')
            ->leftjoin($user_tbl, $user_tbl.'.id','=',$cus_depo_tbl.'.created_by')
            ->where('company_id', $request->cus_id)->get();

        return $this->responseSelectedDataJson(true, $deposit_data, 200);
    }

    public function customerDepositDestroy(Request $request)
    {
        $depo_data = DepositSMA::where('id',$request->item_id)->first();
        $cus_data = CustomerSMA::where('id', $depo_data->company_id)->get();

        /*echo "<pre>";
        print_r($depo_data);
        echo "<br><br><br><br><br><br><br><br><br><br>";
        print_r($cus_data);*/

        if($cus_data->count() > 0 ){
            $depo_amnt = $cus_data[0]->deposit_amount ?? 0;

            $nw_depo_amnt = $depo_amnt - str_replace(',','',$depo_data->amount);
            if(CustomerSMA::where('id', $depo_data->company_id)->update(['deposit_amount' => $nw_depo_amnt]))
                $resp = DepositSMA::where('id',$request->item_id)->delete();
            else
                return response()->json(['status' => false,['message' => "Something went wrong!"]],500);
        }else{
            $resp_msg = ['status' => false,['message' => "Customer not found!"]];
            return response()->json($resp_msg,404);
        }

        if($resp){

            return $this->responseDataJson(true,'Customer deposit record deleted successfully',200);
        }else{

            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }

    public function customerDepositView(){
        return view('payment/customer_deposit_list');
    }

    public function getCusDepositDataTableList(Request $request){
    if ($request->ajax()) {
        $data = DepositSMA::query()
            ->select((new DepositSMA())->getTable().'.*',(new CustomerSMA())->getTable().'.name')
            ->leftJoin((new CustomerSMA())->getTable(),(new CustomerSMA())->getTable().'.id','=', (new DepositSMA())->getTable().'.company_id');

        try {
            return Datatables::of($data)
                ->editColumn('updated_at', function ($user) {
                    return $user->updated_at->format('Y-m-d');
                })
                ->editColumn('amount', function ($user) {
                    return number_format($user->amount,2);
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $actionBtn = '';
                    /*if(auth()->user()->hasPermissionTo('customer-deposit-view') )
                        $actionBtn .= '<span onclick="laborTaskList.ivm.showDeposits('.$row->id.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="See customer deposit list."><i class="fas fa-dollar-sign"></i></span>';
                    if(auth()->user()->hasPermissionTo('customer-view') )
                        $actionBtn .= '<span onclick="laborTaskList.ivm.showDetails('.$row->id.')" class="btn btn-success btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="See customer."><i class="fas fa-eye"></i></span>';
                    if(auth()->user()->hasPermissionTo('customer-edit') )
                        $actionBtn .= '<span onclick="laborTaskList.ivm.editDetails('.$row->id.')" class="btn btn-primary btn-sm mr-1" data-toggle="tooltip" data-placement="top" title="Edit customer."><i class="fas fa-edit"></i></span>'*/;
                    if(auth()->user()->hasPermissionTo('customer-delete') )
                        $actionBtn .= '<span onclick="vehicleTypeList.ivm.deleteRecord(' . $row->id . ')" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Delete customer deposit."><i class="fas fa-trash-alt"></i></span>';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->removeColumn('created_at')
                ->filterColumn('updated_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->whereRaw((new CustomerSMA())->getTable().".name like ?", ["%$keyword%"]);
                })
                ->make(true);
        } catch (\Exception $e) {
            Log::error("getCustomerDepositTableError:".$e->getMessage());
            $this->responseDataJson(false,['Somethings went wrong'],500);
        }
    }
}

}
