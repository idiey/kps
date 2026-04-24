---
description: Update a specific Control Center file
---

# /control-center update Workflow

Update a specific file in `docs/00-control-center/` and keep metadata current.

## Steps

1. Ask which file to update and what needs to change.
2. Edit the file content with concise, actionable updates.
3. Update the `Last Updated` line with today's date (YYYY-MM-DD).
4. Run `node scripts/control-center-brain.mjs` to refresh the Brain Index.
5. Report changed files and a short summary of updates.
