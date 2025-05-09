import React from 'react';
import { Modal, Button } from 'react-bootstrap'; // Using React Bootstrap for modal behavior
import PropTypes from 'prop-types';

const JobOrderReviewModal = ({ show, onClose, onSubmit, jobData }) => {
    return (
        <Modal show={show} onHide={onClose} size="xl" backdrop="static">
            <Modal.Header closeButton>
                <Modal.Title>Job Order Review</Modal.Title>
            </Modal.Header>
            <Modal.Body>
                {/* Labor Task Table */}

                <div className="table-responsive mb-4">
                    <table className="table table-condensed table-bordered table-hover">
                        <thead>
                        <tr className="text-center">
                            <th>Labor Task</th>
                            <th>Hours</th>
                            <th>Act: Hours</th>
                            <th>Hour Rate</th>
                            <th>Labor Cost</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        {jobData.laborTasks.map((task, index) => (
                            <React.Fragment key={index}>
                                <tr style={{ backgroundColor: 'aliceblue' }}>
                                    <td className="text-center">{task.task}</td>
                                    <td>
                                        <input
                                            type="number"
                                            className="form-control text-center"
                                            value={task.hours}
                                            onChange={(e) => task.setHours(e.target.value)}
                                        />
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            className="form-control text-center"
                                            value={task.actualHours}
                                            onChange={(e) => task.setActualHours(e.target.value)}
                                        />
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            className="form-control text-right"
                                            value={task.hourRate}
                                            onChange={(e) => task.setHourRate(e.target.value)}
                                        />
                                    </td>
                                    <td>
                                        <input
                                            type="number"
                                            className="form-control text-right"
                                            value={task.laborCost}
                                            readOnly
                                        />
                                    </td>
                                    <td className="text-right">{task.total}</td>
                                </tr>
                                <tr>
                                    <td colSpan="6">
                      <textarea
                          rows="1"
                          className="form-control"
                          value={task.description}
                          onChange={(e) => task.setDescription(e.target.value)}
                      />
                                    </td>
                                </tr>
                            </React.Fragment>
                        ))}
                        </tbody>
                    </table>
                </div>

                {/* Service Package Table */}
                <div className="table-responsive mb-4">
                    <table className="table table-condensed table-bordered table-hover mb-0">
                        <thead>
                        <tr className="text-center">
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Vehicle Type</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        {jobData.servicePackages.map((pkg, index) => (
                            <tr key={index}>
                                <td className="text-center">{pkg.name}</td>
                                <td className="text-center">{pkg.description}</td>
                                <td className="text-center">{pkg.vehicleType}</td>
                                <td className="text-right">{pkg.price}</td>
                                <td className="text-right">{pkg.subtotal}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                </div>

                {/* Inventory Items Table */}
                <div className="table-responsive">
                    <table className="table table-condensed table-bordered">
                        <thead>
                        <tr className="text-center">
                            <th>Product</th>
                            <th>Net Unit Price</th>
                            <th>Qty</th>
                            {jobData.showDiscount && <th>Discount</th>}
                            {jobData.showTax && <th>Product Tax</th>}
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        {jobData.inventoryItems.map((item, index) => (
                            <tr key={index} title={item.tooltip}>
                                <td className="text-center">
                                    {item.code} - {item.name}
                                    {item.optionName && ` (${item.optionName})`}
                                </td>
                                <td className="text-right">{item.unitPrice}</td>
                                <td className="text-center">
                                    {item.status === 'serv' ? (
                                        item.quantity
                                    ) : (
                                        <input
                                            type="text"
                                            className="form-control text-center"
                                            value={item.quantity}
                                            onChange={(e) => item.setQuantity(e.target.value)}
                                        />
                                    )}
                                </td>
                                {jobData.showDiscount && (
                                    <td className="text-right">{item.discount}</td>
                                )}
                                {jobData.showTax && (
                                    <td className="text-right">
                                        ({item.taxRate}) {item.taxValue}
                                    </td>
                                )}
                                <td className="text-right">{item.subtotal}</td>
                            </tr>
                        ))}
                        </tbody>
                    </table>
                </div>
            </Modal.Body>
            <Modal.Footer className="justify-content-between">
                <Button variant="secondary" onClick={onClose}>
                    Close
                </Button>
                <Button variant="success" onClick={onSubmit}>
                    Submit
                </Button>
            </Modal.Footer>
        </Modal>
    );
};

JobOrderReviewModal.propTypes = {
    show: PropTypes.bool.isRequired,
    onClose: PropTypes.func.isRequired,
    onSubmit: PropTypes.func.isRequired,
    jobData: PropTypes.shape({
        laborTasks: PropTypes.array.isRequired,
        servicePackages: PropTypes.array.isRequired,
        inventoryItems: PropTypes.array.isRequired,
        showDiscount: PropTypes.bool,
        showTax: PropTypes.bool,
    }).isRequired,
};

export default JobOrderReviewModal;
