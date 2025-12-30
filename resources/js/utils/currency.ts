/**
 * Format a number as Malaysian Ringgit currency
 */
export const formatCurrency = (
  amount: number | string | null | undefined,
  options?: Intl.NumberFormatOptions
): string => {
  if (amount === null || amount === undefined || amount === '') {
    return 'RM 0.00';
  }

  const numAmount = typeof amount === 'string' ? parseFloat(amount) : amount;

  if (isNaN(numAmount)) {
    return 'RM 0.00';
  }

  return new Intl.NumberFormat('en-MY', {
    style: 'currency',
    currency: 'MYR',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
    ...options,
  }).format(numAmount);
};

/**
 * Parse currency string to number
 */
export const parseCurrency = (value: string): number => {
  if (!value) return 0;

  // Remove currency symbols and spaces
  const cleaned = value.replace(/[RM\s,]/g, '');
  const parsed = parseFloat(cleaned);

  return isNaN(parsed) ? 0 : parsed;
};

/**
 * Format a number as compact currency (e.g., RM 1.2K, RM 3.4M)
 */
export const formatCompactCurrency = (
  amount: number | string | null | undefined
): string => {
  if (amount === null || amount === undefined || amount === '') {
    return 'RM 0';
  }

  const numAmount = typeof amount === 'string' ? parseFloat(amount) : amount;

  if (isNaN(numAmount)) {
    return 'RM 0';
  }

  return new Intl.NumberFormat('en-MY', {
    style: 'currency',
    currency: 'MYR',
    notation: 'compact',
    minimumFractionDigits: 0,
    maximumFractionDigits: 1,
  }).format(numAmount);
};

/**
 * Calculate percentage
 */
export const calculatePercentage = (
  value: number,
  total: number,
  decimals = 1
): string => {
  if (total === 0) return '0%';
  const percentage = (value / total) * 100;
  return `${percentage.toFixed(decimals)}%`;
};

/**
 * Format number with thousand separators
 */
export const formatNumber = (
  value: number | string | null | undefined,
  decimals = 0
): string => {
  if (value === null || value === undefined || value === '') {
    return '0';
  }

  const numValue = typeof value === 'string' ? parseFloat(value) : value;

  if (isNaN(numValue)) {
    return '0';
  }

  return new Intl.NumberFormat('en-MY', {
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals,
  }).format(numValue);
};
