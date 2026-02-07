# API Integration Guide

> **Last Updated**: 2026-02-02  
> **Version**: 2.0.0-rework  
> **For**: Mobile app developers  

---

## Overview

This guide explains how to integrate the mobile app with the Laravel backend API. It covers authentication, making requests, handling responses, and offline-first patterns.

**Base URL**: `http://your-server.com/api`

**Related Documentation**:
- [Mobile API Design](../02-architecture/13-mobile-api-design.md) - Full API specification
- [Offline Sync Strategy](../02-architecture/14-offline-sync.md) - Sync implementation  

---

## Authentication

### 1. Login Flow

```typescript
// src/services/auth/authService.ts
import apiClient from '../api/apiClient';
import { setAuthToken, removeAuthToken } from '../../utils/storage';

export const AuthService = {
  async login(email: string, password: string) {
    const response = await apiClient.post('/auth/login', {
      email,
      password,
      device_name: 'mobile',
    });

    const { token, user } = response.data.data;
    
    // Store token
    await setAuthToken(token);
    
    return { token, user };
  },

  async logout() {
    try {
      await apiClient.post('/auth/logout');
    } finally {
      await removeAuthToken();
    }
  },

 async refreshToken() {
    const response = await apiClient.post('/auth/refresh');
    const { token } = response.data.data;
    await setAuthToken(token);
    return token;
  },
};
```

### 2. Token Storage

```typescript
// src/utils/storage.ts
import AsyncStorage from '@react-native-async-storage/async-storage';

const AUTH_TOKEN_KEY = '@workshop:auth_token';
const USER_KEY = '@workshop:user';

export async function setAuthToken(token: string): Promise<void> {
  await AsyncStorage.setItem(AUTH_TOKEN_KEY, token);
}

export async function getAuthToken(): Promise<string | null> {
  return await AsyncStorage.getItem(AUTH_TOKEN_KEY);
}

export async function removeAuthToken(): Promise<void> {
  await AsyncStorage.multiRemove([AUTH_TOKEN_KEY, USER_KEY]);
}

export async function setUser(user: User): Promise<void> {
  await AsyncStorage.setItem(USER_KEY, JSON.stringify(user));
}

export async function getUser(): Promise<User | null> {
  const data = await AsyncStorage.getItem(USER_KEY);
  return data ? JSON.parse(data) : null;
}
```

### 3. Request Interceptor

Add auth token to all requests automatically.

```typescript
// src/services/api/apiClient.ts
import axios from 'axios';
import { getAuthToken } from '../../utils/storage';
import { API_BASE_URL, API_TIMEOUT } from '@env';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: API_TIMEOUT,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

// Add auth token to requests
apiClient.interceptors.request.use(
  async (config) => {
    const token = await getAuthToken();
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Handle 401 responses
apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      await removeAuthToken();
      // Navigate to login screen
      RootNavigation.navigate('Login');
    }
    return Promise.reject(error);
  }
);

export default apiClient;
```

---

## Making API Requests

### Job Endpoints

```typescript
// src/services/api/jobService.ts
import apiClient from './apiClient';
import type { Job, PaginatedResponse } from '../../types/api';

export const JobService = {
  /**
   * Fetch jobs with pagination and filters
   */
  async fetchJobs(params?: {
    page?: number;
    per_page?: number;
    status?: string;
    search?: string;
  }): Promise<PaginatedResponse<Job>> {
    const response = await apiClient.get('/jobs', { params });
    return response.data;
  },

  /**
   * Fetch single job by ID
   */
  async fetchJobById(id: string): Promise<Job> {
    const response = await apiClient.get(`/jobs/${id}`);
    return response.data.data;
  },

  /**
   * Update job status
   */
  async updateJobStatus(
    id: string,
    status: string,
    notes?: string
  ): Promise<void> {
    await apiClient.patch(`/jobs/${id}/status`, { status, notes });
  },

  /**
   * Create a new job (offline-capable)
   */
  async createJob(data: CreateJobDto): Promise<Job> {
    const response = await apiClient.post('/jobs', data);
    return response.data.data;
  },
};
```

### Photo Upload

