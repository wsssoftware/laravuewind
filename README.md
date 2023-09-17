# Laravuewind

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wsssoftware/laravuewind.svg?style=flat-square)](https://packagist.org/packages/wsssoftware/laravuewind)
[![npm](https://img.shields.io/npm/v/laravuewind)](https://www.npmjs.com/package/laravuewind)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/wsssoftware/laravuewind/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/wsssoftware/laravuewind/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/wsssoftware/laravuewind/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/wsssoftware/laravuewind/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/wsssoftware/laravuewind.svg?style=flat-square)](https://packagist.org/packages/wsssoftware/laravuewind)

Laravuewind is a package that provides a set of tools to integrate and
improve the development of applications using
[Laravel](https://laravel.com/) with
[Vue](https://vuejs.org/) and
[Tailwind](https://tailwindcss.com/).

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
import laravuewind from './laravuewind/tailwind.ts';

export default {
    content: [
        //...
        './node_modules/laravuewind/resources/js/**/*.{vue,js,ts}',
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


Insert VueJS plugin

```js
import Vue from 'vue';
import Laravuewind from 'laravuewind';

createInertiaApp({
    //...
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(Laravuewind) // <- this one
            .use(ZiggyVue, Ziggy)
            .mount(el);
    },
   //...
});
```


### Form components

#### InputGroup

InputGroup is a component that allows you to group an input field with a label and/or feedback fields.
To know more about it, please check it's [implementation](https://github.com/wsssoftware/laravuewind/blob/main/resources/js/Components/Form/InputGroup.vue)
and [Maskito](https://maskito.dev/getting-started/what-is-maskito) if you want to use masks.

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
