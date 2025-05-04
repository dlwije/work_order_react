// resources/js/utils/initSidebar.js
import {PushMenu, Treeview, CardWidget, FullScreen, Layout} from "admin-lte/dist/js/adminlte.js";
import {initTreeview} from "@/initTreeView.js";

export function initSidebar() {
    const SELECTOR_APP_SIDEBAR = '.layout-wrapper .main-sidebar';
    const SELECTOR_APP_WRAPPER = '.layout-wrapper';
    const SELECTOR_SIDEBAR_TOGGLE = '[data-lte-toggle="sidebar"]';
    const CLASS_NAME_SIDEBAR_OVERLAY = 'sidebar-overlay';
    const Defaults = { /* optional PushMenu defaults */ };

    var _a;
    const sidebar = document === null || document === void 0 ? void 0 : document.querySelector(SELECTOR_APP_SIDEBAR);
    if (sidebar) {
        const data = new PushMenu(sidebar, Defaults);
        data.init();
        window.addEventListener('resize', () => {
            data.init();
        });
    }
    const sidebarOverlay = document.createElement('div');
    sidebarOverlay.className = CLASS_NAME_SIDEBAR_OVERLAY;
    (_a = document.querySelector(SELECTOR_APP_WRAPPER)) === null || _a === void 0 ? void 0 : _a.append(sidebarOverlay);
    sidebarOverlay.addEventListener('touchstart', event => {
        event.preventDefault();
        const target = event.currentTarget;
        const data = new PushMenu(target, Defaults);
        data.collapse();
    }, { passive: true });
    sidebarOverlay.addEventListener('click', event => {
        event.preventDefault();
        const target = event.currentTarget;
        const data = new PushMenu(target, Defaults);
        data.collapse();
    });
    const fullBtn = document.querySelectorAll(SELECTOR_SIDEBAR_TOGGLE);
    fullBtn.forEach(btn => {
        btn.addEventListener('click', event => {
            event.preventDefault();
            let button = event.currentTarget;
            if ((button === null || button === void 0 ? void 0 : button.dataset.lteToggle) !== 'sidebar') {
                button = button === null || button === void 0 ? void 0 : button.closest(SELECTOR_SIDEBAR_TOGGLE);
            }
            if (button) {
                event === null || event === void 0 ? void 0 : event.preventDefault();
                const data = new PushMenu(button, Defaults);
                data.toggle();
            }
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

    initTreeview();

    // 5. Layout Transition on Resize
    const layoutData = new Layout(document.body);
    layoutData.holdTransition();

    // 6. App Loaded Class
    setTimeout(() => {
        document.body.classList.add('app-loaded');
    }, 400);
}
