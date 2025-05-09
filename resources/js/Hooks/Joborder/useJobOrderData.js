// resources/js/Pages/JobOrders/Review/useJobOrderData.js

import { useState } from 'react';
import axios from 'axios';
import accounting from 'accounting-js';

export function useJobOrderData() {
    const [servicePkgList, setServicePkgList] = useState([]);
    const [laborTaskList, setLaborTaskList] = useState([]);
    const [jbItemList, setJbItemList] = useState([]);

    const reviewJobOrder = async (empID, onSuccess, onFail) => {
        try {
            const response = await axios.get(route('joborder.edit.data'), {
                params: {
                    emp_id: empID,
                },
            });

            const data = response.data;
            if (data.status) {
                const servPkg = data.data.serv_pkg_data.map(mapToServicePkg);
                const tasks = data.data.task_data.map(mapToLaborTask);
                const items = data.data.item_data.map((item) => ({
                    ...item, // handle `gridItemListRows` here if needed
                }));

                setServicePkgList(servPkg);
                setLaborTaskList(tasks);
                setJbItemList(items);

                onSuccess?.();
            } else {
                onFail?.('Job order data not found');
            }
        } catch (error) {
            console.error('Review job order error', error);
            onFail?.(error);
        }
    };

    const mapToServicePkg = (obj) => ({
        name: obj?.serv_pkg?.service_name || '',
        description: obj?.serv_pkg?.description || '',
        type: obj?.serv_pkg?.type_name || '',
        price: accounting.formatNumber(obj?.serv_pkg?.price || 0, 2),
        total: accounting.formatNumber(obj?.serv_pkg?.price || 0, 2),
        servicePkgId: obj?.serv_pkg?.service_pkg_id || '',
        id: obj?.serv_pkg?.id || '',
    });

    const mapToLaborTask = (obj) => ({
        name: obj?.task?.task_name || '',
        description: obj?.task?.task_description || '',
        hours: obj?.task?.hours || '',
        actualHours: obj?.task?.actual_hours || '',
        hourlyRate: accounting.formatNumber(obj?.task?.hourly_rate || 0, 2),
        laborCost: accounting.formatNumber(obj?.task?.labor_cost || 0, 2),
        total: accounting.formatNumber(obj?.task?.total || 0, 2),
        taskId: obj?.task?.task_id || '',
        id: obj?.task?.id || '',
        isApproveCost: obj?.task?.is_approve_cost || '',
        isApproveWork: obj?.task?.is_approve_work || '',
    });

    return {
        reviewJobOrder,
        servicePkgList,
        laborTaskList,
        jbItemList,
        setServicePkgList,
        setLaborTaskList,
        setJbItemList,
    };
}
