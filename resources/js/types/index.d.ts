import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
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
    role: string;
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
    | 'penyelia'
    | 'pemeriksa'
    | 'pelulus'
    | 'juruteknik';
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

    // Relationships
    customer?: Customer;
    assigned_user?: User;
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
