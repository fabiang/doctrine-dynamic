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

use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Fabiang\DoctrineDynamic\Mapper\MapperInterface;

class ProxyDriver implements MappingDriver
{
    private MappingDriver $originalDriver;
    private Configuration $configuration;
    private MapperInterface $mapper;

    public function __construct(MappingDriver $originalDriver, Configuration $configuration, MapperInterface $mapper)
    {
        $this->originalDriver = $originalDriver;
        $this->configuration  = $configuration;
        $this->mapper         = $mapper;
    }

    public function loadMetadataForClass(string $className, ClassMetadata $metadata): void
    {
        $this->originalDriver->loadMetadataForClass($className, $metadata);

        if ($this->configuration->has($className)) {
            $configuration = $this->configuration->get($className);
            $this->mapper->map($metadata, $configuration);
        }
    }

    public function getAllClassNames(): array
    {
        return $this->originalDriver->getAllClassNames();
    }

    public function isTransient(string $className): bool
    {
        return $this->originalDriver->isTransient($className);
    }

    public function getOriginalDriver(): MappingDriver
    {
        return $this->originalDriver;
    }
}
