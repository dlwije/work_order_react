import React from 'react';

const ServicePackageModal = ({ isOpen, onClose, onDone, servicePackages = [], onSelect }) => {

    return (
        <div className="modal fade" id="serv_pkg_mod" tabIndex="-1" aria-hidden="true">
            <div className="modal-dialog modal-xl">
                <div className="modal-content">
                    <div className="modal-header">
                        <h4 className="modal-title">Select service packages to the job order</h4>
                        <button
                            type="button"
                            className="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                            onClick={onClose}
                        ></button>
                    </div>
                    <div className="modal-body">
                        <div className="row">
                            <div className="col-md-12">
                                <div className="table-responsive">
                                    <table
                                        className="table table-bordered table-hover"
                                        id="id_serv_pkg_tbl"
                                        style={{ width: '100%' }}
                                    >
                                        <thead>
                                        <tr>
                                            <th>Service Name</th>
                                            <th>Price</th>
                                            <th>Vehicle Type</th>
                                            <th>Created On</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody id="id_serv_pkg_tbody">
                                        {servicePackages.length > 0 ? (
                                            servicePackages.map((pkg, index) => (
                                                <tr key={index}>
                                                    <td>{pkg.name}</td>
                                                    <td>{pkg.price}</td>
                                                    <td>{pkg.vehicle_type}</td>
                                                    <td>{pkg.created_at}</td>
                                                    <td className="text-center">
                                                        <button
                                                            type="button"
                                                            className="btn btn-sm btn-primary"
                                                            onClick={() => onSelect(pkg)}
                                                        >
                                                            <i className="fas fa-plus"></i> Add
                                                        </button>
                                                    </td>
                                                </tr>
                                            ))
                                        ) : (
                                            <tr>
                                                <td colSpan="5" className="text-center text-muted">
                                                    No service packages found.
                                                </td>
                                            </tr>
                                        )}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div className="modal-footer justify-content-between">
                        <button
                            type="button"
                            className="btn btn-default"
                            data-bs-dismiss="modal"
                            onClick={onClose}
                        >
                            Close
                        </button>
                        <button
                            type="button"
                            className="btn btn-success"
                            data-bs-dismiss="modal"
                            onClick={onClose}
                        >
                            Done
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ServicePackageModal;
