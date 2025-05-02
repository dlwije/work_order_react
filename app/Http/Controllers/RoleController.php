<?php

namespace App\Http\Controllers;

use App\DataTables\RoleDataTable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\PermissionHead;
use App\Models\Permission;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        /*$roles = Role::orderBy('id','DESC')->paginate(5);
        return view('roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);*/

        return view('roles.index');
    }

    public function getRoles(Request $request){

        if($request->ajax()){

            $data = Role::query();

            return DataTables::of($data)
                ->editColumn('updated_at', function ($user) {
                    return $user->updated_at->format('Y-m-d');
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="' . route('role.show', $row->id) . '" class="edit btn btn-success btn-sm mr-1"><i class="fas fa-eye"></i></a>';
                    if(auth()->user()->hasPermissionTo('role-edit'))
                        $actionBtn .= '<a href="' . route('role.edit', $row->id) . '" class="delete btn btn-primary btn-sm mr-1"><i class="fas fa-edit"></i></a>';
                    if(auth()->user()->hasPermissionTo('role-delete'))
                        $actionBtn .= '<button type="button" onclick="deleteConfirm('.$row->id.')" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></button>';

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->removeColumn('created_at')
                ->filterColumn('updated_at', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(updated_at, '%Y-%m-%d') like ?", ["%$keyword%"]);
                })
                ->make(true);
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $permission_head = PermissionHead::get();
        $permission = Permission::get();
        return view('roles.create',compact('permission','permission_head'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));


        return redirect()->route('role.index')
            ->with('success_message','Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $permission_head = PermissionHead::get();
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();


        return view('roles.show',compact('role','rolePermissions','permission_head'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $permission_head = PermissionHead::get();
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();


        return view('roles.edit',compact('role','permission','rolePermissions','permission_head'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);


        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();


        $role->syncPermissions($request->input('permission'));


        return redirect()->route('role.index')
            ->with('success','Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $resp = DB::table("roles")->where('id',$id)->delete();

        if($resp){

            return $this->responseDataJson(true,'Role deleted successfully',200);
        }else{

            $resp_msg = ['status' => false,['message' => "Something went wrong!"]];
            return response()->json($resp_msg,401);
        }
    }
}
