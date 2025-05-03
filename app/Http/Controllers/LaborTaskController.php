<?php

namespace App\Http\Controllers;

use App\Imports\LaborTaskImport;
use App\Models\LaborTask;
use App\Models\TaskCategory;
use App\Models\TaskSource;
use App\Models\TaskType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Excel;

class LaborTaskController extends Controller
{
    private $dTablePageType;
    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('permission:labortask-view', ['only' =>['index','show', 'getDataTableList']]);
//        $this->middleware('permission:labortask-edit', ['only' =>['edit', 'update']]);
//        $this->middleware('permission:labortask-add', ['only' =>['create', 'store']]);
//        $this->middleware('permission:labortask-delete', ['only' =>['destroy']]);
//        $this->middleware('permission:labortask-excel-import', ['only' =>['excelImport']]);
//
//        $this->middleware('permission:task-category-view', ['only' =>['taskCategoryView', 'getTaskCategoryDataTableList']]);
//        $this->middleware('permission:task-category-edit', ['only' =>['getTaskCategorySelectedData', 'updateCategory']]);
//        $this->middleware('permission:task-category-add', ['only' =>['storeCategory']]);
//        $this->middleware('permission:task-category-delete', ['only' =>['destroyCategory']]);
//
//        $this->middleware('permission:task-source-view', ['only' =>['taskSourceView', 'getTaskSourceDataTableList']]);
//        $this->middleware('permission:task-source-edit', ['only' =>['getTaskSourceSelectedData', 'updateSource']]);
//        $this->middleware('permission:task-source-add', ['only' =>['storeSource', 'destroySource']]);
//        $this->middleware('permission:task-source-delete', ['only' =>['destroySource']]);
//
//        $this->middleware('permission:task-type-view', ['only' =>['taskTypeView', 'getTaskTypeDataTableList']]);
//        $this->middleware('permission:task-type-edit', ['only' =>['getTaskTypeSelectedData', 'updateType']]);
//        $this->middleware('permission:task-type-add', ['only' =>['storeType', 'destroyType']]);
//        $this->middleware('permission:task-type-delete', ['only' =>['destroyType']]);
    }

    public function index(){
        return view('labor_task/labortask_list');
    }

    public function getDataTableList(Request $request){
        if ($request->ajax()) {

            $this->dTablePageType = $request->page_type;
            $lbr_tsk_tbl = (new LaborTask())->getTable();
            $lbr_tsk_cgry = (new TaskCategory())->getTable();
            $lbr_tsk_src = (new TaskSource())->getTable();
            $lbr_tsk_typ = (new TaskType())->getTable();

            $data = LaborTask::query()
                ->select($lbr_tsk_tbl.'.*', $lbr_tsk_cgry.'.task_category_name', $lbr_tsk_src.'.task_source_name', $lbr_tsk_typ.'.task_type_name')
                ->leftJoin($lbr_tsk_cgry,$lbr_tsk_cgry.'.id','=',$lbr_tsk_tbl.'.category_id')
                ->leftJoin($lbr_tsk_src,$lbr_tsk_src.'.id','=',$lbr_tsk_tbl.'.source_id')
                ->leftJoin($lbr_tsk_typ,$lbr_tsk_typ.'.id','=',$lbr_tsk_tbl.'.task_type_id')
                ->where($lbr_tsk_tbl.'.is_active',1);

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
                        if($this->dTablePageType == "jb"){
                            $actionBtn .= '<span onclick="jobOrder.ivm.getTaskDetails(' . $row->id . ')" class="btn btn-primary btn-sm mr-1"><i class="fas fa-plus"></i></span>';
                        }else {
//                        if(auth()->user()->hasPermissionTo('labortask-view') )
//                            $actionBtn .= '<span onclick="laborTaskList.ivm.editDetails('.$row->id.')" class="btn btn-success btn-sm mr-1"><i class="fas fa-eye"></i></span>';
                            if (auth()->user()->hasPermissionTo('labortask-edit'))
                                $actionBtn .= '<span onclick="laborTaskList.ivm.editDetails(' . $row->id . ')" class="btn btn-primary btn-sm mr-1"><i class="fas fa-edit"></i></span>';
                            if (auth()->user()->hasPermissionTo('labortask-delete'))
                                $actionBtn .= '<span onclick="laborTaskList.ivm.deleteRecord(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></span>';
                        }
                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('task_category_name', function ($query, $keyword) {
                        $query->whereRaw((new TaskCategory())->getTable().".task_category_name like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('task_source_name', function ($query, $keyword) {
                        $query->whereRaw((new TaskSource())->getTable().".task_source_name like ?", ["%$keyword%"]);
                    })
                    ->filterColumn('task_type_name', function ($query, $keyword) {
                        $query->whereRaw((new TaskType())->getTable().".task_type_name like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getLaborTaskError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }
    /*not used*/
    public function create(){
        return view('labor_task/create');
    }

    public function show(){

    }

    public function edit(){

    }
    /*end not used*/

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'tsk_name.required' => 'The Task name field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'tsk_name'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $data = [
                'task_code'=> $request->tsk_code,
                'task_name'=> $request->tsk_name,
                'task_description'=> $request->tsk_description,
                'source_id'=> $request->tsk_source,
                'category_id'=> $request->tsk_category,
                'task_type_id'=> $request->tsk_mod_cls,
                'task_hour'=> str_replace(',','',$request->hours),
                'normal_rate'=> str_replace(',','',$request->hourly_rate),
                'warranty_rate'=> str_replace(',','',$request->warr_hourly_rate),
            ];

            $wo_Data = LaborTask::create($data);

            DB::commit();

            return $this->responseDataJson(true,'Labor task created successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("LaborTaskCreateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'tsk_name.required' => 'The Task name field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'tsk_name'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $wo_id = $request->item_id;

            $data = [
                'task_code'=> $request->tsk_code,
                'task_name'=> $request->tsk_name,
                'task_description'=> $request->tsk_description,
                'source_id'=> $request->tsk_source,
                'category_id'=> $request->tsk_category,
                'task_type_id'=> $request->tsk_mod_cls,
                'task_hour'=> str_replace(',','',$request->hours),
                'normal_rate'=> str_replace(',','',$request->hourly_rate),
                'warranty_rate'=> str_replace(',','',$request->warr_hourly_rate),
            ];

            $wo_Data = LaborTask::where('id',$wo_id)->update($data);

            DB::commit();

            return $this->responseDataJson(true,'Labor task updated successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("LaborTaskUpdateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $employee = LaborTask::findOrFail($request->item_id);

        $active_state = 1;
        if($employee['is_active']) $active_state = 0;

        $dataArray = ['is_active' =>$active_state];

        $resp = $employee->update($dataArray);

        if($active_state)
            $message = "Labor Task has been activated!";
        else
            $message = "Labor Task has been inactivated!";

        if($resp){

            return $this->responseDataJson(true, $message,200);
        }else{

            return $this->responseDataJson(true, "Something went wrong!",401);
        }
    }

    public function excelImport(){
        return view('labor_task/excel_import');
    }

    public function excelImportSubmit(Request $request){
        $msgs = [ 'file.required' => 'Excel file is required.', ];

        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ],$msgs);

        try {

            Excel::import(new LaborTaskImport($request->all()), $request->file);
            return back()->with('success','Successfully inserted');
        }catch (\Maatwebsite\Excel\Validators\ValidationException $e){
            $failures = $e->failures();
            $error_array = array();

            foreach ($failures as $key => $failure) {

                $unique_val = $failure->values()['flat_rate_code'];
                if(empty($failure->values()['flat_rate_code']))
                    $unique_val = $failure->values()['description'];

                $error_array[$key] = $unique_val.'`s '.$failure->errors()[0];

                /*Log::error('studentExcelUpErrorRow: '.$failure->row()); // row that went wrong
                Log::error('studentExcelUpErrorAttr: '.$failure->attribute()); // either heading key (if using heading row concern) or column index
                Log::error('studentExcelUpErrorErr: '.json_encode($failure->errors())); // Actual error messages from Laravel validator
                Log::error('studentExcelUpErrorVales: '.json_encode($failure->values()['email'])); // The values of the row that has failed.*/
            }

            Log::error('laborTaskExcelUpErrorErr: '.json_encode($error_array));
            return back()->withErrors($e->failures());
        }

    }

    public function getLaborTaskData(Request $request): \Illuminate\Http\JsonResponse
    {
        $resp = LaborTask::where('id',$request->item_id)->select('*')->get();

        return $this->responseSelectedDataJson(true, $resp, 200);
    }

    public function getLaborTaskListForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = LaborTask::where('is_active',1)->where('task_name', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = LaborTask::where('is_active',1)->where('task_name', 'LIKE', '%' . $search_term . '%')->get();

            $count = $item_list_count->count();
            $endCount = $offset + $res_count;
            $morePages = $endCount < $count;

            $data = array();
            foreach ($item_list as $row) {
                $data[] = array("id" => $row->id, "text" => $row->task_name);
            }

            return array(
                "results" => $data,
                "pagination" => array("more" => $morePages)
            );

        }catch (\Exception $e){
            Log::error("select2LaborTask: ".$e->getMessage());
        }
    }

    public function getLaborTaskByIdForDrop(Request $request){

        $state_data = LaborTask::where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->task_name);
        }

        return json_encode($data);
    }

    public function getLaborTaskSelectedData(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = ($request->ItemId);
        $resp = LaborTask::where('id','=',$id)->get();

        $resp_array = array("data" => $resp, "count" => $resp->count());

        return response()->json($resp_array);
    }

    public function getTaskCategoryListForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = TaskCategory::where('task_category_name', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = TaskCategory::where('task_category_name', 'LIKE', '%' . $search_term . '%')->get();

            $count = $item_list_count->count();
            $endCount = $offset + $res_count;
            $morePages = $endCount < $count;

            $data = array();
            foreach ($item_list as $row) {
                $data[] = array("id" => $row->id, "text" => $row->task_category_name);
            }

            return array(
                "results" => $data,
                "pagination" => array("more" => $morePages)
            );

        }catch (\Exception $e){
            Log::error("select2TaskCategory: ".$e->getMessage());
        }
    }

    public function getTaskCategoryByIdForDrop(Request $request){

        $state_data = TaskCategory::where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->task_category_name);
        }

        return json_encode($data);
    }

    public function getTaskSourceListForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = TaskSource::where('task_source_name', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = TaskSource::where('task_source_name', 'LIKE', '%' . $search_term . '%')->get();

            $count = $item_list_count->count();
            $endCount = $offset + $res_count;
            $morePages = $endCount < $count;

            $data = array();
            foreach ($item_list as $row) {
                $data[] = array("id" => $row->id, "text" => $row->task_source_name);
            }

            return array(
                "results" => $data,
                "pagination" => array("more" => $morePages)
            );

        }catch (\Exception $e){
            Log::error("select2TaskSource: ".$e->getMessage());
        }
    }

    public function getTaskSourceByIdForDrop(Request $request){

        $state_data = TaskSource::where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->task_source_name);
        }

        return json_encode($data);
    }

    public function getTaskTypeListForDrop(Request $request): array
    {
        try {

            $page = $request->page;
            $res_count = $request->resCount;
            $search_term = $request->searchTerm;

            $offset = ($page - 1) * $res_count;

            $item_list = TaskType::where('task_type_name', 'LIKE', '%' . $search_term . '%')->offset($offset)->limit($res_count)->get();
            $item_list_count = TaskType::where('task_type_name', 'LIKE', '%' . $search_term . '%')->get();

            $count = $item_list_count->count();
            $endCount = $offset + $res_count;
            $morePages = $endCount < $count;

            $data = array();
            foreach ($item_list as $row) {
                $data[] = array("id" => $row->id, "text" => $row->task_type_name);
            }

            return array(
                "results" => $data,
                "pagination" => array("more" => $morePages)
            );

        }catch (\Exception $e){
            Log::error("select2TaskType: ".$e->getMessage());
        }
    }

    public function getTaskTypeByIdForDrop(Request $request){

        $state_data = TaskType::where('id',$request->id)->get();

        $data = array();
        foreach($state_data as $row){

            $data[] = array("id" => $row->id, "text" => $row->task_type_name);
        }

        return json_encode($data);
    }

    /** Task Category functions start **/

    public function taskCategoryView(){
        return view('labor_task/labortask_category_list');
    }

    public function getTaskCategoryDataTableList(Request $request){
        if ($request->ajax()) {

            $data = TaskCategory::query();

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
                        if(auth()->user()->hasPermissionTo('task-category-edit') )
                            $actionBtn .= '<span onclick="laborTaskList.ivm.editDetails('.$row->id.')" class="btn btn-primary btn-sm mr-1"><i class="fas fa-edit"></i></span>';
                        if(auth()->user()->hasPermissionTo('task-category-delete') )
                            $actionBtn .= '<span onclick="laborTaskList.ivm.deleteRecord(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getLaborTaskCategoryError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function storeCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'tsk_cate_name.required' => 'The Task category name field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'tsk_cate_name'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $data = [
                'task_category_name'=> $request->tsk_cate_name,
            ];

            $wo_Data = TaskCategory::create($data);

            DB::commit();

            return $this->responseDataJson(true,'Task Category created successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("LaborTaskCategoryCreateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function getTaskCategorySelectedData(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = ($request->item_id);

        $resp = TaskCategory::where('id','=',$id)->get();

        $resp_array = array("data" => $resp, "count" => $resp->count());

        return response()->json($resp_array);
    }

    public function updateCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'tsk_cate_name.required' => 'The Task category name field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'tsk_cate_name'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $wo_id = $request->item_id;

            $data = [
                'task_category_name'=> $request->tsk_cate_name,
            ];

            $wo_Data = TaskCategory::where('id',$wo_id)->update($data);

            DB::commit();

            return $this->responseDataJson(true,'Labor task category updated successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("LaborTaskCategoryUpdateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function destroyCategory(Request $request): \Illuminate\Http\JsonResponse
    {
        $employee = TaskCategory::findOrFail($request->item_id);

        $active_state = 1;
        $resp = $employee->delete();

        if($active_state)
            $message = "Labor task category has been deleted!";
        else
            $message = "Labor task category has been inactivated!";

        if($resp){

            return $this->responseDataJson(true, $message,200);
        }else{

            return $this->responseDataJson(true, "Something went wrong!",401);
        }
    }

    /** Task Source functions start **/

    public function taskSourceView(){
        return view('labor_task/labortask_source_list');
    }

    public function getTaskSourceDataTableList(Request $request)
    {
        if ($request->ajax()) {

            $data = TaskSource::query();

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
                        if(auth()->user()->hasPermissionTo('task-source-edit') )
                            $actionBtn .= '<span onclick="laborTaskList.ivm.editDetails('.$row->id.')" class="btn btn-primary btn-sm mr-1"><i class="fas fa-edit"></i></span>';
                        if(auth()->user()->hasPermissionTo('task-source-delete') )
                            $actionBtn .= '<span onclick="laborTaskList.ivm.deleteRecord(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getLaborTaskSourceError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function storeSource(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'tsk_src_name.required' => 'The Task source name field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'tsk_src_name'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $data = [
                'task_source_name'=> $request->tsk_src_name,
            ];

            $wo_Data = TaskSource::create($data);

            DB::commit();

            return $this->responseDataJson(true,'Task Source created successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("LaborTaskSourceCreateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function getTaskSourceSelectedData(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = ($request->item_id);

        $resp = TaskSource::where('id','=',$id)->get();

        $resp_array = array("data" => $resp, "count" => $resp->count());

        return response()->json($resp_array);
    }

    public function updateSource(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'tsk_src_name.required' => 'The Task category name field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'tsk_src_name'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $wo_id = $request->item_id;

            $data = [
                'task_source_name'=> $request->tsk_src_name,
            ];

            $wo_Data = TaskSource::where('id',$wo_id)->update($data);

            DB::commit();

            return $this->responseDataJson(true,'Labor task source updated successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("LaborTaskSourceUpdateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function destroySource(Request $request): \Illuminate\Http\JsonResponse
    {
        $employee = TaskSource::findOrFail($request->item_id);

        $active_state = 1;
        $resp = $employee->delete();

        if($active_state)
            $message = "Labor task source has been deleted!";
        else
            $message = "Labor task category has been inactivated!";

        if($resp){

            return $this->responseDataJson(true, $message,200);
        }else{

            return $this->responseDataJson(true, "Something went wrong!",401);
        }
    }

    /** Task Type functions start **/

    public function taskTypeView(){
        return view('labor_task/labortask_type_list');
    }

    public function getTaskTypeDataTableList(Request $request){
        if ($request->ajax()) {

            $data = TaskType::query();

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
                        if(auth()->user()->hasPermissionTo('task-type-edit') )
                            $actionBtn .= '<span onclick="laborTaskList.ivm.editDetails('.$row->id.')" class="btn btn-primary btn-sm mr-1"><i class="fas fa-edit"></i></span>';
                        if(auth()->user()->hasPermissionTo('task-type-delete') )
                            $actionBtn .= '<span onclick="laborTaskList.ivm.deleteRecord(' . $row->id . ')" class="edit btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></span>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

                Log::error("getLaborTaskSourceError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function storeType(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'tsk_type_name.required' => 'The Task type name field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'tsk_type_name'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $data = [
                'task_type_name'=> $request->tsk_type_name,
            ];

            $wo_Data = TaskType::create($data);

            DB::commit();

            return $this->responseDataJson(true,'Task Type created successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("LaborTaskTypeCreateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function getTaskTypeSelectedData(Request $request): \Illuminate\Http\JsonResponse
    {
        $id = ($request->item_id);

        $resp = TaskType::where('id','=',$id)->get();

        $resp_array = array("data" => $resp, "count" => $resp->count());

        return response()->json($resp_array);
    }

    public function updateType(Request $request): \Illuminate\Http\JsonResponse
    {
        $validateMsgs = [
            'tsk_type_name.required' => 'The Task type name field is required',
        ];

        $validateRequest = Validator::make($request->all(),[
            'tsk_type_name'      => ['required'],
        ], $validateMsgs);

        if($validateRequest->fails()){

            return $this->responseDataJson(false, $validateRequest->getMessageBag(), 401);
        }

        DB::beginTransaction();
        try {

            $wo_id = $request->item_id;

            $data = [
                'task_type_name'=> $request->tsk_type_name,
            ];

            $wo_Data = TaskType::where('id',$wo_id)->update($data);

            DB::commit();

            return $this->responseDataJson(true,'Labor task type updated successfully',200);
        }catch (\Exception $e){

            DB::rollBack();
            Log::error("LaborTaskTypeUpdateError:".$e->getMessage());
            return $this->responseDataJson(false,['Somethings went wrong'], 500);
        }
    }

    public function destroyType(Request $request): \Illuminate\Http\JsonResponse
    {
        $employee = TaskType::findOrFail($request->item_id);

        $active_state = 1;
        $resp = $employee->delete();

        if($active_state)
            $message = "Labor task type has been deleted!";
        else
            $message = "Labor task category has been inactivated!";

        if($resp){

            return $this->responseDataJson(true, $message,200);
        }else{

            return $this->responseDataJson(true, "Something went wrong!",401);
        }
    }

}
