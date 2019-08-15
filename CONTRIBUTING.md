# Contributing

We accept contributions via Pull Requests on [Github](https://github.com/eshta/resilient-task).


## Pull Requests

- **Code must follow [PSR-1](http://www.php-fig.org/psr/psr-1/), [PSR-2](http://www.php-fig.org/psr/psr-2/)
  Coding Standards** - The easiest way to apply the conventions is to install
  [PHP Code Sniffer](http://pear.php.net/package/PHP_CodeSniffer) and run it.

- **Add tests!** - Your patch won't be accepted if it doesn't have tests.
  Run [PHPUnit](https://phpunit.de/) to make sure, that all tests pass.

- **Document any change in behaviour** - Make sure the [README.md](README.md)
  and any other relevant documentation are kept up-to-date.

- **Consider our release cycle** - We try to follow [SemVer v2.0.0](http://semver.org/).
  Randomly breaking public APIs is not an option. Update [CHANGES.md](CHANGES.md) accordingly.

- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

## Make shortcuts

If you have [GNU Make](https://www.gnu.org/software/make/) installed, you can use following shortcuts:

- ```make cs``` (instead of ```php vendor/bin/phpcs```) -
    run static code analysis with [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
    to check code style
- ```make csfix``` (instead of ```php vendor/bin/phpcbf```) -
    fix code style violations with [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
    automatically, where possible (ex. PSR-2 code formatting violations)
- ```make test``` (instead of ```php vendor/bin/phpunit```) -
    run tests with [PHPUnit](https://phpunit.de/)
- ```make install``` - instead of ```composer install```
- ```make all``` or just ```make``` without parameters -
    invokes described above **install**, **cs**, **test** tasks sequentially -
    project will be assembled, checked with linter and tested with one single command


## Running Tests

``` bash
$ make test
```
or
``` bash
$ composer test
```


**Happy coding**!