```typescript
// src/services/api/photoService.ts
import apiClient from './apiClient';
import type { Photo } from '../../types/models';

export const PhotoService = {
  /**
   * Upload photo to job
   */
  async uploadPhoto(
    jobId: string,
    photo: {
      uri: string;
      type: string;
      fileName: string;
    }
  ): Promise<Photo> {
    const formData = new FormData();
    formData.append('job_id', jobId);
    formData.append('photo', {
      uri: photo.uri,
      type: photo.type,
      name: photo.fileName,
    } as any);

    const response = await apiClient.post('/jobs/photos', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      timeout: 60000, // 60 seconds for large uploads
    });

    return response.data.data;
  },

  /**
   * Batch upload photos
   */
  async uploadPhotos(
    jobId: string,
    photos: Array<{ uri: string; type: string; fileName: string }>
  ): Promise<Photo[]> {
    const formData = new FormData();
    formData.append('job_id', jobId);

    photos.forEach((photo, index) => {
      formData.append(`photos[${index}]`, {
        uri: photo.uri,
        type: photo.type,
        name: photo.fileName,
      } as any);
    });

    const response = await apiClient.post('/jobs/photos/batch', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      timeout: 120000, // 2 minutes for multiple photos
    });

    return response.data.data;
  },

  /**
   * Delete photo
   */
  async deletePhoto(photoId: string): Promise<void> {
    await apiClient.delete(`/jobs/photos/${photoId}`);
  },
};
```

### Notes

```typescript
// src/services/api/noteService.ts
import apiClient from './apiClient';
import type { Note } from '../../types/models';

export const NoteService = {
  /**
   * Fetch notes for a job
   */
  async fetchNotes(jobId: string): Promise<Note[]> {
    const response = await apiClient.get(`/jobs/${jobId}/notes`);
    return response.data.data;
  },

  /**
   * Create a note
   */
  async createNote(jobId: string, content: string): Promise<Note> {
    const response = await apiClient.post('/jobs/notes', {
      job_id: jobId,
      content,
    });
    return response.data.data;
  },

  /**
   * Update a note
   */
  async updateNote(noteId: string, content: string): Promise<Note> {
    const response = await apiClient.patch(`/jobs/notes/${noteId}`, {
      content,
    });
    return response.data.data;
  },

  /**
   * Delete a note
   */
  async deleteNote(noteId: string): Promise<void> {
    await apiClient.delete(`/jobs/notes/${noteId}`);
  },
};
```

---

## Offline-First Implementation

### 1. Sync Queue

