# This is my package laravuewind

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wsssoftware/laravuewind.svg?style=flat-square)](https://packagist.org/packages/wsssoftware/laravuewind)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/wsssoftware/laravuewind/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/wsssoftware/laravuewind/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/wsssoftware/laravuewind/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/wsssoftware/laravuewind/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/wsssoftware/laravuewind.svg?style=flat-square)](https://packagist.org/packages/wsssoftware/laravuewind)

## Backend Installation

You can install the package via composer:

```bash
composer require wsssoftware/laravuewind
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravuewind-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravuewind-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravuewind-views"
```

## Frontend Installation

On package.json file, add the following line to scripts config:

```json
  "devDependencies": {
    "laravuewind": "latest",
  },
```

On `tailwind.config.js` file, add the following line to content config:

```js
import laravuewind from 'laravuewind/resources/js'

export default {
    content: [
        //...
        './node_modules/laravuewind/resources/js/**/*.vue',
    ],
    plugins: [
        //...
        laravuewind,
    ],
};
```
> **Note:**
> If you use plugins that add `primary` and `secondary` colors as a non default pattern (100, 200, 300) like daisyui,
> you must call this plugin first.  

## Usage

```php
$laravuewind = new Laravuewind();
echo $laravuewind->echoPhrase('Hello, Wsssoftware!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Allan Mariucci Carvalho](https://github.com/wsssoftware)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
