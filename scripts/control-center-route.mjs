import fs from 'node:fs';
import path from 'node:path';

const args = process.argv.slice(2);
const argMap = Object.fromEntries(
  args
    .filter((arg) => arg.startsWith('--'))
    .map((arg) => {
      const [key, ...rest] = arg.replace(/^--/, '').split('=');
      return [key, rest.join('=')];
    })
);

let rawText = argMap.text || '';
const textFile = argMap['text-file'];
const source = argMap.source || 'chat';
const forcedType = argMap.type ? argMap.type.toUpperCase() : null;

if (!rawText.trim() && textFile) {
  try {
    rawText = fs.readFileSync(textFile, 'utf8');
  } catch (error) {
    console.error(`Failed to read --text-file: ${textFile}`);
    process.exit(1);
  }
}

if (!rawText.trim()) {
  console.error('Missing --text for routing.');
  process.exit(1);
}

if (rawText.includes('[no-cc]')) {
  process.exit(0);
}

const root = process.cwd();
const intakePath = path.join(root, 'docs', '00-control-center', '06-intake.md');
const requirementPath = path.join(root, 'docs', '10-requirement', 'requirements-log.md');

if (!fs.existsSync(intakePath)) {
  console.error('Intake log not found at docs/00-control-center/06-intake.md');
  process.exit(1);
}

if (!fs.existsSync(requirementPath)) {
  fs.writeFileSync(
    requirementPath,
    '# Requirements Log\n\nShort, one-line requirements for later evaluation.\n\n## Entries\n\n---\n\n**Last Updated**: 2026-02-08\n',
    'utf8'
  );
}

const normalizedText = normalizeText(rawText);
const type = forcedType || classify(normalizedText);
const today = new Date();
const dateStr = today.toISOString().slice(0, 10);
const summary = sanitizeField(summarize(normalizedText));
const prompt = sanitizeField(truncate(normalizedText, 280));
const tokens = estimateTokens(normalizedText);

appendEntry(
  intakePath,
  `- ${dateStr} | ${type} | ${summary} | tokens:${tokens} | prompt:"${prompt}" | source:${source}`,
  dateStr
);

if (type === 'REQ') {
  appendEntry(requirementPath, `- ${dateStr} | ${summary} | source:${source}`, dateStr);
}

function classify(text) {
  const trimmed = text.trim();
  const lower = trimmed.toLowerCase();

  if (lower.startsWith('req:') || lower.includes('requirement') || lower.includes('must ') || lower.includes('should ') || lower.includes('need to')) {
    return 'REQ';
  }
  if (lower.startsWith('dec:') || lower.includes('decision') || lower.startsWith('decide') || lower.includes('should we')) {
    return 'DEC';
  }
  if (
    lower.startsWith('task:') ||
    lower.startsWith('todo:') ||
    lower.startsWith('fix ') ||
    lower.startsWith('add ') ||
    lower.startsWith('implement ') ||
    lower.startsWith('create ') ||
    lower.startsWith('update ') ||
    lower.startsWith('build ')
  ) {
    return 'TASK';
  }
  if (trimmed.endsWith('?')) {
    return 'Q';
  }
  return 'NOTE';
}

function summarize(value) {
  const cleaned = normalizeText(value);
  if (cleaned.length <= 140) return cleaned;
  return `${cleaned.slice(0, 137)}...`;
}

function normalizeText(value) {
  return value.replace(/\s+/g, ' ').trim();
}

function truncate(value, maxLength) {
  if (value.length <= maxLength) return value;
  return `${value.slice(0, maxLength - 3)}...`;
}

function sanitizeField(value) {
  return value.replace(/\|/g, '/').replace(/"/g, "'");
}

function estimateTokens(value) {
  const length = value.trim().length;
  if (!length) return 0;
  return Math.max(1, Math.ceil(length / 4));
}

function appendEntry(filePath, entryLine, dateStr) {
  const content = fs.readFileSync(filePath, 'utf8');
  const lines = content.split(/\r?\n/);
  const separatorIndex = lines.findIndex((line) => line.trim() === '---');
  const logHeaderIndex = lines.findIndex((line) => line.trim() === '## Log' || line.trim() === '## Entries');
  const insertIndex = logHeaderIndex >= 0 ? logHeaderIndex + 1 : separatorIndex;

  if (insertIndex >= 0) {
    lines.splice(insertIndex, 0, '', entryLine);
  } else {
    lines.push('', entryLine, '', '---');
  }

  const updated = lines
    .map((line) => {
      if (line.match(/^\*\*Last Updated\*\*:/)) {
        return `**Last Updated**: ${dateStr}`;
      }
      return line;
    })
    .join('\n');

  fs.writeFileSync(filePath, updated, 'utf8');
}
