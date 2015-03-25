Seabreeze
=========

[![Code Climate](http://img.shields.io/codeclimate/github/FlameCore/Seabreeze.svg)](https://codeclimate.com/github/FlameCore/Seabreeze)
[![License](http://img.shields.io/packagist/l/flamecore/seabreeze.svg)](http://www.flamecore.org/projects/seabreeze)

Seabreeze is a deployment and testing tool for database-driven web applications. It aims to be very flexible and extensible.

The program uses our self-developed [Synchronizer](https://github.com/FlameCore/Synchronizer) library as backend.


Features
--------

* Deploy files locally and remotely

* Automatically deploy database schemas (planned)

* Run all your tests with one simple command

* Fast and easy to use


Usage
-----

Invoke the program from your project directory:

    vendor/bin/breeze COMMAND ...

To see a list of available commands, use the `list` command:

    vendor/bin/breeze list


Installation
------------

### Install via Composer

Create a file called `composer.json` in your project directory and put the following into it:

```
{
    "require": {
        "flamecore/seabreeze": "dev-master"
    }
}
```

[Install Composer](https://getcomposer.org/doc/00-intro.md#installation-nix) if you don't already have it present on your system:

    curl -sS https://getcomposer.org/installer | php

Use Composer to [download the vendor libraries](https://getcomposer.org/doc/00-intro.md#using-composer) and generate the vendor/autoload.php file:

    php composer.phar install

To make use of the API, include the vendor autoloader and use the classes:

```php
namespace Acme\MyApplication;

use FlameCore\Seabreeze\Manifest\Project;

require 'vendor/autoload.php';
```


Requirements
------------

* You must have at least PHP version 5.4 installed on your system.


Contributors
------------

If you want to contribute, please see the [CONTRIBUTING](CONTRIBUTING.md) file first.

Thanks to the contributors:

* Christian Neff (secondtruth)
