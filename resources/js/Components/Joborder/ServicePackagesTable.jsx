// File: components/ServicePackagesTable.jsx
import React, {useState} from "react";
import ServicePackageModal from "@/Components/Joborder/ServicePackageModal.jsx";

export default function ServicePackagesTable({ servicePackages, currency, onAdd, onRemove, totalAmount }) {
    const [modalSPOpen, setModalSPOpen] = useState(false);

    return (
        <div className="card card-outline card-primary mb-2">

            <div className="card-body p-0">
                <div className="row">
                    <div className="col-md-12">
                        <div className="table-responsive">
                            <table className="table table-bordered table-hover mb-0">
                                <thead>
                                <tr>
                                    <th className="text-center">Service Name</th>
                                    <th className="text-center">Description</th>
                                    <th className="text-center">Vehicle Type</th>
                                    <th className="text-center">Price</th>
                                    <th className="text-center">Subtotal</th>
                                    <th className="text-center">
                                        <button type="button" className="btn btn-primary"
                                                onClick={() => {
                                                    setModalSPOpen(true);
                                                }}><i
                                            className="fas fa-plus"></i>
                                        </button>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                {servicePackages.map((pkg, idx) => (
                                    <tr key={idx}>
                                        <td className="text-center">{pkg.name}</td>
                                        <td className="text-center">{pkg.description}</td>
                                        <td className="text-center">{pkg.vehicleType}</td>
                                        <td className="text-end">{pkg.price}</td>
                                        <td className="text-end">{pkg.subtotal}</td>
                                        <td className="text-center">
                                            <i
                                                className="fa fa-trash-alt"
                                                style={{cursor: "pointer"}}
                                                title="Remove this row"
                                                onClick={() => onRemove(idx)}
                                            ></i>
                                        </td>
                                    </tr>
                                ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div className="row mt-3 mb-3">
                    <div className="offset-md-10 col-md-2">
                        <input
                            type="text"
                            className="form-control text-end"
                            value={totalAmount}
                            readOnly
                            disabled
                        />
                    </div>
                </div>
            </div>
            <ServicePackageModal
                isOpen={modalSPOpen} onClose={() => setModalSPOpen(false)} onDone={() => setModalSPOpen(false)}
                servicePackages={servicePackages}
                onSelect={(pkg) => onAdd(pkg)}
            />
        </div>
    );
}
