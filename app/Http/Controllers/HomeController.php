<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\DataTables;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        return Inertia::render('Dashboard/Dashboard', [
            'routes' => [
                'customerTrends' => route('dashboard.customer-trends'),
                'topCustomerSales' => route('dashboard.top-customer-sales'),
            ]
        ]);
//        return view('welcome');
    }

    public function myCompany(){

        return Inertia::render('Company');
    }

    // app/Http/Controllers/CompanyController.php
    public function store(Request $request)
    {
        $validated = $request->validate([
            'BRNo' => 'required|string|max:255',
            'CompName' => 'required|string|max:255',
            'Address' => 'required|string',
            'Email' => 'nullable|email',
            'WebUrl' => 'nullable|url',
            'OfcTel' => 'nullable|string',
            'Mobile' => 'nullable|string',
            'Fax' => 'nullable|string',
        ]);

        // Save to DB, e.g.:
        Company::create($validated);

        return redirect()->back()->with('success', 'Company saved!');
    }

    public function getUnreadNotify(){
        $log_u_id = auth()->user()->id;

        $notify_list = Notification::select('id', 'notify_body', 'notify_title')->where('sent_to_id', $log_u_id)->whereNull('read_at');

        $res_array = [
          'data' => $notify_list->get(),
          'dataCount' => $notify_list->count()
        ];

        return $this->responseSelectedDataJson(true,$res_array,200);
    }

    public function updateReadState(Request $request){

        $read_state = $request->state;
        if($read_state == 'all'){
            $notify_list = Notification::select('id', 'notify_body', 'notify_title')->where('sent_to_id', auth()->user()->id)->whereNull('read_at')->get();
            foreach ($notify_list AS $n_list){
                $notify = Notification::findOrFail($n_list->id);
                $upArray = ['read_at' => now()];
                $notify->update($upArray);
            }
        }else {
            $notify = Notification::findOrFail($request->id);
            $notify->update(['read_at' => now()]);
        }
        return $this->responseDataJson(true, 'Successfully completed.', 200);
    }
}
