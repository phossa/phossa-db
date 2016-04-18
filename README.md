# phossa-db
[![Build Status](https://travis-ci.org/phossa/phossa-db.svg?branch=master)](https://travis-ci.org/phossa/phossa-db)
[![HHVM](https://img.shields.io/hhvm/phossa/phossa-db.svg?style=flat)](http://hhvm.h4cc.de/package/phossa/phossa-db)
[![Latest Stable Version](https://img.shields.io/packagist/vpre/phossa/phossa-db.svg?style=flat)](https://packagist.org/packages/phossa/phossa-db)
[![License](https://poser.pugx.org/phossa/phossa-db/license)](http://mit-license.org/)

Introduction
---
*phossa-db* is a PHP db connection management library which handles the
interaction with db.

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

  	Multiple db connections are used in round-robin fashion and weighting factor
  	(1-10) supported. Each connection is monitored (pinged).

  - driver tagging, so user can tag different db connection as 'reader' or
    'writer'

- Easy profiling, get each executed sql and its execution time.

- Secure. All SQL executed through prepare/execute in low-level drivers.

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

Usage
---

- Driver

  - DDL using execute()

  	```php
  	$db = new Phossa\Db\Pdo\Driver([
        'dsn' => 'mysql:dbname=test;host=127.0.0.1;charset=utf8'
  	]);

    // simple delete
  	$res = $db->execute("DELETE FROM test WHERE id < 10");
  	if (false === $res) {
      	echo $db->getError() . \PHP_EOL;
  	} else {
      	echo sprintf("Deleted %d records", $res) . \PHP_EOL;
  	}

	// with parameters
	$res = $db->execute("INSERT INTO test (name) VALUES (?)", [ 100 ]);
	if ($res) {
		$id = (int) $db->getLastInsertId();
	}
	```

  - SELECT using query()

  	```php
  	// simple select
  	$res = $db->query("SELECT * FROM test WHERE id < 10");
  	if (false === $res) {
      	echo $db->getError() . \PHP_EOL;
  	} else {
      	$rows = $res->fetchAll();
  	}

	// with parameters & fetch first 5 rows
	$res = $db->query("SELECT * FROM test WHERE id > ? LIMIT ?", [10, 20]);
	if ($res && $res->isQuery()) {
		$firstFiveRows = $res->fetchRow(5);
	}

	// fetch first field
	$res = $db->query("SELECT id, name FROM test WHERE id < :id", ['id' => 10]);
	if ($res && $res->isQuery()) {
		$firstCols = $res->fetchCol('id');
	}
	```

- Statment

  `Statement` is returned by `$db->prepare()`.

  ```php
  // PREPARE using prepare()
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

- Result

  `Result` is returned by `$db->execute()`, `$db->query()` or `$stmt->execute()`

  ```php
  $res = $db->query(...);
  if ($res) {
      // SELECT
      if ($res->isQuery()) {
          // get fields count
          $fieldCount = $res->fieldCount();
          // row count
          $rowCount   = $res->rowCount();

      // DDL
      } else {
          $affectedRows = $res->affectedRows();
      }
  }
  ```

Driver manager
---
Driver manager manages multiple db connections. Weighting factor N means add
one driver virtually N times. Adding driver *A* with factor 5 and adding driver
*B* with factor 1 into the pool, means when calling `getDriver()`, user will
get *A* five times vs *B* for one time.

```
// writable connect 1
$db1 = (new Phossa\Db\Pdo\Driver($conf1))->addTag('RW');

// dbreader 2
$db2 = (new Phossa\Db\Pdo\Driver($conf2))->addTag('RO');

// dbreader 3
$db3 = (new Phossa\Db\Pdo\Driver($conf3))->addTag('RO');

// db manager
$dbm = (new Phossa\Db\Manager\Manager())
    ->addDriver($db1, 1)    // writable connection with factor 1
    ->addDriver($db2, 5)	// read_only, factor 5
    ->addDriver($db3, 5)	// read_only, factor 5

// get a db connect, no matter writable or read only
$db = $dbm->getDriver();

// get a readonly driver
$db = $dbm->getDriver('RO');
```

SQL profiling
---
Get the executed SQL and its execution time.

```php
// init driver
$db = new Phossa\Db\Pdo\Driver($conf);

// enable profiling
$db->enableProfiling();

// execute a DELETE
$db->execute("DELETE FROM test WHERE test_id > 10");

// get sql
$sql  = $db->getProfiler()->getSql();
$time = $db->getProfiler()->getExecutionTime();
```

Dependencies
---

- PHP >= 5.4.0

- phossa/phossa-shared ~1.0.10

License
---

[MIT License](http://mit-license.org/)
