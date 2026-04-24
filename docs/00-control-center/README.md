# Control Center README

> Central memory hub for the KPS project.

**Purpose**: Provide quick access to all Control Center documents  
**Last Updated**: 2026-04-07

## Overview

The Control Center is a memory hub that maintains essential project context, decisions, and state information. It serves as a single source of truth for project status and history.

## Contents

### Core Documents

1. **[Brain Index](00-brain-index.md)** - Quick reference summary of project state
2. **[Project Brief](01-project-brief.md)** - High-level overview of purpose, scope, and objectives
3. **[Current State](02-current-state.md)** - Implementation status and active work
4. **[System Map](03-system-map.md)** - Technical architecture and component relationships
5. **[Decisions](04-decisions.md)** - Key architectural and design decisions
6. **[AI Agent Brief](05-ai-agent-brief.md)** - Instructions and context for AI agents
7. **[Intake Log](06-intake.md)** - Chronological log of requests and work items

### Supporting Documents

- **[Brain Protocol](07-brain-protocol.md)** - Guidelines for maintaining Control Center

## Quick Access

### For Developers

- Start with [AI Agent Brief](05-ai-agent-brief.md) for code patterns and guidelines
- Check [System Map](03-system-map.md) for architecture overview
- Review [Decisions](04-decisions.md) for design rationale

### For Product Owners

- Start with [Project Brief](01-project-brief.md) for business context
- Check [Current State](02-current-state.md) for progress
- Review [Intake Log](06-intake.md) for work history

### For AI Agents

- Start with [Brain Index](00-brain-index.md) for quick context
- Read [AI Agent Brief](05-ai-agent-brief.md) for patterns and constraints
- Check [Current State](02-current-state.md) for what's in progress

## Workflows

Use these slash workflows to interact with Control Center from Claude or Codex:

- `/cc` or `/control-center` - General Control Center access
- `/cc-summary` - Generate quick summary
- `/cc-update` - Update a specific file
- `/cc-decision` - Add a decision entry
- `/cc-validate` - Check freshness
- `/cc-intake` - Log a new request
- `/cc-route` - Route information to appropriate file

## Command Source of Truth and Sync

- Source of truth (authoring): `.agent/workflows/*.md`
- Codex command mirror: `.codex/commands/*.md`

When workflows are added or changed, sync them to Codex:

```powershell
New-Item -ItemType Directory -Force .codex\commands | Out-Null
Copy-Item .agent\workflows\*.md .codex\commands\ -Force
```

## Maintenance

The Control Center should be updated:

- **Daily**: Intake log for new requests
- **Weekly**: Current state for progress updates
- **As needed**: Decisions for architectural choices
- **Monthly**: Brain index for summary refresh

## Related Documentation

- [Main Documentation](../README.md)
- [PRD](../02-architecture/01-prd.md)
- [System Design](../02-architecture/02-system-design.md)

---

**Last Updated**: 2026-04-07
