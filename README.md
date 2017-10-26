# Adminaut

[![Crowdin](https://d322cqt584bo4o.cloudfront.net/adminaut/localized.svg)](https://crowdin.com/project/adminaut)
[![Packagist](https://img.shields.io/packagist/v/adminaut/adminaut.svg)](https://packagist.org/packages/adminaut/adminaut)
[![License](http://img.shields.io/:license-mit-blue.svg)](http://doge.mit-license.org)

## Description

Adminaut is the open-source platform for rapid development of PHP applications with the automatic construction of administration backend.

It's built on the top of [PHP](https://secure.php.net/), [Zend Framework](https://framework.zend.com/), [Doctrine ORM](http://www.doctrine-project.org/projects/orm.html) and other frameworks.

## Installation

### 1. Install with Composer

Install the latest stable version with Composer:

```bash
composer require adminaut/adminaut
```

Or install the latest develop version with Composer:

```bash
composer require adminaut/adminaut:dev-develop
```

Or install manually by adding value `"adminaut/adminaut": "dev-master"` into `composer.json` file to `"require"` object and running command:
 
```bash
composer install
```

### 2. Enable module in your application

Composer should automatically enable `Adminaut` module and other required modules during installation. 

In case it does not, you can enable module manually by adding values to array in file `config/modules.config.php`. At the end, it should look like PHP array below.

```php
<?php
// config/modules.config.php

return [
    'Zend\Mail',
    'Zend\Router',
    'Zend\Validator',
    'DoctrineModule',    // Add this line, before Adminaut module.
    'DoctrineORMModule', // Add this line, before Adminaut module.
    'TwbBundle',         // Add this line, before Adminaut module.
    'Adminaut',          // Add this line, before Application module.
    'Application',
];
```

### 3. Set up your configuration

Look into file `vendor/adminaut/adminaut/config/adminaut.local.php.dist` and copy it's content to your application config.

### 4. Set up Doctrine connection

[https://github.com/doctrine/DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule).

### 5. Create/update DB

You need to create Adminaut entities.

If you don't have DB yet, run command:

```bash
vendor/bin/doctrine-module orm:schema-tool:create
```

If you already have some DB and some data in it, check what will be updated with command:

```bash
vendor/bin/doctrine-module orm:schema-tool:update --dump-sql
```

and if everything is OK, then run command:

```bash
vendor/bin/doctrine-module orm:schema-tool:update --force
```

## Links

- [Adminaut translation project at Crowdin](https://crowdin.com/project/adminaut)

## Browser Support (admin)

- IE 9+
- Firefox (latest)
- Chrome (latest)
- Safari (latest)
- Opera (latest)

## Credits

### Adminaut is build with:
- [PHP](https://secure.php.net/)
- [Zend Framework](https://framework.zend.com)
- [Doctrine Project](http://www.doctrine-project.org)
- [AdminLTE Control Panel Template](https://almsaeedstudio.com)
- [Bootstrap 3](https://getbootstrap.com)
- [Font Awesome](http://fontawesome.io)
- [Crowdin](https://crowdin.com)

## License

Adminaut is an open source project by Moviatic s.r.o. that is licensed under [MIT](http://opensource.org/licenses/MIT). 
Moviatic s.r.o. reserves the right to change the license of future releases.
