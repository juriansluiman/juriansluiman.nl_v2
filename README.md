juriansluiman.nl
================

This repository contains the project powering
[juriansluiman.nl](https://juriansluiman.nl). The site enables a blog and some static
ages, all powered by the [Slim PHP microframework](http://www.slimframework.com).
The source is put online to help others with similar projects. If you see any
bug, please don't hesitate to send in a pull request!

Installation
---
The repository is a PHP project using [composer](https://getcomposer.org) for
the PHP dependencies, [Bower](http://bower.io) for its frontend dependencies
and [gulp](http://gulpjs.com) to compile the stylesheets & javascript files.

Install composer, run `composer install`. Next, install Bower and Gulp and run
`bower install` and `npm install`. Thereafter, run `gulp` to compile the static
files.

If your webserver is set up correctly, the site should display just fine.

Considerations
---
The site is using Slim, ran via the `app.php` in the root of this project. All
endpoints are defined in `app/controllers.php`. Feel free to have a look around.

The blog articles are stored in Redis, a fast and powerful key-value store.