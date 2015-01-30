autoloader
==========

A simple PHP autoloader that loads class files recursively.

### Purpose

There are several nice fully-featured, [PSR-0](http://www.php-fig.org/psr/psr-0/)/[PSR-4]
(http://www.php-fig.org/psr/psr-4/) compliant autoloaders out there.  Many
packages such as [Composer]() come with them pre-packaged, but they can also be
used independently.  Why create yet another with so many truly nice options
available?  Simplicity.  I wanted something simple and basic that I could drop
into any project with a basic directory structure and be up and running without
the complexities that come with other solutions.  I also wanted something that
I could release to the Open Source community that would be simple enough for
beginners to use and understand.  If you're using Composer or Laravel or some
other package that includes an autoloader, you should use it.  This is for the
times when you're not.

### Target audience and prerequisites
This project is aimed at programmers with a basic knowledge of PHP and MySQL.
It assumes that you already have:
* A web server running PHP
* A basic understanding of general programming concepts and PHP syntax

## A Brief Explanation

### What is autoloading?
Class autoloading, typically referred to simply as "autoloading", is a method
of including necessary class files in a project dynamically at runtime as
opposed to hard-coding an include statement for each class file dependency in
every file.  This allows for faster development and less bloated files.

### PHP support for autoloading
PHP 5 added support for autoloading with the introduction of the `__autoload()`
function.  The `spl_autoload_register()` function added in PHP 5.1.2 provided
more flexibility and is considered to be the proper method of implementing
autoloading in PHP today.  More recently, PHP 5.3 has added support for
namespaces.

## Changelog

**v1.1.3**
* Minor updates to documentation.

**v1.1.2**
* Minor fixes to README.md.

**v1.1.1**
* Added comment to sample index.php file.

**v1.1.0**
* Initial release


## Requirements

* **PHP 5.3+**, Minimum PHP 5.1.2

## Live Demo
A working demo of this code is provided in the sample directory.  Upload this
directory to your webserver and access the index.php page in your web browser.

## License

The MIT License (MIT)

Copyright (c) 2014 Rob Dunham

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

**[http://opensource.org/licenses/MIT](http://opensource.org/licenses/MIT)**

## Disclaimer

The code presented here is fully functioning and should not present any issues
when used in a production environment.  However, server environments and coding
practices differ widely and this code should be considered for educational
purposes only.

## Contribute

Suggestions and pull requests are always welcome.

#### USAGE

An example implementation can be found in the sample directory.  The
*autoload.php* file should be placed the topmost directory of your project
where class files are located, or in your project's root directory.  For best
performance, it's best to keep all of your class files in a separate directory
away from the rest of your project.  This helps to limit unnecessary overhead
caused by traversing the file system.

Once the *autoload.php* file is in place, include it once near the top of your
current PHP file.

```php
<?php

// include the autoloader class near the top of your page
include 'classes/Autoloader.php';
```

Classes can then be loading dynamically without any additional include
statements as long as the class file is found in the same directory or in a
directory beneath the autoload.php file itself.

By default, the autoloader assumes that you are using the *`classname.php`* file
naming convention.  In other words, the class *`MyObject`* would be found in a
file named *`MyObject.php`*.  The autoloader is not case-sensitive.  Support for
other naming conventions is available.  If your naming convention uses file
names such as *`MyObject.class`* or *`MyObject.class.php`*, you can change the
default file extension near the bottom of the Autoloader.php file.

```php
Autoloader::setFileExt('.class.php');
```


## Useful links

- [Autoloading Classes (PHP Manual)](http://php.net/manual/en/language.oop5.autoload.php)
- [PSR-Huh?](http://code.tutsplus.com/tutorials/psr-huh--net-29314)
- [Practical Guide To PSR-0 and PSR-4](http://engineeredweb.com/blog/2014/practical-guide-psr0-psr4/)
- [Battle of the Autoloaders: PSR-0 vs. PSR-4](http://www.sitepoint.com/battle-autoloaders-psr-0-vs-psr-4/)
