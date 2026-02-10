---
description: Classify and route a prompt into Control Center memory
---

# /control-center-route Workflow

Classify the prompt and route it to the intake log (and requirements log if REQ).

## Steps

1. Ask for the prompt text if not provided.
2. If the user provides an explicit tag (REQ/DEC/TASK/Q/NOTE), use it; otherwise classify.
3. Run: `node scripts/control-center-route.mjs --text="..." --source=chat` (add `--type=REQ` if explicit).
4. Run `node scripts/control-center-brain.mjs` to refresh the Brain Index.
5. Respond with the detected type and the one-line summary only.
