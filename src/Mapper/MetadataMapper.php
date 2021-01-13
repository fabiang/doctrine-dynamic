<?php

/**
 * Copyright 2015-2021 Fabian Grutschus. All rights reserved.
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
 * @copyright 2015-2021 Fabian Grutschus. All rights reserved.
 * @license   BSD-2-Clause
 */

namespace Fabiang\DoctrineDynamic\Mapper;

use Fabiang\DoctrineDynamic\Configuration\Field;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Fabiang\DoctrineDynamic\Configuration\Entity as EntityConfiguration;

class MetadataMapper implements Mapper
{

    public function map(ClassMetadata $metadata, EntityConfiguration $configuration)
    {
        if ($configuration->getRepository()) {
            $metadata->customRepositoryClassName = $configuration->getRepository();
        }

        foreach ($configuration->getFields() as $field) {
            $this->mapRelation('oneToOne', $field, $metadata);
            $this->mapRelation('manyToOne', $field, $metadata);
            $this->mapRelation('oneToMany', $field, $metadata);
            $this->mapRelation('manyToMany', $field, $metadata);
        }
    }

    /**
     * @param string $type
     * @param Field $field
     * @param ClassMetadata $metadata
     */
    private function mapRelation($type, Field $field, ClassMetadata $metadata)
    {
        $getMethod = 'get' . ucfirst($type);
        $relations = $field->{$getMethod}();
        $mapMethod = 'map' . ucfirst($type);

        foreach ($relations as $relation) {
            $relationConfig = [
                'fieldName'    => $field->getName(),
                'targetEntity' => $relation->getTargetEntity(),
            ];

            if (method_exists($relation, 'getInversedBy')) {
                $relationConfig['inversedBy'] = $relation->getInversedBy();
            }

            if (method_exists($relation, 'getMappedBy')) {
                $relationConfig['mappedBy'] = $relation->getMappedBy();
            }

            if (method_exists($relation, 'getJoinColumn')
                && null !== ($joinColumn = $relation->getJoinColumn())) {
                $relationConfig['joinColumns'] = [
                    [
                        'name'                 => $joinColumn->getName(),
                        'referencedColumnName' => $joinColumn->getReferencedColumnName(),
                    ]
                ];
            }

            if (method_exists($relation, 'getJoinTable')
                && null !== ($joinTable = $relation->getJoinTable())) {
                $relationConfig['joinTable'] = [
                    'name' => $joinTable->getName(),
                ];
            }

            $metadata->{$mapMethod}($relationConfig);
        }
    }
}
