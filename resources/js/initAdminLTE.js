// import 'jquery'; // if required
import 'admin-lte';

export function initAdminLTE() {
    // Force AdminLTE to reinitialize plugins
    // These are needed especially after Inertia navigations

    // Sidebar toggle
    const sidebarToggle = document.querySelector('[data-widget="pushmenu"]');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            window.dispatchEvent(new Event('resize')); // Fix layout bug sometimes
        });
    }

    // Re-init dropdowns handled by Bootstrap 5 (already works via data-bs-toggle)

    // Re-init Treeview (manually if needed)
    document.querySelectorAll('[data-lte-toggle="treeview"]').forEach((el) => {
        el.addEventListener('click', () => {
            // Can trigger manual treeview init here
        });
    });

    // Initialize any other AdminLTE widgets like CardWidget, etc., if used
    // Example for CardWidget:
    document.querySelectorAll('[data-widget="card-widget"]').forEach((el) => {
        // Card widget events are already handled by AdminLTE, no need to manually initialize
    });
}
