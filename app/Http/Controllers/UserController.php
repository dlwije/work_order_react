<?php

namespace App\Http\Controllers;

use App\DataTables\UserDataTable;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
//use DataTables;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('permission:user-management|user-list|user-create|user-edit|user-delete', ['only' => ['index','store']]);
//        $this->middleware('permission:user-create', ['only' => ['create','store']]);
//        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:user-delete', ['only' => ['destroy']]);

    }
    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index()
    {
//        return view('users.index');
        return Inertia::render('Users/List', [
            'usersIndexUrl' => route('users.list'),
            'userCreateUrl' => route('user.create'),
            'userShowUrl'   => null,
            'userDeleteUrl' => null,
            'canCreate'     => auth()->user()->can('user-create'),
        ]);
    }

    public function getUsers(Request $request){

        if ($request->ajax()) {
            $data = User::query();

            try {
                return Datatables::of($data)
                    ->editColumn('updated_at', function ($user) {
                        return $user->updated_at->format('Y-m-d');
                    })
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $actionBtn = '<a href="' . route('user.show', $row->id) . '" class="edit btn btn-success btn-sm mr-1"><i class="fas fa-eye"></i></a>';
                        $actionBtn .= '<a href="' . route('user.edit', $row->id) . '" class="delete btn btn-primary btn-sm mr-1"><i class="fas fa-edit"></i></a>';
                        $actionBtn .= '<button type="button" onclick="deleteConfirm('.$row->id.')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></button>';
                        return $actionBtn;
                    })
                    ->addColumn('roles', function ($row) {
                        return $row->roles->pluck('name')->all();
                    })
                    ->rawColumns(['action'])
                    ->removeColumn('email_verified_at', 'created_at')
                    ->filterColumn('updated_at', function ($query, $keyword) {
                        $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                    })
                    ->make(true);
            } catch (\Exception $e) {

            }
        }
    }

    public function create()
    {
//        $emp_list = Employee::where('is_active',1)->get();
//        $roles = Role::pluck('name','name')->all();
//        $pos_roles = DB::table('sma_groups')
//            ->where('name','!=' ,'customer')
//            ->where('name','!=','supplier')->get();
////        print_r($pos_roles);exit();
//        return view('users.create',compact('roles', 'pos_roles','emp_list'));

        $emp_list = Employee::where('is_active', 1)->get();
        $roles = Role::pluck('name', 'name');
        $pos_roles = DB::table('sma_groups')
            ->where('name', '!=', 'customer')
            ->where('name', '!=', 'supplier')
            ->get();

        return Inertia::render('Users/Create', [
            'roles' => $roles,
            'pos_roles' => $pos_roles,
            'emp_list' => $emp_list,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,user_name',
            'emp_id' => 'required',
            'password' => 'required|same:confirm_password',
            'roles' => 'required',
            'pos_role' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {

            $input = $request->all();
            $emp_data = Employee::select('first_name','last_name')->where('id', $input['emp_id'])->first();

            $fname = $emp_data['first_name'] ?? '';
            $lname = $emp_data['last_name'] ?? '';
            $name = $fname.' '.$lname;
            $email = strtolower($input['email']);
            $u_name = strtolower($input['username']);
            $pass = $input['password'];

            $wo_user = [
                'emp_id' => $input['emp_id'],
                'name' => $name,
                'first_name' => $fname,
                'last_name' => $lname,
                'email' => $email,
                'user_name' => $u_name,
                'password' => Hash::make($pass),
            ];

            $userWO = User::create($wo_user);
            $userWOId = $userWO->id;
            $userWO->assignRole($request->input('roles'));

            $pos_user = [
                'username'       => $u_name,
                'password'       => $this->hashPosPassword($pass),
                'email'          => $email,
                'active'         => 1,
                'first_name'     => $fname,
                'last_name'      => $lname,
                'company'        => $name,
                'phone'          => NULL, 'gender'         => NULL,
                'group_id'       => $input['pos_role'],
                'biller_id'      => NULL, 'warehouse_id'   => NULL,
                'view_right'     => '0', 'edit_right'      => '0', 'allow_discount' => '0',
                'ip_address'     => \request()->ip(), 'created_on'     => time(), 'last_login'     => time(),
                'wo_user_id'     => $userWOId
            ];

            DB::table('sma_users')->insertGetId($pos_user);

            DB::commit();
            return redirect()->route('user.index')
                ->with('success_message','Users created successfully');

        }catch (\Exception $e){
            DB::rollBack();
            Log::error("UserstoreSaveError:".$e->getMessage());
            return redirect()->route('user.create')
                ->with('error','Something went wrong!');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
//        $user = User::find($id);
//        return view('users.show',compact('user'));

        $user = User::find($id);
        $userRole = $user->roles->pluck('name','name')->all();

        return Inertia::render('Users/Show', [
            'user' => $user,
            'user_role' => $userRole,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $emp_list = Employee::where('is_active',1)->get();
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();


        return view('users.edit',compact('user','roles','userRole','emp_list'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'emp_id' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();


        $user->assignRole($request->input('roles'));


        return redirect()->route('user.index')
            ->with('success_message','Users updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $resp = User::find($id)->delete();
        if($resp){

            return $this->responseDataJson(true,'Users deleted successfully',200);
        }else{

            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }

    public function getUserList(){
        $user_tbl_nm = (new User())->getTable();
        $role_tbl = (new Role())->getTable();
        $usr_rl_tbl_name = "model_has_roles";

        $data_array = User::query()
            ->leftJoin($usr_rl_tbl_name,"model_has_roles.model_id", (new User())->getTable().".id")
            ->leftJoin($role_tbl, $role_tbl.".id",$usr_rl_tbl_name.".role_id")
            ->where($user_tbl_nm.'.is_active',1)
            ->get([$user_tbl_nm.'.*',DB::raw($role_tbl.'.name AS roleName'), DB::raw("DATE_FORMAT(".$user_tbl_nm.".created_at, '%Y-%m-%d') AS created_date")]);
        return $this->responseSelectedDataJson(true, $data_array, 200);
    }

    public function getUserData(Request $request){
        try {
            $user_tbl_nm = (new User())->getTable();
            $role_tbl = (new Role())->getTable();
            $usr_rl_tbl_name = "model_has_roles";
            $emp_tbl = (new Employee())->getTable();

            $data_array = User::query()
                ->leftJoin($emp_tbl, $emp_tbl.'.id', '=', $user_tbl_nm.'.emp_id')
                ->leftJoin($usr_rl_tbl_name,"model_has_roles.model_id", (new User())->getTable().".id")
                ->leftJoin($role_tbl, $role_tbl.".id",$usr_rl_tbl_name.".role_id")
                ->where($user_tbl_nm.'.id',$request->userId)
                ->first([$user_tbl_nm.'.*',$emp_tbl.".full_name",DB::raw($role_tbl.'.name AS roleName'), DB::raw("DATE_FORMAT(".$user_tbl_nm.".created_at, '%Y-%m-%d') AS created_date")]);
//            $data_array['te'] = Users::find(4)->employee->full_name;
            return $this->responseSelectedDataJson(true, $data_array, 200);

        }catch (\Exception $e){
            Log::error($e->getMessage());
            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }
}
