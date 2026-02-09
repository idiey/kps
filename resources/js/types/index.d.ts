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
    siteAdminWorkshop?: { id: string; name: string; code: string } | null;
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
    roles?: UserRole[];
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
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

// Workshop System Types

export type JobStatus =
    | 'new'
    | 'pending_inspection'
    | 'inspection_in_progress'
    | 'inspection_approved'
    | 'inspection_rejected'
    | 'awaiting_parts'
    | 'repair_in_progress'
    | 'pending_review'
    | 'in_progress'
    | 'completed'
    | 'pending_kew_pa_10_return'
    | 'kew_pa_10_returned'
    | 'invoiced'
    | 'cancelled';

export type JobPriority = 'low' | 'medium' | 'high' | 'urgent';
export type UserRole =
    | 'pentadbiran'
    | 'company_admin'
    | 'penyelia'
    | 'pemeriksa'
    | 'pelulus'
    | 'juruteknik'
    | 'kaunter';
export type NoteType =
    | 'general'
    | 'diagnostic'
    | 'repair'
    | 'parts'
    | 'customer_communication';

export interface StatusOption {
    value: JobStatus;
    label: string;
    color: string;
}

export interface PriorityOption {
    value: JobPriority;
    label: string;
    color: string;
}

export interface Customer {
    id: number;
    name: string;
    email?: string;
    phone: string;
    address?: string;
    department?: string;
    government_entity?: string;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
}

export interface WorkshopJob {
    id: number;
    job_number: string;
    customer_id: number;
    assigned_to?: number | null;
    title: string;
    description: string;
    status: JobStatus;
    priority?: JobPriority;
    vehicle_registration?: string;
    asset_tag?: string;
    estimated_cost?: number;
    actual_cost?: number;
    started_at?: string | null;
    completed_at?: string | null;
    invoiced_at?: string | null;
    due_date?: string | null;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;

    // Base fields
    job_mode: 'NORMAL' | 'KEW_PA_10';
    expected_completion_date?: string | null;
    location?: string | null;

    // KEW.PA-10 Specific Fields
    kew_vehicle_registration?: string | null;
    kew_asset_tag?: string | null;
    kew_department?: string | null;
    kew_inspection_date?: string | null;
    kew_inspector_name?: string | null;
    kew_inspector_ic?: string | null;
    kew_findings?: string | null;
    kew_recommendations?: string | null;
    kew_approval_status?: 'pending' | 'approved' | 'rejected' | null;
    kew_rejection_reason?: string | null;

    // Relationships
    customer?: Customer;
    assigned_user?: User;
    assigned_technician?: User; // Helper alias often used
    notes?: JobNote[];
    status_histories?: JobStatusHistory[];
    assignments?: JobAssignment[];
    workflow?: Workflow;
}

export interface JobNote {
    id: number;
    workshop_job_id: number;
    user_id: number;
    content: string;
    is_public: boolean;
    note_type?: NoteType;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;

    // Relationships
    user?: User;
    job?: WorkshopJob;

    // Computed
    can_edit?: boolean;
    can_delete?: boolean;
}

export interface JobAssignment {
    id: number;
    workshop_job_id: number;
    assigned_to: number;
    assigned_by: number;
    assigned_at: string;
    notes?: string;
    is_current: boolean;
    created_at: string;
    updated_at: string;

    // Relationships
    assigned_user?: User;
    assigned_by_user?: User;
    job?: WorkshopJob;
}

export interface JobStatusHistory {
    id: number;
    workshop_job_id: number;
    user_id: number;
    from_status?: JobStatus | null;
    to_status: JobStatus;
    notes?: string;
    changed_at: string;
    created_at: string;
    updated_at: string;

    // Relationships
    user?: User;
    job?: WorkshopJob;
}

export interface TimelineItem {
    type: 'status_change' | 'assignment' | 'note';
    timestamp: string;
    data: Record<string, any>;
}

export interface JobFilters {
    status?: JobStatus;
    priority?: JobPriority;
    assigned_to?: number;
    customer_id?: number;
    search?: string;
    overdue?: boolean;
}

export interface DashboardStatistics {
    total_jobs: number;
    new_jobs: number;
    in_progress_jobs: number;
    completed_jobs: number;
    overdue_jobs: number;
    unassigned_jobs?: number;
}

export interface Workflow {
    id: number;
    name: string;
    code: string;
    description?: string;
    is_active: boolean;
    is_default: boolean;
    metadata?: Record<string, any>;
    created_at: string;
    updated_at: string;
}

export interface TechnicianWorkload {
    id: number;
    name: string;
    email: string;
    active_jobs_count: number;
    assigned_jobs?: WorkshopJob[];
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

// Site/Workshop Types

export type SiteRole = 'site_admin' | 'supervisor' | 'technician' | 'staff';

export interface Company {
    id: string;
    name: string;
    subdomain?: string;
    tier: 'free' | 'professional' | 'enterprise';
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

export interface Workshop {
    id: string;
    company_id?: string;
    name: string;
    code: string;
    address?: string;
    phone?: string;
    email?: string;
    operating_hours?: Record<string, { open: string; close: string }>;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;

    // Relationships
    company?: Company;
    assigned_users?: (User & { pivot?: { role: SiteRole } })[];
}

export interface SiteContext {
    site: Workshop | null;
    siteRole: SiteRole | null;
    isSiteSelected: boolean;
    isSiteAdmin: boolean;
    isSupervisor: boolean;
}

// KPS Types

export type KpsSiteRole = 'site_admin' | 'staff';

export interface KpsSite {
    id: string;
    name: string;
    code: string;
    address?: string;
    phone?: string;
    email?: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
}

export interface KpsPeneroka {
    id: string;
    site_id: string;
    name: string;
    ic_number?: string | null;
    phone?: string | null;
    address?: string | null;
    created_at: string;
    updated_at: string;
    deleted_at?: string | null;
}

export interface KpsDebt {
    id: string;
    peneroka_id: string;
    priority: number;
    balance: number;
    original_amount: number;
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
