# User Manual Writer Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build and install a reusable Codex skill that writes chaptered end-user manuals under `docs/user_manual/` for any repository.

**Architecture:** Implement the skill directly in the user's global Codex skills directory so it is auto-discovered across repositories. Keep the first version intentionally lean: one `SKILL.md` with the workflow and guardrails, plus `agents/openai.yaml` for UI metadata. Because subagent validation is not available in this session, use explicit behavior checks and static validation instead of fresh-agent pressure tests.

**Tech Stack:** Markdown, YAML, Python helper scripts (`init_skill.py`, `quick_validate.py`)

---

## File Structure

- Create: `C:\Users\zuraidiismail\.codex\skills\user-manual-writer\SKILL.md`
- Create: `C:\Users\zuraidiismail\.codex\skills\user-manual-writer\agents\openai.yaml`
- Use: `C:\Users\zuraidiismail\.codex\skills\.system\skill-creator\scripts\init_skill.py`
- Use: `C:\Users\zuraidiismail\.codex\skills\.system\skill-creator\scripts\quick_validate.py`

### Task 1: Scaffold the Skill Folder

**Files:**
- Create: `C:\Users\zuraidiismail\.codex\skills\user-manual-writer\SKILL.md`
- Create: `C:\Users\zuraidiismail\.codex\skills\user-manual-writer\agents\openai.yaml`

- [ ] **Step 1: Verify the target skill does not already exist**

```powershell
Test-Path 'C:\Users\zuraidiismail\.codex\skills\user-manual-writer'
```

Expected: `False`

- [ ] **Step 2: Scaffold the skill with the local helper**

```powershell
python 'C:\Users\zuraidiismail\.codex\skills\.system\skill-creator\scripts\init_skill.py' `
  user-manual-writer `
  --path 'C:\Users\zuraidiismail\.codex\skills' `
  --interface 'display_name=User Manual Writer' `
  --interface 'short_description=Draft end-user manuals for any repository' `
  --interface 'default_prompt=Use $user-manual-writer to draft a chaptered end-user manual for this repository.'
```

Expected: skill directory created with `SKILL.md` and `agents/openai.yaml`

- [ ] **Step 3: Verify the scaffolded file layout**

```powershell
Get-ChildItem -Recurse 'C:\Users\zuraidiismail\.codex\skills\user-manual-writer'
```

Expected: shows `SKILL.md` and `agents\openai.yaml`

### Task 2: Replace the Template With the Real Skill

**Files:**
- Modify: `C:\Users\zuraidiismail\.codex\skills\user-manual-writer\SKILL.md`
- Modify: `C:\Users\zuraidiismail\.codex\skills\user-manual-writer\agents\openai.yaml`

- [ ] **Step 1: Define the behavior checks that must fail without this skill and pass with it**

```text
Check 1: When asked to "write a user manual", Codex must stop and ask for audience, workflows, scope, prerequisites, and existing source material before drafting.
Check 2: After intake, Codex must propose a `docs/user_manual/` outline with `README.md` plus numbered chapter files and wait for approval before writing files.
Check 3: When drafting, Codex must keep the content user-facing, task-based, and explicit about unknowns instead of inventing behavior.
```

Expected: these checks become the acceptance criteria for the skill text

- [ ] **Step 2: Replace `SKILL.md` with the final workflow and guardrails**

```markdown
---
name: user-manual-writer
description: Use when Codex needs to write, rewrite, expand, or reorganize a user manual, end-user guide, operator guide, admin manual, help documentation, or task-based product manual for any repository.
---

# User Manual Writer

## Overview

Write end-user manuals as a grounded workflow, not as a freeform dump. Start with user intake,
inspect the repo second, propose the manual outline third, and only then draft files.

**Core principle:** Do not draft a manual before audience, workflows, and scope are explicit.

## Intake Gate

Before inspecting the repo deeply or writing any manual files, ask the user one question at a time
until you know:

- the primary audience and roles
- the workflows the manual must cover
- what is in scope and out of scope
- prerequisites, permissions, or environment assumptions
- whether source material already exists, such as old docs, screenshots, SOPs, or support notes

If those inputs are unclear, keep asking. Do not start drafting the manual early.

## Repository Discovery

After intake, inspect only the repo context needed to ground the manual:

- `README*`, `docs/**`, and existing user-facing docs
- routes, navigation labels, menus, and page titles
- forms, tables, and action labels users actually see
- role and permission concepts
- setup, troubleshooting, and operational notes already present

Prefer product terminology users will recognize over internal class or file names.

## Outline Before Drafting

Before creating or rewriting manual files, propose the structure under:

```text
docs/
  user_manual/
    README.md
    01-...
    02-...
```

The proposal must include:

- the intended audience
- the chapter filenames
- one-line purpose for each chapter

Wait for user approval before writing files.

## Writing Rules

When drafting:

