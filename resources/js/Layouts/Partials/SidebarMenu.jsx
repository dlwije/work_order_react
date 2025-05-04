const SidebarMenu = ({ label, icon, items }) => {
    const isMenuOpen = items.some(item => isActive(item.key));

    return (
        <li className={`nav-item ${isMenuOpen ? 'menu-open' : ''}`}>
            <a href="#" className="nav-link">
                <i className={`nav-icon ${icon}`}></i>
                <p>
                    {label}
                    <i className="nav-arrow bi bi-chevron-right"></i>
                </p>
            </a>
            <ul className="nav nav-treeview">
                {items.map(({ name, routeName, key }) => (
                    <li className="nav-item" key={key}>
                        <Link href={route(routeName)} className={`nav-link ${isActive(key) ? 'active' : ''}`}>
                            <i className="far fa-circle nav-icon"></i>
                            <p>{name}</p>
                        </Link>
                    </li>
                ))}
            </ul>
        </li>
    );
};
