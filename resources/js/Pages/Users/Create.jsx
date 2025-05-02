import React, {useEffect, useState} from 'react';
import {Link, router, usePage} from '@inertiajs/react';
import AdminLayout from "@/lteLayouts/AdminLayout.jsx";
import { toast } from 'react-hot-toast';

export default function CreateUser() {
    const { roles, pos_roles, emp_list, errors, flash } = usePage().props;

    useEffect(() => {
        if (flash?.success_message) {
            toast.success(flash.success_message);
        }

        if (errors?.error) {
            toast.error(errors.error);
        }
    }, [flash, errors]);

    const [form, setForm] = useState({
        emp_id: '',
        email: '',
        username: '',
        password: '',
        confirm_password: '',
        roles: [],
        pos_role: '',
    });

    const handleChange = (e) => {
        const { name, value, type, selectedOptions } = e.target;
        if (type === 'select-multiple') {
            const values = Array.from(selectedOptions).map(option => option.value);
            setForm({ ...form, [name]: values });
        } else {
            setForm({ ...form, [name]: value });
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        router.post('/users/store', form);
    };

    return (
        <AdminLayout>
            <div className="container-fluid">
            <div className="row">

                <div className="offset-md-2 col-md-7">
                    <form onSubmit={handleSubmit}>
                        <div className="card card-primary card-outline">
                            <div className="card-header">
                                <h3 className="card-title">Create User</h3>
                                <div className="card-tools">
                                    <Link className="btn btn-sm btn-secondary" href={route('user.index')}>
                                        <i className="fas fa-hand-o-left"></i> Go Back
                                    </Link>
                                </div>
                            </div>

                            <div className="card-body">
                                {/* Employee */}
                                <div className="form-group">
                                    <strong>Employee: <span className="text-danger">*</span></strong>
                                    <select className="form-control" name="emp_id" required value={form.emp_id} onChange={handleChange}>
                                        <option value="">Select an Employee</option>
                                        {emp_list.map(emp => (
                                            <option key={emp.id} value={emp.id}>{emp.full_name}</option>
                                        ))}
                                    </select>
                                    {errors.emp_id && <div className="text-danger">{errors.emp_id}</div>}
                                </div>

                                {/* Email and Username */}
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <strong>Email: <span className="text-danger">*</span></strong>
                                            <input type="email" name="email" className="form-control" placeholder="Email" required value={form.email} onChange={handleChange} />
                                            {errors.email && <div className="text-danger">{errors.email}</div>}
                                        </div>
                                    </div>
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <strong>Username: <span className="text-danger">*</span></strong>
                                            <input type="text" name="username" className="form-control" placeholder="Username" required value={form.username} onChange={handleChange} />
                                            {errors.username && <div className="text-danger">{errors.username}</div>}
                                        </div>
                                    </div>
                                </div>

                                {/* Passwords */}
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <strong>Password: <span className="text-danger">*</span></strong>
                                            <input type="password" name="password" className="form-control" placeholder="Password" required value={form.password} onChange={handleChange} />
                                            {errors.password && <div className="text-danger">{errors.password}</div>}
                                        </div>
                                    </div>
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <strong>Confirm Password: <span className="text-danger">*</span></strong>
                                            <input type="password" name="confirm_password" className="form-control" placeholder="Confirm Password" required value={form.confirm_password} onChange={handleChange} />
                                            {errors.confirm_password && <div className="text-danger">{errors.confirm_password}</div>}
                                        </div>
                                    </div>
                                </div>

                                {/* Roles */}
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <strong>Work Order Role: <span className="text-danger">*</span></strong>
                                            <select name="roles" multiple className="form-control" required onChange={handleChange}>
                                                {Object.entries(roles).map(([key, value]) => (
                                                    <option key={key} value={key}>{value}</option>
                                                ))}
                                            </select>
                                            {errors.roles && <div className="text-danger">{errors.roles}</div>}
                                        </div>
                                    </div>

                                    {/* POS Role */}
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <strong>POS Role: <span className="text-danger">*</span></strong>
                                            <select name="pos_role" className="form-control" required value={form.pos_role} onChange={handleChange}>
                                                <option value="">Select a Role</option>
                                                {pos_roles.map(role => (
                                                    <option key={role.id} value={role.id}>{role.name}</option>
                                                ))}
                                            </select>
                                            {errors.pos_role && <div className="text-danger">{errors.pos_role}</div>}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="card-footer">
                                <Link href={route('user.index')} className="btn btn-default">Cancel</Link>
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
