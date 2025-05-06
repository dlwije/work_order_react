import React from 'react';
const LaborTaskModal = ({ isOpen, onClose, onDone, tasks = [] }) => {
    if (!isOpen) return null;

    return (
        <div className="modal fade show d-block" style={{ backgroundColor: 'rgba(0,0,0,0.5)' }}>
            <div className="modal-dialog modal-xl">
                <div className="modal-content">
                    <div className="modal-header">
                        <h4 className="modal-title">Select labor task to the job order</h4>
                        <button
                            type="button"
                            className="btn-close"
                            onClick={onClose}
                            aria-label="Close"
                        />
                    </div>
                    <div className="modal-body">
                        <div className="table-responsive">
                            <table className="table table-bordered table-hover w-100">
                                <thead className="table-light">
                                <tr>
                                    <th>Task Code</th>
                                    <th>Task Name</th>
                                    <th>Source</th>
                                    <th>Category</th>
                                    <th>Model/Class</th>
                                    <th>Hour</th>
                                    <th>Rate</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {tasks.length > 0 ? (
                                    tasks.map((task, index) => (
                                        <tr key={index}>
                                            <td>{task.task_code}</td>
                                            <td>{task.task_name}</td>
                                            <td>{task.source}</td>
                                            <td>{task.category}</td>
                                            <td>{task.model_class}</td>
                                            <td>{task.hour}</td>
                                            <td>{task.rate}</td>
                                            <td>

                                                <button
                                                    type="button"
                                                    className="btn btn-default"
                                                    aria-label="Delete"
                                                    onClick={() => task.onDelete?.()}
                                                >
                                                    <i className="fas fa-trash" />
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="8" className="text-center text-muted">
                                            No labor tasks found.
                                        </td>
                                    </tr>
                                )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div className="modal-footer justify-content-between">
                        <button
                            type="button"
                            className="btn btn-default"
                            onClick={onClose}
                            aria-label="Close"
                        />
                        <button
                            type="button"
                            className="btn btn-success"
                            onClick={onDone}
                            aria-label="Done"
                        />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default LaborTaskModal;
