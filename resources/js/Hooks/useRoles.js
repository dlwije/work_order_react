// resources/js/Hooks/useRoles.js
import { usePage } from '@inertiajs/react';

export default function useRoles() {
    const { url, props } = usePage();
    const user = props.auth?.user; // Optional chaining to safely access user
    const permissions = props.auth?.permissions || [];
    const roles = props.auth?.roles || [];

    // Check if roles are available and are an array
    const hasRole = (role) => Array.isArray(roles) && roles.includes(role);
    const unlessRole = (role) => !hasRole(role);
    const hasPermission = (perm) => permissions.includes(perm);
    const hasAnyPermission = (...perms) => perms.some((perm) => permissions.includes(perm));

    return {
        user,
        url,
        unlessRole,
        hasRole,
        hasPermission,
        hasAnyPermission,
    };
}
