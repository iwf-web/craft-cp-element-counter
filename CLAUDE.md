# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Craft CMS 5.x plugin that displays element counts (Entries, Assets, Categories, Users) in the control panel sidebar navigation.

## Development Commands

```bash
# Check code style (ECS - Easy Coding Standard)
composer check-cs

# Fix code style issues
composer fix-cs

# Run static analysis
composer phpstan
```

## Architecture

The plugin follows standard Craft CMS plugin architecture:

- **Main Plugin Class** (`src/CpElementCounter.php`): Registers the asset bundle on CP requests via `EVENT_AFTER_LOAD_PLUGINS`
- **CountController** (`src/controllers/CountController.php`): Exposes JSON API endpoints for fetching counts:
  - `actionGetEntriesCount` - counts by section UIDs
  - `actionGetCategoriesCount` - counts by category group UIDs
  - `actionGetUsersCount` - counts by user group UIDs + admins + total
  - `actionGetAssetsCount` - counts by folder IDs
- **CountService** (`src/services/CountService.php`): Backend logic using Craft element queries to count elements
- **Frontend Assets** (`src/resources/`): JavaScript calls controller endpoints and injects counts into the CP sidebar; CSS styles the counter badges

## Code Style

Uses Craft CMS ECS ruleset (`SetList::CRAFT_CMS_4` in `ecs.php`). All PHP files require `declare(strict_types=1)`.

## Branching

- Branch new features from `develop`
- Uses [Conventional Commits](https://www.conventionalcommits.org/) for automated releases
