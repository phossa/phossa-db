# phossa-db
[![Build Status](https://travis-ci.org/phossa/phossa-db.svg?branch=master)](https://travis-ci.org/phossa/phossa-db)
[![HHVM](https://img.shields.io/hhvm/phossa/phossa-db.svg?style=flat)](http://hhvm.h4cc.de/package/phossa/phossa-db)
[![Latest Stable Version](https://img.shields.io/packagist/vpre/phossa/phossa-db.svg?style=flat)](https://packagist.org/packages/phossa/phossa-db)
[![License](https://poser.pugx.org/phossa/phossa-db/license)](http://mit-license.org/)

Introduction
---
phossa-db is PHP db connection management library which handles the interaction
with db.

It requires PHP 5.4 and supports PHP 7.0+, HHVM. It is compliant with
[PSR-1][PSR-1], [PSR-2][PSR-2], [PSR-4][PSR-4].

[PSR-1]: http://www.php-fig.org/psr/psr-1/ "PSR-1: Basic Coding Standard"
[PSR-2]: http://www.php-fig.org/psr/psr-2/ "PSR-2: Coding Style Guide"
[PSR-4]: http://www.php-fig.org/psr/psr-4/ "PSR-4: Autoloader"

Features
---

- Simple interface. Nothing you don't need.

- Multiple db platform/driver support, currently PDO (all PDO drivers) and
  Mysqli.

- Handles multiple connections through driver manager

  - Round-robin load balancing

  	Multiple db connections are used in round-robin fashion (weighted 1-10).
  	Each connection is monitored and timed with connection time.

  - driver tagging, so user can tag different db connection as 'reader' or
    'writer'

- Easy profiling, get each sql and its execution time.

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
        "phossa/phossa-db": "1.*"
      }
  }
  ```

- **Simple usage**

  ```php
  $db = new Phossa\Db\Pdo\Driver($conf);

  // example 1: DDL using execute()
  $res = $db->execute("DELETE FROM test WHERE id < :id", [ 'id' => 10 ]);
  if (false === $res) {
      echo $db->getError() . \PHP_EOL;
  } else {
      echo sprintf("Deleted %d records", $res) . \PHP_EOL;
  }

  // example 2: SELECT query
  $res = $db->query("SELECT * FROM test WHERE id < ?", [ 10 ]);
  if (false === $res) {
      echo $db->getError() . \PHP_EOL;
  } else {
      $rows = $res->fetchAll();
  }

  // example 3: prepare statement
  $stmt = $db->prepare("SELECT * FROM test WHERE id < :id");
  if (false === $stmt) {
      echo $db->getError() . \PHP_EOL;
  } else {
      $res = $stmt->execute(['id' => 10]);
      if (false === $res) {
         echo $db->getError() . \PHP_EOL;
      } else {
         $rows = $res->fetchAll();
      }
  }
  ```

Dependencies
---

- PHP >= 5.4.0

- phossa/phossa-shared ~1.0.10

License
---

[MIT License](http://mit-license.org/)
