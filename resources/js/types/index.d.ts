import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
    permissions: string[];
    roles: string[];
    isGlobalAdmin: boolean;
    isCompanyAdmin: boolean;
    isSiteAdminOnly: boolean;
    assignedSites: string[];
    siteAdminSite?: { id: string; name: string; code: string } | null;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
    roles?: string[];
    permission?: string;
    children?: NavItem[];
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    phone?: string;
    department?: string;
    email_verified_at: string | null;
    active: boolean;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
export type KpsSiteRole = 'site_admin' | 'staff';

export interface KpsSite {
    id: string;
    name: string;
    code: string;
    state?: string;
    district?: string;
    address?: string;
    phone?: string;
    email?: string;
    is_active: boolean;
    hutang_weightage_pct?: number;
    created_at: string;
    updated_at: string;
    updated_by?: number;
    deleted_at?: string | null;
}

export interface KpsPeneroka {
    id: string;
    site_id: string;
    name: string;
    ic_number?: string | null;
    phone?: string | null;
    address?: string | null;
    debts_count?: number;
    total_outstanding?: number | null;
    current_month_deduction_total?: number | null;
    latest_deduction_month?: string | null;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    site?: KpsSite;
}

export interface KpsDebt {
    id: string;
    peneroka_id: string;
    priority: number;
    balance: number;
    original_amount: number;
    monthly_potongan_limit?: number | null;
    due_date?: string | null;
    description?: string | null;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
    peneroka?: KpsPeneroka;
}

export interface KpsMonthlyDeduction {
    id: string;
    peneroka_id: string;
    site_id: string;
    month: string;
    amount: number;
    unallocated_amount: number;
    is_closed: boolean;
    closed_at?: string | null;
    created_at: string;
    updated_at: string;
    peneroka?: KpsPeneroka;
    allocations?: KpsDeductionAllocation[];
}

export interface KpsDeductionAllocation {
    id: string;
    monthly_deduction_id: string;
    debt_id: string;
    amount: number;
    created_at: string;
    updated_at: string;
    debt?: KpsDebt;
}

export interface KpsAuditLog {
    id: string;
    site_id: string;
    user_id?: number | null;
    action: string;
    auditable_type: string;
    auditable_id: string;
    metadata?: Record<string, string | number | boolean | null> | null;
    created_at: string;
    updated_at: string;
    user?: Pick<User, 'id' | 'name' | 'email'> | null;
    auditable_label?: string;
}

export interface PaginationMeta {
    current_page: number;
    from: number;
    last_page: number;
    path: string;
    per_page: number;
    to: number;
    total: number;
}

export interface PaginationLinks {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
}

export interface PaginatedResponse<T> {
    data: T[];
    links: PaginationLinks;
    meta: PaginationMeta;
}
