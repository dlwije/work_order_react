import React from 'react';

export default function VehicleAccordion({ form }) {
    return (
        <div className="accordion mt-4" id="vehicleAccordion">
            <div className="accordion-item">
                <h2 className="accordion-header" id="headingVehicle">
                    <button className="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVehicle">
                        Vehicle Information
                    </button>
                </h2>
                <div id="collapseVehicle" className="accordion-collapse collapse show">
                    <div className="accordion-body">

                        {/* Tabs Navigation */}
                        <ul className="nav nav-tabs" id="vehicleTabs" role="tablist">
                            <li className="nav-item" role="presentation">
                                <button className="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                                    Info
                                </button>
                            </li>
                            <li className="nav-item" role="presentation">
                                <button className="nav-link" id="warranty-tab" data-bs-toggle="tab" data-bs-target="#warranty" type="button" role="tab">
                                    Warranty
                                </button>
                            </li>
                            <li className="nav-item" role="presentation">
                                <button className="nav-link" id="insurance-tab" data-bs-toggle="tab" data-bs-target="#insurance" type="button" role="tab">
                                    Insurance & Contact
                                </button>
                            </li>
                        </ul>

                        {/* Tabs Content */}
                        <div className="tab-content pt-3">
                            <div className="tab-pane fade show active" id="info" role="tabpanel">
                                <div className="row">
                                    <div className="col-md-3 mb-2">
                                        <label>Registration No</label>
                                        <input className="form-control" value={form.mod_cont?.registration_no || ''} readOnly />
                                    </div>
                                    <div className="col-md-3 mb-2">
                                        <label>Brand</label>
                                        <input className="form-control" value={form.mod_cont?.brand || ''} readOnly />
                                    </div>
                                    <div className="col-md-3 mb-2">
                                        <label>Model</label>
                                        <input className="form-control" value={form.mod_cont?.model || ''} readOnly />
                                    </div>
                                    <div className="col-md-3 mb-2">
                                        <label>Year</label>
                                        <input className="form-control" value={form.mod_cont?.year || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Provider/Company Name</label>
                                        <input className="form-control" id="id_mod_cont_comp_name" value={form.mod_cont?.comp_name || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Contract No</label>
                                        <input className="form-control" id="id_mod_cont_warranty_no" value={form.mod_cont?.warranty_no || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Contract Expiry</label>
                                        <input className="form-control" id="id_mod_cont_expire_date" value={form.mod_cont?.expiry_date || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Phone Number</label>
                                        <input className="form-control" id="id_mod_cont_phone_no" value={form.mod_cont?.phone_no || ''} readOnly />
                                    </div>
                                </div>
                            </div>

                            <div className="tab-pane fade" id="warranty" role="tabpanel">
                                <div className="row">
                                    <div className="col-md-4 mb-2">
                                        <label>Provider/Company Name</label>
                                        <input className="form-control" id="id_mod_warr_comp_name" value={form.mod_warr?.comp_name || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Warranty No</label>
                                        <input className="form-control" id="id_mod_warr_warranty_no" value={form.mod_warr?.warranty_no || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Date of Purchase</label>
                                        <input className="form-control" id="id_mod_warr_dop" value={form.mod_warr?.dop || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Warranty Expiry</label>
                                        <input className="form-control" id="id_mod_warr_expire_date" value={form.mod_warr?.expiry_date || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Phone Number</label>
                                        <input className="form-control" id="id_mod_warr_phone_no" value={form.mod_warr?.phone_no || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Warranty Type</label>
                                        <input className="form-control" value={form.mod_warr?.warranty_type || ''} readOnly />
                                    </div>
                                </div>
                            </div>

                            <div className="tab-pane fade" id="insurance" role="tabpanel">
                                <div className="row">
                                    <div className="col-md-4 mb-2">
                                        <label>Insurance Company</label>
                                        <input className="form-control" id="id_mod_ins_comp_name" value={form.mod_ins?.insurance_company || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Policy No</label>
                                        <input className="form-control" id="id_mod_ins_policy_no" value={form.mod_ins?.policy_no || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Expiry Date</label>
                                        <input className="form-control" id="id_mod_ins_expire_date" value={form.mod_ins?.expiry_date || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Contact Person</label>
                                        <input className="form-control" value={form.mod_ins?.contact_person || ''} readOnly />
                                    </div>
                                    <div className="col-md-4 mb-2">
                                        <label>Phone Number</label>
                                        <input className="form-control" id="id_mod_ins_phone_no" value={form.mod_ins?.phone_no || ''} readOnly />
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    );
}
