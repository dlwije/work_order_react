import React, {useEffect, useState} from 'react';
import LaborTaskModal from "@/Components/Joborder/LaborTaskModal.jsx";

export default function LaborTaskTable({ tasks, onAdd, onChange, onRemove, currency, laborTasksFromLaravel }) {
    const [modalOpen, setModalOpen] = useState(false);

    // useEffect(() => {
    //     const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    //     const tooltipList = [...tooltipTriggerList].map(
    //         (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    //     );
    //
    //     return () => {
    //         tooltipList.forEach((tooltip) => tooltip.dispose());
    //     };
    // }, []);

    const laborTasks = laborTasksFromLaravel?.map(task => ({
        ...task,
        onDelete: () => console.log('delete task', task.id),
    }));

    const calculateTblLineTotal = (index, rowData, setTasks) => {
        const col3 = parseFloat(rowData.col3);
        const col5 = parseFloat(rowData.col5?.replace(/[^0-9.-]/g, ''));

        if (isNaN(col3) || isNaN(col5)) {
            alert('Unexpected value'); // Replace with sweetAlertMsg if you integrate it
            return;
        }

        const amount = col3 * col5;

        onChange(index, 'col7', amount.toFixed(2));
        // setTasks((prevTasks) => {
        //     const newTasks = [...prevTasks];
        //     newTasks[index] = {
        //         ...newTasks[index],
        //         col7: amount.toFixed(2),
        //     };
        //     return newTasks;
        // });

        calculateLaborTaskTblTot(setTasks);
    };

    const calculateLaborTaskTblTot = (setTasks) => {
        setTasks((prevTasks) => {
            const subtotal = prevTasks.reduce((acc, task) => {
                const val = parseFloat(task.col7?.replace(/[^0-9.-]/g, ''));
                return acc + (isNaN(val) ? 0 : val);
            }, 0);

            // You may store subtotal in a separate state
            console.log('Labor Subtotal:', subtotal.toFixed(2)); // Or setSubtotal(subtotal.toFixed(2))

            return prevTasks;
        });
    };

    return (
        <div className="card mt-4">
            <div className="card-header d-flex justify-between">
                <h5 className="card-title">Labor Tasks</h5>
                {/*<button type="button" className="btn btn-sm btn-primary" onClick={onAdd}>*/}
                {/*    <i className="fas fa-plus"></i> Add Row*/}
                {/*</button>*/}
            </div>
            <div className="card-body p-0">
                <table className="table table-bordered table-sm">
                    <thead className="bg-secondary text-white text-center">
                    <tr>
                        {/*<th>Particulars</th>*/}
                        {/*<th>Qty</th>*/}
                        {/*<th>Rate ({currency})</th>*/}
                        {/*<th>Amount</th>*/}
                        {/*<th>Discount</th>*/}
                        {/*<th>Total</th>*/}
                        {/*<th style={{width: 30}}></th>*/}

                        <th style={{width: 30}}>#</th>
                        <th style="" className="text-center">Labor Task</th>
                        <th style="" className="text-center">Hours</th>
                        <th style="" className="text-center">Act: Hours</th>
                        <th className="text-center" style="">Hour Rate</th>
                        <th className="text-center" style="">Labor Cost</th>
                        <th className="text-center" style="">
                            Subtotal
                            {/*(<span data-bind="html: default_currenty"></span>)*/}
                        </th>
                        <th className="text-center" style="">
                            <button type="button" className="btn btn-primary"
                                    onClick={()=> {
                                        setModalOpen(true);
                                    }}><i
                                className="fas fa-plus"></i></button>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    {tasks?.map((task, index) => (
                        <tr key={index}>
                            <td className="text-center">{index + 1}</td>
                            <td>
                                <input className="form-control form-control-sm"
                                       value={task.col1 || ''}
                                       onChange={(e) => onChange(index, 'col1', e.target.value)}/>
                            </td>
                            {/*<td>*/}
                            {/*    <input type="number" className="form-control form-control-sm"*/}
                            {/*           value={task.col2}*/}
                            {/*           onChange={(e) => onChange(index, 'col2', e.target.value)}/>*/}
                            {/*</td>*/}
                            <td>
                                <input type="number" className="form-control form-control-sm"
                                       value={task.col3 || ''}
                                       onChange={(e) => {
                                           const value = e.target.value;
                                           onChange(index, 'col3', value);
                                           calculateTblLineTotal(index, { ...task, col3: value });
                                       }}
                                />
                            </td>
                            <td>
                                <input className="form-control form-control-sm"
                                       value={task.col4 || ''}
                                       onChange={(e) => onChange(index, 'col4', e.target.value)} />
                            </td>
                            <td>
                                <input className="form-control form-control-sm"
                                       value={task.col5 || ''}
                                       onChange={(e) => {
                                           const value = e.target.value;
                                           onChange(index, 'col5', value);
                                           calculateTblLineTotal(index, { ...task, col5: value });
                                       }}
                                />
                            </td>
                            <td>
                                <input className="form-control form-control-sm"
                                       value={task.col6 || ''}
                                       onChange={(e) => onChange(index, 'col6', e.target.value)} />
                            </td>
                            <td>
                                <input className="form-control form-control-sm"
                                       value={task.col7 || ''}
                                       onChange={(e) => onChange(index, 'col7', e.target.value)} />
                            </td>
                            <td className="text-center">
                                <button type="button" className="btn btn-sm btn-danger" data-tip="Remove this row" onClick={() => onRemove(index)}>
                                    <i className="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    ))}
                    {tasks.length === 0 && (
                        <tr>
                            <td colSpan="8" className="text-center text-muted">No labor tasks added.</td>
                        </tr>
                    )}
                    </tbody>
                </table>
            </div>
            <LaborTaskModal isOpen={modalOpen} onClose={() => setModalOpen(false)} onDone={() => setModalOpen(false)} tasks={laborTasks} />
        </div>
    );
}
