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

namespace Fabiang\DoctrineDynamic;

use Zend\Stdlib\ArrayUtils;
use Fabiang\DoctrineDynamic\Exception\UnexpectedValueException;
use Fabiang\DoctrineDynamic\Exception\RuntimeException;

class ConfigurationFactory
{
    /**
     * @var array
     */
    private $mappings = [
        'oneToOne' => Configuration\Mapping\OneToOne::class,
    ];

    /**
     * @param array|\Traversable $configuration
     * @return \Fabiang\DoctrineDynamic\Configuration
     */
    public function factory($configuration)
    {
        $configurationArray = ArrayUtils::iteratorToArray($configuration, true);

        $configurationObject = new Configuration;
        foreach ($configurationArray as $entityName => $entityConfig) {
            $entity = new Configuration\Entity($entityName);
            $this->configureFields($entity, $entityConfig);
            $configurationObject->add($entity);
        }
        return $configurationObject;
    }

    /**
     * @param \Fabiang\DoctrineDynamic\Configuration\Entity $entity
     * @param array $entityConfig
     */
    private function configureFields(Configuration\Entity $entity, array $entityConfig)
    {
        foreach ($entityConfig as $fieldName => $fieldConfig) {
            $field = new Configuration\Field($fieldName);
            $this->configureMappings($field, $fieldConfig, $entity->getName());
            $entity->addField($field);
        }
    }

    /**
     * @param \Fabiang\DoctrineDynamic\Configuration\Field $field
     * @param array $fieldConfig
     * @param string $entityName
     * @throws UnexpectedValueException
     */
    private function configureMappings(Configuration\Field $field, array $fieldConfig, $entityName)
    {
        foreach ($this->mappings as $mappingType => $mappingClassName) {
            if (isset($fieldConfig[$mappingType])) {
                if (!ArrayUtils::isList($fieldConfig[$mappingType])) {
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

    /**
     * @param array $mappingConfig
     * @return \Fabiang\DoctrineDynamic\Configuration\Mapping\OneToOne
     */
    private function configureOneToOne(array $mappingConfig)
    {
        $oneToOne = new Configuration\Mapping\OneToOne;
        $oneToOne->setTargetEntity($this->getOption($mappingConfig, 'targetEntity', true));
        $oneToOne->setInversedBy($this->getOption($mappingConfig, 'inversedBy'));
        $oneToOne->setMappedBy($this->getOption($mappingConfig, 'mappedBy'));

        if (isset($mappingConfig['joinColumns'])) {
            $oneToOne->setJoinColumn($this->configureJoinColumn($mappingConfig['joinColumns']));
        }

        return $oneToOne;
    }

    /**
     * @param array $joinColumnConfig
     * @return \Fabiang\DoctrineDynamic\Configuration\Mapping\JoinColumn
     */
    private function configureJoinColumn(array $joinColumnConfig)
    {
        $name                 = $this->getOption($joinColumnConfig, 'name', true);
        $referencedColumnName = $this->getOption($joinColumnConfig, 'referencedColumnName', true);

        $joinColumn = new Configuration\Mapping\JoinColumn($name, $referencedColumnName);
        return $joinColumn;
    }

    /**
     * @param array $config
     * @param string $option
     * @param boolean $required
     * @return array
     * @throws RuntimeException
     */
    private function getOption(array $config, $option, $required = false)
    {
        if (!isset($config[$option])) {
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
