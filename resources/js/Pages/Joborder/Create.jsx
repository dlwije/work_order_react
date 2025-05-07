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
import AdminLayout from "@/lteLayouts/AdminLayout.jsx";
import select2 from "select2";
import Select2Dropdown from "@/Components/Select2Dropdown.jsx";
import AutoComplete from "@/Components/AutoComplete.jsx";

export default function Create({ woOptions, cusOptions, vehiOptions, defaultCurrency }) {

    const [customerSelection, setCustomerSelection] = useState(null);
    const [vehicleSelection, setVehicleSelection] = useState(0);

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
        // const { name, value } = e.target;
        setCustomerSelection(e);
        setVehicleSelection(prev => prev + 1); // triggers full reset of second dropdown
        // setForm(prev => ({ ...prev, [name]: value }));
    };
    const handleVehiChange = (e) => {
        setVehicleSelection(e);
        console.log(vehicleSelection);
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
        <AdminLayout>
            <div className="container-fluid">
                <div className="card card-primary card-outline">
                    <div className="card-header">
                        <h3 className="card-title">Create Job Order</h3>
                        <div className="card-tools">
                            <Link href={route('joborder.list')} className="btn btn-sm btn-secondary">← Go Back</Link>
                        </div>
                    </div>

                    <div className="card-body">
                        <div className="row">
                            <div className="col-md-2">
                                <label>WO No<span className="text-danger">*</span></label>
                                <AutoComplete
                                    endpoint={route('workorder.serialno.drop.list')}
                                    placeholder="Search Serial No..."
                                    defaultValue=""
                                    onSelect={(item) => console.log("Selected", item)}
                                    onChange={handleWOChange}
                                />

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
                                <AutoComplete
                                    endpoint={route('customer.drop.list')}
                                    placeholder="Search Customer..."
                                    defaultValue=""
                                    onSelect={handleCusChange}
                                />
                            </div>
                            <div className="col-md-2">
                                <label>Vehicle</label>
                                {customerSelection && (
                                    <AutoComplete
                                        endpoint={route('customer.vehicle.byid.drop.list')}
                                        extraParams={{ id: customerSelection.id }}
                                        placeholder="Search Vehicle..."
                                        defaultValue=""
                                        onSelect={handleVehiChange}
                                    />
                                )}
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

                        <div className="card card-outline card-success mb-2">
                            <InventoryItemInput />

                            <InventoryItemsTable
                                items={form.jbItemList}
                                onAdd={addJBItems}
                                onRemove={removeJBItems}
                                currency={defaultCurrency}
                            />
                        </div>
                            <JobOrderTotals
                                totals={totals}
                                onChange={handleTotalsChange}
                                onCalculateTotal={calculateTotal}
                                onCalculateDiscount={calculateDiscount}
                            />

                        <div className="form-group mt-4 text-end">
                            <button className="btn btn-success btn-sm" onClick={handleSubmit}>
                                <i className="fas fa-save"></i> Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
