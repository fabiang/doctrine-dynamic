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

namespace Fabiang\DoctrineDynamic\Mapper;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Fabiang\DoctrineDynamic\Configuration\Entity as EntityConfiguration;

class MetadataMapper implements Mapper
{

    public function map(ClassMetadata $metadata, EntityConfiguration $configuration)
    {
        if ($configuration->getRepository()) {
            $metadata->customRepositoryClassName = $configuration->getRepository();
        }

        foreach ($configuration->getFields() as $field) {
            foreach ($field->getOneToOne() as $oneToOne) {
                $oneToOneConfig = [
                    'fieldName'    => $field->getName(),
                    'targetEntity' => $oneToOne->getTargetEntity(),
                    'inversedBy'   => $oneToOne->getInversedBy(),
                    'mappedBy'     => $oneToOne->getMappedBy(),
                ];

                $joinColumn = $oneToOne->getJoinColumn();
                if (null !== $joinColumn) {
                    $oneToOneConfig['joinColumns'] = [
                        [
                            'name'                 => $joinColumn->getName(),
                            'referencedColumnName' => $joinColumn->getReferencedColumnName(),
                        ]
                    ];
                }

                $metadata->mapOneToOne($oneToOneConfig);
            }

            foreach ($field->getManyToOne() as $manyToOne) {
                $manyToOneConfig = [
                    'fieldName'    => $field->getName(),
                    'targetEntity' => $manyToOne->getTargetEntity(),
                    'inversedBy'   => $manyToOne->getInversedBy(),
                ];

                $joinColumn = $manyToOne->getJoinColumn();
                if (null !== $joinColumn) {
                    $manyToOneConfig['joinColumns'] = [
                        [
                            'name'                 => $joinColumn->getName(),
                            'referencedColumnName' => $joinColumn->getReferencedColumnName(),
                        ]
                    ];
                }

                $metadata->mapManyToOne($manyToOneConfig);
            }

            foreach ($field->getOneToMany() as $oneToMany) {
                $oneToMany = [
                    'fieldName'    => $field->getName(),
                    'targetEntity' => $oneToMany->getTargetEntity(),
                    'mappedBy'     => $oneToMany->getMappedBy(),
                ];

                $metadata->mapOneToMany($oneToMany);
            }

            foreach ($field->getManyToMany() as $manyToMany) {
                $manyToManyConfig = [
                    'fieldName'    => $field->getName(),
                    'targetEntity' => $manyToMany->getTargetEntity(),
                    'inversedBy'   => $manyToMany->getInversedBy(),
                    'mappedBy'     => $manyToMany->getMappedBy(),
                ];

                $joinTable = $manyToMany->getJoinTable();
                if (null !== $joinTable) {
                    $manyToManyConfig['joinTable'] = [
                        'name' => $joinTable->getName(),
                    ];
                }

                $metadata->mapManyToMany($manyToManyConfig);
            }
        }
    }
}
