<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Yajra\DataTables\DataTables;

class EmployeeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('permission:employee-list', ['only' =>['index','show', 'getEmployees']]);
//        $this->middleware('permission:employee-edit', ['only' =>['edit', 'update']]);
//        $this->middleware('permission:employee-add', ['only' =>['employeeCreate', 'employeeCreateSubmit']]);
//        $this->middleware('permission:employee-delete', ['only' =>['destroy']]);
    }

    public function index(){

//        return view('employee/employee_list');

        return Inertia::render('Employee/List', [
            'employeesIndexUrl' => route('employee.table.list'),
            'employeeShowUrl' => route('employee.show'),
            'employeeDeleteUrl' => route('employee.destroy'),
            'csrfToken' => csrf_token(),
        ]);
    }

    public function employeeCreate(){

        return view('employee/create');
    }

    public function getEmployees(Request $request){

        if ($request->ajax()) {
            $data = Employee::query()->where('is_active',1);

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $actionBtn = '';
                        if(auth()->user()->hasPermissionTo('employee-view') )
                            $actionBtn .= '<button type="button" onclick="employeeList.ivm.showDetails('.$row->id.')" class="edit btn btn-success btn-sm mr-2">Show</button>';
                        if(auth()->user()->hasPermissionTo('employee-edit') )
                            $actionBtn .= '<a href="' . route('employee.edit', $row->id) . '" class="delete btn btn-primary btn-sm mr-2">Edit</a>';
                        if(auth()->user()->hasPermissionTo('employee-delete') )
                            $actionBtn .= '<button type="button" onclick="employeeList.ivm.deleteRecord(' . $row->id . ')" class="edit btn btn-danger btn-sm">Delete</button>';

                        return $actionBtn;
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {
                Log::error("getEmployeeError:".$e->getMessage());
                $this->responseDataJson(false,['Somethings went wrong'],500);
            }
        }
    }

    public function employeeCreateSubmit(Request $request){

        $validateMsgs = [
            'first_name.required' => 'Your First name is required'
        ];

        $validateRequest = Validator::make($request->all(),[

            'first_name'      => ['required', 'string', 'max:255'],
        ],$validateMsgs);

        if($validateRequest->fails()){

            return response()->json([
                'status' => false,
                'message' => $validateRequest->getMessageBag()
            ], 401);
        }

        $pro_img = NULL;

        try {
            $data = [
                'emp_no' => $request->emp_no,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name.' '.$request->last_name,
                'pro_imgs' => $pro_img,
            ];
            $resp = Employee::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Employee created successfully'
            ], 200);

        }catch (\Exception $e){
            Log::error("EmployeeCreateError:".$e->getMessage());
            return response()->json([
                'status' => false,
                'message' => ['Somethings went wrong']
            ], 500);
        }

    }

    public function edit($id){
        $empData = Employee::findOrFail($id);

        return view('employee/edit', compact('empData'));
    }

    public function update(Request $request){
        $validateMsgs = [
            'first_name.required' => 'Your First name is required'
        ];

        $validateRequest = Validator::make($request->all(),[

            'first_name'      => ['required', 'string', 'max:255'],
        ],$validateMsgs);

        if($validateRequest->fails()){

            return response()->json([
                'status' => false,
                'message' => $validateRequest->getMessageBag()
            ], 401);
        }

        $pro_img = NULL;

        try {
            $emp = Employee::findOrFail($request->emp_id);

            $dataUp = [
                'emp_no' => $request->emp_no,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => $request->first_name.' '.$request->last_name,
                'pro_img' => $pro_img
            ];
            $resp_msg = $emp->update($dataUp);

            if($resp_msg){
                return response()->json(['status' => true,'message' => 'Employee has been updated! '],200);
            }

            return response()->json(['status' => false,'message' => "Something went wrong!"],500);

        }catch (\Exception $e){

            Log::error("EmployeeUpdateError:".$e->getMessage());
            return response()->json([
                'status' => false,
                'message' => ['Somethings went wrong']
            ], 500);
        }
    }

    public function show(Request $request){
        $id = ($request->emp_id);
        $resp = Employee::where('id','=',$id)->get();

        $resp_array = array(
            'status' => true,
            'dataCount' => $resp->count(),
            'data' => $resp
        );

        return response()->json($resp_array);
    }

    public function destroy(Request $request){
        $employee = Employee::findOrFail($request->emp_id);

        $active_state = 1;
        if($employee['is_active']) $active_state = 0;

        $dataArray = [
            'is_active' =>$active_state
        ];

        $resp = $employee->update($dataArray);

        if($active_state)
            $message = "Employee has been activated!";
        else
            $message = "Employee has been inactivated!";

        if($resp){
            $resp_msg = ['status' => true,'message' => $message];
            return response()->json($resp_msg,200);
        }else{

            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }

    public function getEmpListForRDrop(){

        $resp = Employee::where('is_active',1)->get(['id', 'full_name']);
        /*$opt_arr = array();
        foreach ($resp AS $res){
            $opt_arr[]=[
                'value' => $res->id,
                'label' => $res->full_name
            ];
        }*/

        return $this->responseSelectedDataJson(true,$resp,200);
    }
}
