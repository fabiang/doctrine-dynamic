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

use PHPUnit\Framework\TestCase;
use Fabiang\DoctrineDynamic\Configuration\Entity;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @coversDefaultClass Fabiang\DoctrineDynamic\Configuration
 */
final class ConfigurationTest extends TestCase
{

    use ProphecyTrait;

    /**
     * @var Configuration
     */
    private $config;

    protected function setUp(): void
    {
        $this->config = new Configuration;
    }

    /**
     * @covers ::add
     * @covers ::getEntities
     * @covers ::has
     * @covers ::get
     * @uses Fabiang\DoctrineDynamic\Configuration\Entity
     */
    public function testConfigObject()
    {
        $this->assertCount(0, $this->config->getEntities());
        $entity = new Entity('foo');
        $this->config->add($entity);
        $this->assertSame(['foo' => $entity], $this->config->getEntities());
        $this->assertTrue($this->config->has('foo'));
        $this->assertFalse($this->config->has('bar'));
        $this->assertSame($entity, $this->config->get('foo'));
        $this->assertNull($this->config->get('bar'));
    }

}
