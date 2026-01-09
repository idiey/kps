# 09. PWA Requirement & Architecture (Proposed)

## 📌 Context

To support technicians in the workshop environment (where Wi-Fi might be unstable and mobile data is preferred), the system requires transformation into a **Progressive Web App (PWA)**. This will allow the use of native mobile features and improve the reliability of the "Evidence Management" module.

## 🚀 Key Requirements

### 1. Offline Photo Queueing
- **Technician Need:** Take photos even when the connection drops.
- **Technical Requirement:** Implement **IndexedDB** storage to queue photo uploads.
- **Workflow:** Photos captured via `JobPhoto` module should be stored locally first, then synced automatically to `PhotoStorageService` once the connection is restored.

### 2. Push Notifications
- **Role Need:** Supervisors and Technicians need real-time alerts for new job assignments.
- **Technical Requirement:** Integration with **Web Push API** and **Service Workers**.
- **Implementation:** Use a library like `Vite PWA Plugin` to manage the service worker lifecycle.

### 3. Home Screen Installation (A2HS)
- **User Need:** Access the workshop system like a native app without a browser URL bar.
- **Technical Requirement:**
  - Web Manifest (`manifest.json`) with workshop icons and theme colors.
  - Responsive design ensuring all "Job" and "Inspection" pages are mobile-first.

### 4. Biometric Authentication (Optional/Future)
- **Security Need:** Fast login for technicians in the field.
- **Technical Requirement:** **WebAuthn API** for face/fingerprint unlock.

---

## 🛠️ Proposed Tech Stack

- **Library:** [Vite PWA Plugin](https://vite-pwa-org.netlify.app/)
- **Strategy:** `InjectManifest` for custom background sync logic.
- **Cache Strategy:** `CacheFirst` for static assets (CSS/JS/Icons) to ensure instant load.

## 🏁 Success Criteria

1. System passes Lighthouse PWA audit (100/100).
2. Technician can open the app and view their current "My Jobs" list without an active internet connection.
3. "Add to Home Screen" prompt appears on Android/iOS devices.

---

**Last Updated**: 2026-01-07
**Status**: Proposed / High Priority
