import React from 'react';

const JobOrderTotals = ({
                            totals,
                            onChange,
                            onCalculateTotal,
                            onCalculateDiscount,
                        }) => {
    const renderInput = (name, readOnly = false, onBlur = null) => (
        <input
            type="text"
            name={name}
            className="form-control text-end"
            value={totals[name] || ''}
            onChange={(e) => onChange(name, e.target.value)}
            onBlur={onBlur}
            readOnly={readOnly}
            disabled={readOnly}
        />
    );

    return (
        <>
            {/* Row 1: Totals for parts/labor/other/subtotal */}
            <div className="row mt-3">
                <div className="offset-md-4 col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">
                            Total Parts:
                            <i
                                className="fas fa-info-circle ms-1"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Item prices which are belongs to a specific Service Package will not be added to this amount."
                            ></i>
                        </label>
                        <div className="col-md-7">{renderInput('tot_parts_amnt', true)}</div>
                    </div>
                </div>

                <div className="col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Total Labor:</label>
                        <div className="col-md-7">{renderInput('tot_labor_tsk_amnt', true)}</div>
                    </div>
                </div>

                <div className="col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Total Other:</label>
                        <div className="col-md-7">{renderInput('tot_other_amnt', true)}</div>
                    </div>
                </div>

                <div className="col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Sub Total:</label>
                        <div className="col-md-7">{renderInput('sub_tot_amnt', true)}</div>
                    </div>
                </div>
            </div>

            {/* Row 2: Tax/Supply Charge/Shipping/Total */}
            <div className="row mt-3">
                <div className="offset-md-4 col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Tax1:</label>
                        <div className="col-md-7">{renderInput('tax1_amnt', true)}</div>
                    </div>
                </div>

                <div className="col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Supply Charge:</label>
                        <div className="col-md-7">
                            {renderInput('sup_charge_amnt', false, onCalculateTotal)}
                        </div>
                    </div>
                </div>

                <div className="col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Shipping:</label>
                        <div className="col-md-7">
                            {renderInput('shipping_amnt', false, onCalculateTotal)}
                        </div>
                    </div>
                </div>

                <div className="col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Total:</label>
                        <div className="col-md-7">{renderInput('tot_amnt', true)}</div>
                    </div>
                </div>
            </div>

            {/* Row 3: Discount/Fee/Diag Fee/Grand Total */}
            <div className="row mt-3">
                <div className="offset-md-4 col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Discount:</label>
                        <div className="col-md-7">
                            {renderInput('discount_amnt', false, onCalculateDiscount)}
                        </div>
                    </div>
                </div>

                <div className="col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Fee:</label>
                        <div className="col-md-7">{renderInput('fee_amnt')}</div>
                    </div>
                </div>

                <div className="col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Diag. Fee:</label>
                        <div className="col-md-7">{renderInput('diag_fee_amnt')}</div>
                    </div>
                </div>

                <div className="col-md-2">
                    <div className="form-group row">
                        <label className="col-md-5 col-form-label">Grand Total:</label>
                        <div className="col-md-7">{renderInput('grand_tot_amnt', true)}</div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default JobOrderTotals;