- write for end users, operators, or admins, not developers
- favor task-based steps and concrete outcomes
- call out prerequisites, permissions, and irreversible actions
- use the product's actual labels, buttons, and menu names
- keep sections short and easy to scan
- cross-link related chapters when that reduces repetition
- include troubleshooting only for real failure points
- mark unknown behavior explicitly instead of inventing details

## Default Output Shape

Use `docs/user_manual/README.md` as the entry point. It should contain:

- the manual title
- intended audience
- scope summary
- links to every chapter
- quick links to the most common tasks when useful

Use numbered chapter files for the manual body. Common chapter patterns include:

- overview or getting access
- core workflows
- feature-specific tasks
- troubleshooting
- FAQ or appendix

Do not force irrelevant chapters into the manual.

## Guardrails

Never:

- draft before the intake gate is complete
- skip the outline approval step
- write architecture-heavy or code-level explanations unless they affect user operation
- assume workflow details the user and repo have not confirmed
- bury warnings inside long paragraphs

If repo evidence conflicts with user input, surface the conflict and ask a follow-up question.

## Final Verification

Before claiming the manual is complete, verify:

- every file is under `docs/user_manual/`
- `README.md` links to all chapters
- filenames are numbered consistently
- the written files match the approved outline
- unsupported assumptions are removed or clearly marked
- the content is user-facing and task-based

## Common Mistakes

- Starting from repo exploration before asking who the manual is for
- Translating internal code terms directly into user docs
- Writing one long README instead of a chaptered manual
- Filling gaps with guesses instead of calling out unknowns
```

Expected: `SKILL.md` is concise, triggerable, and aligned with the approved design

- [ ] **Step 3: Replace `agents/openai.yaml` with the final UI metadata**

```yaml
interface:
  display_name: "User Manual Writer"
  short_description: "Draft end-user manuals for any repository"
  default_prompt: "Use $user-manual-writer to draft a chaptered end-user manual for this repository."
```

Expected: metadata is valid and the default prompt explicitly names `$user-manual-writer`

- [ ] **Step 4: Re-read the skill files and confirm there are no placeholders**

```powershell
Get-Content -Raw 'C:\Users\zuraidiismail\.codex\skills\user-manual-writer\SKILL.md'
Get-Content -Raw 'C:\Users\zuraidiismail\.codex\skills\user-manual-writer\agents\openai.yaml'
```

Expected: no `TODO`, `TBD`, or template scaffolding remains

### Task 3: Validate the Skill

**Files:**
- Test: `C:\Users\zuraidiismail\.codex\skills\user-manual-writer\SKILL.md`
- Test: `C:\Users\zuraidiismail\.codex\skills\user-manual-writer\agents\openai.yaml`

- [ ] **Step 1: Run the static validator**

```powershell
python 'C:\Users\zuraidiismail\.codex\skills\.system\skill-creator\scripts\quick_validate.py' `
  'C:\Users\zuraidiismail\.codex\skills\user-manual-writer'
```

Expected: validation passes with exit code `0`

- [ ] **Step 2: Check the final installed file list**

```powershell
Get-ChildItem -Recurse 'C:\Users\zuraidiismail\.codex\skills\user-manual-writer'
```

Expected: only the intended skill files are present

- [ ] **Step 3: Run the acceptance checklist against the final skill text**

```text
Prompt A: "Build a user manual for this repo."
Expected behavior: the skill forces intake questions before drafting.

Prompt B: "Turn the existing docs into a user manual."
Expected behavior: the skill requires an outline under `docs/user_manual/` before file creation.

Prompt C: "Write an operator guide even if some workflows are unclear."
Expected behavior: the skill tells Codex to mark unknowns and avoid inventing behavior.
```

Expected: the written skill text unambiguously directs all three behaviors

### Task 4: Final Verification for Completion

**Files:**
- Test: `C:\Users\zuraidiismail\.codex\skills\user-manual-writer\SKILL.md`
- Test: `C:\Users\zuraidiismail\.codex\skills\user-manual-writer\agents\openai.yaml`

- [ ] **Step 1: Re-run the validator fresh**

```powershell
python 'C:\Users\zuraidiismail\.codex\skills\.system\skill-creator\scripts\quick_validate.py' `
  'C:\Users\zuraidiismail\.codex\skills\user-manual-writer'
```

Expected: validation still passes

- [ ] **Step 2: Confirm the skill directory exists at the auto-discovered path**

```powershell
Test-Path 'C:\Users\zuraidiismail\.codex\skills\user-manual-writer'
```

Expected: `True`

- [ ] **Step 3: Summarize the installed skill and its trigger terms**

```text
Installed skill: user-manual-writer
Path: C:\Users\zuraidiismail\.codex\skills\user-manual-writer
Triggers: user manual, end-user guide, operator guide, admin manual, help documentation
```

Expected: summary matches the final files exactly
