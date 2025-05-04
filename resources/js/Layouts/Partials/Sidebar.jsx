// resources/js/Layouts/Partials/Sidebar.jsx
import React from 'react';
import { Link, usePage } from '@inertiajs/react';
import NavLink from "@/Components/NavLink.jsx";

export default function Sidebar({ unlessRole, hasRole, hasPermission, hasAnyPermission, url }) {
    const isActive = (route) => url.endsWith(route);
    console.log(url);
    console.log(isActive);

    return (
        <aside className="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <div className="sidebar-brand">
                {/* begin::Brand Link */}
                <a href="#" className="brand-link">
                    {/* begin::Brand Image */}
                    <img
                        src="/img/squre_orions_white_only_logo.png"
                        alt="LTE Logo"
                        className="brand-image opacity-75 shadow"
                    />
                    {/* end::Brand Image */}
                    {/* begin::Brand Text */}
                    <span className="brand-text fw-light">Orions360</span>
                    {/* end::Brand Text */}
                </a>
                {/* end::Brand Link */}
            </div>
            {/* Sidebar Menu */}
            <div className="sidebar-wrapper">
                <nav className="mt-2">
                    <ul className="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu"
                        data-accordion="false">

                        {/* Dashboard */}
                        {hasRole('System Admin') && (
                            <li className={`nav-item ${isActive('home') ? 'menu-open' : ''}`}>
                                <a href="#" className="nav-link">
                                    <i className="nav-icon bi bi-speedometer"></i>
                                    <p>
                                        Dashboard
                                        <i className="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul className="nav nav-treeview">
                                    <li className="nav-item">
                                        <Link href={route('home')}
                                              className={`nav-link ${isActive('home') ? 'active' : ''}`}>
                                            <i className="nav-icon bi bi-circle"></i>
                                            <p>Dashboard v1</p>
                                        </Link>
                                    </li>
                                </ul>
                            </li>
                        )}

                        {/* Company Setup */}
                        {hasRole('System Admin') && hasAnyPermission('user-list', 'role-list') && (
                            <li className={`nav-item ${['my-company', 'user', 'role', 'edit'].some(isActive) ? 'menu-open' : ''}`}>
                                <a href="#" className="nav-link">
                                    <i className="nav-icon fas fa-cogs"></i>
                                    <p>Company Setup <i className="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul className="nav nav-treeview">
                                    <li className="nav-item">
                                        <Link href={route('myCompany')}
                                              className={`nav-link ${isActive('my-company') ? 'active' : ''}`}>
                                            <i className="far fa-circle nav-icon"></i>
                                            <p>My Company</p>
                                        </Link>
                                    </li>
                                    {hasPermission('user-list') && (
                                        <li className="nav-item">
                                            <Link href={route('user.index')}
                                                  className={`nav-link ${['user', 'edit'].some(isActive) ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Users</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('role-list') && (
                                        <li className="nav-item">
                                            <Link href={route('role.index')}
                                                  className={`nav-link ${['role', 'edit'].some(isActive) ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>User Roles</p>
                                            </Link>
                                        </li>
                                    )}
                                </ul>
                            </li>
                        )}

                        {/* Work Orders */}
                        {unlessRole('Technician') && hasAnyPermission('workorder-list', 'wworkorder-list', 'estimate-list') && (
                            <li className={`nav-item ${['workorder-list', 'w-workorder-list', 'estimate-list'].some(isActive) ? 'menu-open' : ''}`}>
                                <a href="#" className="nav-link">
                                    <i className="nav-icon fas fa-people-carry"></i>
                                    <p>Work Orders <i className="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul className="nav nav-treeview">
                                    {hasPermission('workorder-list') && (
                                        <li className="nav-item">
                                            <Link href={route('workorder.list')}
                                                  className={`nav-link ${isActive('workorder-list') ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Work Order</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('wworkorder-list') && (
                                        <li className="nav-item">
                                            <Link href={route('w.workorder.list')}
                                                  className={`nav-link ${isActive('w-workorder-list') ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Warranty Work Order</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('estimate-list') && (
                                        <li className="nav-item">
                                            <Link href={route('estimate.list')}
                                                  className={`nav-link ${isActive('estimate-list') ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Estimate</p>
                                            </Link>
                                        </li>
                                    )}
                                </ul>
                            </li>
                        )}

                        {hasAnyPermission(
                            'joborder-list',
                            'jo-cost-unapprove-list',
                            'jo-work-unapprove-list',
                            'jo-approved-list'
                        ) && (
                            <li className={`nav-item ${['joborder-list', 'joborder-create', 'joborder-edit', 'joborder-unapprove-cost-list', 'joborder-approved-list', 'joborder-assign-window'].some(isActive) ? 'menu-open' : ''}`}>
                                <a href="#" className="nav-link">
                                    <i className="nav-icon fas fa-tasks"></i>
                                    <p>
                                        Job Order
                                        <i className="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul className="nav nav-treeview">
                                    {hasPermission('joborder-list') && (
                                        <li className="nav-item">
                                            <Link href={route('joborder.list')}
                                                  className={`nav-link ${['joborder-list', 'joborder-create', 'joborder-edit'].some(isActive) ? 'active' : ''}`}>
                                                <i className="far fa-dot-circle nav-icon"></i>
                                                <p>List</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('jo-cost-unapprove-list') && (
                                        <li className="nav-item">
                                            <Link href={route('joborder.unapprove.cost.list')}
                                                  className={`nav-link ${isActive('joborder-unapprove-cost-list') ? 'active' : ''}`}>
                                                <i className="far fa-dot-circle nav-icon"></i>
                                                <p>To Be Approve (Cost)</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('jo-work-unapprove-list') && (
                                        <li className="nav-item">
                                            <Link href="#" className="nav-link">
                                                <i className="far fa-dot-circle nav-icon"></i>
                                                <p>To Be Approve (Work)</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('jo-approved-list') && (
                                        <li className="nav-item">
                                            <Link href={route('joborder.approved.list')}
                                                  className={`nav-link ${['joborder-approved-list', 'joborder-assign-window'].some(isActive) ? 'active' : ''}`}>
                                                <i className="far fa-dot-circle nav-icon"></i>
                                                <p>Approved List</p>
                                            </Link>
                                        </li>
                                    )}
                                </ul>
                            </li>
                        )}

                        {hasPermission('invoice-list') && (
                            <li className={`nav-item ${['invoice-list', 'invoice-create'].some(isActive) ? 'menu-open' : ''}`}>
                                <a href="#" className="nav-link">
                                    <i className="nav-icon fas fa-shopping-basket"></i>
                                    <p>
                                        Sales
                                        <i className="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul className="nav nav-treeview">
                                    <li className="nav-item">
                                        <Link href={route('invoice.list')}
                                              className={`nav-link ${['invoice-list', 'invoice-create'].some(isActive) ? 'active' : ''}`}>
                                            <i className="far fa-circle nav-icon"></i>
                                            <p>Invoice</p>
                                        </Link>
                                    </li>
                                </ul>
                            </li>
                        )}

                        {(hasPermission('labors-complete-list') || hasPermission('labors-assign-list')) && (
                            <li className={`nav-item ${['task-assigned-list', 'task-completed-list', 'service-completed-list'].some(isActive) ? 'menu-open' : ''}`}>
                                <a href="#" className="nav-link">
                                    <i className="nav-icon fas fa-tasks"></i>
                                    <p>
                                        Labor's tasks
                                        <i className="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul className="nav nav-treeview">
                                    {hasPermission('labors-assign-list') && (
                                        <li className="nav-item">
                                            <Link href={route('task.assigned.list')} className={`nav-link ${isActive('task-assigned-list') ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Assigned labor task</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('labors-complete-list') && (
                                        <>
                                            <li className="nav-item">
                                                <Link href={route('task.completed.list')} className={`nav-link ${isActive('task-completed-list') ? 'active' : ''}`}>
                                                    <i className="far fa-circle nav-icon"></i>
                                                    <p>Completed labor task</p>
                                                </Link>
                                            </li>
                                            <li className="nav-item">
                                                <Link href={route('service.completed.list')} className={`nav-link ${isActive('service-completed-list') ? 'active' : ''}`}>
                                                    <i className="far fa-circle nav-icon"></i>
                                                    <p>Completed service packages</p>
                                                </Link>
                                            </li>
                                        </>
                                    )}
                                </ul>
                            </li>
                        )}

                        {(hasPermission('customer-list') || hasPermission('employee-list')) && !hasRole('Technician') && (
                            <li className={`nav-item ${['employee-list', 'employee-create', 'employee-edit', 'customer-list', 'customer-edit', 'customer-create'].some(isActive) ? 'menu-open' : ''}`}>
                                <a href="#" className="nav-link">
                                    <i className="nav-icon fas fa-users"></i>
                                    <p>
                                        Members
                                        <i className="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul className="nav nav-treeview">
                                    {hasPermission('customer-list') && (
                                        <li className="nav-item">
                                            <Link href={route('customer.list')} className={`nav-link ${['customer-list', 'customer-edit', 'customer-create'].some(isActive) ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Customer</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('employee-list') && (
                                        <li className="nav-item">
                                            <Link href={route('employeeList')} className={`nav-link ${['employee-list', 'employee-edit', 'employee-create'].some(isActive) ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Employee</p>
                                            </Link>
                                        </li>
                                    )}
                                </ul>
                            </li>
                        )}

                        {(hasPermission('vehicle-list') || hasPermission('vehicle-type-list')) && (
                            <li className={`nav-item ${['vehicle-list', 'vehicle-type-list'].some(isActive) ? 'menu-open' : ''}`}>
                                <a href="#" className="nav-link">
                                    <i className="nav-icon fas fa-shuttle-van"></i>
                                    <p>
                                        Vehicles
                                        <i className="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul className="nav nav-treeview">
                                    {hasPermission('vehicle-list') && (
                                        <li className="nav-item">
                                            <Link href={route('vehicle.list')} className={`nav-link ${isActive('vehicle-list') ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Vehicle</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('vehicle-type-list') && (
                                        <li className="nav-item">
                                            <Link href={route('vehicle.type.list')} className={`nav-link ${isActive('vehicle-type-list') ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Vehicle Type</p>
                                            </Link>
                                        </li>
                                    )}
                                </ul>
                            </li>
                        )}

                        {(
                            hasPermission('service_pkg-list') ||
                            hasPermission('labortask-list') ||
                            hasPermission('task-category-list') ||
                            hasPermission('task-source-list') ||
                            hasPermission('task-type-list')
                        ) && (
                            <li className={`nav-item ${[
                                'labortask-list',
                                'labortask-create',
                                'labortask-edit',
                                'labortask-excel',
                                'task-category-list',
                                'task-source-list',
                                'task-type-list',
                                'service-package-list'
                            ].some(isActive) ? 'menu-open' : ''}`}>
                                <a href="#" className="nav-link">
                                    <i className="nav-icon fas fa-box-open"></i>
                                    <p>
                                        Service Packages
                                        <i className="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul className="nav nav-treeview">
                                    {hasPermission('service_pkg-list') && (
                                        <li className="nav-item">
                                            <Link href={route('service.package.list')} className={`nav-link ${isActive('service-package-list') ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Service Package</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('labortask-list') && (
                                        <li className="nav-item">
                                            <Link href={route('labortask.list')} className={`nav-link ${['labortask-list', 'labortask-edit', 'labortask-create', 'labortask-excel'].some(isActive) ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Labor Task</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('task-category-list') && (
                                        <li className="nav-item">
                                            <Link href={route('task.category.list')} className={`nav-link ${isActive('task-category-list') ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Task Category</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('task-source-list') && (
                                        <li className="nav-item">
                                            <Link href={route('task.source.list')} className={`nav-link ${isActive('task-source-list') ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Task Source</p>
                                            </Link>
                                        </li>
                                    )}
                                    {hasPermission('task-type-list') && (
                                        <li className="nav-item">
                                            <Link href={route('task.type.list')} className={`nav-link ${isActive('task-type-list') ? 'active' : ''}`}>
                                                <i className="far fa-circle nav-icon"></i>
                                                <p>Task Type</p>
                                            </Link>
                                        </li>
                                    )}
                                </ul>
                            </li>
                        )}

                        {(
                            hasPermission('cus-pay-list') || hasPermission('cus-depo-list')
                        ) && (
                            <SidebarMenu
                                label="Payments"
                                icon="fas fa-money-bill-wave"
                                items={[
                                    hasPermission('cus-pay-list') && {
                                        name: 'Customer Payment',
                                        routeName: 'customer.payment.list', // replace with actual route name if available
                                        key: 'customer-payment'
                                    },
                                    hasPermission('cus-depo-list') && {
                                        name: 'Customer Deposit',
                                        routeName: 'customer.deposit.list',
                                        key: 'customer-deposit-list'
                                    }
                                ].filter(Boolean)}
                            />
                        )}

                        {/* Inventory nav */}
                        {hasRole(['Parts Manager', 'System Admin']) && (
                            <SidebarMenu
                                label="Inventory"
                                icon="fas fa-dolly-flatbed"
                                items={[
                                    { name: 'Technician\'s Parts Request', routeName: 'technician.parts.request', key: 'technician-parts-request' },
                                    { name: 'Warranty\'s Parts Request', routeName: 'warranty.parts.request', key: 'warranty-parts-request' }
                                ]}
                            />
                        )}

                        {/* Report nav */}
                        {hasRole(['System Admin']) && (
                            <SidebarMenu
                                label="Report"
                                icon="fas fa-chart-line"
                                items={[
                                    { name: 'Cash Collections', routeName: 'cash.collections', key: 'cash-collections' },
                                    { name: 'Profit & Loss', routeName: 'profit.loss', key: 'profit-loss' },
                                    { name: 'Profit & Loss Hierarchy', routeName: 'profit.loss.hierarchy', key: 'profit-loss-hierarchy' },
                                    { name: 'Stock Details', routeName: 'stock.details', key: 'stock-details' },
                                    { name: 'Daily Item Issues', routeName: 'daily.item.issues', key: 'daily-item-issues' },
                                    { name: 'Customer Details', routeName: 'customer.details', key: 'customer-details' },
                                    { name: 'Service Details', routeName: 'service.details', key: 'service-details' }
                                ]}
                            />
                        )}
                    </ul>
                </nav>
            </div>
        </aside>
    );
}
