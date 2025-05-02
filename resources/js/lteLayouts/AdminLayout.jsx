import React, {useEffect} from 'react';
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.jsx";
import { Link, usePage} from "@inertiajs/react";
import useRoles from "@/Hooks/useRoles.js";
import Navbar from "@/Layouts/Partials/Navbar.jsx";
import Sidebar from "@/Layouts/Partials/Sidebar.jsx";
import Footer from "@/Layouts/Partials/Footer.jsx";
import {Toaster} from "react-hot-toast";


const AdminLayout = ({ children }) => {

    const { user, url, unlessRole, hasRole, hasPermission, hasAnyPermission } = useRoles();

    useEffect(() => {
        const evt = new Event('lte.init');
        window.dispatchEvent(evt);
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
