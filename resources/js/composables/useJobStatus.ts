import type { JobStatus, JobPriority } from '@/types';

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
    pending: {
      label: 'Pending',
      color: 'gray',
      bgColor: 'bg-gray-100 dark:bg-gray-800',
      textColor: 'text-gray-700 dark:text-gray-300',
    },
    in_progress: {
      label: 'In Progress',
      color: 'blue',
      bgColor: 'bg-blue-100 dark:bg-blue-800',
      textColor: 'text-blue-700 dark:text-blue-300',
    },
    awaiting_parts: {
      label: 'Awaiting Parts',
      color: 'yellow',
      bgColor: 'bg-yellow-100 dark:bg-yellow-800',
      textColor: 'text-yellow-700 dark:text-yellow-300',
    },
    on_hold: {
      label: 'On Hold',
      color: 'orange',
      bgColor: 'bg-orange-100 dark:bg-orange-800',
      textColor: 'text-orange-700 dark:text-orange-300',
    },
    completed: {
      label: 'Completed',
      color: 'green',
      bgColor: 'bg-green-100 dark:bg-green-800',
      textColor: 'text-green-700 dark:text-green-300',
    },
    cancelled: {
      label: 'Cancelled',
      color: 'red',
      bgColor: 'bg-red-100 dark:bg-red-800',
      textColor: 'text-red-700 dark:text-red-300',
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
    normal: {
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
    return statusConfigs[status] || statusConfigs.pending;
  };

  const getPriorityConfig = (priority: JobPriority): PriorityConfig => {
    return priorityConfigs[priority] || priorityConfigs.normal;
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
    return Object.entries(priorityConfigs).map(([value, config]) => ({
      value: value as JobPriority,
      label: config.label,
    }));
  };

  const canTransitionTo = (from: JobStatus, to: JobStatus): boolean => {
    const transitions: Record<JobStatus, JobStatus[]> = {
      pending: ['in_progress', 'cancelled'],
      in_progress: ['awaiting_parts', 'on_hold', 'completed', 'cancelled'],
      awaiting_parts: ['in_progress', 'on_hold', 'cancelled'],
      on_hold: ['in_progress', 'cancelled'],
      completed: [],
      cancelled: [],
    };

    return transitions[from]?.includes(to) || false;
  };

  const getAvailableTransitions = (currentStatus: JobStatus): { value: JobStatus; label: string }[] => {
    return getAllStatuses().filter((status) => canTransitionTo(currentStatus, status.value));
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
