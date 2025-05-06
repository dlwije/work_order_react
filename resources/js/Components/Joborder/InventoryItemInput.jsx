// File: components/InventoryItemInput.jsx
import React from "react";

export default function InventoryItemInput({ value, onChange }) {
    return (

            <div className="row mb-3">
                <div className="col-md-12">
                    <div className="bg-light p-2">
                        <div className="input-group">
                            <div className="input-group-prepend">
                <span className="input-group-text">
                  <span className="fas fa-barcode fa-2x"></span>
                </span>
                            </div>
                            <input
                                type="text"
                                className="form-control form-control-lg"
                                id="add_item"
                                placeholder="Add product to order"
                                value={value}
                                onChange={onChange}
                            />
                        </div>
                    </div>
                </div>
            </div>

    );
}