```typescript
// src/services/sync/syncQueue.ts
import { database } from '../../database';
import { Q } from '@nozbe/watermelondb';
import apiClient from '../api/apiClient';

export interface SyncQueueItem {
  id: string;
  operation_type: 'CREATE' | 'UPDATE' | 'DELETE';
  entity_type: 'job' | 'note' | 'photo';
  entity_id: string;
  data: any;
  retry_count: number;
  status: 'pending' | 'processing' | 'failed';
}

export const syncQueue = {
  /**
   * Add item to sync queue
   */
  async enqueue(item: Omit<SyncQueueItem, 'id' | 'retry_count' | 'status'>) {
    await database.write(async () => {
      await database.collections
        .get('sync_queue')
        .create((record: any) => {
          record.operation_type = item.operation_type;
          record.entity_type = item.entity_type;
          record.entity_id = item.entity_id;
          record.data = JSON.stringify(item.data);
          record.retry_count = 0;
          record.status = 'pending';
        });
    });
  },

  /**
   * Get pending items
   */
  async getPendingItems(): Promise<SyncQueueItem[]> {
    const records = await database.collections
      .get('sync_queue')
      .query(Q.where('status', 'pending'))
      .fetch();

    return records.map((r: any) => ({
      id: r.id,
      operation_type: r.operation_type,
      entity_type: r.entity_type,
      entity_id: r.entity_id,
      data: JSON.parse(r.data),
      retry_count: r.retry_count,
      status: r.status,
    }));
  },

  /**
   * Process a sync queue item
   */
  async processSyncItem(item: SyncQueueItem): Promise<void> {
    try {
      // Mark as processing
      await this.updateItemStatus(item.id, 'processing');

      // Execute the sync operation
      await this.executeSyncOperation(item);

      // Remove from queue on success
      await this.removeItem(item.id);
    } catch (error) {
      console.error('Sync failed for item:', item.id, error);

      // Increment retry count
      await this.incrementRetryCount(item.id);

      if (item.retry_count >= 3) {
        await this.updateItemStatus(item.id, 'failed');
      } else {
        await this.updateItemStatus(item.id, 'pending');
      }
    }
  },

  /**
   * Execute the actual API call based on operation type
   */
  async executeSyncOperation(item: SyncQueueItem): Promise<void> {
    const { operation_type, entity_type, entity_id, data } = item;

    switch (entity_type) {
      case 'job':
        if (operation_type === 'CREATE') {
          await apiClient.post('/jobs', data);
        } else if (operation_type === 'UPDATE') {
          await apiClient.patch(`/jobs/${entity_id}`, data);
        } else if (operation_type === 'DELETE') {
          await apiClient.delete(`/jobs/${entity_id}`);
        }
        break;

      case 'note':
        if (operation_type === 'CREATE') {
          await apiClient.post('/jobs/notes', data);
        } else if (operation_type === 'UPDATE') {
          await apiClient.patch(`/jobs/notes/${entity_id}`, data);
        } else if (operation_type === 'DELETE') {
          await apiClient.delete(`/jobs/notes/${entity_id}`);
        }
        break;

      case 'photo':
        // Photos are handled specially due to FormData
        await this.syncPhotoUpload(item);
        break;
    }
  },

  async syncPhotoUpload(item: SyncQueueItem): Promise<void> {
    const formData = new FormData();
    formData.append('job_id', item.data.job_id);
    formData.append('photo', {
      uri: item.data.uri,
      type: item.data.type,
      name: item.data.fileName,
    } as any);

    await apiClient.post('/jobs/photos', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  },

  async updateItemStatus(id: string, status: string): Promise<void> {
    await database.write(async () => {
      const record = await database.collections.get('sync_queue').find(id);
      await record.update((r: any) => {
        r.status = status;
      });
    });
  },

  async incrementRetryCount(id: string): Promise<void> {
    await database.write(async () => {
      const record = await database.collections.get('sync_queue').find(id);
      await record.update((r: any) => {
        r.retry_count += 1;
      });
    });
  },

  async removeItem(id: string): Promise<void> {
    await database.write(async () => {
      const record = await database.collections.get('sync_queue').find(id);
      await record.destroyPermanently();
    });
  },
};
```

### 2. Network-Aware Operations

```typescript
// src/hooks/useNetworkAwareRequest.ts
import { useState } from 'react';
import NetInfo from '@react-native-community/netinfo';
import { syncQueue } from '../services/sync/syncQueue';

export function useNetworkAwareRequest<T>() {
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  async function executeRequest(
    onlineRequest: () => Promise<T>,
    offlineAction: {
      operation_type: 'CREATE' | 'UPDATE' | 'DELETE';
      entity_type: 'job' | 'note' | 'photo';
      entity_id: string;
      data: any;
    }
  ): Promise<T | null> {
    setIsLoading(true);
    setError(null);

    const netInfo = await NetInfo.fetch();

    try {
      if (netInfo.isConnected) {
        // Online: Execute request immediately
        const result = await onlineRequest();
        setIsLoading(false);
        return result;
      } else {
        // Offline: Queue for later sync
        await syncQueue.enqueue(offlineAction);
        setIsLoading(false);
        return null; // Indicate offline mode
      }
    } catch (err) {
      setError('Request failed');
      setIsLoading(false);
      throw err;
    }
  }

  return { executeRequest, isLoading, error };
}

// Usage example
function createJobOfflineAware(jobData: CreateJobDto) {
  const { executeRequest } = useNetworkAwareRequest<Job>();

  return executeRequest(
    // Online request
    () => JobService.createJob(jobData),
    // Offline action
    {
      operation_type: 'CREATE',
      entity_type: 'job',
      entity_id: generateTempId(),
      data: jobData,
    }
  );
}
```

---

## Error Handling

### 1. API Error Response Format

```typescript
// src/types/api.ts
export interface APIError {
  message: string;
  errors?: Record<string, string[]>;
  code?: string;
}

export interface APIResponse<T> {
  data: T;
  message?: string;
  meta?: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}
```

### 2. Error Handler Utility

