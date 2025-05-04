import React, { useEffect } from 'react';
import Chart from 'chart.js/auto';
import AdminLayout from "@/lteLayouts/AdminLayout.jsx";
import {usePage} from "@inertiajs/react";

const Dashboard = () => {
    const { props } = usePage();
    const customerTrendsUrl = props.routes.customerTrends;
    const topCustomerSalesUrl = props.routes.topCustomerSales;

    useEffect(() => {

        const chartInstances = [];

        const fetchAndRenderCharts = async () => {
            try {
                // --- Pie Chart (Top Customers by Sales) ---
                const pieResponse = await axios.get(topCustomerSalesUrl);
                const pieCtx = document.getElementById('pieChart').getContext('2d');

                const pieChart = new Chart(pieCtx, {
                    type: 'pie',
                    data: {
                        labels: pieResponse.data.labels,
                        datasets: pieResponse.data.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' },
                            title: { display: true, text: 'Top 5 Customers by Sales' }
                        }
                    }
                });
                chartInstances.push(pieChart);

                // --- Bar Chart (Customer Trends) ---
                const barResponse = await axios.get(customerTrendsUrl);
                const barCtx = document.getElementById('barChart').getContext('2d');

                const barChart = new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: barResponse.data.labels,
                        datasets: barResponse.data.datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        },
                        plugins: {
                            legend: { display: true }
                        }
                    }
                });
                chartInstances.push(barChart);

                // --- Line Chart (Static) ---
                const lineCtx = document.getElementById('lineChart').getContext('2d');
                const lineChart = new Chart(lineCtx, {
                    type: 'line',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr'],
                        datasets: [{
                            label: 'Profit & Loss',
                            data: [3000, 4000, 3200, 4500],
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.2)',
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
                chartInstances.push(lineChart);
            } catch (err) {
                console.error("Failed to load chart data", err);
            }
        };

        fetchAndRenderCharts();

        return () => {
            chartInstances.forEach(chart => chart.destroy());
        };
    }, [customerTrendsUrl, topCustomerSalesUrl]);

    return (
        <AdminLayout>
        <div className="row">
            <div className="col-6">
                {/* Pie Chart */}
                <div className="card">
                    <div className="card-header">
                        <h3 className="card-title">Top 5 Customers</h3>
                        <div className="card-tools">
                            {/*<button className="btn btn-tool" data-card-widget="collapse"><i className="fas fa-minus"/>*/}
                            {/*</button>*/}
                            {/*<button className="btn btn-tool" data-card-widget="remove"><i className="fas fa-times"/>*/}
                            {/*</button>*/}
                            <button type="button" className="btn btn-tool" data-lte-toggle="card-collapse">
                                <i data-lte-icon="expand" className="bi bi-plus-lg"></i>
                                <i data-lte-icon="collapse" className="bi bi-dash-lg"></i>
                            </button>
                            <button type="button" className="btn btn-tool" data-lte-toggle="card-remove">
                                <i className="bi bi-x-lg"></i>
                            </button>
                        </div>

                    </div>
                    <div className="card-body">
                        <canvas id="pieChart" style={{height: 260}}/>
                    </div>
                </div>

                {/* Bar Chart */}
                <div className="card card-success">
                <div className="card-header">
                        <h3 className="card-title">New vs Returning Customers</h3>
                        <div className="card-tools">
                            <button className="btn btn-tool" data-card-widget="collapse"><i className="fas fa-minus" /></button>
                            <button className="btn btn-tool" data-card-widget="remove"><i className="fas fa-times" /></button>
                        </div>
                    </div>
                    <div className="card-body">
                        <canvas id="barChart" style={{ height: 250 }} />
                    </div>
                </div>
            </div>

            <div className="col-6">
                <div className="card card-outline">
                    <div className="card-header">
                        <h3 className="card-title">Service Station Overview</h3>
                        <div className="card-tools">
                            <button className="btn btn-tool" data-card-widget="collapse"><i className="fas fa-minus" /></button>
                            <button className="btn btn-tool" data-card-widget="remove"><i className="fas fa-times" /></button>
                        </div>
                    </div>
                </div>

                <div className="row">
                    {[
                        { text: 'Total Vehicle Serviced Today', number: 1410 },
                        { text: 'This Month', number: 1410 },
                        { text: 'Ongoing Services', number: 8 },
                        { text: 'Most Frequent Vehicle Brands', number: 2 },
                        { text: 'Total Revenue', number: 8 },
                        { text: 'Total Sales', number: 2 }
                    ].map((item, i) => (
                        <div key={i} className="col-md-6 col-sm-6 col-12">
                            <div className="info-box">
                                <span className="info-box-icon bg-info"><i className="far fa-envelope" /></span>
                                <div className="info-box-content">
                                    <span className="info-box-number">{item.number}</span>
                                    <span className="info-box-text">{item.text}</span>
                                </div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Line Chart */}
                <div className="card card-success">
                    <div className="card-header">
                        <h3 className="card-title">Profit & Loss</h3>
                        <div className="card-tools">
                            <button className="btn btn-tool" data-card-widget="collapse"><i className="fas fa-minus" /></button>
                            <button className="btn btn-tool" data-card-widget="remove"><i className="fas fa-times" /></button>
                        </div>
                    </div>
                    <div className="card-body">
                        <canvas id="lineChart" style={{ height: 250 }} />
                    </div>
                </div>
            </div>
        </div>
        </AdminLayout>
    );
};

export default Dashboard;
