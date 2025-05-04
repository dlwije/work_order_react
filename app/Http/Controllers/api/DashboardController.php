<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\SaleSMA;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $topCustomers = SaleSMA::with('customer')
            ->selectRaw('customer_id, SUM(grand_total) as total_sales')
            ->groupBy('customer_id')
            ->orderByDesc('total_sales')
            ->take(5)
            ->get();

        $labels = [];
        $data = [];

        foreach ($topCustomers as $sale) {
            $labels[] = $sale->customer->name ?? 'Unknown';
            $data[] = round($sale->total_sales, 2);
        }

        // Pad to 6 entries if needed
        while (count($labels) < 6) {
            $labels[] = 'Others';
            $data[] = 0;
        }

        $response = [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                ]
            ]
        ];

        return response()->json($response);
    }

    public function getCustomerTrendsForChart()
    {
        $year = now()->year;

        $labels = [];
        $newData = [];
        $returningData = [];

        for ($month = 1; $month <= 12; $month++) {
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

            $labels[] = $startOfMonth->format('F');

            // Customers who bought this month
            $monthlyCustomers = SaleSMA::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->select('customer_id')
                ->distinct()
                ->pluck('customer_id');

            // New customers
            $newCustomers = SaleSMA::whereIn('customer_id', $monthlyCustomers)
                ->select('customer_id', DB::raw('MIN(date) as first_purchase'))
                ->groupBy('customer_id')
                ->havingRaw('MIN(date) BETWEEN ? AND ?', [$startOfMonth, $endOfMonth])
                ->get()
                ->count();

            $returningCustomers = $monthlyCustomers->count() - $newCustomers;

            $newData[] = $newCustomers;
            $returningData[] = max(0, $returningCustomers);
        }

        $dataset = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'New',
                    'backgroundColor' => 'rgba(60,141,188,0.9)',
                    'borderColor' => 'rgba(60,141,188,0.8)',
                    'pointRadius' => false,
                    'pointColor' => '#3b8bba',
                    'pointStrokeColor' => 'rgba(60,141,188,1)',
                    'pointHighlightFill' => '#fff',
                    'pointHighlightStroke' => 'rgba(60,141,188,1)',
                    'data' => $newData
                ],
                [
                    'label' => 'Returning',
                    'backgroundColor' => 'rgba(210, 214, 222, 1)',
                    'borderColor' => 'rgba(210, 214, 222, 1)',
                    'pointRadius' => false,
                    'pointColor' => 'rgba(210, 214, 222, 1)',
                    'pointStrokeColor' => '#c1c7d1',
                    'pointHighlightFill' => '#fff',
                    'pointHighlightStroke' => 'rgba(220,220,220,1)',
                    'data' => $returningData
                ]
            ]
        ];

        return response()->json($dataset);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