```typescript
// src/utils/errorHandler.ts
import { AxiosError } from 'axios';
import type { APIError } from '../types/api';

export function handleAPIError(error: unknown): string {
  if (axios.isAxiosError(error)) {
    const axiosError = error as AxiosError<APIError>;

    if (axiosError.response) {
      // Server responded with error
      const { message, errors } = axiosError.response.data;

      if (errors) {
        // Validation errors
        const firstError = Object.values(errors)[0];
        return firstError[0];
      }

      return message || 'An error occurred';
    }

    if (axiosError.code === 'ECONNABORTED') {
      return 'Request timeout. Please try again.';
    }

    if (!axiosError.response) {
      return 'Network error. Please check your connection.';
    }
  }

  return 'An unexpected error occurred';
}
```

### 3. Using in Components

```typescript
// Example usage
async function handleCreateJob() {
  try {
    const job = await JobService.createJob(formData);
    Alert.alert('Success', 'Job created successfully');
    navigation.goBack();
  } catch (error) {
    const errorMessage = handleAPIError(error);
    Alert.alert('Error', errorMessage);
  }
}
```

---

## Pagination

```typescript
// src/hooks/usePaginatedJobs.ts
import { useState, useEffect } from 'react';
import { JobService } from '../services/api/jobService';
import type { Job, PaginatedResponse } from '../types/api';

export function usePaginatedJobs() {
  const [jobs, setJobs] = useState<Job[]>([]);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);
  const [isLoading, setIsLoading] = useState(false);
  const [isLoadingMore, setIsLoadingMore] = useState(false);

  async function loadJobs() {
    setIsLoading(true);
    try {
      const response = await JobService.fetchJobs({ page: 1, per_page: 20 });
      setJobs(response.data);
      setLastPage(response.meta.last_page);
      setPage(1);
    } catch (error) {
      console.error('Failed to load jobs:', error);
    } finally {
      setIsLoading(false);
    }
  }

  async function loadMore() {
    if (page >= lastPage || isLoadingMore) return;

    setIsLoadingMore(true);
    try {
      const nextPage = page + 1;
      const response = await JobService.fetchJobs({
        page: nextPage,
        per_page: 20,
      });

      setJobs([...jobs, ...response.data]);
      setPage(nextPage);
    } catch (error) {
      console.error('Failed to load more jobs:', error);
    } finally {
      setIsLoadingMore(false);
    }
  }

  useEffect(() => {
    loadJobs();
  }, []);

  return {
    jobs,
    isLoading,
    isLoadingMore,
    hasMore: page < lastPage,
    loadMore,
    refresh: loadJobs,
  };
}
```

---

## Testing API Integration

### Mock API for Tests

```typescript
// __mocks__/apiClient.ts
import { jest } from '@jest/globals';

const mockApiClient = {
  get: jest.fn(),
  post: jest.fn(),
  patch: jest.fn(),
  delete: jest.fn(),
};

export default mockApiClient;
```

### Unit Test Example

```typescript
// __tests__/services/jobService.test.ts
import { JobService } from '../../src/services/api/jobService';
import apiClient from '../../src/services/api/apiClient';

jest.mock('../../src/services/api/apiClient');

describe('JobService', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('should fetch jobs successfully', async () => {
    const mockJobs = [
      { id: '1', job_number: 'JOB-001', status: 'pending' },
    ];

    (apiClient.get as jest.Mock).mockResolvedValue({
      data: { data: mockJobs },
    });

    const jobs = await JobService.fetchJobs();

    expect(apiClient.get).toHaveBeenCalledWith('/jobs', { params: undefined });
    expect(jobs.data).toEqual(mockJobs);
  });

  it('should handle API errors', async () => {
    (apiClient.get as jest.Mock).mockRejectedValue(
      new Error('Network error')
    );

    await expect(JobService.fetchJobs()).rejects.toThrow('Network error');
  });
});
```

---

## Best Practices

1. **Always handle offline scenarios** - Queue operations when offline
2. **Implement retry logic** - Max 3 retries with exponential backoff
3. **Use TypeScript** - Define types for all API responses
4. **Handle errors gracefully** - User-friendly error messages
5. **Implement pagination** - Don't load all data at once
6. **Optimize photo uploads** - Compress before upload, batch when possible
7. **Cache responses** - Use AsyncStorage for frequently accessed data
8. **Monitor network state** - Show offline indicator to users

---

## Related Documentation

- [Mobile API Design](../02-architecture/13-mobile-api-design.md)
- [Offline Sync Strategy](../02-architecture/14-offline-sync.md)
- [Mobile Development Setup](03-mobile-development-setup.md)

---

**Happy coding! 🚀**
