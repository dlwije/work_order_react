import React from "react";

const JobItemListTable = ({ jbItemList }) => {
    return (
        <div className="table-responsive">
            <table className="table table-bordered table-striped">
                <thead className="thead-light">
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Description</th>
                    <th>Taxable</th>
                    <th>Tax Rate</th>
                    <th>Discount</th>
                    <th>Total Discount</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                {jbItemList.length === 0 ? (
                    <tr>
                        <td colSpan="12" className="text-center">No items added</td>
                    </tr>
                ) : (
                    jbItemList.map((item, index) => (
                        <tr key={index}>
                            <td>{item.col1}</td>
                            <td>{item.col2}</td>
                            <td>{item.col3}</td>
                            <td>{item.col4}</td>
                            <td className="text-right">{item.col5}</td>
                            <td className="text-right">{item.col6}</td>
                            <td>{item.col12}</td>
                            <td>{item.col11 === 1 ? "Yes" : "No"}</td>
                            <td className="text-right">{item.col10}</td>
                            <td>{item.col16} {item.col15 === "percentage" ? "%" : ""}</td>
                            <td className="text-right">{item.col17}</td>
                            <td>
                                <button
                                    className="btn btn-sm btn-primary"
                                    title="Edit"
                                    data-toggle="tooltip"
                                >
                                    <i className="fas fa-edit"></i>
                                </button>{" "}
                                <button
                                    className="btn btn-sm btn-danger"
                                    title="Delete"
                                    data-toggle="tooltip"
                                >
                                    <i className="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    ))
                )}
                </tbody>
            </table>
        </div>
    );
};

export default JobItemListTable;
