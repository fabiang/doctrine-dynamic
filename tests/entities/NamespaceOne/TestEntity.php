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

namespace Fabiang\DoctrineDynamic\Behat\NamespaceOne\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Fabiang\DoctrineDynamic\Behat\NamespaceTwo\Entity\TestEntity as TestEntityTwo;

/**
 * @Entity
 */
class TestEntity
{
    /**
     * @va integer
     * @Column(name="id", type="integer")
     * @Id
     */
    private $id;

    /**
     * @var TestEntityTwo
     */
    private $oneToOne;

    /**
     * @var ArrayCollection
     */
    private $oneToMany;

    public function __construct()
    {
        $this->oneToMany = new ArrayCollection;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getOneToOne()
    {
        return $this->oneToOne;
    }

    public function setOneToOne(TestEntityTwo $oneToOne)
    {
        $this->oneToOne = $oneToOne;
    }

    public function getOneToMany()
    {
        return $this->oneToMany;
    }

    public function addOneToMany(TestEntityTwo $testEntity)
    {
        if (!$this->oneToMany->contains($testEntity)) {
            $this->oneToMany->add($testEntity);
        }
    }
}
