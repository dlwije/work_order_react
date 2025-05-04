import React, {useEffect} from 'react';
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.jsx";
import {Link, router, usePage} from "@inertiajs/react";
import useRoles from "@/Hooks/useRoles.js";
import Navbar from "@/Layouts/Partials/Navbar.jsx";
import Sidebar from "@/Layouts/Partials/Sidebar.jsx";
import Footer from "@/Layouts/Partials/Footer.jsx";
import {Toaster} from "react-hot-toast";
import {initSidebar} from "@/initSidebar.js";
import {initTreeview} from "@/initTreeView.js";
import Inertia from "bootstrap/js/src/dom/event-handler.js";
import {initCardWidget} from "@/initCardWidget.js";

import {onDOMContentLoaded} from "admin-lte/src/ts/util/index.js";
import {initAdminLTE} from "@/initAdminLTE.js";


const AdminLayout = ({ children }) => {

    const { user, url, unlessRole, hasRole, hasPermission, hasAnyPermission } = useRoles();

    useEffect(() => {
        onDOMContentLoaded(() => {
            // console.log('sdadsdsd');
            initSidebar();
            // initTreeview();
            initCardWidget();
        })
    }, []);
    return (
        <div className="app-wrapper">
            {/* Navbar */}
            <Navbar user={user} />

            {/* Sidebar */}
            <Sidebar unlessRole={unlessRole}
                hasRole={hasRole}
                     hasPermission={hasPermission}
                     hasAnyPermission={hasAnyPermission}
                     url={url}
                     />

            {/* Main Content */}
            <main className="app-main">
                <Toaster position="top-right" />
                {children}
            </main>

            {/* Footer */}
            {/* -begin::Footer */}
            <Footer />
            {/* -end::Footer */}
        </div>
    );
};

export default AdminLayout;
