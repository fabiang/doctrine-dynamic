# fabiang/doctrine-dynamic

Proxy Driver for [Doctrine](http://doctrine-project.org/) which allows you to add
custom relations dynamically by configuration.

This is useful if you use foreign entities, which you can't change, but you like
to add own relations between them and your entities.

[![Latest Stable Version](https://poser.pugx.org/fabiang/doctrine-dynamic/version)](https://packagist.org/packages/fabiang/doctrine-dynamic)
[![License](https://poser.pugx.org/fabiang/doctrine-dynamic/license)](https://packagist.org/packages/fabiang/doctrine-dynamic)
[![Dependency Status](https://gemnasium.com/badges/github.com/fabiang/doctrine-dynamic.svg)](https://gemnasium.com/github.com/fabiang/doctrine-dynamic)
[![Build Status](https://travis-ci.org/fabiang/doctrine-dynamic.svg?branch=master)](https://travis-ci.org/fabiang/doctrine-dynamic)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fabiang/doctrine-dynamic/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fabiang/doctrine-dynamic/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/fabiang/doctrine-dynamic/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/fabiang/doctrine-dynamic/?branch=master)

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

## Framework support

* [Zend Framework 2 & 3](https://github.com/fabiang/doctrine-dynamic-zf)

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

Thie library is tested with [PHPUnit](https://phpunit.de/) and [Behat](http://behat.org/).

Fork the project on Github and send an pull request with your changes.
Make sure you didn't break anything with running the following commands:

```console
composer install
./vendor/bin/phpunit
./vendor/bin/behat
```

## Licence

BSD-2-Clause. See the [LICENSE.md](LICENSE.md).
