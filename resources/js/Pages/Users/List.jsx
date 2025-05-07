import { useEffect, useRef } from 'react';
import Swal from 'sweetalert2';
import axios from 'axios';
import 'datatables.net-bs5';
import AdminLayout from "@/lteLayouts/AdminLayout.jsx";
import {Link} from "@inertiajs/react";

export default function UserList({ usersIndexUrl, userCreateUrl, userShowUrl, userDeleteUrl, canCreate }) {
    const tableRef = useRef(null);

    useEffect(() => {
        // const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        // [...tooltipTriggerList].forEach(tooltipTriggerEl => new Tooltip(tooltipTriggerEl));

        const table = $(tableRef.current).DataTable({
            processing: true,
            serverSide: true,
            ajax: usersIndexUrl,
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'roles', name: 'roles' },
                { data: 'updated_at', name: 'updated_at' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
            ],
            drawCallback: function () {
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
        });

        return () => {
            table.destroy(true);
        };
    }, []);

    const showUser = (userId) => {
        axios.get(userShowUrl, { params: { id: userId } })
            .then(({ data }) => {
                if (data.status) {
                    $('#user_show_name').text(data.data.name);
                    $('#user_show_email').text(data.data.email);
                    $('#user_show_roles').text(data.data.roles.join(', '));
                    $('#user_show_modal').modal('show');
                } else {
                    Swal.fire('Failed', 'Users not found', 'warning');
                }
            });
    };

    const deleteUser = (userId) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won’t be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`${userDeleteUrl}/${userId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then((res) => {
                    $(tableRef.current).DataTable().ajax.reload();
                    Swal.fire('Deleted!', res.data.message, 'success');
                }).catch(() => {
                    Swal.fire('Error', 'Could not delete user.', 'error');
                });
            }
        });
    };

    return (
        <>
            <AdminLayout>
                <div className="card card-outline card-primary">
                    <div className="card-header">
                        <h3 className="card-title">Users</h3>
                        <div className="card-tools">
                            {canCreate && (
                                <Link href={userCreateUrl} className="btn btn-sm btn-success">
                                    <i className="fas fa-plus mr-1"></i> Add New
                                </Link>
                            )}
                        </div>
                    </div>
                    <div className="card-body">
                        <div className="table-responsive">
                            <table ref={tableRef} className="table table-bordered table-hover" id="users_table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Updated On</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {/* Modal */}
                <div className="modal fade" id="user_show_modal">
                    <div className="modal-dialog modal-md">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h4 className="modal-title">User Details - <span id="user_show_name" /></h4>
                                <button type="button" className="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div className="modal-body">
                                <dl className="row">
                                    <dt className="col-sm-4">Email</dt>
                                    <dd className="col-sm-8" id="user_show_email"></dd>
                                    <dt className="col-sm-4">Roles</dt>
                                    <dd className="col-sm-8" id="user_show_roles"></dd>
                                </dl>
                            </div>
                            <div className="modal-footer justify-content-between">
                                <button type="button" className="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </AdminLayout>
        </>
    );
}
