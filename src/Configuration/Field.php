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

namespace Fabiang\DoctrineDynamic\Configuration;

class Field
{
    private string $name;

    /** @var Mapping\ManyToMany[] */
    private array $manyToMany = [];

    /** @var Mapping\ManyToOne[] */
    private array $manyToOne = [];

    /** @var Mapping\OneToMany[] */
    private array $oneToMany = [];

    /** @var Mapping\OneToOne[] */
    private array $oneToOne = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addManyToMany(Mapping\ManyToMany $manyToMany): void
    {
        $this->manyToMany[] = $manyToMany;
    }

    public function addManyToOne(Mapping\ManyToOne $manyToOne): void
    {
        $this->manyToOne[] = $manyToOne;
    }

    public function addOneToMany(Mapping\OneToMany $oneToMany): void
    {
        $this->oneToMany[] = $oneToMany;
    }

    public function addOneToOne(Mapping\OneToOne $oneToOne): void
    {
        $this->oneToOne[] = $oneToOne;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Mapping\ManyToMany[]
     */
    public function getManyToMany(): array
    {
        return $this->manyToMany;
    }

    /**
     * @return Mapping\ManyToOne[]
     */
    public function getManyToOne(): array
    {
        return $this->manyToOne;
    }

    /**
     * @return Mapping\OneToMany[]
     */
    public function getOneToMany(): array
    {
        return $this->oneToMany;
    }

    /**
     * @return Mapping\OneToOne[]
     */
    public function getOneToOne(): array
    {
        return $this->oneToOne;
    }
}
