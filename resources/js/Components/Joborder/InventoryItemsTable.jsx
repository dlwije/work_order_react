import React from "react";

export default function InventoryItemsTable({ items, currency, onRemove, onEdit }) {
    return (
        <div className="row">
            <div className="col-md-12">
                <div className="table-responsive">
                    <table className="table table-bordered">
                        <thead>
                        <tr>
                            <th className="text-center">Product</th>
                            <th className="text-center">Net Unit Price</th>
                            <th className="text-center">Qty</th>
                            <th className="text-center">Discount</th>
                            <th className="text-center">Product Tax</th>
                            <th className="text-center">Subtotal ({currency})</th>
                            <th className="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        {items.map((item, idx) => (
                            <tr key={idx}>
                                <td className="text-center">
                                    {item.code} - {item.name}
                                    {item.optionName && ` (${item.optionName})`}
                                    {!item.isService && (
                                        <i
                                            className="fas fa-edit float-end"
                                            style={{ cursor: "pointer" }}
                                            onClick={() => onEdit(idx)}
                                        ></i>
                                    )}
                                </td>
                                <td className="text-end">{item.isService ? "0.00" : item.price}</td>
                                <td className="text-center">
                                    {item.isService ? (
                                        item.qty
                                    ) : (
                                        <input
                                            type="text"
                                            className="form-control text-center"
                                            value={item.qty}
                                            onChange={(e) => item.onQtyChange(idx, e.target.value)}
                                        />
                                    )}
                                </td>
                                <td className="text-end">{item.isService ? "0.00" : item.discount}</td>
                                <td className="text-end">
                                    {item.isService ? "0.00" : `(${item.taxRate}) ${item.taxVal}`}
                                </td>
                                <td className="text-end">{item.isService ? "0.00" : item.subtotal}</td>
                                <td className="text-center">
                                    {!item.isService && (
                                        <i
                                            className="fa fa-trash-alt"
                                            style={{ cursor: "pointer" }}
                                            onClick={() => onRemove(idx)}
                                        ></i>
                                    )}
                                </td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}
