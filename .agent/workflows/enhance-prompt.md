---
description: Enhance a prompt to be comprehensive and concise for LLMs
---

# Prompt Enhancement Workflow

This workflow helps you transform a basic request into a high-quality, comprehensive, and concise prompt optimized for LLM performance.

## Core Principles

1. **Clarity & Conciseness**: Remove ambiguity and fluff. Use direct language.
2. **Comprehensive Context**: Include Role, Goal, Context, and Constraints.
3. **Structured Format**: Use markdown headers or clear sections to organize the prompt.
4. **Explicit Output**: Clearly define how the result should look (format, tone, length).

## Prompt Structure Standard

When enhancing a prompt, organize it into these sections:

- **Identity/Role**: Who is the AI? (e.g., "Expert Laravel Developer")
- **Task/Objective**: What is the core task?
- **Context**: Background info, current state, relevant files.
- **Rules/Constraints**: What to avoid, specific libraries to use, coding style.
- **Output Format**: JSON, Markdown, Code only, etc.

## How to Enhance a Prompt

When the user asks to enhance a prompt (e.g., "Enhance this: 'make a login page'"):

1.  **Analyze the Request**: Identify the missing details.
    *   *Missing*: Tech stack (Vue/Laravel?), validation rules, design style?
2.  **Draft the Enhanced Prompt**:
    *   Assign a persona.
    *   Expand the task description.
    *   Add constraints (e.g., "Use Composition API", "Follow SOLID principles").
3.  **Review**: Ensure it is concise. Remove polite filler lines ("Please", "I would like").

### Example

**Input**:
"Help me fix the bug in the controller."

**Enhanced**:
```markdown
# Role
Expert Laravel Backend Developer

# Context
I am encountering a bug in `WorkflowController.php` where `middleware()` is undefined.
Project uses Laravel 10.

# Task
Analyze the provided controller code and fix the `middleware()` method call error.
Ensure the solution adheres to modern Laravel best practices (e.g., constructor injection or route-based middleware).

# Output
- Explain the cause of the error.
- Provide the corrected code block.
```

## Usage

Use this workflow by typing `/enhance-prompt` followed by your draft idea or prompt.
The agent will rewrite it to be production-ready.
