import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import AdminLayout from "@/lteLayouts/AdminLayout.jsx";

export default function Show() {
    const { user, user_role } = usePage().props;

    console.log(user_role);
    return (
        <AdminLayout>
            <div className="container-fluid py-4">
            <div className="row justify-center">
                <div className="col-md-6 offset-md-3">
                    <div className="card card-primary card-outline shadow">
                        <div className="card-header">
                            <h3 className="card-title">Details of {user.name} User</h3>
                            <div className="card-tools">
                                <Link href={route('user.index')} className="btn btn-sm btn-secondary">
                                    <i className="fas fa-hand-o-left mr-1"></i> Go Back
                                </Link>
                            </div>
                        </div>

                        <div className="card-body">
                            <div className="form-group">
                                <strong>Name:</strong> {user.name}
                            </div>
                            <div className="form-group">
                                <strong>Email:</strong> {user.email}
                            </div>
                            <div className="form-group">
                                <strong>Roles:</strong>{' '}
                                {user?.roles && user.roles.length > 0 ? (
                                    user.roles.map((role, index) => (
                                        <span key={index} className="badge bg-success me-2">
            {role.name}
        </span>
                                    ))
                                ) : (
                                    <span className="text-muted">No roles assigned</span>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </AdminLayout>
    );
}
