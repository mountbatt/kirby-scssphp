# SCSSPHP Plugin for Kirby 3 & 4

This is a preprocessor for SCSS files. Built using the [scssphp library](https://github.com/scssphp/scssphp) by Leaf Corcoran. This Kirby 3 & 4 plugin will automatically process SCSS files when changed. As an option, you can use this plugin to create 'critical CSS'.

This repo is based on [kirby-v2-scssphp library](https://github.com/bartvandebiezen/kirby-v2-scssphp) by bartvandebiezen but ported to be compatible with latest Kirby 3.9.x & 4.x and PHP 8.2+

## Installing SCSS

1. Copy folder ‘scssphp’ inside ‘plugins’ to Kirby’s plugins folder.
2. Copy file ‘scss.php’ inside ‘snippets’ to Kirby’s snippets folder.
3. Call the SCSS snippet with `<?php snippet('scss') ?>` in your HTML head.
4. Create a folder ‘scss’ inside Kirby’s assets folder.
5. Create a file ‘default.scss’ and place it inside ‘assets/scss’.
6. Make sure the folder ‘assets/css’ exists on your server.
7. Add `'scssNestedCheck' => true` to the config of your dev environment. [Read more about multi environment setup for Kirby](https://getkirby.com/docs/guide/configuration#multi-environment-setup).

## Using SCSS plugin

After installing this plugin, 'assets/css/default.css' will be overwritten automatically. Make sure you backup your original CSS.

It is possible to create different SCSSs for each Kirby template. Just use the name of your template file for the SCSS file (e.g. 'article.scss' for 'templates/article.php'), and place it in 'assets/scss'. If no SCSS file for a template can be found, 'default.scss' will be used.


## Compatibility

PHP 8.2+
