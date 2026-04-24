---
description: Generate a short summary from the Brain Index
---

# /control-center summary Workflow

Provide a summary of the Control Center using `00-brain-index.md`. Accepts optional argument `brief` or `detailed`.

## Steps

1. Check for `docs/00-control-center/00-brain-index.md`.
2. If missing, run `node scripts/control-center-brain.mjs` to generate it.
3. Ask which output format to use: `brief` or `detailed` (default to `brief`). If the user supplies it as an argument (e.g., `/control-center-summary brief`), use that.
4. If `brief`, return 3 bullets: current state, key decision, biggest risk/stale item.
5. If `detailed`, include sections for Quick Links, Summaries, Freshness, and Stale Files.
6. Report if any items are stale or missing metadata.
