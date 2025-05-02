import { useEffect, useRef } from 'react';
import Swal from 'sweetalert2';
import axios from 'axios';

export default function EmployeeList({ employeesIndexUrl, employeeShowUrl, employeeDeleteUrl, canAdd }) {
    const tableRef = useRef(null);

    useEffect(() => {
        const table = $(tableRef.current).DataTable({
            processing: true,
            serverSide: true,
            ajax: employeesIndexUrl,
            columns: [
                { data: 'emp_no', name: 'emp_no' },
                { data: 'full_name', name: 'full_name' },
                { data: 'updated_at', name: 'updated_at' },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                }
            ],
            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        return () => {
            table.destroy(true);
        };
    }, []);

    const showDetails = (empId) => {
        axios.get(employeeShowUrl, { params: { emp_id: empId } })
            .then(({ data }) => {
                if (data.status) {
                    $('#show_mod_title').text(data.data[0].full_name);
                    $('#show_mod_fname').text(data.data[0].first_name);
                    $('#show_mod_lname').text(data.data[0].last_name);
                    $('#emp_show_mod').modal('show');
                } else {
                    Swal.fire('Failed', 'Employee not found', 'warning');
                }
            });
    };

    const deleteRecord = (empId) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post(employeeDeleteUrl, {
                    emp_id: empId,
                    _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }).then(() => {
                    $(tableRef.current).DataTable().ajax.reload();
                    Swal.fire('Deleted!', 'Record has been deleted.', 'success');
                }).catch(() => {
                    Swal.fire('Error', 'Could not delete record.', 'error');
                });
            }
        });
    };

    return (
        <>
            <div className="card" id="employee_list_view">
                <div className="card-header d-flex justify-content-between align-items-center">
                    <h3 className="card-title">Employees</h3>
                    {canAdd && (
                        <a href="/employee/create" className="btn btn-success btn-sm">
                            <i className="fas fa-plus mr-1"></i> Add New
                        </a>
                    )}
                </div>
                <div className="card-body">
                    <div className="table-responsive">
                        <table ref={tableRef} className="table table-bordered table-hover" id="example2">
                            <thead>
                            <tr>
                                <th>Employee No</th>
                                <th>Full Name</th>
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
            <div className="modal fade" id="emp_show_mod">
                <div className="modal-dialog modal-lg">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h4 className="modal-title">Details of <span id="show_mod_title" /></h4>
                            <button type="button" className="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div className="modal-body">
                            <dl className="row">
                                <dt className="col-sm-3">First Name</dt>
                                <dd className="col-sm-9" id="show_mod_fname"></dd>
                                <dt className="col-sm-3">Last Name</dt>
                                <dd className="col-sm-9" id="show_mod_lname"></dd>
                            </dl>
                        </div>
                        <div className="modal-footer justify-content-between">
                            <button type="button" className="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
