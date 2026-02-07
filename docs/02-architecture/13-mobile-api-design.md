# Mobile API Design

> **Status**: Planning  
> **Last Updated**: 2026-02-02  
> **Version**: 1.0  
> **Platform**: React Native + Expo  

---

## Overview

This document defines the REST API architecture for the Workshop Management System mobile application, optimized for mobile bandwidth constraints, offline-first operations, and battery efficiency.

## Design Principles

### 1. Mobile-First Optimization

- **Minimal Payloads**: Only send required fields
- **Pagination**: All list endpoints support cursor-based pagination
- **Compression**: gzip/brotli compression for all responses
- **Conditional Requests**: ETags and If-Modified-Since support

### 2. Offline-First Architecture

- **Sync Endpoints**: Incremental data synchronization
- **Batch Operations**: Queue multiple operations in one request
- **Conflict Resolution**: Last-write-wins with server priority
- **Version Tracking**: `updated_at` timestamps on all entities

### 3. Battery & Network Efficiency

- **Request Batching**: Combine multiple operations
- **Delta Sync**: Only transfer changed data since last sync
- **Background Sync**: Low-priority sync queue
- **Exponential Backoff**: Retry failed requests gracefully

---

## Authentication

### Token-Based (Laravel Sanctum)

**Login Endpoint**

```http
POST /api/mobile/v1/auth/login
Content-Type: application/json

{
  "email": "technician@workshop.com",
  "password": "password123",
  "device_id": "abc-123-device-uuid",
  "device_name": "iPhone 14 Pro"
}
```

**Response**

```json
{
  "token": "1|abcdefgh...",
  "user": {
    "id": "uuid",
    "name": "John Technician",
    "email": "john@workshop.com",
    "role": "technician",
    "workshop_id": "uuid",
    "workshop_name": "Main Workshop",
    "permissions": ["jobs.view", "jobs.update"]
  },
  "token_expires_at": "2026-03-02T15:42:04Z"
}
```

### Biometric Authentication

After initial login, mobile app uses:
- **Face ID / Touch ID** (iOS)
- **Fingerprint / Face Unlock** (Android)

Biometric unlock retrieves stored token from secure keychain.

---

## Core Endpoints

### Jobs API

#### GET /api/mobile/v1/jobs

Retrieve jobs with filters and pagination.

**Query Parameters**:
- `status` - Filter by job status (new, in_progress, completed)
- `assigned_to_me` - Boolean, filter assigned jobs
- `updated_since` - ISO 8601 timestamp for delta sync
- `cursor` - Pagination cursor
- `limit` - Results per page (default: 20, max: 100)

**Response**:

```json
{
  "data": [
    {
      "id": "uuid",
      "job_number": "WJ-20260202-0001",
      "job_mode": "NORMAL",
      "status": "in_progress",
      "customer_name": "Ahmad Abdullah",
      "vehicle": "Proton X70 - ABC1234",
      "description": "Engine service + brake pads",
      "assigned_to_id": "uuid",
      "created_at": "2026-02-01T10:00:00Z",
      "updated_at": "2026-02-02T14:30:00Z",
      "photos_count": 3,
      "notes_count": 2
    }
  ],
  "meta": {
    "next_cursor": "eyJpZCI6InV1aWQiLCJ1cGRhdGVkX2F0IjoiMjAyNi0wMi0wMlQxNDozMDowMFoifQ==",
    "has_more": true
  },
  "sync": {
    "server_time": "2026-02-02T15:42:04Z",
    "total_jobs": 150
  }
}
```

#### GET /api/mobile/v1/jobs/{id}

Get full job details including notes, photos, and history.

**Response**:

```json
{
  "id": "uuid",
  "job_number": "WJ-20260202-0001",
  "job_mode": "NORMAL",
  "status": "in_progress",
  "customer": {
    "id": "uuid",
    "name": "Ahmad Abdullah",
    "phone": "+60123456789",
    "email": "ahmad@example.com"
  },
  "vehicle": {
    "make": "Proton",
    "model": "X70",
    "plate_number": "ABC1234",
    "year": 2023
  },
  "description": "Engine service + brake pads replacement",
  "assigned_to": {
    "id": "uuid",
    "name": "John Technician"
  },
  "notes": [
    {
      "id": "uuid",
      "content": "Started engine inspection",
      "visibility": "private",
      "created_by": "John Technician",
      "created_at": "2026-02-02T10:30:00Z"
    }
  ],
  "photos": [
    {
      "id": "uuid",
      "url": "https://cdn.workshop.com/photos/abc123.jpg",
      "thumbnail_url": "https://cdn.workshop.com/photos/abc123_thumb.jpg",
      "caption": "Engine bay - before",
      "taken_at": "2026-02-02T10:00:00Z",
      "gps": {"lat": 3.1390, "lng": 101.6869}
    }
  ],
  "created_at": "2026-02-01T10:00:00Z",
  "updated_at": "2026-02-02T14:30:00Z"
}
```

