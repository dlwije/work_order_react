<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class GuestRegisterController extends Controller
{
    public function index()
    {
        $emp_list = Employee::where('is_active',1)->get();
        $roles = Role::pluck('name','name')->all();
        $pos_roles = DB::table('sma_groups')
            ->where('name','!=' ,'customer')
            ->where('name','!=','supplier')->get();
        return view('users.create',compact('roles', 'pos_roles','emp_list'));
    }
}
