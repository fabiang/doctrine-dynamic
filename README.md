# fabiang/doctrine-dynamic

Proxy Driver for [Doctrine](http://doctrine-project.org/) which allows you to add
custom relations dynamically by configuration.

This is useful if you use foreign entities, which you can't change, but you like
to add own relations between them and your entities.

[![Build Status](https://travis-ci.org/fabiang/doctrine-dynamic.svg?branch=master)](https://travis-ci.org/fabiang/doctrine-dynamic)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fabiang/doctrine-dynamic/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fabiang/doctrine-dynamic/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/fabiang/doctrine-dynamic/version)](https://packagist.org/packages/fabiang/doctrine-dynamic)
[![License](https://poser.pugx.org/fabiang/doctrine-dynamic/license)](https://packagist.org/packages/fabiang/doctrine-dynamic)

## Features

* Setting all possible relations to entities:
** OneToOne
** ManyToOne
** OneToMany
** ManyToMany
* Setting repository class

## Installation

Run the following `composer` command:

```console
$ composer require fabiang/doctrine-dynamic
```

## Available Bindings

* [Zend Framework 2/3](https://github.com/fabiang/doctrine-dynamic-zf)

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

## LICENSE

BSD-2-Clause. See the [LICENSE](LICENSE.md).
