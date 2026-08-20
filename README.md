<div align="center">
    <h1>Yoast Seo Laravel</h1>
</div>

<p align="center">
    <a href="https://packagist.org/packages/jenilutfifauzi/yoast-seo-laravel"><img src="https://img.shields.io/packagist/v/jenilutfifauzi/yoast-seo-laravel.svg?style=flat-square" alt="Packagist"></a>
    <a href="https://packagist.org/packages/jenilutfifauzi/yoast-seo-laravel"><img src="https://img.shields.io/packagist/php-v/jenilutfifauzi/yoast-seo-laravel.svg?style=flat-square" alt="PHP from Packagist"></a>
    <a href="https://packagist.org/packages/jenilutfifauzi/yoast-seo-laravel"><img src="https://badge.laravel.cloud/badge/jenilutfifauzi/yoast-seo-laravel?style=flat" alt="Laravel versions"></a>
    <a href="https://github.com/jenilutfifauzi/yoast-seo-laravel/actions"><img alt="GitHub Workflow Status (main)" src="https://img.shields.io/github/actions/workflow/status/jenilutfifauzi/yoast-seo-laravel/tests.yml?branch=main&label=Tests&style=flat-square"></a>
    <a href="https://packagist.org/packages/jenilutfifauzi/yoast-seo-laravel"><img src="https://img.shields.io/packagist/dt/jenilutfifauzi/yoast-seo-laravel.svg?style=flat-square" alt="Total Downloads"></a>
</p>



## Installation

You can install the package via Composer:

```bash
composer require jenilutfifauzi/yoast-seo-laravel
```

You may publish all of the package's resources at once:

```bash
php artisan vendor:publish --tag="yoast-seo-laravel"
```

Or, you may publish each resource individually:

### Publishing the Configuration File

```bash
php artisan vendor:publish --tag="yoast-seo-laravel-config"
```

### Publishing and Running the Migrations

```bash
php artisan vendor:publish --tag="yoast-seo-laravel-migrations"
php artisan migrate
```

### Publishing the Views

```bash
php artisan vendor:publish --tag="yoast-seo-laravel-views"
```

### Publishing the Translations

```bash
php artisan vendor:publish --tag="yoast-seo-laravel-lang"
```

### Publishing the Public Assets

```bash
php artisan vendor:publish --tag="yoast-seo-laravel-assets"
```

## Usage

<!-- Add a basic usage example here. -->

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Thank you for considering contributing to Yoast Seo Laravel! Please review our [contributing guide](.github/CONTRIBUTING.md) to get started.

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [jeni](https://github.com/jeni)
- [All Contributors](../../contributors)

## License

Yoast Seo Laravel is open-sourced software licensed under the [MIT license](LICENSE.md).
