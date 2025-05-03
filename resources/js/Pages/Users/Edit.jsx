import React from 'react';
import {Link, useForm} from '@inertiajs/react';
import AdminLayout from "@/lteLayouts/AdminLayout.jsx";

export default function EditUser({ user, roles, userRole, empList }) {
    const { data, setData, put, errors } = useForm({
        emp_id: user.emp_id || '',
        name: user.name || '',
        email: user.email || '',
        password: '',
        confirmPassword: '',
        roles: Array.isArray(userRole) ? userRole : [], // ensure it's always an array
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('user.update', user.id));
    };

    return (
        <AdminLayout>
        <div className="container-fluid">
            <div className="row">
                <div className="offset-md-3 col-md-6">
                    <form onSubmit={handleSubmit}>
                        <div className="card card-primary card-outline">
                            <div className="card-header">
                                <h3 className="card-title">Edit User</h3>
                                <div className="card-tools">
                                    <Link className="btn btn-sm btn-secondary" href={route('user.index')}>
                                        <i className="fas fa-hand-point-left me-1"></i>Go Back
                                    </Link>
                                </div>
                            </div>

                            <div className="card-body">
                                <div className="form-group">
                                    <label><strong>Employee:</strong></label>
                                    <select
                                        className="form-control"
                                        name="emp_id"
                                        value={data.emp_id}
                                        onChange={(e) => setData('emp_id', e.target.value)}
                                        required
                                    >
                                        <option value="">Select an Employee</option>
                                        {empList.map((e) => (
                                            <option key={e.id} value={e.id}>
                                                {e.full_name}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.emp_id && <div className="text-danger">{errors.emp_id}</div>}
                                </div>

                                <div className="form-group">
                                    <label><strong>Name:</strong></label>
                                    <input
                                        type="text"
                                        className="form-control"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                    />
                                    {errors.name && <div className="text-danger">{errors.name}</div>}
                                </div>

                                <div className="form-group">
                                    <label><strong>Email:</strong></label>
                                    <input
                                        type="email"
                                        className="form-control"
                                        value={data.email}
                                        onChange={(e) => setData('email', e.target.value)}
                                    />
                                    {errors.email && <div className="text-danger">{errors.email}</div>}
                                </div>

                                <div className="form-group">
                                    <label><strong>Password:</strong></label>
                                    <input
                                        type="password"
                                        className="form-control"
                                        value={data.password}
                                        onChange={(e) => setData('password', e.target.value)}
                                    />
                                    {errors.password && <div className="text-danger">{errors.password}</div>}
                                </div>

                                <div className="form-group">
                                    <label><strong>Confirm Password:</strong></label>
                                    <input
                                        type="password"
                                        className="form-control"
                                        value={data.confirmPassword}
                                        onChange={(e) => setData('confirmPassword', e.target.value)}
                                    />
                                    {errors.confirmPassword && <div className="text-danger">{errors.confirmPassword}</div>}
                                </div>

                                <div className="form-group">
                                    <label><strong>Role:</strong></label>
                                    <select
                                        multiple
                                        className="form-control"
                                        value={data.roles}
                                        onChange={(e) =>
                                            setData('roles', Array.from(e.target.selectedOptions, (option) => option.value))
                                        }
                                    >
                                        {Object.entries(roles).map(([key, label]) => (
                                            <option key={key} value={key}>
                                                {label}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.roles && <div className="text-danger">{errors.roles}</div>}
                                </div>
                            </div>

                            <div className="card-footer">
                                <a href={route('user.index')} className="btn btn-default">Cancel</a>
                                <button type="submit" className="btn btn-success float-end">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        </AdminLayout>
    );
}
