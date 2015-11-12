<?php

/**
 * Copyright 2015 Fabian Grutschus. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * The views and conclusions contained in the software and documentation are those
 * of the authors and should not be interpreted as representing official policies,
 * either expressed or implied, of the copyright holders.
 *
 * @author    Fabian Grutschus <f.grutschus@lubyte.de>
 * @copyright 2015 Fabian Grutschus. All rights reserved.
 * @license   BSD-2-Clause
 */

namespace Fabiang\DoctrineDynamic\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;
use Doctrine\ORM\Tools\Setup as DoctrineSetup;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Fabiang\DoctrineDynamic\ProxyDriverFactory;
use Fabiang\DoctrineDynamic\Behat\NamespaceOne\Entity\TestEntity as TestEntityOne;
use Fabiang\DoctrineDynamic\Behat\NamespaceTwo\Entity\TestEntity as TestEntityTwo;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use PHPUnit_Framework_Assert as Assert;

/**
 * Defines application features from the specific context.
 */
final class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * @var array
     */
    private $paths = [];

    /**
     * @var DoctrineEntityManager
     */
    private $entityManager;

    /**
     * @var ProxyDriverFactory
     */
    private $proxyDriverFactory;

    /**
     * @var array
     */
    private $configuration = [];

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(array $paths)
    {
        $this->paths              = $paths;
        $this->proxyDriverFactory = new ProxyDriverFactory();
    }

    /**
     * @Given One to one mapping configuration
     */
    public function oneToOneMappingConfiguration()
    {
        $this->configuration[TestEntityOne::class] = [
            'oneToOne' => [
                'oneToOne' => [
                    [
                        'targetEntity' => TestEntityTwo::class,
                        'inversedBy'   => 'oneToOne',
                        'joinColumns'  => [
                            'name'                 => 'oneToOne',
                            'referencedColumnName' => 'id'
                        ]
                    ]
                ]
            ]
        ];

        $this->configuration[TestEntityTwo::class] = [
            'oneToOne' => [
                'oneToOne' => [
                    [
                        'targetEntity' => TestEntityOne::class,
                        'mappedBy'     => 'oneToOne',
                    ]
                ]
            ]
        ];
    }

    /**
     * @Given many to one mapping configuration
     */
    public function manyToOneMappingConfiguration()
    {
        $this->configuration[TestEntityTwo::class] = [
            'manyToOne' => [
                'manyToOne' => [
                    [
                        'targetEntity' => TestEntityOne::class,
                        'inversedBy'   => 'oneToMany',
                        'joinColumns'  => [
                            'name'                 => 'manyToOne',
                            'referencedColumnName' => 'id'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @Given one to many mapping configuration
     */
    public function oneToManyMappingConfiguration()
    {
        $this->configuration[TestEntityOne::class] = [
            'oneToMany' => [
                'oneToMany' => [
                    [
                        'targetEntity' => TestEntityTwo::class,
                        'mappedBy'     => 'manyToOne',
                    ]
                ]
            ]
        ];
    }

    /**
     * @Given many to many mapping configuration
     */
    public function manyToManyMappingConfiguration()
    {
        $this->configuration[TestEntityOne::class] = [
            'manyToMany' => [
                'manyToMany' => [
                    [
                        'targetEntity' => TestEntityTwo::class,
                        'inversedBy'   => 'manyToMany',
                        'joinTable'    => [
                            'name' => 'ManyToManyTableName',
                        ]
                    ]
                ]
            ]
        ];

        $this->configuration[TestEntityTwo::class] = [
            'manyToMany' => [
                'manyToMany' => [
                    [
                        'targetEntity' => TestEntityOne::class,
                        'mappedBy'     => 'manyToMany',
                    ]
                ]
            ]
        ];
    }

    /**
     * @Then Entity :entity should have one-to-one mapping as owning side
     */
    public function entityShouldHaveOneToOneMappingAsOwningSide($entity)
    {
        $mappings = $this->getMappingData($entity);
        Assert::assertArrayHasKey('oneToOne', $mappings);
        $mappingField = $mappings['oneToOne'];
        Assert::assertArraySubset(
            [
                'fieldName'    => 'oneToOne',
                'targetEntity' => TestEntityTwo::class,
                'inversedBy'   => 'oneToOne',
                'mappedBy'     => null,
                'isOwningSide' => true,
                'joinColumns'  => [
                    [
                        'name'                 => 'oneToOne',
                        'referencedColumnName' => 'id',
                    ]
                ]
            ],
            $mappingField
        );
    }

    /**
     * @Then Entity :entity should have one-to-one mapping as inverse side
     */
    public function entityShouldHaveOneToOneMappingAsInverseSide($entity)
    {
        $mappings = $this->getMappingData($entity);
        Assert::assertArrayHasKey('oneToOne', $mappings);
        $mappingField = $mappings['oneToOne'];
        Assert::assertArraySubset(
            [
                'fieldName'    => 'oneToOne',
                'targetEntity' => TestEntityOne::class,
                'inversedBy'   => null,
                'mappedBy'     => 'oneToOne',
                'isOwningSide' => false,
            ],
            $mappingField
        );
    }

    /**
     * @Then entity :entity should have many-to-one mapping as owning side
     */
    public function entityShouldHaveManyToOneMappingAsOwningSide($entity)
    {
        $mappings = $this->getMappingData($entity);
        Assert::assertArrayHasKey('manyToOne', $mappings);
        $mappingField = $mappings['manyToOne'];
        Assert::assertArraySubset(
            [
                'fieldName'    => 'manyToOne',
                'targetEntity' => TestEntityOne::class,
                'inversedBy'   => 'oneToMany',
                'mappedBy'     => null,
                'isOwningSide' => true,
                'joinColumns'  => [
                    [
                        'name'                 => 'manyToOne',
                        'referencedColumnName' => 'id',
                    ]
                ]
            ],
            $mappingField
        );
    }

    /**
     * @Then entity :entity should have one-to-many mapping as inverse side
     */
    public function entityShouldHaveOneToManyMappingAsInverseSide($entity)
    {
        $mappings = $this->getMappingData($entity);
        Assert::assertArrayHasKey('oneToMany', $mappings);
        $mappingField = $mappings['oneToMany'];
        Assert::assertArraySubset(
            [
                'fieldName'    => 'oneToMany',
                'targetEntity' => TestEntityTwo::class,
                'inversedBy'   => null,
                'mappedBy'     => 'manyToOne',
                'isOwningSide' => false,
            ],
            $mappingField
        );
    }

    /**
     * @Then entity :entity should have many-to-many mapping as owning side
     */
    public function entityShouldHaveManyToManyMappingAsOwningSide($entity)
    {
        $mappings = $this->getMappingData($entity);
        Assert::assertArrayHasKey('manyToMany', $mappings);
        $mappingField = $mappings['manyToMany'];
        Assert::assertArraySubset(
            [
                'fieldName'    => 'manyToMany',
                'targetEntity' => TestEntityTwo::class,
                'inversedBy'   => 'manyToMany',
                'mappedBy'     => null,
                'isOwningSide' => true,
                'joinTable'  => [
                    'name' => 'ManyToManyTableName',
                ]
            ],
            $mappingField
        );
    }

    /**
     * @Then entity :entity should have many-to-many mapping as inverse side
     */
    public function entityShouldHaveManyToManyMappingAsInverseSide($entity)
    {
        $mappings = $this->getMappingData($entity);
        Assert::assertArrayHasKey('manyToMany', $mappings);
        $mappingField = $mappings['manyToMany'];
        Assert::assertArraySubset(
            [
                'fieldName'    => 'manyToMany',
                'targetEntity' => TestEntityOne::class,
                'inversedBy'   => null,
                'mappedBy'     => 'manyToMany',
                'isOwningSide' => false,
            ],
            $mappingField
        );
    }


    private function getMappingData($entity)
    {
        $entityManager = $this->getEntityManager();
        $metadata = $entityManager->getClassMetadata($entity);
        $mappings = $metadata->associationMappings;
        return $mappings;
    }

    private function getEntityManager()
    {
        if (null !== $this->entityManager) {
            return $this->entityManager;
        }

        $config = DoctrineSetup::createConfiguration(true);
        $mappingDriver = new MappingDriverChain();

        foreach ($this->paths as $namespace => $path) {
            $mappingDriver->addDriver($config->newDefaultAnnotationDriver([$path]), $namespace);
        }

        $config->setMetadataDriverImpl($mappingDriver);
        $this->entityManager = DoctrineEntityManager::create(
            [
                'driver' => 'pdo_sqlite',
                'path'   => ':memory:'
            ],
            $config
        );
        $this->proxyDriverFactory->factory($this->entityManager, $this->configuration);
        return $this->entityManager;
    }
}
