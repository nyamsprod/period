---
layout: default
title: Installation
---

# Installation

## System Requirements

`Period` requires **PHP 8.0** but the latest stable version of PHP.

## Composer

`Period` is available on [Packagist](https://packagist.org/packages/league/period) and can be installed using [Composer](https://getcomposer.org/).

~~~bash
$ composer require league/period
~~~

## Going Solo

You can also use `Period` without using Composer by downloading the library and:

- using any other [PSR-4](http://www.php-fig.org/psr/psr-4/) compatible autoloader.
- using the bundle autoloader script as shown below:

~~~php
require 'path/to/league/repo/autoload.php';

use League\Period\Datepoint;

Datepoint::fromDateString('2012-05-23')->day()->endDate();
//returns DateTimeImmutable('2012-05-24 00:00:00.000000');
~~~

where `path/to/league/repo` represents the path where the library was extracted.
