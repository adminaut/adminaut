# Adminaut

Installation
------------

### Main Setup

#### With composer (the faster way)

1. Add this project in your composer.json:

    ```json
    "require": {
        "adminaut/adminaut": "dev-master"
    }
    ```

2. Now tell composer to download __MFCC Admin Module__ by running the command:

    ```bash
    $ php composer.phar update
    ```

3. Add following modules to your application
    ```php
    'DoctrineModule',
    'DoctrineORMModule',
    'TwbBundle',
    'BsbFlysystem',
    'Adminaut'
    ```

4. copy vendor/adminaut/adminaut/config/adminaut.local.php.dist to your apllication config and set up

5. set up doctrine connection viz. https://github.com/doctrine/DoctrineORMModule

6. To create db from Doctrine ORM Entity

    ```bash
    $ vendor/bin/doctrine-module orm:schema-tool:create
    ```
  
    ```bash
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

