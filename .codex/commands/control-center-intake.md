---
description: Log a prompt into the Control Center intake log
---

# /control-center-intake Workflow

Log a prompt as requirement, decision, task, question, or note.

## Steps

1. Ask for tag (REQ, DEC, TASK, Q, NOTE) and the short summary if not provided.
2. Append one line to `docs/00-control-center/06-intake.md` using the format:
   `YYYY-MM-DD | TAG | summary | source`.
3. Update the `Last Updated` line in the intake file.
4. Run `node scripts/control-center-brain.mjs` to refresh the Brain Index.
5. Report the new intake entry.
