import { useEffect, useRef } from 'react';
import Swal from 'sweetalert2';
import axios from 'axios';
import 'datatables.net-bs5';
import AdminLayout from "@/lteLayouts/AdminLayout.jsx";
import {Link} from "@inertiajs/react";
// import 'sweetalert2/src/sweetalert2.scss';
import useRoles from "@/Hooks/useRoles.js";
import { Inertia } from '@inertiajs/inertia';
import { Tooltip } from 'bootstrap';

const JobOrders = () => {
    const { user, url, unlessRole, hasRole, hasPermission, hasAnyPermission } = useRoles();

    useEffect(() => {

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach((tooltipTriggerEl) => {
            new Tooltip(tooltipTriggerEl);
        });

        const table = $('#example2').DataTable({
            processing: true,
            serverSide: true,
            ajax: route('joborder.table.list'),
            columns: [
                { data: 'woSerialNo', name: 'woSerialNo' },
                { data: 'serial_no', name: 'serial_no' },
                { data: 'jo_date', name: 'jo_date' },
                { data: 'name', name: 'name' },
                { data: 'joStatus', name: 'joStatus' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
            ],
            initComplete: () => {
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
        });

        return () => {
            table.destroy();
        };
    }, []);

    const showDetails = (empID) => {
        Inertia.get(route('joborder.show'), { emp_id: empID }, {
            preserveScroll: true,
            onSuccess: ({ props }) => {
                const data = props.flash.data;
                if (data?.status) {
                    $('#show_mod_title').html(data.data[0].full_name);
                    $('#show_mod_fname').html(data.data[0].first_name);
                    $('#show_mod_lname').html(data.data[0].last_name);
                    $('#emp_show_mod').modal('show');
                }
            },
        });
    };

    const deleteRecord = (empID) => {
        Swal.fire({
            title: 'Are you sure, You want to delete this?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, confirm it!',
            customClass: {
                confirmButton: 'btn btn-primary mr-1',
                cancelButton: 'btn btn-warning',
            },
            buttonsStyling: false,
        }).then((result) => {
            if (result.isConfirmed) {
                Inertia.post(route('joborder.destroy'), { emp_id: empID }, {
                    preserveScroll: true,
                    onSuccess: ({ props }) => {
                        const data = props.flash.data;
                        if (data?.status) {
                            $('#example2').DataTable().ajax.reload();
                            Swal.fire('Success!', data.message, 'success');
                        }
                    },
                });
            } else {
                Swal.fire('Canceled!', 'Record deletion canceled.', 'info');
            }
        });
    };

    return (
        <AdminLayout>
            <div className="card card-outline card-primary">
                <div className="card-header">
                    <h3 className="card-title">Job Orders</h3>
                    {hasPermission('joborder-add') && (
                        <div className="card-tools">
                            <Link href={route('joborder.create')} className="btn btn-sm btn-success">
                                <i className="fas fa-plus mr-1"></i> Add New
                            </Link>
                        </div>
                    )}
                </div>
                <div className="card-body">
                    <div className="table-responsive">
                        <table id="example2" className="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>WO SerialNo</th>
                                <th>JO SerialNo</th>
                                <th>JO Date</th>
                                <th>Customer Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
};

export default JobOrders;
