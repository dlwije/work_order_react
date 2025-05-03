@extends('template.master')
@section('title','Welcome')


@section('content')
    <div class="row">
        <div class="col-6">

            <!-- PIE CHART -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Top 5 Customers</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- BAR CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">New vs Returning Customers</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

        <div class="col-6">
            <div class="card card-outline">
                <div class="card-header">
                    <h3 class="card-title">Service Station Overview</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                            <i class="fas fa-times"></i></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-number">1,410</span>
                            <span class="info-box-text">Total Vehicle Serviced Today</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-number">1,410</span>
                            <span class="info-box-text">This Month</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-number">8</span>
                            <span class="info-box-text">Ongoing Services</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-number">2</span>
                            <span class="info-box-text">Most Frequent Vehicle Brands</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Revenue</span>
                            <span class="info-box-number">8</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Total Sales</span>
                            <span class="info-box-number">2</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
            </div>

            <!-- LINE CHART -->
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Profit & Loss</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="lineChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- Default box -->

    <!-- /.card -->
    @push('scripts')
        <script src="{{asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
        <script>
            var areaChartData = {
                labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [
                    {
                        label               : 'New',
                        backgroundColor     : 'rgba(60,141,188,0.9)',
                        borderColor         : 'rgba(60,141,188,0.8)',
                        pointRadius          : false,
                        pointColor          : '#3b8bba',
                        pointStrokeColor    : 'rgba(60,141,188,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data                : [28, 48, 40, 19, 86, 27, 90]
                    },
                    {
                        label               : 'Returning',
                        backgroundColor     : 'rgba(210, 214, 222, 1)',
                        borderColor         : 'rgba(210, 214, 222, 1)',
                        pointRadius         : false,
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : [65, 59, 80, 81, 56, 55, 40]
                    },
                ]
            }

            var donutData        = {
                labels: [
                    'Chrome',
                    'IE',
                    'FireFox',
                    'Safari',
                    'Opera',
                    'Navigator',
                ],
                datasets: [
                    {
                        data: [700,500,400,600,300,100],
                        backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                    }
                ]
            }

            //-------------
            //- BAR CHART -
            //-------------
            // var barChartCanvas = $('#barChart').get(0).getContext('2d')
            // var barChartData = $.extend(true, {}, areaChartData)
            // var temp0 = areaChartData.datasets[0]
            // var temp1 = areaChartData.datasets[1]
            // barChartData.datasets[0] = temp1
            // barChartData.datasets[1] = temp0
            //
            // var barChartOptions = {
            //     responsive              : true,
            //     maintainAspectRatio     : false,
            //     datasetFill             : false
            // }

            document.addEventListener("DOMContentLoaded", function () {
                {{--$.ajax({--}}
                {{--    url: '{{ route('dashboard.customer-trends') }}', // your Laravel route--}}
                {{--    method: 'GET',--}}
                {{--    success: function (response) {--}}
                {{--        // Directly use the datasets and labels from the API response--}}
                {{--        var areaChartData = {--}}
                {{--            labels: response.labels,--}}
                {{--            datasets: response.datasets--}}
                {{--        };--}}

                {{--        // Bar Chart--}}
                {{--        var barChartCanvas = $('#barChart').get(0).getContext('2d');--}}
                {{--        var barChartData = $.extend(true, {}, areaChartData);--}}
                {{--        barChartData.datasets[0] = areaChartData.datasets[1];--}}
                {{--        barChartData.datasets[1] = areaChartData.datasets[0];--}}

                {{--        new Chart(barChartCanvas, {--}}
                {{--            type: 'bar',--}}
                {{--            data: barChartData,--}}
                {{--            options: {--}}
                {{--                responsive: true,--}}
                {{--                maintainAspectRatio: false,--}}
                {{--                scales: {--}}
                {{--                    y: {--}}
                {{--                        beginAtZero: true--}}
                {{--                    }--}}
                {{--                },--}}
                {{--                plugins: {--}}
                {{--                    legend: {--}}
                {{--                        display: true--}}
                {{--                    }--}}
                {{--                }--}}
                {{--            }--}}
                {{--        });--}}
                {{--    },--}}
                {{--    error: function () {--}}
                {{--        console.error("Failed to load chart data");--}}
                {{--    }--}}
                {{--});--}}
            });

            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData        = donutData;
            var pieOptions     = {
                maintainAspectRatio : false,
                responsive : true,
            }
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            {{--fetch('{{ route('dashboard.top-customer-sales') }}')--}}
            {{--    .then(response => response.json())--}}
            {{--    .then(data => {--}}
            {{--        const ctx = $('#pieChart').get(0).getContext('2d');--}}
            {{--        new Chart(ctx, {--}}
            {{--            type: 'pie',--}}
            {{--            data: {--}}
            {{--                labels: data.labels,--}}
            {{--                datasets: data.datasets--}}
            {{--            },--}}
            {{--            options: {--}}
            {{--                responsive: true,--}}
            {{--                plugins: {--}}
            {{--                    legend: {--}}
            {{--                        position: 'right',--}}
            {{--                    },--}}
            {{--                    title: {--}}
            {{--                        display: true,--}}
            {{--                        text: 'Top 5 Customers by Sales'--}}
            {{--                    }--}}
            {{--                }--}}
            {{--            }--}}
            {{--        });--}}
            {{--    })--}}
            {{--    .catch(error => {--}}
            {{--        console.error('Error loading chart data:', error);--}}
            {{--    });--}}

            var areaChartData = {
                labels  : ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [
                    {
                        label               : 'Profit',
                        backgroundColor     : 'rgba(60,141,188,0.9)',
                        borderColor         : 'rgba(60,141,188,0.8)',
                        pointRadius          : false,
                        pointColor          : '#3b8bba',
                        pointStrokeColor    : 'rgba(60,141,188,1)',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data                : [28, 48, 40, 19, 86, 27, 90]
                    },
                    {
                        label               : 'Loss',
                        backgroundColor     : 'rgba(210, 214, 222, 1)',
                        borderColor         : 'rgba(210, 214, 222, 1)',
                        pointRadius         : false,
                        pointColor          : 'rgba(210, 214, 222, 1)',
                        pointStrokeColor    : '#c1c7d1',
                        pointHighlightFill  : '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        data                : [65, 59, 80, 81, 56, 55, 40]
                    },
                ]
            }

            var areaChartOptions = {
                maintainAspectRatio : false,
                responsive : true,
                legend: {
                    display: false
                },
                scales: {
                    xAxes: [{
                        gridLines : {
                            display : false,
                        }
                    }],
                    yAxes: [{
                        gridLines : {
                            display : false,
                        }
                    }]
                }
            }

            //-------------
            //- LINE CHART -
            //--------------
            var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
            var lineChartOptions = $.extend(true, {}, areaChartOptions)
            var lineChartData = $.extend(true, {}, areaChartData)
            lineChartData.datasets[0].fill = false;
            lineChartData.datasets[1].fill = false;
            lineChartOptions.datasetFill = false

            var lineChart = new Chart(lineChartCanvas, {
                type: 'line',
                data: lineChartData,
                options: lineChartOptions
            })
        </script>
    @endpush
@endsection
