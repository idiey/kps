import { format, formatDistanceToNow, parseISO, isValid } from 'date-fns';

/**
 * Format a date string to a human-readable format
 */
export const formatDate = (
  date: string | Date | null | undefined,
  formatStr = 'dd MMM yyyy'
): string => {
  if (!date) return '-';

  try {
    const parsedDate = typeof date === 'string' ? parseISO(date) : date;
    if (!isValid(parsedDate)) return '-';
    return format(parsedDate, formatStr);
  } catch (error) {
    console.error('Error formatting date:', error);
    return '-';
  }
};

/**
 * Format a date string to include time
 */
export const formatDateTime = (
  date: string | Date | null | undefined,
  formatStr = 'dd MMM yyyy, HH:mm'
): string => {
  return formatDate(date, formatStr);
};

/**
 * Format a date string to a relative time (e.g., "2 hours ago")
 */
export const formatRelative = (
  date: string | Date | null | undefined,
  options?: { addSuffix?: boolean }
): string => {
  if (!date) return '-';

  try {
    const parsedDate = typeof date === 'string' ? parseISO(date) : date;
    if (!isValid(parsedDate)) return '-';
    return formatDistanceToNow(parsedDate, {
      addSuffix: options?.addSuffix ?? true,
    });
  } catch (error) {
    console.error('Error formatting relative date:', error);
    return '-';
  }
};

/**
 * Format a date for form inputs (YYYY-MM-DD)
 */
export const formatDateForInput = (date: string | Date | null | undefined): string => {
  return formatDate(date, 'yyyy-MM-dd');
};

/**
 * Check if a date is in the past
 */
export const isPast = (date: string | Date | null | undefined): boolean => {
  if (!date) return false;

  try {
    const parsedDate = typeof date === 'string' ? parseISO(date) : date;
    if (!isValid(parsedDate)) return false;
    return parsedDate < new Date();
  } catch (error) {
    return false;
  }
};

/**
 * Check if a date is in the future
 */
export const isFuture = (date: string | Date | null | undefined): boolean => {
  if (!date) return false;

  try {
    const parsedDate = typeof date === 'string' ? parseISO(date) : date;
    if (!isValid(parsedDate)) return false;
    return parsedDate > new Date();
  } catch (error) {
    return false;
  }
};

/**
 * Get the number of days between two dates
 */
export const daysBetween = (
  date1: string | Date,
  date2: string | Date
): number => {
  try {
    const parsedDate1 = typeof date1 === 'string' ? parseISO(date1) : date1;
    const parsedDate2 = typeof date2 === 'string' ? parseISO(date2) : date2;

    if (!isValid(parsedDate1) || !isValid(parsedDate2)) return 0;

    const diffTime = Math.abs(parsedDate2.getTime() - parsedDate1.getTime());
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
  } catch (error) {
    return 0;
  }
};
