# Adminaut


## Description

Adminaut is the open-source platform for rapid development of PHP applications with the automatic construction of administration backend. It's built on the top of PHP, Zend Framework, Doctrine ORM and other frameworks.


## Installation

1. Add this project to your composer.json:

    ```json
    "require": {
        "adminaut/adminaut": "dev-master"
    }
    ```

2. Tell composer to download Adminaut module:

    ```bash
    $ php composer.phar update
    ```

3. Add following modules to your application:
    ```php
    'DoctrineModule',
    'DoctrineORMModule',
    'TwbBundle',
    'BsbFlysystem',
    'Adminaut'
    ```

4. Copy vendor/adminaut/adminaut/config/adminaut.local.php.dist to your apllication config and configure.

5. Set up Doctrine connection - https://github.com/doctrine/DoctrineORMModule

6. Create DB from Doctrine ORM Entity:

    ```bash
    $ vendor/bin/doctrine-module orm:schema-tool:create
    $ vendor/bin/doctrine-module orm:schema-tool:update --force
    ```

## Browser Support (admin)

- IE 9+
- Firefox (latest)
- Chrome (latest)
- Safari (latest)
- Opera (latest)


## Credits

### Adminaut is build with:
- [Zend Framework](https://framework.zend.com/)
- [Doctrine Project](http://www.doctrine-project.org/)
- [AdminLTE Control Panel Template](https://almsaeedstudio.com/)
- [Bootstrap 3](https://getbootstrap.com/)
- [Font Awesome](http://fontawesome.io)


## License

Adminaut is an open source project by Moviatic s.r.o. that is licensed under [MIT](http://opensource.org/licenses/MIT). Moviatic s.r.o. reserves the right to change the license of future releases.

