// resources/js/Layouts/Partials/Sidebar.jsx
import React from 'react';
import { Link, usePage } from '@inertiajs/react';

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
                        src=""
                        alt="LTE Logo"
                        className="brand-image opacity-75 shadow"
                    />
                    {/* end::Brand Image */}
                    {/* begin::Brand Text */}
                    <span className="brand-text fw-light">AdminLTE 4</span>
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

                        {/* Add more menu items here as needed */}
                    </ul>
                </nav>
            </div>
        </aside>
    );
}
