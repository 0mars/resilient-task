# Resilient Task

[![Build Status](https://travis-ci.org/eshta/resilient-task.svg?branch=master)](https://travis-ci.org/eshta/resilient-task)
[![CodeCov](https://codecov.io/gh/eshta/resilient-task/branch/master/graph/badge.svg)](https://codecov.io/gh/eshta/resilient-task)
[![Release](https://img.shields.io/github/release/eshta/resilient-task.svg)](https://github.com/eshta/resilient-task/releases)
[![PHPv](https://img.shields.io/packagist/php-v/eshta/resilient-task.svg)](http://www.php.net)
[![Downloads](https://img.shields.io/packagist/dt/eshta/resilient-task.svg)](https://packagist.org/packages/eshta/resilient-task)

TODO: Project description

## Usage

Install the ```eshta/resilient-task``` package:

```bash
$ composer require eshta/resilient-task
```

## Example
```php
$procedure = function() use (&$executionTimes) {
            $executionTimes++;
            return $executionTimes;
};

$runner = new ResilientTaskRunner(50, 60, 0.5);
```

## Contributing
See [CONTRIBUTING](CONTRIBUTING.md) and [Code of Conduct](CONDUCT.md),
if you want to make contribution (pull request)
or just build and test project on your own.

## Resources

* [Changes History](CHANGES.md)
* [Bug Tracker](https://github.com/eshta/resilient-task/issues)
* [Authors](https://github.com/eshta/resilient-task/contributors)
