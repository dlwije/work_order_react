import ApplicationLogo from '@/Components/ApplicationLogo';
import Dropdown from '@/Components/Dropdown';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import { Link, usePage } from '@inertiajs/react';
import {useEffect, useState} from 'react';
import AdminLayout from "@/lteLayouts/AdminLayout.jsx";

const $ = window.$;

export default function AuthenticatedLayout({ header, children }) {

    const user = usePage().props.auth.user;

    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    return (
        <AdminLayout user={user}>
            {/* begin::App Content Header */}
            <div className="app-content-header">
                {/* begin::Container */}
                <div className="container-fluid">
                    {/* begin::Row */}
                    <div className="row">
                        <div className="col-sm-6"><h3 className="mb-0">Dashboard</h3></div>
                        <div className="col-sm-6">
                            <ol className="breadcrumb float-sm-end">
                                <li className="breadcrumb-item"><a href="#">Home</a></li>
                                <li className="breadcrumb-item active" aria-current="page">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                    {/* end::Row */}
                </div>
                {/* end::Container */}
            </div>
            {/* end::App Content Header */}

            {/* begin::App Content */}
            <div className="app-content">
                {/* begin::Container */}
                <div className="container-fluid">
                    <div className="row">
                        {/* begin::Col */}
                        <div className="col-lg-3 col-6">
                            {/* begin::Small Box Widget 1 */}
                            <div className="small-box text-bg-primary">
                                <div className="inner">
                                    <h3>150</h3>
                                    <p>New Orders</p>
                                </div>
                                <svg
                                    className="small-box-icon"
                                    fill="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                    aria-hidden="true"
                                >
                                    <path
                                        d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"
                                    ></path>
                                </svg>
                                <a
                                    href="#"
                                    className="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                                >
                                    More info <i className="bi bi-link-45deg"></i>
                                </a>
                            </div>
                            {/* end::Small Box Widget 1 */}
                        </div>
                        {/* end::Col */}
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
