## Getting Started
1. Clone the GitHub repository into your themes directory.
1. In the plugin directory run `npm i`.
1. Run `npm run dev` to compile your files automatically whenever you've made changes to the associated files.
1. Run `npm run build` to compile files for release.

## License
Demaxin WordPress Theme, Copyright (C) 2021, Nikolai Nasibullin.
Demaxin is distributed under the terms of the GNU GPL.

## Update POT file
1. Install WP-CLI and add to PATH https://wp-cli.org/#installing
1. Run `npm run build` to compile files for release.
1. Navigate to ./languages
1. Run `wp i18n make-pot ./.. demaxin_test.pot --exclude="src/"`
1. To subtract new strings run `wp i18n make-pot ./.. demaxin_test-new.pot --subtract="demaxin_test.pot" --exclude="src/"`
