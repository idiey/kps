# User Manual Writer Skill - Design Spec

**Date:** 2026-04-04
**Status:** Approved

---

## Overview

KPS needs a reusable Codex skill that can write end-user manuals for any repository, not just this
project. The skill should guide Codex through a disciplined workflow: stop for user intake first,
inspect the repository second, propose a chaptered manual outline under `docs/user_manual/`, and
only then draft the manual content.

The skill should optimize for operational clarity, task-based documentation, and low hallucination
risk. It should produce documentation that fits cleanly into repositories with existing docs
structures while still working in repos that have little or no documentation.

---

## Goals

1. Create a portable skill that can be installed once and used across repositories.
2. Standardize user manual output in `docs/user_manual/`.
3. Force an intake step before drafting to capture audience, workflows, and scope boundaries.
4. Generate multi-file manuals with a `README.md` entry point plus numbered chapters.
5. Keep content focused on end users, operators, or admins rather than developers.
6. Require a final quality pass for structure, links, gaps, and unsupported claims.

---

## Non-Goals

- Auto-generate screenshots or image assets.
- Produce developer documentation, architecture docs, or API references.
- Infer product behavior without user confirmation when repo evidence is weak or contradictory.
- Enforce one universal chapter set for all products regardless of scope.

---

## Recommended Approach

Implement a workflow-first skill named `user-manual-writer`.

This skill should encode the authoring sequence directly in `SKILL.md` rather than relying on heavy
automation. The hard part of user-manual writing is not scaffolding files; it is collecting the
right context, shaping a useful outline, and avoiding invented workflows. A workflow-first skill is
therefore the lowest-maintenance and highest-signal option.

The skill may include lightweight references or examples if they materially improve consistency, but
it should avoid scripts unless a later iteration shows repeated mechanical work that cannot be
handled reliably in prose.

---

## Triggering

The skill should trigger when the user asks Codex to:

- write or draft a user manual
- create end-user documentation
- build a help guide, operator guide, or admin manual
- restructure existing user-facing docs into a manual
- document workflows for non-technical users

The description should emphasize the trigger conditions, not summarize the workflow. It should make
clear that the skill is for user-facing manuals and task-based help content across repositories.

---

## Skill Workflow

### 1. Mandatory Intake Gate

Before inspecting code in depth or drafting files, the skill should require Codex to ask the user
for the minimum manual-writing inputs:

- target audience or role(s)
- main workflows the manual must cover
- scope boundaries and exclusions
- prerequisites or environment assumptions
- whether source material already exists, such as old docs, screenshots, or SOPs

If the user does not provide enough information, Codex should keep asking one question at a time.
The skill should explicitly forbid drafting the manual before this intake is complete.

### 2. Repository Discovery

After intake, Codex should inspect the repository for product context:

- existing docs and README files
- navigation labels and route names
- UI text, forms, menus, and workflow terminology
- role and permission concepts
- setup or troubleshooting notes already present in the repo

This scan should be used to ground terminology and identify likely manual chapters, not to bypass
the intake gate.

### 3. Outline Proposal and Approval

Before writing the manual, Codex should propose the `docs/user_manual/` structure and wait for user
approval. The proposal should include:

- `docs/user_manual/README.md`
- numbered chapter files
- a short description of what each chapter covers

The skill should instruct Codex to tailor chapter names to the product. Example chapter types may
include overview, access, common tasks, feature workflows, troubleshooting, and FAQ, but these are
examples rather than fixed requirements.

### 4. Drafting

Once the outline is approved, Codex should draft the manual chapter by chapter. Each chapter should:

- speak to end users in plain language
- use task-based steps where possible
- call out prerequisites, permissions, and expected outcomes
- identify unknown behavior explicitly instead of inventing details
- cross-link to related chapters where helpful

### 5. Final Verification

Before claiming completion, Codex should check:

- all manual files live under `docs/user_manual/`
- `README.md` links to every chapter
- numbering and filenames are consistent
- content matches the approved outline
- unsupported assumptions are marked or removed
- troubleshooting and recovery guidance exists where the workflow can fail

---

## Output Structure

The skill should standardize on this root:

```text
docs/
  user_manual/
    README.md
    01-overview.md
    02-core-workflows.md
    03-troubleshooting.md
    ...
```

The exact chapter names should adapt to the product, but the files should remain numbered and live
under the same root.

### README Responsibilities

`docs/user_manual/README.md` should contain:

- the manual title
- intended audience
- scope summary
- navigation to all chapters
- quick-start or most common task links when applicable

### Chapter Responsibilities

Each numbered chapter should have one clear purpose. Likely chapter patterns include:

- getting started or access
- core workflows
- feature-specific tasks
- troubleshooting
- FAQ or reference appendix

The skill should tell Codex to avoid creating empty sections or irrelevant chapters just to satisfy a
template.

---

## Writing Standards

The skill should bias Codex toward:

- concise, direct instructions
- user-facing terminology taken from the product
- numbered steps for procedures
- warnings where actions are destructive or permission-bound
- examples only when they reduce ambiguity
- minimal implementation detail unless it affects user operation

The skill should explicitly avoid:

- architecture-heavy explanations
- code-level descriptions
- speculative behavior
- copy that sounds like marketing rather than operational guidance

---

## Bundled Skill Contents

Initial version:

- `SKILL.md` with the workflow and guardrails
- `agents/openai.yaml` for Codex UI metadata

Optional later additions if needed:

- `references/outline-examples.md` with a few chapter map patterns for common app types
- `references/intake-checklist.md` if the intake sequence becomes large enough to justify
  extraction

No scripts are required in the first version.

---

## Validation

Validation should cover both structure and behavior.

### Static Validation

- run the skill validation script against the finished skill folder
- confirm the frontmatter follows naming and description rules
- confirm `agents/openai.yaml` is present and aligned with the skill

### Behavioral Validation

Test the skill with at least one realistic prompt that asks for a user manual. The expected behavior
is:

1. Codex stops and asks intake questions first.
2. Codex proposes a `docs/user_manual/` outline before drafting.
3. Codex writes user-facing chapters rather than developer docs.

If the first pass skips the intake gate or drafts too early, revise the skill wording before
finalizing it.

---

## Implementation Notes

- Default installation location: the user's auto-discovered Codex skills directory.
- Skill name should be short, searchable, and verb-led enough to trigger reliably.
- The skill should remain generic across repositories and avoid KPS-specific assumptions.
- The first release should favor clarity over completeness. Additional reference files can be added
  after real usage exposes gaps.

---

## Risks and Mitigations

| Risk | Mitigation |
| --- | --- |
| Codex drafts immediately without enough context | Make the intake gate explicit and mandatory |
| Codex writes developer docs instead of user docs | Repeatedly frame target output as end-user manual content |
| Manuals become bloated with irrelevant chapters | Require outline approval before drafting |
| Repo evidence is incomplete or contradictory | Instruct Codex to surface unknowns and ask follow-up questions |
| Skill becomes too rigid for different products | Keep chapter names adaptive while fixing only the root structure |

---

## Out of Scope

- automatic screenshot capture
- PDF export
- localization and translation workflows
- publishing to external doc portals
