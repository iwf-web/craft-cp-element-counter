# Control Panel Element Counter plugin for Craft CMS 5.x

[![License](https://img.shields.io/github/license/iwf-web/craft-cp-element-counter)][license]

This plugin brings back the CP Element Count plugin, yeah!

Plugin shows you the number of elements - Entries, Assets, Categories and Users directly in the control panel.

![Screenshot](https://raw.githubusercontent.com/iwf-web/craft-cp-element-counter/refs/heads/main/resources/screenshot.png)

Brought to you by [IWF](https://www.iwf.ch/web-solutions) with a big thank you to [André Elvan](https://www.vaersaagod.no/) for the original plugin.

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- Craft CMS 5.0.0 or later

### Installation

You can install this plugin from the Plugin Store or with Composer.

#### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for “Control Panel Element Counter”. Then press “Install”.

#### With Composer

Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project.test

# tell Composer to load the plugin
composer require iwf/craft-control-panel-element-counter

# tell Craft to install the plugin
./craft plugin/install control-panel-element-counter
```

## Contributing

Please read [CONTRIBUTING.md][contributing] for details on our code of conduct and the process for submitting pull requests.

This project uses [Conventional Commits](https://www.conventionalcommits.org/) for automated releases and changelog generation.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For available versions, see the [tags on this repository][gh-tags].

There is no `version` field in `composer.json` — Composer derives the version from the Git tag.

## Releasing

Releases are automated with [release-please][release-please]. Tags and changelog entries are never written by hand.

1. Merge your work into `develop`, using [Conventional Commits](https://www.conventionalcommits.org/).
2. Open a pull request from `develop` to `main` and merge it.
3. release-please opens a release pull request (`chore(main): release x.y.z`) that bumps [`.github/release-please-manifest.json`](.github/release-please-manifest.json) and prepends an entry to `CHANGELOG.md`.
4. Merge that pull request. The tag and the GitHub release follow automatically.

The commit types drive the version bump: a `feat:` gives a minor release, `fix:` and `perf:` a patch, and `feat!:` or a `BREAKING CHANGE:` footer a major one.

Only the types configured in [`.github/release-please-config.json`](.github/release-please-config.json) reach the changelog — `feat`, `fix`, `perf`, `refactor` and `docs`. Everything else, `chore`, `test`, `style`, `build` and `ci` included, is left out. The bullet text is the commit description verbatim, so it is worth writing that line for a reader of the changelog.

### Pull request titles

Do not prefix pull request titles with a Conventional Commit type.

GitHub writes the pull request title into the body of the merge commit, and release-please reads it like any other commit message. A pull request titled `feat: count carts` therefore contributes a changelog entry of its own, on top of the entries from the commits it contains, and the same work gets listed twice. Give the pull request a plain title ("Cart counters") and let the commits speak.

## Authors

### Special thanks for all the people who had helped this project so far

- **Stefan Friedrich** - [stefanfriedrich](https://github.com/stefanfriedrich)

See also the full list of [contributors][gh-contributors] who participated in this project.

### I would like to join this list. How can I help the project?

We're currently looking for contributions for the following:

- [ ] Bug fixes
- [ ] Translations
- [ ] etc...

For more information, please refer to our [CONTRIBUTING.md][contributing] guide.

## License

This project is licensed under the MIT License - see the [LICENSE.txt](LICENSE.txt) file for details.

## Acknowledgments

This project currently uses no third-party libraries or copied code.

[license]: https://github.com/iwf-web/craft-cp-element-counter/blob/main/LICENSE.txt
[gh-tags]: https://github.com/iwf-web/craft-cp-element-counter/tags
[gh-contributors]: https://github.com/iwf-web/craft-cp-element-counter/contributors
[contributing]: https://github.com/iwf-web/.github/blob/main/CONTRIBUTING.md
[release-please]: https://github.com/googleapis/release-please
