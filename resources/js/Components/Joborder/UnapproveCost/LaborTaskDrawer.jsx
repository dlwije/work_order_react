import React from 'react';
import { Drawer, DrawerContent, DrawerHeader, DrawerTitle } from '@/components/ui/drawer';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Button } from '@/components/ui/button';

const LaborTaskDrawer = ({ open, onClose, task, onChange, onSave }) => {
    return (
        <Drawer open={open} onClose={onClose} side="right" size="lg" className="shadow-lg">
            <DrawerHeader className="bg-primary text-white">
                <DrawerTitle>Edit Labor Task</DrawerTitle>
            </DrawerHeader>

            <DrawerContent className="p-4">
                <div className="row g-3">

                    <div className="col-md-6">
                        <label className="form-label">Task Name</label>
                        <Input
                            value={task.col1}
                            onChange={(e) => onChange({ ...task, col1: e.target.value })}
                            className="form-control"
                        />
                    </div>

                    <div className="col-md-6">
                        <label className="form-label">Task Hours</label>
                        <Input
                            value={task.col3}
                            onChange={(e) => onChange({ ...task, col3: e.target.value })}
                            className="form-control"
                        />
                    </div>

                    <div className="col-md-6">
                        <label className="form-label">Actual Hours</label>
                        <Input
                            value={task.col4}
                            onChange={(e) => onChange({ ...task, col4: e.target.value })}
                            className="form-control"
                        />
                    </div>

                    <div className="col-md-6">
                        <label className="form-label">Hourly Rate</label>
                        <Input
                            value={task.col5}
                            onChange={(e) => onChange({ ...task, col5: e.target.value })}
                            className="form-control"
                        />
                    </div>

                    <div className="col-md-6">
                        <label className="form-label">Labor Cost</label>
                        <Input
                            value={task.col6}
                            onChange={(e) => onChange({ ...task, col6: e.target.value })}
                            className="form-control"
                        />
                    </div>

                    <div className="col-md-6">
                        <label className="form-label">Total</label>
                        <Input
                            value={task.col7}
                            onChange={(e) => onChange({ ...task, col7: e.target.value })}
                            className="form-control"
                        />
                    </div>

                    <div className="col-12">
                        <label className="form-label">Task Description</label>
                        <Textarea
                            value={task.col2}
                            onChange={(e) => onChange({ ...task, col2: e.target.value })}
                            className="form-control"
                            rows={3}
                        />
                    </div>
                </div>

                <div className="mt-4 d-flex justify-content-end gap-2">
                    <Button variant="secondary" onClick={onClose}>
                        Cancel
                    </Button>
                    <Button variant="primary" onClick={onSave}>
                        Save
                    </Button>
                </div>
            </DrawerContent>
        </Drawer>
    );
};

export default LaborTaskDrawer;
