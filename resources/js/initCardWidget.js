import {CardWidget} from "admin-lte";

export function initCardWidget() {
    const SELECTOR_DATA_REMOVE = '[data-lte-toggle="card-remove"]';
    const SELECTOR_DATA_COLLAPSE = '[data-lte-toggle="card-collapse"]';
    const SELECTOR_DATA_MAXIMIZE = '[data-lte-toggle="card-maximize"]';

    const Default = {
        animationSpeed: 500,
        collapseTrigger: SELECTOR_DATA_COLLAPSE,
        removeTrigger: SELECTOR_DATA_REMOVE,
        maximizeTrigger: SELECTOR_DATA_MAXIMIZE
    };

    document.querySelectorAll(SELECTOR_DATA_COLLAPSE).forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            new CardWidget(e.target, Default).toggle();
        });
    });

    document.querySelectorAll(SELECTOR_DATA_REMOVE).forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            new CardWidget(e.target, Default).remove();
        });
    });

    document.querySelectorAll(SELECTOR_DATA_MAXIMIZE).forEach(btn => {
        btn.addEventListener('click', e => {
            e.preventDefault();
            new CardWidget(e.target, Default).toggleMaximize();
        });
    });
}
