<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage;

use Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientBridge;
use Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Shared\Kernel\Store;

class ProductLabelStorageDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_STORAGE = 'CLIENT_STORAGE';
    const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return new ProductLabelStorageToStorageClientBridge($container->getLocator()->storage()->client());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container[self::SERVICE_SYNCHRONIZATION] = function (Container $container) {
            return new ProductLabelStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    protected function addStore(Container $container): Container
    {
        $container[self::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }
}
