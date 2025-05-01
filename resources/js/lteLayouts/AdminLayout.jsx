import React, {useEffect} from 'react';
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.jsx";


const AdminLayout = ({ user, children }) => {
    useEffect(() => {
        const evt = new Event('lte.init');
        window.dispatchEvent(evt);
    }, []);
    return (
        <div className="app-wrapper">
            {/* Navbar */}
            <nav className="app-header navbar navbar-expand bg-body">
                <div className="container-fluid">
                    {/* begin::Start Navbar Links */}
                    <ul className="navbar-nav">
                        <li className="nav-item">
                            <a className="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                                <i className="bi bi-list"></i>
                            </a>
                        </li>
                        <li className="nav-item d-none d-md-block"><a href="#" className="nav-link">Home</a></li>
                        <li className="nav-item d-none d-md-block"><a href="#" className="nav-link">Contact</a></li>
                    </ul>
                    {/* end::Start Navbar Links */}
                    {/* begin::End Navbar Links */}
                    <ul className="navbar-nav ms-auto">
                    {/* begin::Navbar Search */}
                        <li className="nav-item">
                            <a className="nav-link" data-widget="navbar-search" href="#" role="button">
                                <i className="bi bi-search"></i>
                            </a>
                        </li>
                    {/* end::Navbar Search */}
                    {/* begin::Messages Dropdown Menu */}
                        <li className="nav-item dropdown">
                            <a className="nav-link" data-bs-toggle="dropdown" href="#">
                                <i className="bi bi-chat-text"></i>
                                <span className="navbar-badge badge text-bg-danger">3</span>
                            </a>
                            <div className="dropdown-menu dropdown-menu-lg dropdown-menu-end">

                                <div className="dropdown-divider"></div>
                                <a href="#" className="dropdown-item">
                    {/* begin::Message */}
                                    <div className="d-flex">
                                        <div className="flex-shrink-0">
                                            <img
                                                src=""
                                                alt="User Avatar"
                                                className="img-size-50 rounded-circle me-3"
                                            />
                                        </div>
                                        <div className="flex-grow-1">
                                            <h3 className="dropdown-item-title">
                                                Nora Silvester
                                                <span className="float-end fs-7 text-warning">
                          <i className="bi bi-star-fill"></i>
                        </span>
                                            </h3>
                                            <p className="fs-7">The subject goes here</p>
                                            <p className="fs-7 text-secondary">
                                                <i className="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                            </p>
                                        </div>
                                    </div>
                    {/* end::Message */}
                                </a>
                                <div className="dropdown-divider"></div>
                                <a href="#" className="dropdown-item dropdown-footer">See All Messages</a>
                            </div>
                        </li>
                    {/* end::Messages Dropdown Menu */}
                    {/* begin::Notifications Dropdown Menu */}
                        <li className="nav-item dropdown">
                            <a className="nav-link" data-bs-toggle="dropdown" href="#">
                                <i className="bi bi-bell-fill"></i>
                                <span className="navbar-badge badge text-bg-warning">15</span>
                            </a>
                            <div className="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                                <span className="dropdown-item dropdown-header">15 Notifications</span>
                                <div className="dropdown-divider"></div>
                                <a href="#" className="dropdown-item">
                                    <i className="bi bi-envelope me-2"></i> 4 new messages
                                    <span className="float-end text-secondary fs-7">3 mins</span>
                                </a>
                                <div className="dropdown-divider"></div>
                                <a href="#" className="dropdown-item">
                                    <i className="bi bi-people-fill me-2"></i> 8 friend requests
                                    <span className="float-end text-secondary fs-7">12 hours</span>
                                </a>
                                <div className="dropdown-divider"></div>
                                <a href="#" className="dropdown-item">
                                    <i className="bi bi-file-earmark-fill me-2"></i> 3 new reports
                                    <span className="float-end text-secondary fs-7">2 days</span>
                                </a>
                                <div className="dropdown-divider"></div>
                                <a href="#" className="dropdown-item dropdown-footer"> See All Notifications </a>
                            </div>
                        </li>
                    {/* end::Notifications Dropdown Menu */}
                    {/* begin::Fullscreen Toggle */}
                        <li className="nav-item">
                            <a className="nav-link" href="#" data-lte-toggle="fullscreen">
                                <i data-lte-icon="maximize" className="bi bi-arrows-fullscreen"></i>
                                <i data-lte-icon="minimize" className="bi bi-fullscreen-exit" style={{ display: 'none' }}></i>
                            </a>
                        </li>
                    {/* end::Fullscreen Toggle */}
                    {/* begin::User Menu Dropdown */}
                        <li className="nav-item dropdown user-menu">
                            <a href="#" className="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <img
                                    src=""
                                    className="user-image rounded-circle shadow"
                                    alt="User Image"
                                />
                                <span className="d-none d-md-inline">{user.name}</span>
                            </a>
                            <ul className="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    {/* begin::User Image */}
                                <li className="user-header text-bg-primary">
                                    <img
                                        src=""
                                        className="rounded-circle shadow"
                                        alt="User Image"
                                    />
                                    <p>
                                        {user.name}
                                        <small>{user.email}</small>
                                    </p>
                                </li>
                    {/* end::User Image */}
                    {/* begin::Menu Body */}

                    {/* end::Menu Body */}
                    {/* begin::Menu Footer */}
                                <li className="user-footer">
                                    <a href="#" className="btn btn-default btn-flat">Profile</a>
                                    <ResponsiveNavLink href={route('profile.edit')}>
                                        Profile
                                    </ResponsiveNavLink>
                                    <ResponsiveNavLink
                                        method="post"
                                        href={route('logout')}
                                        as="button"
                                    >
                                        Log Out
                                    </ResponsiveNavLink>
                                </li>
                    {/* end::Menu Footer */}
                            </ul>
                        </li>
                    {/* end::User Menu Dropdown */}
                    </ul>
                    {/* end::End Navbar Links */}
                </div>
            </nav>

            {/* Sidebar */}
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
                <div className="sidebar-wrapper">
                    <nav className="mt-2">
                        <ul
                            className="nav sidebar-menu flex-column"
                            data-lte-toggle="treeview"
                            role="menu"
                            data-accordion="false"
                        >
                            <li className="nav-item menu-open">
                                <a href="#" className="nav-link active">
                                    <i className="nav-icon bi bi-speedometer"></i>
                                    <p>
                                        Dashboard
                                        <i className="nav-arrow bi bi-chevron-right"></i>
                                    </p>
                                </a>
                                <ul className="nav nav-treeview">
                                    <li className="nav-item">
                                        <a href="#" className="nav-link active">
                                            <i className="nav-icon bi bi-circle"></i>
                                            <p>Dashboard v1</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {/* Add more links here */}
                        </ul>
                    </nav>
                </div>
            </aside>

            {/* Main Content */}
            <main className="app-main">
                {children}
            </main>

            {/* Footer */}
            {/* -begin::Footer */}
            <footer className="app-footer">
            {/* -begin::To the end */}
                <div className="float-end d-none d-sm-inline">Anything you want</div>
            {/* -end::To the end */}
            {/* -begin::Copyright */}
                <strong>
                    Copyright &copy; 2014-2024&nbsp;
                    <a href="https://adminlte.io" className="text-decoration-none">AdminLTE.io</a>.
                </strong>
                All rights reserved.
            {/* -end::Copyright */}
            </footer>
            {/* -end::Footer */}
        </div>
    );
};

export default AdminLayout;
