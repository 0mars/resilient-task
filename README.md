# Resilient Task

[![Build Status](https://travis-ci.org/eshta/resilient-task.svg?branch=master)](https://travis-ci.org/eshta/resilient-task)
[![CodeCov](https://codecov.io/gh/eshta/resilient-task/branch/master/graph/badge.svg)](https://codecov.io/gh/eshta/resilient-task)
[![Release](https://img.shields.io/github/release/eshta/resilient-task.svg)](https://github.com/eshta/resilient-task/releases)
[![PHPv](https://img.shields.io/packagist/php-v/eshta/resilient-task.svg)](http://www.php.net)
[![Downloads](https://img.shields.io/packagist/dt/eshta/resilient-task.svg)](https://packagist.org/packages/eshta/resilient-task)

Resilient Task Runner, A circuit breaker implementation, highly configurable task runner with number of max retries, back-off factor, maximum sleep time, and starting sleep time.

## Usage

Install the ```eshta/resilient-task``` package:

```bash
$ composer require eshta/resilient-task
```

## Example
```php
use GuzzleHttp\Exception\ConnectException;


$task = function() {
    try {
        $response = $client->request('GET', 'https://github.com/_abc_123_404');

        return $response;
    } catch (ConnectException $e) {
        echo Psr7\str($e->getRequest());
    }
};

$runner = new ResilientTaskRunner(10, 16, 0.5);
$response = $runner->run($task);

if (is_null($response)) {
    throw new MyFavouriteException('Service call failed!');
}
```
- try 10 times at most
- maximum sleep time between retries 16 seconds
- first sleep time is half a second
- back-off factor [2 default]: double sleeping time after each failed attempt

**Note:**: the runner will only stop when there is a non-null result returned by the task, or the max tries have been exhausted

## Contributing
See [CONTRIBUTING](CONTRIBUTING.md) and [Code of Conduct](CONDUCT.md),
if you want to make contribution (pull request)
or just build and test project on your own.

## Resources

* [Changes History](CHANGES.md)
* [Bug Tracker](https://github.com/eshta/resilient-task/issues)
* [Authors](https://github.com/eshta/resilient-task/contributors)
