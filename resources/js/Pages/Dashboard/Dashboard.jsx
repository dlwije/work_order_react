import React, { useEffect } from 'react';
import Chart from 'chart.js/auto';
import AdminLayout from "@/lteLayouts/AdminLayout.jsx";

const Dashboard = () => {
    useEffect(() => {
        new Chart(document.getElementById('pieChart'), {
            type: 'pie',
            data: {
                labels: ['Customer A', 'Customer B', 'Customer C', 'Customer D', 'Customer E'],
                datasets: [{
                    data: [300, 50, 100, 40, 120],
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: {
                labels: ['New', 'Returning'],
                datasets: [{
                    label: 'Customers',
                    data: [200, 150],
                    backgroundColor: ['#28a745', '#17a2b8']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        new Chart(document.getElementById('lineChart'), {
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
    }, []);

    return (
        <AdminLayout>
        <div className="row">
            <div className="col-6">
                {/* Pie Chart */}
                <div className="card">
                    <div className="card-header">
                        <h3 className="card-title">Top 5 Customers</h3>
                        <div className="card-tools">
                            <button className="btn btn-tool" data-card-widget="collapse"><i className="fas fa-minus" /></button>
                            <button className="btn btn-tool" data-card-widget="remove"><i className="fas fa-times" /></button>
                        </div>
                    </div>
                    <div className="card-body">
                        <canvas id="pieChart" style={{ height: 250 }} />
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
