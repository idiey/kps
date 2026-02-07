import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

/**
 * Permission composable for checking user permissions and roles.
 * Uses permissions shared from HandleInertiaRequests.php
 */
export function usePermission() {
    const page = usePage();

    const permissions = computed<string[]>(() =>
        (page.props.auth as any)?.permissions || []
    );

    const roles = computed<string[]>(() =>
        (page.props.auth as any)?.roles || []
    );

    /**
     * Check if the user has a specific permission.
     */
    const hasPermission = (permission: string | string[]): boolean => {
        const perms = permissions.value;
        if (Array.isArray(permission)) {
            return permission.some(p => perms.includes(p));
        }
        return perms.includes(permission);
    };

    /**
     * Check if the user has a specific role.
     */
    const hasRole = (role: string | string[]): boolean => {
        const r = roles.value;
        if (Array.isArray(role)) {
            return role.some(rl => r.includes(rl));
        }
        return r.includes(role);
    };

    /**
     * Check if the user has all specified permissions.
     */
    const hasAllPermissions = (requiredPermissions: string[]): boolean => {
        return requiredPermissions.every(p => permissions.value.includes(p));
    };

    /**
     * Check if the user has all specified roles.
     */
    const hasAllRoles = (requiredRoles: string[]): boolean => {
        return requiredRoles.every(r => roles.value.includes(r));
    };

    return {
        permissions,
        roles,
        hasPermission,
        hasRole,
        hasAllPermissions,
        hasAllRoles,
        can: hasPermission, // Alias for easier usage similar to Laravel
    };
}
