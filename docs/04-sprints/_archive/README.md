# Archive: Old Sprint Documentation

> **Archived Date**: 2026-02-02  
> **Reason**: System rework - replaced with new sprint planning

---

## Archived Files

These files are from the previous version of the project (either Dolibarr module or pre-rework Laravel version). They have been archived because the system is being reworked with new architecture:

### Old Sprint Files

1. **01-sprint-overview.md** - Old sprint planning (Dolibarr module)
2. **02-sprint-0-foundation.md** - Old foundation sprint (Dolibarr module)
3. **03-sprint-1-core.md** - Old core features sprint (Laravel v1)
4. **04-sprint-kew-pa-10-foundation.md** - Old KEW.PA-10 specific sprint
5. **05-sprint-2-workflow-restructuring.md** - Old workflow restructuring sprint

### Why Archived?

The system has been redesigned from:
- ❌ **Old**: Government-only KEW.PA-10 system with dynamic workflows
- ✅ **New**: Multi-tenant Workshop Management System with static dual job modes

### Current Sprint Documentation

See the active sprint planning:
- [03-sprint-rework-overview.md](../03-sprint-rework-overview.md) - Current 6-sprint rework plan
- [04-rework-todo.md](../04-rework-todo.md) - Detailed TODO checklist (~80 tasks)

### Architecture Changes

| Old System | New System |
|------------|------------|
| Dolibarr module | Standalone Laravel 12 app |
| Government-only | Multi-tenant (SaaS) |
| Dynamic workflow DB | Static job modes |
| Single tenant | Company → Workshop hierarchy |
| No mobile app | React Native mobile app |

---

**Note**: These files are kept for reference only. Do not use them for current development.
