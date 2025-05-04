import React from 'react';
import { useForm } from '@inertiajs/react';
import AdminLayout from "@/lteLayouts/AdminLayout.jsx";

export default function CompanyForm() {
    const { data, setData, post, processing, errors, reset } = useForm({
        BRNo: '',
        CompName: '',
        Address: '',
        Email: '',
        WebUrl: '',
        OfcTel: '',
        Mobile: '',
        Fax: '',
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post('/company/store', {
            onSuccess: () => reset(), // Clear form on success
        });
    };

    return (
        <AdminLayout>
        <div className="container-fluid">
            <div className="row">
                <div className="offset-md-3 col-md-6">
                    <div className="card card-primary card-outline">
                        <div className="card-header">
                            <h3 className="card-title">My Company</h3>
                        </div>

                        <form onSubmit={handleSubmit}>
                            <div className="card-body">
                                {[
                                    { id: 'BRNo', label: 'BR Number' },
                                    { id: 'CompName', label: 'Company Name' },
                                    { id: 'Address', label: 'Address', type: 'textarea' },
                                    { id: 'Email', label: 'Email', type: 'email' },
                                    { id: 'WebUrl', label: 'Web Site' },
                                    { id: 'OfcTel', label: 'Office Telephone' },
                                    { id: 'Mobile', label: 'Mobile', type: 'tel' },
                                    { id: 'Fax', label: 'Fax', type: 'tel' },
                                ].map(({ id, label, type = 'text' }) => (
                                    <div className="mb-3" key={id}>
                                        <label htmlFor={id} className="form-label">{label}</label>
                                        {type === 'textarea' ? (
                                            <textarea
                                                id={id}
                                                className="form-control"
                                                rows="1"
                                                value={data[id]}
                                                onChange={(e) => setData(id, e.target.value)}
                                            />
                                        ) : (
                                            <input
                                                type={type}
                                                id={id}
                                                className="form-control"
                                                maxLength="15"
                                                value={data[id]}
                                                onChange={(e) => setData(id, e.target.value)}
                                            />
                                        )}
                                        {errors[id] && (
                                            <div className="text-danger mt-1 small">{errors[id]}</div>
                                        )}
                                    </div>
                                ))}
                            </div>

                            <div className="card-footer">
                                <button type="submit" className="btn btn-primary float-end" disabled={processing}>
                                    {processing ? 'Submitting...' : 'Submit'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </AdminLayout>
    );
}
