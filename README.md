# Literary Calendar

A calendar for literary and poetry events in NYC.

## Development

This app is a Craft CMS app (PHP & MySQL) hosted on Heroku. Setting up a local development environment requires the following dependencies:

- [PHP](https://www.php.net/) version 5.6.* (see [installation suggestions](#installing-php) below)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/)

[This article describes how to configure a Craft app for Heroku deployment](https://medium.com/@aj1215/craft-cms-on-heroku-79b991665b0b). Note that it suggests installing Composer globally. This doesn't seem to be necessary, deployment will work just as well with a local install of Composer.

### Installing PHP

Because the default installation of PHP (on Mac OS X, at least) doesn't include many necessary extensions, we suggest using [Homebrew](http://brew.sh/) to install your own PHP build and any libraries/extensions.

The [homebrew-php](https://github.com/Homebrew/homebrew-php) repo contains instructions for installing a PHP version as well as extensions.

For example, once you have installed PHP with Homebrew, you can install an extension like imagick:

```shell-session
$ brew install php56-imagick
```
