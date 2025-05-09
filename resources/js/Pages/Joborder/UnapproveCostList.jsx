import React, {useEffect, useState} from 'react';
import { Link } from '@inertiajs/react';
import JobOrderReviewModal from "@/Pages/Joborder/UnapproveCost/JobOrderReviewModal.jsx";
import {transformItemList} from "@/Hooks/Joborder/transformItemList.js";

const JobOrderApproval = ({ jobOrders }) => {
    const [showReviewModal, setShowReviewModal] = useState(false);
    const [selectedJobData, setSelectedJobData] = useState(null);

    // const [defaultCurrency, setDefaultCurrency] = useState(settings.default_currency);
    const [laborTaskList, setLaborTaskList] = useState([]);
    const [jbItemList, setJbItemList] = useState([]);
    const [servicePkgList, setServicePkgList] = useState([]);
    const [deleteServicePkgList, setDeleteServicePkgList] = useState([]);
    const [deleteJbItemList, setDeleteJbItemList] = useState([]);

    const handleReview = (job) => {
        setSelectedJobData(job); // Or fetch job data via Inertia visit/axios
        setShowReviewModal(true);
    };

    useEffect(() => {
        const table = new DataTable('#example2', {
            processing: true,
            serverSide: true,
            ajax: route('joborder.approve.cost.table.list'),
            columns: [
                { data: 'woSerialNo', name: 'woSerialNo' },
                { data: 'serial_no', name: 'serial_no' },
                { data: 'name', name: 'name' },
                { data: 'vinNo', name: 'vinNo', className: 'text-center' },
                { data: 'taskStatus', name: 'taskStatus' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
            ],
            initComplete: () => {
                window.$('[data-bs-toggle="tooltip"]').tooltip();
            },
        });

        return () => {
            table.destroy();
        };
    }, []);

    const [totServPkgAmnt, setTotServPkgAmnt] = useState('');

    const reviewJoborder = async (empID) => {
        try {
            StartLoading();
            const { data } = await axios.get(route('joborder.edit.data'), {
                params: {
                    _token: document.querySelector('meta[name="csrf-token"]').content,
                    emp_id: empID,
                },
            });

            StopLoading();

            if (data.status) {
                const servPkgList = data.data.serv_pkg_data.map(item => transformServicePkg(item));
                const laborTaskList = data.data.task_data.map(item => transformLaborTask(item));
                const jbItemList = data.data.item_data.map(item => transformItemList(item)); // implement transformItemList separately

                setServicePkgList(servPkgList);
                setLaborTaskList(laborTaskList);
                setJbItemList(jbItemList);

                setModalVisible(true);
            } else {
                toast.warning("Failed to fetch job order details.");
            }
        } catch (err) {
            StopLoading();
            toast.error("Error fetching job order details.");
        }
    };

    const calculateServPkgTblTot = (servicePkgList) => {
        const subtotal = servicePkgList.reduce((total, item) => {
            const value = parseFloat(item.col5.replace(/[^0-9-.]/g, '')) || 0;
            return total + value;
        }, 0);
        setTotServPkgAmnt(accounting.formatNumber(subtotal, 2));
    };

    const transformServicePkg = (obj) => {
        if (!obj || !obj.serv_pkg) return {};
        const s = obj.serv_pkg;
        return {
            col1: s.service_name || '',
            col2: s.description || '',
            col3: s.type_name || '',
            col4: accounting.formatNumber(s.price || 0, 2),
            col5: accounting.formatNumber(s.price || 0, 2),
            col6: s.service_pkg_id,
            col7: s.id,
        };
    };

    const transformLaborTask = (obj) => {
        if (!obj || !obj.task) return {};
        const t = obj.task;
        return {
            col1: t.task_name || '',
            col2: t.task_description || '',
            col3: t.hours || '',
            col4: t.actual_hours || '',
            col5: accounting.formatNumber(t.hourly_rate || 0, 2),
            col6: accounting.formatNumber(t.labor_cost || 0, 2),
            col7: accounting.formatNumber(t.total || 0, 2),
            col8: t.task_id,
            col9: t.id,
            col10: t.is_approve_cost,
            col11: t.is_approve_work,
        };
    };

    const loadItems = (items) => {
        const transformedItems = items.map(item => transformItemList(item, "up"));
        setJbItemList(transformedItems);
    };

    return (
        <div className="card" id="task_list_view">
            <div className="card-header">
                <h3 className="card-title">To Be Approved (Cost) Job Orders</h3>
            </div>

            <div className="card-body">
                <div className="row">
                    <div className="col-md-12">
                        <div className="table-responsive">
                            <table className="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>WO No</th>
                                    <th>JO No</th>
                                    <th>Customer Name</th>
                                    <th>Vin No</th>
                                    {/* <th>Total</th> */}
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                {jobOrders.length > 0 ? (
                                    jobOrders.map((order) => (
                                        <tr key={order.id}>
                                            <td>{order.wo_number}</td>
                                            <td>{order.jo_number}</td>
                                            <td>{order.customer_name}</td>
                                            <td>{order.vin_number}</td>
                                            <td>{order.status}</td>
                                            <td>
                                                <Link
                                                    href={route('job-orders.show', order.id)}
                                                    className="btn btn-primary btn-sm"
                                                >
                                                    Review
                                                </Link>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="6" className="text-center">
                                            No job orders to approve.
                                        </td>
                                    </tr>
                                )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {/* Job order review modal can be added here when needed */}
            {selectedJobData && (
                <JobOrderReviewModal
                    show={showReviewModal}
                    onClose={() => setShowReviewModal(false)}
                    onSubmit={() => {
                        // Submit logic here
                    }}
                    jobData={selectedJobData}
                />
            )}
        </div>
    );
};

export default JobOrderApproval;
