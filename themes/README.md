# Tin Can Forum Custom Themes

Tin Can allows you to customize the look of your forum with custom themes.

There are two ways to use a custom theme.

### 1) Make your own copy of the default theme

Copy the themes/tincan directory to a new directory named for your theme:

`cp -R themes/tincan themes/mytheme`

Edit the theme templates to suit your needs.

### 2) Create a child theme from the default theme

A child theme contains only the templates you want to overwrite. Tin Can will
automatically use templates from the parent theme when templates don't exist
in the child theme.

A child theme's name is always structured using a hyphen to identify the parent
theme. For example, *tincan-example* is a child theme of *tincan*.

Copy the provided example child theme to get started quickly:

`cp -R themes/tincan-example themes/tincan-mytheme`

**Note:** Avoid making changes to the default Tin Can theme.
Updates to Tin Can may overwrite or break your changes.

## Enabling your custom theme

Log into an account with the Administrator role and, under Forum Settings,
scroll down to the "Theme" section and enter the name of your theme.
The name must match the directory name.

For example, if your custom theme is in the directory *themes/mytheme*,
enter *mytheme*

## Sass and CSS

Tin Can themes use [Sass](https://sass-lang.com/guide) rather than CSS directly.
You'll need [Node.js](https://nodejs.org/) installed.

To compile a theme's CSS, enter the theme directory:

`cd themes/tincan`

First get things set up by running:

`npm install`

To compile CSS once, run:

`npm run sass:build`

To watch and compile CSS as you work on the SCSS files, run:

`npm run sass:watch`

A CSS file will be compiled from the contents of `sass/style.scss` and will appear as `css/style.css`

You'll only need to run `npm install` once.
