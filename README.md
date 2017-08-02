# GelfSupport

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A composer compliant package to support GELF logging within laravel/symfony projects

## Structure

```
config/     Contains configuration files
src/        Contains the package source code
tests/      Contains PHPUnit tests
vendor/     Contains dependencies
```

## Install

Via Composer

``` bash
$ composer require renepardon/gelf-support:"dev-master"
```

### laravel

Add the following line to the **config/app.php** file within **providers** section:

``` php
\RenePardon\GelfSupport\GelfSupportServiceProvider::class,
```

Adjust the **.env** file to contain required constants which points to your Graylog2 server

```
GRAYLOG_ENABLED=true
GRAYLOG_HOST=localhost
GRAYLOG_PORT=12201
```

You can of course adjust the configuration file directly so publish it to your appication with the following command:

``` bash
php artisan vendor:publish --provider="RenePardon\GelfSupport\GelfSupportServiceProvider" --tag="config"
```

### symfony

TO BE DONE - WE HAVE TO REGISTER THE BUNDLE WITHIN AppKernel.php

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

TO BE DONE - FEEL FREE TO WRITE SOME TESTS

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email rene.pardon@boonweb.de instead of using the issue tracker.

## Credits

- [Christoph, René Pardon][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/renepardon/gelf-support.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/renepardon/gelf-support/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/renepardon/gelf-support.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/renepardon/gelf-support.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/renepardon/gelf-support.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/renepardon/gelf-support
[link-travis]: https://travis-ci.org/renepardon/gelf-support
[link-scrutinizer]: https://scrutinizer-ci.com/g/renepardon/gelf-support/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/renepardon/gelf-support
[link-downloads]: https://packagist.org/packages/renepardon/gelf-support
[link-author]: https://github.com/renepardon
[link-contributors]: ../../contributors
