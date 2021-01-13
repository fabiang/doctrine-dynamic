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
use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Fabiang\DoctrineDynamic\Configuration\Entity;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @coversDefaultClass Fabiang\DoctrineDynamic\ProxyDriver
 */
final class ProxyDriverTest extends TestCase
{

    use ProphecyTrait;

    /**
     * @var ProxyDriver
     */
    private $driver;

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $originalDriver;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * @var \Prophecy\Prophecy\ObjectProphecy
     */
    private $mapper;

    protected function setUp(): void
    {
        $this->originalDriver = $this->prophesize(MappingDriver::class);
        $this->configuration  = new Configuration;
        $this->mapper         = $this->prophesize(Mapper\Mapper::class);
        $this->driver         = new ProxyDriver(
            $this->originalDriver->reveal(),
            $this->configuration,
            $this->mapper->reveal()
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::loadMetadataForClass
     * @uses Fabiang\DoctrineDynamic\Configuration
     * @uses Fabiang\DoctrineDynamic\Configuration\Entity
     */
    public function loadingMetaData()
    {
        $classMetaData = $this->prophesize(ClassMetadata::class)->reveal();
        $this->originalDriver->loadMetadataForClass('baz', $classMetaData)
            ->shouldBeCalled()
            ->willReturn('something');

        $entity = new Entity('baz');
        $this->configuration->add($entity);

        $this->mapper->map($classMetaData, $entity)
            ->shouldBeCalled();

        $this->assertSame('something', $this->driver->loadMetadataForClass('baz', $classMetaData));
    }

    /**
     * @test
     * @covers ::getAllClassNames
     * @covers ::isTransient
     * @uses Fabiang\DoctrineDynamic\ProxyDriver::__construct
     */
    public function proxying()
    {
        $this->originalDriver->getAllClassNames()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->assertTrue($this->driver->getAllClassNames());

        $this->originalDriver->isTransient('foobar')
            ->shouldBeCalled()
            ->willReturn(true);

        $this->assertTrue($this->driver->isTransient('foobar'));
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::getOriginalDriver
     */
    public function getOriginalDriver()
    {
        $this->assertSame(
            $this->originalDriver->reveal(),
            $this->driver->getOriginalDriver()
        );
    }

}
