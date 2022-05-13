<?php

/**
 * Copyright 2015-2022 Fabian Grutschus. All rights reserved.
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
 */

declare(strict_types=1);

namespace Fabiang\DoctrineDynamic;

use Fabiang\DoctrineDynamic\Configuration\Mapping\JoinColumn;
use Fabiang\DoctrineDynamic\Configuration\Mapping\OneToOne;
use Fabiang\DoctrineDynamic\Exception\RuntimeException;
use Fabiang\DoctrineDynamic\Exception\UnexpectedValueException;
use Laminas\Stdlib\ArrayUtils;

use function sprintf;
use function ucfirst;

class ConfigurationFactory
{
    private array $mappings = [
        'oneToOne'   => OneToOne::class,
        'manyToOne'  => Configuration\Mapping\ManyToOne::class,
        'oneToMany'  => Configuration\Mapping\OneToMany::class,
        'manyToMany' => Configuration\Mapping\ManyToMany::class,
    ];

    public function factory(iterable $configuration): Configuration
    {
        $configurationArray = ArrayUtils::iteratorToArray($configuration, true);

        $configurationObject = new Configuration();
        foreach ($configurationArray as $entityName => $entityConfig) {
            $entity = new Configuration\Entity($entityName);

            if (isset($entityConfig['options'])) {
                $this->configureOptions($entity, $entityConfig['options']);
            }

            if (isset($entityConfig['fields'])) {
                $this->configureFields($entity, $entityConfig['fields']);
            }
            $configurationObject->add($entity);
        }
        return $configurationObject;
    }

    private function configureFields(Configuration\Entity $entity, array $entityConfig): void
    {
        foreach ($entityConfig as $fieldName => $fieldConfig) {
            $field = new Configuration\Field($fieldName);
            $this->configureMappings(
                $field,
                $fieldConfig,
                $entity->getName()
            );
            $entity->addField($field);
        }
    }

    private function configureOptions(Configuration\Entity $entity, array $options): void
    {
        foreach ($options as $option => $value) {
            $setter = 'set' . ucfirst($option);
            $entity->{$setter}($value);
        }
    }

    /**
     * @throws UnexpectedValueException
     */
    private function configureMappings(Configuration\Field $field, array $fieldConfig, string $entityName): void
    {
        foreach ($this->mappings as $mappingType => $mappingClassName) {
            if (isset($fieldConfig[$mappingType])) {
                if (! ArrayUtils::isList($fieldConfig[$mappingType])) {
                    throw new UnexpectedValueException(sprintf(
                        'Mapping definition for field "%s" at entity "%s" of mapping type "%s" must be an list',
                        $field->getName(),
                        $entityName,
                        $mappingType
                    ));
                }

                $mappingConfigList = $fieldConfig[$mappingType];

                $mappingMethod = 'configure' . ucfirst($mappingType);
                $fieldMethod   = 'add' . ucfirst($mappingType);
                foreach ($mappingConfigList as $mappingConfig) {
                    $field->$fieldMethod($this->$mappingMethod($mappingConfig));
                }
            }
        }
    }

    private function configureOneToOne(array $mappingConfig): OneToOne
    {
        $oneToOne = new OneToOne();
        $oneToOne->setTargetEntity($this->getOption($mappingConfig, 'targetEntity', true));
        $oneToOne->setInversedBy($this->getOption($mappingConfig, 'inversedBy'));
        $oneToOne->setMappedBy($this->getOption($mappingConfig, 'mappedBy'));

        if (isset($mappingConfig['joinColumns'])) {
            $oneToOne->setJoinColumn($this->configureJoinColumn($mappingConfig['joinColumns']));
        }

        return $oneToOne;
    }

    private function configureManyToOne(array $mappingConfig): Configuration\Mapping\ManyToOne
    {
        $manyToOne = new Configuration\Mapping\ManyToOne();
        $manyToOne->setTargetEntity($this->getOption($mappingConfig, 'targetEntity', true));
        $manyToOne->setInversedBy($this->getOption($mappingConfig, 'inversedBy'));

        if (isset($mappingConfig['joinColumns'])) {
            $manyToOne->setJoinColumn($this->configureJoinColumn($mappingConfig['joinColumns']));
        }

        return $manyToOne;
    }

    private function configureOneToMany(array $mappingConfig): Configuration\Mapping\OneToMany
    {
        $oneToMany = new Configuration\Mapping\OneToMany();
        $oneToMany->setTargetEntity($this->getOption($mappingConfig, 'targetEntity', true));
        $oneToMany->setMappedBy($this->getOption($mappingConfig, 'mappedBy'));
        return $oneToMany;
    }

    private function configureManyToMany(array $mappingConfig): Configuration\Mapping\ManyToMany
    {
        $manyToMany = new Configuration\Mapping\ManyToMany();
        $manyToMany->setTargetEntity($this->getOption($mappingConfig, 'targetEntity', true));
        $manyToMany->setInversedBy($this->getOption($mappingConfig, 'inversedBy'));
        $manyToMany->setMappedBy($this->getOption($mappingConfig, 'mappedBy'));

        if (isset($mappingConfig['joinTable'])) {
            $manyToMany->setJoinTable($this->configureJoinTable($mappingConfig['joinTable']));
        }

        return $manyToMany;
    }

    private function configureJoinColumn(array $joinColumnConfig): JoinColumn
    {
        $name                 = $this->getOption($joinColumnConfig, 'name', true);
        $referencedColumnName = $this->getOption($joinColumnConfig, 'referencedColumnName', true);

        return new JoinColumn($name, $referencedColumnName);
    }

    private function configureJoinTable(array $joinTableConfig): Configuration\Mapping\JoinTable
    {
        $name = $this->getOption($joinTableConfig, 'name', true);
        return new Configuration\Mapping\JoinTable($name);
    }

    /**
     * @return mixed
     * @throws RuntimeException
     */
    private function getOption(array $config, string $option, bool $required = false)
    {
        if (! isset($config[$option])) {
            if ($required) {
                throw new RuntimeException(sprintf(
                    'Configuration for field "%s" is required',
                    $option
                ));
            }

            return null;
        }

        return $config[$option];
    }
}
