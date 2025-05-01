// resources/js/utils/initSidebar.js
import { PushMenu, Treeview } from 'admin-lte';
import {CardWidget, FullScreen, Layout} from "admin-lte/src/ts/adminlte.js";

export function initSidebar() {
    const SELECTOR_APP_SIDEBAR = '.layout-wrapper .main-sidebar';
    const SELECTOR_APP_WRAPPER = '.layout-wrapper';
    const SELECTOR_SIDEBAR_TOGGLE = '[data-lte-toggle="sidebar"]';
    const CLASS_NAME_SIDEBAR_OVERLAY = 'sidebar-overlay';
    const Defaults = { /* optional PushMenu defaults */ };

    const sidebar = document.querySelector(SELECTOR_APP_SIDEBAR);
    if (sidebar) {
        const data = new PushMenu(sidebar, Defaults);
        data.init();

        window.addEventListener('resize', () => data.init());
    }

    const wrapper = document.querySelector(SELECTOR_APP_WRAPPER);
    if (wrapper && !wrapper.querySelector(`.${CLASS_NAME_SIDEBAR_OVERLAY}`)) {
        const sidebarOverlay = document.createElement('div');
        sidebarOverlay.className = CLASS_NAME_SIDEBAR_OVERLAY;
        wrapper.appendChild(sidebarOverlay);

        const collapseFn = (event) => {
            event.preventDefault();
            const data = new PushMenu(sidebar, Defaults);
            data.collapse();
        };

        sidebarOverlay.addEventListener('touchstart', collapseFn, { passive: true });
        sidebarOverlay.addEventListener('click', collapseFn);
    }

    document.querySelectorAll(SELECTOR_SIDEBAR_TOGGLE).forEach(btn => {
        btn.addEventListener('click', (event) => {
            event.preventDefault();
            const data = new PushMenu(btn, Defaults);
            data.toggle();
        });
    });

    // --- Initialize Treeview (Expandable Menu Items) ---
    const treeButtons = document.querySelectorAll('[data-lte-toggle="treeview"]');
    treeButtons.forEach(btn => {
        btn.addEventListener('click', event => {
            const target = event.target;
            const targetItem = target.closest('.nav-item');
            const targetLink = target.closest('.nav-link');

            if (
                (target?.getAttribute('href') === '#') ||
                (targetLink?.getAttribute('href') === '#')
            ) {
                event.preventDefault();
            }

            if (targetItem) {
                const treeview = new Treeview(targetItem, {}); // Pass options if needed
                treeview.toggle();
            }
        });
    });

    // --- Card Widget Controls ---
    const collapseBtn = document.querySelectorAll('[data-lte-widget="card-collapse"]');
    collapseBtn.forEach(btn => {
        btn.addEventListener('click', event => {
            event.preventDefault();
            const target = event.target;
            const data = new CardWidget(target, Default);
            data.toggle();
        });
    });

    const removeBtn = document.querySelectorAll('[data-lte-widget="card-remove"]');
    removeBtn.forEach(btn => {
        btn.addEventListener('click', event => {
            event.preventDefault();
            const target = event.target;
            const data = new CardWidget(target, Default);
            data.remove();
        });
    });

    const maxBtn = document.querySelectorAll('[data-lte-widget="card-maximize"]');
    maxBtn.forEach(btn => {
        btn.addEventListener('click', event => {
            event.preventDefault();
            const target = event.target;
            const data = new CardWidget(target, Default);
            data.toggleMaximize();
        });
    });

    // --- Fullscreen Toggle ---
    const fullscreenButtons = document.querySelectorAll('[data-lte-toggle="fullscreen"]');
    fullscreenButtons.forEach(btn => {
        btn.addEventListener('click', event => {
            event.preventDefault();
            const button = event.target.closest('[data-lte-toggle="fullscreen"]');
            if (button) {
                const data = new FullScreen(button, undefined);
                data.toggleFullScreen();
            }
        });
    });

    // 5. Layout Transition on Resize
    const layoutData = new Layout(document.body);
    layoutData.holdTransition();

    // 6. App Loaded Class
    setTimeout(() => {
        document.body.classList.add('app-loaded');
    }, 400);
}
