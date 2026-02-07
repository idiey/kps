# Mobile Development Setup Guide

> **Last Updated**: 2026-02-02  
> **Version**: 2.0.0-rework  
> **Platform**: React Native + Expo  

---

## Prerequisites

### Required Software

- **Node.js**: 18+ LTS
- **npm**: 9+ or **Yarn**: 3+
- **Git**: Latest version
- **Expo CLI**: Latest version
- **iOS Development** (macOS only):
  - Xcode 14+
  - iOS 13+ simulator
  - CocoaPods (`sudo gem install cocoapods`)
- **Android Development**:
  - Android Studio
  - Android SDK 31+
  - Java JDK 11+

### Mobile Device or Emulator

- **iOS**: Physical iPhone (iOS 13+) or Simulator
- **Android**: Physical Android device (8.0+) or Emulator (AVD)
- **Expo Go app** installed on physical device (for quick testing)

---

## Project Structure

```
mobile/
├── src/
│   ├── screens/          # Screen components
│   │   ├── auth/         # Login, registration
│   │   ├── jobs/         # Job list, details, create
│   │   ├── photos/       # Camera, gallery, upload
│   │   └── sync/         # Sync status, conflict resolution
│   │
│   ├── components/       # Reusable components
│   │   ├── common/       # Button, Input, Card
│   │   ├── jobs/         # JobCard, StatusBadge
│   │   └── sync/         # SyncIndicator, OfflineBanner
│   │
│   ├── navigation/       # React Navigation setup
│   │   ├── AppNavigator.tsx
│   │   ├── AuthNavigator.tsx
│   │   └── MainTabNavigator.tsx
│   │
│   ├── services/         # Business logic
│   │   ├── api/          # API client
│   │   ├── auth/         # Authentication service
│   │   ├── sync/         # Sync engine
│   │   └── storage/      # Local storage
│   │
│   ├── database/         # WatermelonDB models
│   │   ├── models/       # Job, Photo, Note
│   │   ├── schema.ts     # Database schema
│   │   └── migrations.ts # Schema migrations
│   │
│   ├── utils/            # Helper functions
│   │   ├── formatters.ts
│   │   ├── validators.ts
│   │   └── constants.ts
│   │
│   ├── hooks/            # Custom React hooks
│   │   ├── useAuth.ts
│   │   ├── useSync.ts
│   │   └── useNetwork.ts
│   │
│   ├── types/            # TypeScript types
│   │   ├── api.ts
│   │   ├── models.ts
│   │   └── navigation.ts
│   │
│   └── config/           # Configuration
│       ├── api.config.ts
│       ├── app.config.ts
│       └── theme.ts
│
├── assets/               # Images, fonts, icons
├── app.json              # Expo configuration
├── package.json
├── tsconfig.json
├── babel.config.js
└── README.md
```

---

## Initial Setup

### 1. Navigate to Mobile Directory

```bash
cd mobile/
```

### 2. Install Dependencies

```bash
# Using npm
npm install

# Or using Yarn
yarn install
```

### 3. Install iOS Dependencies (macOS only)

```bash
npx pod-install
```

### 4. Environment Configuration

Create `.env` file:

```bash
cp .env.example .env
```

Edit `.env`:

```env
# API Configuration
API_BASE_URL=http://192.168.1.100:8000/api
API_TIMEOUT=30000

# Authentication
AUTH_TOKEN_KEY=@workshop:auth_token
REFRESH_TOKEN_KEY=@workshop:refresh_token

# Sync Configuration
SYNC_INTERVAL=300000
MAX_RETRY_ATTEMPTS=3

# Photo Upload
MAX_PHOTO_SIZE_MB=5
PHOTO_QUALITY=0.8
MAX_PHOTOS_PER_UPLOAD=10

# Feature Flags
ENABLE_OFFLINE_MODE=true
ENABLE_PUSH_NOTIFICATIONS=true
ENABLE_DEBUG_MENU=true
```

> **Note**: Replace `192.168.1.100:8000` with your local Laravel server IP

---

## Development Workflow

### Start Expo Development Server

```bash
npm start
# or
yarn start
# or
npx expo start
```

### Running on iOS

```bash
# iOS Simulator
npm run ios

# Specific iOS version
npm run ios -- --simulator="iPhone 14 Pro"
```

### Running on Android

```bash
# Android Emulator
npm run android

# Specific Android device
adb devices
npm run android -- --device "Pixel_5_API_31"
```

### Running on Physical Device

1. Install **Expo Go** app from App Store / Play Store
2. Start dev server: `npm start`
3. Scan QR code with:
   - iOS: Camera app
   - Android: Expo Go app

---

## Database Setup (WatermelonDB)

### Creating a Model

```bash
# No CLI, create manually
touch src/database/models/Job.ts
```

Example model:

```typescript
// src/database/models/Job.ts
import { Model } from '@nozbe/watermelondb';
import { field, date, readonly } from '@nozbe/watermelondb/decorators';

export default class Job extends Model {
  static table = 'jobs';

  @field('job_number') jobNumber!: string;
  @field('customer_name') customerName!: string;
  @field('status') status!: string;
  @field('sync_status') syncStatus!: string;
  @date('created_at') createdAt!: Date;
  @date('updated_at') updatedAt!: Date;
  @readonly @date('synced_at') syncedAt!: Date;
}
```

### Update Schema

```typescript
// src/database/schema.ts
import { appSchema, tableSchema } from '@nozbe/watermelondb';

export default appSchema({
  version: 1,
  tables: [
    tableSchema({
      name: 'jobs',
      columns: [
        { name: 'job_number', type: 'string', isIndexed: true },
        { name: 'customer_name', type: 'string' },
        { name: 'status', type: 'string' },
        { name: 'sync_status', type: 'string' },
        { name: 'created_at', type: 'number' },
        { name: 'updated_at', type: 'number' },
        { name: 'synced_at', type: 'number', isOptional: true },
      ],
    }),
  ],
});
```