#### PATCH /api/mobile/v1/jobs/{id}

Update job status or details.

**Request**:

```json
{
  "status": "completed",
  "completion_note": "All repairs completed successfully"
}
```

**Response**: Updated job object

---

### Photo Upload API

#### POST /api/mobile/v1/jobs/{id}/photos

Upload photo with GPS metadata.

**Content-Type**: `multipart/form-data`

**Fields**:
- `photo` - Image file (JPEG, PNG, max 10MB)
- `caption` - Optional photo description
- `gps_latitude` - GPS latitude (optional)
- `gps_longitude` - GPS longitude (optional)
- `taken_at` - ISO 8601 timestamp when photo was taken

**Response**:

```json
{
  "id": "uuid",
  "url": "https://cdn.workshop.com/photos/def456.jpg",
  "thumbnail_url": "https://cdn.workshop.com/photos/def456_thumb.jpg",
  "caption": "Brake pads - after replacement",
  "taken_at": "2026-02-02T14:00:00Z",
  "gps": {"lat": 3.1390, "lng": 101.6869},
  "created_at": "2026-02-02T14:01:00Z"
}
```

**Compression Strategy**:
- Mobile app compresses images to max 1920x1080 before upload
- JPEG quality: 85%
- Exif data preserved (GPS, timestamp)

---

### Notes API

#### POST /api/mobile/v1/jobs/{id}/notes

Add note to a job.

**Request**:

```json
{
  "content": "Replaced brake pads and rotors",
  "visibility": "private" 
}
```

**Response**: Created note object

---

### Sync API

#### POST /api/mobile/v1/sync

Batch synchronization endpoint for offline operations.

**Request**:

```json
{
  "device_id": "abc-123-device-uuid",
  "last_sync_at": "2026-02-02T10:00:00Z",
  "operations": [
    {
      "id": "local_uuid_1",
      "type": "JOB_UPDATE",
      "entity_id": "job_uuid",
      "data": {"status": "completed"},
      "timestamp": "2026-02-02T14:00:00Z"
    },
    {
      "id": "local_uuid_2",
      "type": "NOTE_CREATE",
      "parent_id": "job_uuid",
      "data": {"content": "Work completed", "visibility": "private"},
      "timestamp": "2026-02-02T14:05:00Z"
    }
  ]
}
```

**Response**:

```json
{
  "success": true,
  "results": [
    {
      "local_id": "local_uuid_1",
      "status": "success",
      "entity_id": "job_uuid"
    },
    {
      "local_id": "local_uuid_2",
      "status": "success",
      "entity_id": "note_uuid_created"
    }
  ],
  "updated_entities": {
    "jobs": [...],
    "notes": [...]
  },
  "sync": {
    "server_time": "2026-02-02T15:42:04Z"
  }
}
```

---

## Error Handling

### Standard Error Response

```json
{
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid.",
    "details": {
      "status": ["The status field is required."]
    }
  }
}
```

### Error Codes

| Code | HTTP Status | Description |
|------|-------------|-------------|
| `VALIDATION_ERROR` | 422 | Input validation failed |
| `UNAUTHORIZED` | 401 | Invalid or expired token |
| `FORBIDDEN` | 403 | Insufficient permissions |
| `NOT_FOUND` | 404 | Resource not found |
| `CONFLICT` | 409 | Data conflict (e.g., stale version) |
| `SERVER_ERROR` | 500 | Internal server error |
| `SERVICE_UNAVAILABLE` | 503 | Temporary outage |

---

## Rate Limiting

- **Authenticated requests**: 120 requests per minute
- **Photo uploads**: 20 uploads per minute
- **Sync endpoint**: 10 requests per minute

Rate limit headers:

```
X-RateLimit-Limit: 120
X-RateLimit-Remaining: 115
X-RateLimit-Reset: 1612345678
```

---

## Versioning Strategy

API versioned at `/api/mobile/v1/`.

**Version Support**:
- Current version (v1): Full support
- Previous version: 6 months grace period
- Deprecated: 3 months warning before removal

---

## Performance Targets

| Metric | Target | Notes |
|--------|--------|-------|
| **Response Time** | < 200ms | 95th percentile |
| **Payload Size** | < 50KB | Average job list response |
| **Photo Upload** | < 3s | 2MB image over 4G |
| **Sync Duration** | < 5s | 100 queued operations |

---

## Related Documentation

- [Offline Sync Strategy](14-offline-sync.md) - Synchronization architecture
- [Mobile Application PRD](11-mobile-prd.md) - Mobile app requirements
- [Notification Architecture](15-notification-architecture.md) - Push notifications
