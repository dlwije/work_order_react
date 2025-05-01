// Register jQuery globally
import $ from 'jquery';
window.$ = window.jQuery = $;

// Register Axios
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Optional: use jQuery after it's defined
window.BASE_URL = $('meta[name=base_url]').prop('content');

// Bootstrap must be loaded before AdminLTE
import 'bootstrap/dist/js/bootstrap.bundle.js';

// Dynamically import AdminLTE to ensure correct order
import('admin-lte/dist/js/adminlte.js').then(() => {
    console.log('AdminLTE loaded successfully');
});

import '../css/app.css'; // Your custom styles
import 'admin-lte/dist/css/adminlte.min.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@fortawesome/fontawesome-free/css/all.min.css';

import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import {initSidebar} from "@/initSidebar.js";

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.jsx`,
            import.meta.glob('./Pages/**/*.jsx'),
        ),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);

        // ✅ Run AdminLTE sidebar init AFTER render
        setTimeout(() => {
            initSidebar();
        }, 0);
    },
    progress: {
        color: '#4B5563',
    },
});
