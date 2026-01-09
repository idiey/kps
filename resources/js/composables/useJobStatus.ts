import type { JobPriority, JobStatus } from '@/types';

export interface StatusConfig {
    label: string;
    color: string;
    bgColor: string;
    textColor: string;
    icon?: string;
}

export interface PriorityConfig {
    label: string;
    color: string;
    bgColor: string;
    textColor: string;
    value: number;
}

export const useJobStatus = () => {
    const statusConfigs: Record<JobStatus, StatusConfig> = {
        new: {
            label: 'New',
            color: 'blue',
            bgColor: 'bg-blue-100 dark:bg-blue-800',
            textColor: 'text-blue-700 dark:text-blue-300',
        },
        pending_inspection: {
            label: 'Pending Inspection',
            color: 'cyan',
            bgColor: 'bg-cyan-100 dark:bg-cyan-800',
            textColor: 'text-cyan-700 dark:text-cyan-300',
        },
        inspection_in_progress: {
            label: 'Inspection In Progress',
            color: 'indigo',
            bgColor: 'bg-indigo-100 dark:bg-indigo-800',
            textColor: 'text-indigo-700 dark:text-indigo-300',
        },
        inspection_approved: {
            label: 'Inspection Approved',
            color: 'teal',
            bgColor: 'bg-teal-100 dark:bg-teal-800',
            textColor: 'text-teal-700 dark:text-teal-300',
        },
        inspection_rejected: {
            label: 'Inspection Rejected',
            color: 'red',
            bgColor: 'bg-red-100 dark:bg-red-800',
            textColor: 'text-red-700 dark:text-red-300',
        },
        awaiting_parts: {
            label: 'Awaiting Parts',
            color: 'orange',
            bgColor: 'bg-orange-100 dark:bg-orange-800',
            textColor: 'text-orange-700 dark:text-orange-300',
        },
        repair_in_progress: {
            label: 'Repair In Progress',
            color: 'amber',
            bgColor: 'bg-amber-100 dark:bg-amber-800',
            textColor: 'text-amber-700 dark:text-amber-300',
        },
        pending_review: {
            label: 'Pending Review',
            color: 'violet',
            bgColor: 'bg-violet-100 dark:bg-violet-800',
            textColor: 'text-violet-700 dark:text-violet-300',
        },
        in_progress: {
            label: 'In Progress',
            color: 'yellow',
            bgColor: 'bg-yellow-100 dark:bg-yellow-800',
            textColor: 'text-yellow-700 dark:text-yellow-300',
        },
        completed: {
            label: 'Completed',
            color: 'green',
            bgColor: 'bg-green-100 dark:bg-green-800',
            textColor: 'text-green-700 dark:text-green-300',
        },
        pending_kew_pa_10_return: {
            label: 'Pending KEW.PA-10 Return',
            color: 'sky',
            bgColor: 'bg-sky-100 dark:bg-sky-800',
            textColor: 'text-sky-700 dark:text-sky-300',
        },
        kew_pa_10_returned: {
            label: 'KEW.PA-10 Returned',
            color: 'emerald',
            bgColor: 'bg-emerald-100 dark:bg-emerald-800',
            textColor: 'text-emerald-700 dark:text-emerald-300',
        },
        invoiced: {
            label: 'Invoiced',
            color: 'purple',
            bgColor: 'bg-purple-100 dark:bg-purple-800',
            textColor: 'text-purple-700 dark:text-purple-300',
        },
        cancelled: {
            label: 'Cancelled',
            color: 'gray',
            bgColor: 'bg-gray-100 dark:bg-gray-800',
            textColor: 'text-gray-700 dark:text-gray-300',
        },
    };

    const priorityConfigs: Record<JobPriority, PriorityConfig> = {
        low: {
            label: 'Low',
            color: 'gray',
            bgColor: 'bg-gray-100 dark:bg-gray-800',
            textColor: 'text-gray-700 dark:text-gray-300',
            value: 1,
        },
        medium: {
            label: 'Normal',
            color: 'blue',
            bgColor: 'bg-blue-100 dark:bg-blue-800',
            textColor: 'text-blue-700 dark:text-blue-300',
            value: 2,
        },
        high: {
            label: 'High',
            color: 'orange',
            bgColor: 'bg-orange-100 dark:bg-orange-800',
            textColor: 'text-orange-700 dark:text-orange-300',
            value: 3,
        },
        urgent: {
            label: 'Urgent',
            color: 'red',
            bgColor: 'bg-red-100 dark:bg-red-800',
            textColor: 'text-red-700 dark:text-red-300',
            value: 4,
        },
    };

    const getStatusConfig = (status: JobStatus): StatusConfig => {
        return statusConfigs[status] || statusConfigs.new;
    };

    const getPriorityConfig = (priority: JobPriority): PriorityConfig => {
        return priorityConfigs[priority] || priorityConfigs.medium;
    };

    const getStatusLabel = (status: JobStatus): string => {
        return getStatusConfig(status).label;
    };

    const getPriorityLabel = (priority: JobPriority): string => {
        return getPriorityConfig(priority).label;
    };

    const getAllStatuses = (): { value: JobStatus; label: string }[] => {
        return Object.entries(statusConfigs).map(([value, config]) => ({
            value: value as JobStatus,
            label: config.label,
        }));
    };

    const getAllPriorities = (): { value: JobPriority; label: string }[] => {
        const priorities: JobPriority[] = ['low', 'medium', 'high', 'urgent'];
        return priorities.map((value) => ({
            value,
            label: priorityConfigs[value].label,
        }));
    };

    const canTransitionTo = (from: JobStatus, to: JobStatus): boolean => {
        // Simplified transitions for now, should match backend policy
        const transitions: Partial<Record<JobStatus, JobStatus[]>> = {
            new: ['pending_inspection', 'in_progress', 'cancelled'],
            pending_inspection: ['inspection_in_progress', 'cancelled'],
            inspection_in_progress: ['inspection_approved', 'inspection_rejected'],
            inspection_approved: ['repair_in_progress', 'awaiting_parts'],
            inspection_rejected: ['new', 'cancelled'],
            awaiting_parts: ['repair_in_progress'],
            repair_in_progress: ['pending_review', 'awaiting_parts'],
            pending_review: ['completed', 'repair_in_progress'],
            in_progress: ['completed', 'awaiting_parts'],
            completed: ['pending_kew_pa_10_return', 'invoiced', 'in_progress'],
            pending_kew_pa_10_return: ['kew_pa_10_returned'],
            kew_pa_10_returned: ['invoiced'],
            invoiced: [],
            cancelled: [],
        };

        return transitions[from]?.includes(to) || false;
    };

    const getAvailableTransitions = (
        currentStatus: JobStatus,
    ): { value: JobStatus; label: string }[] => {
        return getAllStatuses().filter((status) =>
            canTransitionTo(currentStatus, status.value),
        );
    };

    return {
        statusConfigs,
        priorityConfigs,
        getStatusConfig,
        getPriorityConfig,
        getStatusLabel,
        getPriorityLabel,
        getAllStatuses,
        getAllPriorities,
        canTransitionTo,
        getAvailableTransitions,
    };
};
