---
description: Validate Control Center freshness
---

# /control-center validate Workflow

Check freshness and missing metadata using the Brain script.

## Steps

1. Run `node scripts/control-center-brain.mjs --check`.
2. If stale files are reported, list them with ages.
3. If the Brain Index is missing, run `node scripts/control-center-brain.mjs` to generate it.
4. Suggest updates for any stale or missing metadata.
