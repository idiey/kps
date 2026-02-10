---
description: Append a dated decision entry
---

# /control-center decision Workflow

Record a new decision in `docs/00-control-center/04-decisions.md`.

## Steps

1. Ask for the decision title, date (YYYY-MM-DD), and 1-3 bullet details.
2. Append a new entry under the Decisions section using the same format.
3. Update the `Last Updated` line.
4. Run `node scripts/control-center-brain.mjs` to refresh the Brain Index.
5. Report the new entry location and a short summary.