---

## API Integration

### API Service Example

```typescript
// src/services/api/apiClient.ts
import axios from 'axios';
import { API_BASE_URL } from '@env';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  timeout: 30000,
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
});

// Request interceptor (add auth token)
apiClient.interceptors.request.use(async (config) => {
  const token = await getAuthToken();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Response interceptor (handle errors)
apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      await handleSessionExpired();
    }
    return Promise.reject(error);
  }
);

export default apiClient;
```

### Making API Calls

```typescript
// src/services/api/jobService.ts
import apiClient from './apiClient';
import { Job } from '../../types/models';

export const JobService = {
  async fetchJobs(): Promise<Job[]> {
    const response = await apiClient.get('/jobs');
    return response.data.data;
  },

  async fetchJobById(id: string): Promise<Job> {
    const response = await apiClient.get(`/jobs/${id}`);
    return response.data.data;
  },

  async updateJobStatus(id: string, status: string): Promise<void> {
    await apiClient.patch(`/jobs/${id}/status`, { status });
  },
};
```

---

## Offline Sync Implementation

### Sync Service

```typescript
// src/services/sync/syncEngine.ts
import NetInfo from '@react-native-community/netinfo';
import { database } from '../../database';
import { syncQueue } from './syncQueue';
import { JobService } from '../api/jobService';

export class SyncEngine {
  private syncInterval: NodeJS.Timer | null = null;

  async startPeriodicSync() {
    this.syncInterval = setInterval(() => {
      this.performSync();
    }, 300000); // 5 minutes
  }

  async performSync() {
    const isConnected = await NetInfo.fetch().then(
      (state) => state.isConnected
    );

    if (!isConnected) {
      console.log('Skipping sync: No network connection');
      return;
    }

    try {
      // Push local changes
      await this.pushLocalChanges();

      // Pull remote changes
      await this.pullRemoteChanges();
    } catch (error) {
      console.error('Sync failed:', error);
      this.handleSyncError(error);
    }
  }

  private async pushLocalChanges() {
    const queue = await syncQueue.getPendingItems();
    for (const item of queue) {
      await syncQueue.processSyncItem(item);
    }
  }

  private async pullRemoteChanges() {
    const lastSyncTime = await AsyncStorage.getItem('last_sync_time');
    const jobs = await JobService.fetchJobs();
    await database.write(async () => {
      // Update local database
    });
  }
}
```

---

## Push Notifications Setup

### 1. Install Firebase Cloud Messaging

```bash
npx expo install @react-native-firebase/app @react-native-firebase/messaging
```

### 2. Configure Firebase

Add `google-services.json` (Android) and `GoogleService-Info.plist` (iOS) to project.

### 3. Request Permissions

```typescript
// src/services/notifications/notificationService.ts
import messaging from '@react-native-firebase/messaging';

export const NotificationService = {
  async requestPermission() {
    const authStatus = await messaging().requestPermission();
    return (
      authStatus === messaging.AuthorizationStatus.AUTHORIZED ||
      authStatus === messaging.AuthorizationStatus.PROVISIONAL
    );
  },

  async getDeviceToken(): Promise<string> {
    return await messaging().getToken();
  },

  async registerDevice(token: string) {
    await apiClient.post('/devices/register', {
      device_id: getUniqueId(),
      fcm_token: token,
      platform: Platform.OS,
    });
  },
};
```

---

## Testing

### Unit Tests (Jest)

```bash
npm test
```

### E2E Tests (Detox)

```bash
# Build iOS app
npm run build:e2e:ios

# Run iOS E2E tests
npm run test:e2e:ios

# Build Android app
npm run build:e2e:android

# Run Android E2E tests
npm run test:e2e:android
```

---

## Debugging

### React Native Debugger

1. Install [React Native Debugger](https://github.com/jhen0409/react-native-debugger)
2. Start debugger
3. In Expo, shake device > "Debug Remote JS"

### Flipper (Advanced)

1. Install [Flipper](https://fbflipper.com/)
2. Install React Native plugin
3. Enable network inspection, database viewer, logs

### Common Issues

**1. Metro bundler cache issues**
```bash
npx expo start -c
```

**2. iOS build fails**
```bash
cd ios/
pod deintegrate
pod install
cd ..
npm run ios
```

**3. Android build fails**
```bash
cd android/
./gradlew clean
cd ..
npm run android
```

**4. Database not initializing**
```bash
# Clear app data
# iOS: Reset simulator
# Android: Uninstall and reinstall app
```

---

## Build for Production

### iOS (TestFlight)

```bash
# requires Apple Developer account
eas build --platform ios
```

### Android (Google Play)

```bash
eas build --platform android
```

See [Mobile Deployment Guide](../05-deployment/03-mobile-deployment.md) for full release process.

---

## Useful Commands

```bash
# Clear cache
npx expo start -c

# Upgrade Expo SDK
npx expo upgrade

# Check for outdated packages
npm outdated

# TypeScript type checking
npm run typecheck

# Linting
npm run lint

# Format code
npm run format
```

---

## Resources

- [React Native Docs](https://reactnative.dev/docs/getting-started)
- [Expo Docs](https://docs.expo.dev/)
- [WatermelonDB Docs](https://nozbe.github.io/WatermelonDB/)
- [React Navigation](https://reactnavigation.org/docs/getting-started)
- [Firebase Cloud Messaging](https://rnfirebase.io/messaging/usage)
- [Architecture Diagram](../02-architecture/14-offline-sync.md)
- [API Documentation](../02-architecture/13-mobile-api-design.md)

---

**Ready to build! 📱**
