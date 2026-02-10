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

const type = (argMap.type || 'note').toUpperCase();
const text = argMap.text || '';
const source = argMap.source || 'chat';

if (!text.trim()) {
  console.error('Missing --text for intake entry.');
  process.exit(1);
}

const root = process.cwd();
const intakePath = path.join(root, 'docs', '00-control-center', '06-intake.md');

if (!fs.existsSync(intakePath)) {
  console.error('Intake log not found at docs/00-control-center/06-intake.md');
  process.exit(1);
}

const normalizedText = normalizeText(text);
const today = new Date();
const dateStr = today.toISOString().slice(0, 10);
const summary = sanitizeField(summarize(normalizedText));
const prompt = sanitizeField(truncate(normalizedText, 280));
const tokens = estimateTokens(normalizedText);
const entry = `- ${dateStr} | ${type} | ${summary} | tokens:${tokens} | prompt:"${prompt}" | source:${source}`;

const content = fs.readFileSync(intakePath, 'utf8');
const updated = insertEntry(content, entry, dateStr);

fs.writeFileSync(intakePath, updated, 'utf8');

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

function insertEntry(content, entryLine, dateStr) {
  const lines = content.split(/\r?\n/);
  const separatorIndex = lines.findIndex((line) => line.trim() === '---');

  if (separatorIndex === -1) {
    lines.push(entryLine, '', '---', '', `**Last Updated**: ${dateStr}`);
    return lines.join('\n');
  }

  const logHeaderIndex = lines.findIndex((line) => line.trim() === '## Log');
  const insertIndex = logHeaderIndex >= 0 ? logHeaderIndex + 1 : separatorIndex;

  lines.splice(insertIndex, 0, '', entryLine);

  const updated = lines
    .map((line) => {
      if (line.match(/^\*\*Last Updated\*\*:/)) {
        return `**Last Updated**: ${dateStr}`;
      }
      return line;
    })
    .join('\n');

  return updated;
}
