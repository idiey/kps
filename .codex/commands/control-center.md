---
description: Read or update the Control Center memory hub
---

# /control-center Workflow

Interact with `docs/00-control-center/` to read or update project memory.

## Steps

1. Check if `docs/00-control-center/` exists. If not, create the standard files: `README.md`, `01-project-brief.md`, `02-current-state.md`, `03-system-map.md`, `04-decisions.md`, `05-ai-agent-brief.md`.

2. Ask what the user wants: read a summary, update a specific file, add a decision entry, validate freshness, log an intake item, or route a prompt. If they want a specific action, use the matching workflow (`/control-center-summary`, `/control-center-update`, `/control-center-decision`, `/control-center-validate`, `/control-center-intake`, `/control-center-route`). `/cc` is an alias for `/control-center`.

3. When updating, keep entries concise, use concrete dates (YYYY-MM-DD), and update the `Last Updated` line at the bottom of the file.

4. Report changed files and a short summary of updates.
