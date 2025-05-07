import React, { useState } from 'react';
import { Inertia } from '@inertiajs/inertia';
import VehicleAccordion from '@/components/JobOrder/VehicleAccordion';
import LaborTaskTable from '@/components/JobOrder/LaborTaskTable';
// import { Card, CardContent } from '@/components/ui/card';
import {Link} from "@inertiajs/react";
import ServicePackagesTable from "@/Components/Joborder/ServicePackagesTable.jsx";
import InventoryItemInput from "@/Components/Joborder/InventoryItemInput.jsx";
import InventoryItemsTable from "@/Components/Joborder/InventoryItemsTable.jsx";
import JobOrderTotals from "@/Components/Joborder/JobOrderTotals.jsx";

export default function Create({ woOptions, cusOptions, vehiOptions, defaultCurrency }) {

    const [form, setForm] = useState({
        wo_serial: '',
        jo_type: '',
        jo_date: '',
        woRemark: '',
        mod_ins: {},
        mod_cont: {},
        mod_warr: {},
        laborTaskList: [],
        servicePkgList: [],
        jbItemList: [],
    });
    const [totals, setTotals] = useState({
        tot_parts_amnt: '',
        tot_labor_tsk_amnt: '',
        tot_other_amnt: '',
        sub_tot_amnt: '',
        tax1_amnt: '',
        sup_charge_amnt: '',
        shipping_amnt: '',
        tot_amnt: '',
        discount_amnt: '',
        fee_amnt: '',
        diag_fee_amnt: '',
        grand_tot_amnt: '',
    });

    const handleTotalsChange = (field, value) => {
        setTotals((prev) => ({ ...prev, [field]: value }));
    };

    const calculateTotal = () => {
        // logic for updating total based on supply, shipping, etc.
    };

    const calculateDiscount = () => {
        // logic for applying discount
    };

    const handleWOChange = (e) => {
        const { name, value } = e.target;
        setForm(prev => ({ ...prev, [name]: value }));
    };
    const handleJOChange = (e) => {
        const { name, value } = e.target;
        setForm(prev => ({ ...prev, [name]: value }));
    };
    const handleCusChange = (e) => {
        const { name, value } = e.target;
        setForm(prev => ({ ...prev, [name]: value }));
    };
    const handleVehiChange = (e) => {
        const { name, value } = e.target;
        setForm(prev => ({ ...prev, [name]: value }));
    };

    const addLaborTask = () => {
        setForm(prev => ({
            ...prev,
            laborTaskList: [
                ...prev.laborTaskList,
                { col1: '', col2: '', col3: '', col4: '', col5: '', col6: '', col7: '' }
            ]
        }));
    };
    const addServicePkg = () => {
        setForm(prev => ({
            ...prev,
            servicePkgList: [
                ...prev.servicePkgList,
                { col1: '', col2: '', col3: '', col4: '', col5: '', col6: '', col7: '' }
            ]
        }));
    };
    const addJBItems = () => {
        setForm(prev => ({
            ...prev,
            jbItemList: [
                ...prev.jbItemList,
                { col1: '', col2: '', col3: '', col4: '', col5: '', col6: '', col7: '' }
            ]
        }));
    };

    const updateTask = (index, key, value) => {
        const updated = [...form.laborTaskList];
        updated[index][key] = value;
        setForm(prev => ({ ...prev, laborTaskList: updated }));
    };

    const removeTask = (index) => {
        const updated = form.laborTaskList.filter((_, i) => i !== index);
        setForm(prev => ({ ...prev, laborTaskList: updated }));
    };
    const updateServicePkg = (index, key, value) => {
        const updated = [...form.laborTaskList];
        updated[index][key] = value;
        setForm(prev => ({ ...prev, laborTaskList: updated }));
    };

    const removeServicePkg = (index) => {
        const updated = form.laborTaskList.filter((_, i) => i !== index);
        setForm(prev => ({ ...prev, laborTaskList: updated }));
    };
    const updateJBItems = (index, key, value) => {
        const updated = [...form.laborTaskList];
        updated[index][key] = value;
        setForm(prev => ({ ...prev, laborTaskList: updated }));
    };

    const removeJBItems = (index) => {
        const updated = form.laborTaskList.filter((_, i) => i !== index);
        setForm(prev => ({ ...prev, laborTaskList: updated }));
    };

    const handleSubmit = () => {
        Inertia.post('/joborders', form);
    };

    return (
        <div className="container-fluid">
            <div className="card card-primary card-outline">
                <div className="card-header d-flex justify-between">
                    <h3 className="card-title">Create Job Order</h3>
                    <a href="/joborders" className="btn btn-sm btn-secondary">← Go Back</a>
                </div>
                <div className="card-header">
                    <h3 className="card-title">Create User</h3>
                    <div className="card-tools">
                        <Link className="btn btn-sm btn-secondary" href={route('joborder.list')}>
                            <i className="fas fa-hand-o-left"></i> Go Back
                        </Link>
                    </div>
                </div>
                <div className="card-body">
                    <div className="row">
                        <div className="col-md-2">
                            <label>WO No<span className="text-danger">*</span></label>
                            <select name="wo_serial" id="id_wo_serial_drop" className="form-control"
                                    onChange={handleWOChange}>
                                {woOptions?.map(opt => (
                                    <option key={opt.id} value={opt.id}>{opt.text}</option>
                                ))}
                            </select>
                        </div>
                        <div className="col-md-2">
                            <label>Job Type</label>
                            <select name="jo_type" className="form-control" onChange={handleJOChange}>
                                <option value=""></option>
                                <option value="os">On Site</option>
                                <option value="op">On Premises</option>
                            </select>
                        </div>
                        <div className="col-md-2">
                            <label>JO Date</label>
                            <input type="text" name="jo_date" className="form-control" value={form.jo_date} readOnly/>
                        </div>
                        <div className="col-md-2">
                            <label>Customer</label>
                            <select name="customer" id="ID_cus_name" className="form-control"
                                    onChange={handleCusChange}>
                                {cusOptions?.map(opt => (
                                    <option key={opt.id} value={opt.id}>{opt.text}</option>
                                ))}
                            </select>
                        </div>
                        <div className="col-md-2">
                            <label>Customer</label>
                            <select name="vehicle" id="ID_vehi_name" className="form-control"
                                    onChange={handleVehiChange}>
                                {vehiOptions?.map(opt => (
                                    <option key={opt.id} value={opt.id}>{opt.text}</option>
                                ))}
                            </select>
                        </div>
                    </div>

                    <div className="row mb-3">
                        <div className="col-md-12">
                            <label>Remarks</label>
                            <textarea name="woRemark" className="form-control" rows="2" value={form.woRemark} readOnly/>
                        </div>
                    </div>

                    {/*<VehicleAccordion form={form}/>*/}

                    <LaborTaskTable
                        tasks={form.laborTaskList}
                        onAdd={addLaborTask}
                        onChange={updateTask}
                        onRemove={removeTask}
                        currency={defaultCurrency}
                    />

                    <ServicePackagesTable
                        servicePackages={form.servicePkgList}
                        onAdd={addServicePkg}
                        onRemove={removeServicePkg}
                        currency={defaultCurrency}
                    />

                    <div className="callout callout-success">
                        <InventoryItemInput />

                        <InventoryItemsTable
                            items={form.jbItemList}
                            onAdd={addJBItems}
                            onRemove={removeJBItems}
                            currency={defaultCurrency}
                        />

                        <JobOrderTotals
                            totals={totals}
                            onChange={handleTotalsChange}
                            onCalculateTotal={calculateTotal}
                            onCalculateDiscount={calculateDiscount}
                        />
                    </div>

                    <div className="form-group mt-4 text-end">
                        <button className="btn btn-success btn-sm" onClick={handleSubmit}>
                            <i className="fas fa-save"></i> Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}
