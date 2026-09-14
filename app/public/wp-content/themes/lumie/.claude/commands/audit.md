---
description: Audit npm and Composer dependencies and update vulnerable packages
argument-hint: "[--dry-run] [npm|composer]"
allowed-tools: Bash, Read, Edit, Grep, Glob
---

# Dependency Vulnerability Audit

Goal: identify and update any vulnerable dependencies in this WordPress theme (both npm and Composer), without breaking the build.

## Scope

Run against the theme root (current working directory). Skip vendored frontend libs in `dist/`, `node_modules/`, and `vendor/` — those are build artifacts, never edit them directly.

Arguments:

- `npm` — only audit npm dependencies
- `composer` — only audit Composer dependencies
- `--dry-run` — report findings only, do NOT modify lockfiles or `package.json` / `composer.json`
- (no args) — audit both ecosystems

## Procedure

### 1. Pre-flight checks

Run in parallel:

- `git status --short` — confirm working tree is clean. If dirty, stop and ask the user whether to proceed or stash first. Never overwrite uncommitted work.
- `git branch --show-current` — record the current branch.
- Check `package.json` and `composer.json` exist; skip whichever is missing.

### 2. Audit npm (if applicable)

```bash
npm audit --json
```

Parse the output:

- Capture total vulnerabilities by severity (critical, high, moderate, low, info).
- For each advisory, note: package name, severity, vulnerable range, fixed range, whether it's a direct or transitive dependency.
- If `npm audit` reports nothing, state "npm: 0 vulnerabilities" and move on.

### 3. Audit Composer (if applicable)

```bash
composer audit --format=json
```

If `composer audit` is unavailable (older Composer), fall back to:

```bash
composer outdated --direct
```

…and check the [FriendsOfPHP/security-advisories](https://github.com/FriendsOfPHP/security-advisories) feed via `composer require --dev roave/security-advisories:dev-latest` only if explicitly approved by the user.

Parse output the same way as npm: severity, package, current vs. safe version, direct vs. transitive.

### 4. Categorize fixes

Group findings into three buckets:

1. **Safe auto-fixes** — patch/minor bumps within current major (semver-safe).
2. **Breaking fixes** — require a major version bump. Flag and explain the breaking change; do NOT apply without explicit user approval.
3. **No fix available** — vulnerability reported but no patched version exists. Note as a known risk; suggest mitigation (config change, removal, replacement).

### 5. Apply fixes (skip if `--dry-run`)

For each safe auto-fix:

- npm: `npm update <package>` (or `npm install <package>@<fixed-version>` for direct deps). Avoid `npm audit fix --force` — it can introduce breaking changes silently.
- Composer: `composer update <vendor/package> --with-dependencies`.

After each fix, run the build to confirm nothing broke:

- `npm run build` for npm changes
- `composer dump-autoload -o` for Composer changes

If a build fails, revert that specific package change (`git checkout -- package-lock.json package.json` or the Composer equivalent for that package) and add it to the breaking-fixes bucket instead.

### 6. Report

Output a concise summary:

```
## Audit Summary

### Fixed
- <package>: <old> → <new> (<severity>)

### Needs approval (breaking)
- <package>: <old> → <new> — <reason / migration notes>

### No fix available
- <package> (<severity>) — <CVE / advisory link>

### Build status
- npm build: pass | fail
- composer autoload: pass | fail
```

Reference advisories with their CVE/GHSA id and a link to the advisory page so the user can verify.

### 7. Do NOT

- Do not run `npm audit fix --force` — too risky.
- Do not edit `package-lock.json` or `composer.lock` by hand.
- Do not commit changes — leave the diff staged-but-unstaged for the user to review.
- Do not bump major versions without explicit approval, even if `npm audit` recommends it.
- Do not skip the post-fix build verification.

## Output

End with a one-line next-step recommendation: e.g. "Review the diff, then commit with `chore(deps): patch vulnerable dependencies`" — or, if breaking fixes remain, "Run `/audit composer` after upgrading <package> manually."
