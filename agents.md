# agents.md -- ownCloud Updater Server

## Repository Overview

Server component that responds to ownCloud Server update check requests. Licensed under MIT. PHP-based with a Docker deployment option.

- **Product family:** Infrastructure / Tooling
- **Primary language(s):** PHP, Gherkin

## Architecture & Key Paths

- `config/` -- Update configuration (version mappings)
- `src/` -- PHP application source
- `public/` -- Web entry point
- `tests/` -- Integration tests (Behat)
- `overlay/` -- Docker overlay files
- `Dockerfile` -- Docker image build
- `Makefile` -- Build and test automation
- `composer.json` -- PHP dependencies

## Development Conventions

- PHP application
- Behat for integration testing
- Docker-based deployment

## Build & Test Commands

```bash
composer install              # Install dependencies
make test                     # Run integration tests (starts local server)
make test-production          # Test against production instance
make test-php-unit            # Run PHP unit tests
```

## Important Constraints

- Licensed under MIT. The OSPO target is Apache 2.0.
- Deployed at updates.owncloud.com for production use.
- All contributions require a DCO sign-off.
- Do not introduce new **copyleft-licensed dependencies** (GPL, AGPL, LGPL, MPL) without explicit discussion in an issue first. This is especially important for repos that are migrating to or already under Apache 2.0, as copyleft dependencies would block or complicate that migration.


## OSPO Policy Constraints

### GitHub Actions
- **Only** use actions owned by `owncloud`, created by GitHub (`actions/*`), verified on the GitHub Marketplace, or verified by the ownCloud Maintainers.
- Pin all actions to their full commit SHA (not tags): `uses: actions/checkout@<SHA> # vX.Y.Z`
- Never introduce actions from unverified third parties.

### Dependency Management
- Dependabot is configured for automated dependency updates.
- Review and merge Dependabot PRs as part of regular maintenance.
- Do not introduce new dependencies without discussion in an issue first.

### Git Workflow
- **Rebase policy**: Always rebase; never create merge commits. Use `git pull --rebase` and `git rebase` before pushing.
- **Signed commits**: All commits **must** be PGP/GPG signed (`git commit -S -s`).
- **DCO sign-off**: Every commit needs a `Signed-off-by` line (`git commit -s`).
- **Conventional Commits & Squash Merge**: Use the [Conventional Commits](https://www.conventionalcommits.org/) format where the repository enforces it. Many repos use squash merge, where the PR title becomes the commit message on the default branch — apply Conventional Commits format to PR titles as well. A reusable GitHub Actions workflow enforces this.

## Context for AI Agents

The update logic is in `config/config.php`, which maps ownCloud versions to available updates. Tests verify that the correct update response is returned for each version. The server returns XML responses with version info and download URLs.
