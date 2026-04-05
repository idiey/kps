# KPS Local Install Wizard Design

Date: 2026-04-05  
Status: Approved in conversation

## 1. Goal

Deliver KPS as a Windows-first installable product that works without manual environment setup.  
Primary usage is on one local PC, with optional access from other PCs on the same LAN.

## 2. Chosen Direction

- Platform: Windows only (V1)
- Network model: Single-PC by default, optional LAN access
- Runtime model: Bundled stack (no external PHP/DB required)
- Database: SQLite
- Architecture option selected: Native installer + Windows Service

## 3. Options Considered

### A. Native installer + Windows Service (selected)

- Pros: Most reliable startup, easiest support model, best fit for no-setup expectation
- Cons: Requires service packaging and Windows installer work

### B. Desktop shell app (Electron/Tauri) + embedded backend

- Pros: Strong desktop-app feel
- Cons: More packaging/runtime complexity, larger maintenance surface

### C. Portable launcher (no formal installer)

- Pros: Fastest initial distribution
- Cons: Weak upgrades, weak operational control, weaker enterprise supportability

## 4. Runtime Architecture

Install to:

- `C:\Program Files\KPS\` (application binaries/runtime)
- `C:\ProgramData\KPS\` (writable data, config, logs, backups)

Bundled components:

- Laravel app + built frontend assets
- Bundled PHP runtime
- Bundled local web server (Caddy recommended)
- SQLite database file (`ProgramData\KPS\data\kps.sqlite`)
- KPS Windows Service (auto-start)

Default access:

- `http://localhost:18400` (configurable at install time)

Startup sequence:

1. Windows Service starts on boot.
2. Runtime boots app server.
3. If bootstrap not completed, redirect to setup wizard.
4. If bootstrap completed, redirect to login/dashboard.

Operational controls:

- Service start/stop/restart from installer repair utilities
- Runtime health endpoint for installer and diagnostics
- Local diagnostic bundle export for support

## 5. Installation Wizard Flow

Two-phase setup:

### Phase A: Native installer

1. Welcome/license
2. Install path and data path confirmation
3. Port selection (default 18400, conflict check)
4. Service registration and startup
5. Launch browser to local KPS URL

### Phase B: In-app bootstrap wizard

1. Create initial admin account
2. Set organization basics (name/timezone/locale)
3. Seed baseline roles/permissions
4. Create initial site (optional skip allowed)
5. Final readiness checks and redirect to app

Bootstrap guard:

- Until completed, all app routes redirect to setup workflow
- Partial setup progress is resumable after interruption

## 6. Optional LAN Mode

Default mode:

- Bind to localhost only (`127.0.0.1`)
- No inbound firewall rule

When LAN mode is enabled by admin:

1. Bind service to LAN interface (or controlled listen mode)
2. Create/update Windows firewall rule for KPS port
3. Show discoverable connect URL (for example `http://192.168.1.20:18400`)

V1 LAN security controls:

- Explicit opt-in with warning
- Same-subnet default policy
- Optional IP allowlist
- Login rate limiting
- Session timeout policy
- Audit logging for LAN mode and admin access events
- Persistent UI indicator when LAN mode is enabled

## 7. Backup, Restore, and Update

Protected data:

- SQLite DB
- Uploaded files/storage
- Local configuration in `ProgramData\KPS`

Backup:

- In-app manual backup to timestamped zip archive
- Optional scheduled backup via Windows Task Scheduler
- Retention policy (for example keep last 14 backups)

Restore:

1. Enter maintenance mode
2. Validate backup manifest/version
3. Restore DB/files atomically
4. Restart service
5. Run post-restore health checks

Update:

- New installer performs in-place binary/runtime update
- `ProgramData\KPS` data is preserved by default
- Pre-update safety backup is created automatically
- Migrations run once after upgrade

Recovery:

- Installer repair mode for service/runtime/path permissions
- Support diagnostics export without secrets

## 8. Delivery Plan (V1)

Phase 1: Packaging and service runtime

- Bundle app + PHP + web server
- Register/operate Windows Service
- Localhost startup and health checks

Phase 2: First-run setup wizard

- Bootstrap gating
- Admin creation + org setup + initial seed/site

Phase 3: Optional LAN mode

- LAN toggle UX
- Firewall automation
- Audit and warning banner behavior

Phase 4: Backup/restore/update pipeline

- Manual and scheduled backups
- Restore flow with validation
- Installer-safe update path

## 9. Risks and Mitigations

- Port conflicts: detect early and offer alternative port
- Service startup failures: diagnostics + repair workflow
- SQLite integrity risks: WAL mode + graceful shutdown + pre-change backups
- Accidental LAN exposure: LAN off by default + explicit warnings + policy controls
- Upgrade migration failures: auto-backup + documented rollback steps

## 10. Acceptance Criteria

1. Fresh Windows machine installs and runs KPS without manual PHP/DB setup.
2. First-run wizard completes and produces working admin login.
3. KPS auto-starts after reboot via Windows Service.
4. LAN mode can be enabled/disabled and behaves correctly.
5. Backup and restore succeed on the same machine.
6. Installer update preserves data/settings and migrates safely.
7. Core KPS workflows remain functional after install/update.

## 11. Out of Scope for V1

- macOS/Linux installers
- Internet-facing multi-site hosting model
- Automatic TLS certificate lifecycle
- HA/cluster deployment
- Cloud-managed database modes

