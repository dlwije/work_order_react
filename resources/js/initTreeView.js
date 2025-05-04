import { Treeview } from 'admin-lte';

export function initTreeview() {
    const SELECTOR_DATA_TOGGLE = '[data-lte-toggle="treeview"]';
    const SELECTOR_NAV_ITEM = '.nav-item';
    const SELECTOR_NAV_LINK = '.nav-link';
    const Default = {
        animationSpeed: 300,
        accordion: true
    };

    document.querySelectorAll(SELECTOR_DATA_TOGGLE).forEach(btn => {
        btn.addEventListener('click', event => {
            const target = event.target;
            const targetItem = target.closest(SELECTOR_NAV_ITEM);
            const targetLink = target.closest(SELECTOR_NAV_LINK);

            if ((target?.getAttribute('href') === '#') || (targetLink?.getAttribute('href') === '#')) {
                event.preventDefault();
            }

            if (targetItem) {
                const treeview = new Treeview(targetItem, Default);
                treeview.toggle();
            }
        });
    });
}
