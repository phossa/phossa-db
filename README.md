# phossa-db
[![Build Status](https://travis-ci.org/phossa/phossa-db.svg?branch=master)](https://travis-ci.org/phossa/phossa-db)
[![HHVM](https://img.shields.io/hhvm/phossa/phossa-db.svg?style=flat)](http://hhvm.h4cc.de/package/phossa/phossa-db)
[![Latest Stable Version](https://img.shields.io/packagist/vpre/phossa/phossa-db.svg?style=flat)](https://packagist.org/packages/phossa/phossa-db)
[![License](https://poser.pugx.org/phossa/phossa-db/license)](http://mit-license.org/)

Introduction
---
phossa-db is PHP db connection management, statistics package which handles
the interaction with db.

It requires PHP 5.4 and supports PHP 7.0+, HHVM. It is compliant with
[PSR-1][PSR-1], [PSR-2][PSR-2], [PSR-4][PSR-4].

[PSR-1]: http://www.php-fig.org/psr/psr-1/ "PSR-1: Basic Coding Standard"
[PSR-2]: http://www.php-fig.org/psr/psr-2/ "PSR-2: Coding Style Guide"
[PSR-4]: http://www.php-fig.org/psr/psr-4/ "PSR-4: Autoloader"

Features
---

- Multiple db platform/driver support

- Handles multiple connections at the same time

  - Connection pool

  - Connection monitoring and fallback

- Automaitc db reader/writer support

- debug mode

  - Query log

  - Query profiling

Getting started
---

- **Installation**

  Install via the [`composer`](https://getcomposer.org/) utility.

  ```
  composer require "phossa/phossa-db=1.*"
  ```

  or add the following lines to your `composer.json`

  ```json
  {
      "require": {
        "phossa/phossa-db": "^1.0.0"
      }
  }
  ```

- **Simple usage**

  ```php

  ```

Dependencies
---

- PHP >= 5.4.0

- phossa/phossa-shared 1.*

- phossa/phossa-logger 1.* if logging and statistics enabled

License
---

[MIT License](http://mit-license.org/)
