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

use Doctrine\ORM\EntityManagerInterface;
use Traversable;
use Fabiang\DoctrineDynamic\Exception\InvalidArgumentException;
use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Fabiang\DoctrineDynamic\Exception\RuntimeException;
use Fabiang\DoctrineDynamic\Mapper\MetadataMapper;

class ProxyDriverFactory
{

    /**
     * @var ConfigurationFactory
     */
    private $configurationFactory;

    public function __construct()
    {
        $this->configurationFactory = new ConfigurationFactory;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param array|Traversable|Configuration $configuration
     * @return ProxyDriver[]
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function factory(EntityManagerInterface $entityManager, $configuration)
    {
        if (is_array($configuration) || $configuration instanceof Traversable) {
            $configuration = $this->configurationFactory->factory($configuration);
        }

        if (!($configuration instanceof Configuration)) {
            throw new InvalidArgumentException(sprintf(
                'Argument #1 passed to "%s" must be an instance of "%s", "%s" or an array, "%s" given',
                __METHOD__,
                Configuration::class,
                Traversable::class,
                is_object($configuration) ? get_class($configuration) : gettype($configuration)
            ));
        }

        $doctrineConfig = $entityManager->getConfiguration();

        $driverImplementation = $doctrineConfig->getMetadataDriverImpl();
        if (!($driverImplementation instanceof MappingDriverChain)) {
            throw new RuntimeException(sprintf(
                'Driver implementation is no instance of "%s", instance of "%s" given',
                MappingDriverChain::class,
                get_class($driverImplementation)
            ));
        }

        $metadataMapper = new MetadataMapper();

        $drivers = $driverImplementation->getDrivers();
        $proxyDrivers = [];
        foreach ($drivers as $namespace => $originalDriver) {
            $proxyDriver = new ProxyDriver(
                $originalDriver,
                $configuration,
                $metadataMapper
            );
            $driverImplementation->addDriver($proxyDriver, $namespace);
            $proxyDrivers[$namespace] = $proxyDriver;
        }
        return $proxyDrivers;
    }
}
