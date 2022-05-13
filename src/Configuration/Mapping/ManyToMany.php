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

namespace Fabiang\DoctrineDynamic\Configuration\Mapping;

use Fabiang\DoctrineDynamic\Configuration\Mapping\JoinTable;

class ManyToMany implements MappingConfigInterface
{
    private ?string $targetEntity = null;
    private ?string $inversedBy   = null;
    private ?string $mappedBy     = null;
    private ?JoinTable $joinTable = null;

    public function getTargetEntity(): ?string
    {
        return $this->targetEntity;
    }

    public function getInversedBy(): ?string
    {
        return $this->inversedBy;
    }

    public function getMappedBy(): ?string
    {
        return $this->mappedBy;
    }

    public function getJoinTable(): ?JoinTable
    {
        return $this->joinTable;
    }

    public function setTargetEntity(string $targetEntity): void
    {
        $this->targetEntity = $targetEntity;
    }

    public function setInversedBy(?string $inversedBy): void
    {
        $this->inversedBy = $inversedBy;
    }

    public function setMappedBy(?string $mappedBy): void
    {
        $this->mappedBy = $mappedBy;
    }

    public function setJoinTable(?JoinTable $joinTable): void
    {
        $this->joinTable = $joinTable;
    }
}
