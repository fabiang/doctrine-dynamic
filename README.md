# fabiang/doctrine-dynamic

Proxy Driver for [Doctrine](http://doctrine-project.org/) which allows you to add
custom relations dynamically by configuration.

This is useful if you use foreign entities, which you can't change, but you like
to add own relations between them and your entities.

[![Latest Stable Version](https://poser.pugx.org/fabiang/doctrine-dynamic/version)](https://packagist.org/packages/fabiang/doctrine-dynamic)
[![License](https://poser.pugx.org/fabiang/doctrine-dynamic/license)](https://packagist.org/packages/fabiang/doctrine-dynamic)  
[![Unit Tests](https://github.com/fabiang/doctrine-dynamic/actions/workflows/unit.yml/badge.svg)](https://github.com/fabiang/doctrine-dynamic/actions/workflows/unit.yml)
[![Integration Tests](https://github.com/fabiang/doctrine-dynamic/actions/workflows/behat.yml/badge.svg)](https://github.com/fabiang/doctrine-dynamic/actions/workflows/behat.yml)
[![Static Code Analysis](https://github.com/fabiang/doctrine-dynamic/actions/workflows/static.yml/badge.svg)](https://github.com/fabiang/doctrine-dynamic/actions/workflows/static.yml)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fabiang/doctrine-dynamic/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/fabiang/doctrine-dynamic/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/fabiang/doctrine-dynamic/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/fabiang/doctrine-dynamic/?branch=main)

## Features

* Setting all possible relations to entities:
  * OneToOne
  * ManyToOne
  * OneToMany
  * ManyToMany
* Setting repository class

## Installation

New to Composer? Read the [introduction](https://getcomposer.org/doc/00-intro.md#introduction). Run the following Composer command:

```console
$ composer require fabiang/doctrine-dynamic
```

## Framework integration

* [Laminas](https://github.com/fabiang/doctrine-dynamic-laminas)

## Usage

```php
<?php

use Fabiang\DoctrineDynamic\ConfigurationFactory;
use Fabiang\DoctrineDynamic\ProxyDriverFactory;
use Doctrine\ORM\EntityManager;

$configurationFactory = new ConfigurationFactory();
$configuration = $configurationFactory->factory([
    \Mymodule\Entity\Customer::class => [
        'options' => [
            'repository' => \Mymodule\Repository\CustomerRepository::class,
        ],
        'fields' => [
            'fieldname' => [
                'products' => [
                    'oneToMany' => [
                        [
                            'targetEntity' => \Mymodule\Entity\Customer::class,
                            'mappedBy'     => 'customer',
                        ],
                    ]
                ],
            ]
        ]
    ],
    \Mymodule\Entity\Products::class => [
        'fields' => [
            'customer' => [
                'manyToOne' => [
                    [
                        'targetEntity' => \Mymodule\Entity\Products::class,
                        'inversedBy'   => 'products',
                        'joinColumns'  => [
                            'name'                 => 'customer_id',
                            'referencedColumnName' => 'id'
                        ]
                    ],
                ]
            ],
        ]
    ],
]);

/** @var $entityManager EntityManager */
// get it from a container for example
$entityManager = $container->get(EntityManager::class);

$proxyDriverFactory = new ProxyDriverFactory();
$proxyDriverFactory->factory($entityManager, $configuration);
```

## Development

This library is tested with [PHPUnit](https://phpunit.de/) and [Behat](http://behat.org/).

Fork the project on Github and send an pull request with your changes.
Make sure you didn't break anything with running the following commands:

```console
composer install
./vendor/bin/phpunit
./vendor/bin/behat
```

## Licence

BSD-2-Clause. See the [LICENSE.md](LICENSE.md).
