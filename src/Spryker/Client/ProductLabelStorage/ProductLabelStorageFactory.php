<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToLocaleClientInterface;
use Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStoreClientInterface;
use Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToUtilEncodingServiceInterface;
use Spryker\Client\ProductLabelStorage\ProductView\ProductViewExpander;
use Spryker\Client\ProductLabelStorage\ProductView\ProductViewExpanderInterface;
use Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory;
use Spryker\Client\ProductLabelStorage\Storage\LabelDictionaryReader;
use Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReader;

class ProductLabelStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReaderInterface
     */
    public function createProductAbstractLabelStorageReader()
    {
        return new ProductAbstractLabelReader(
            $this->getStorage(),
            $this->getSynchronizationService(),
            $this->createLabelDictionaryReader(),
            $this->getUtilEncodingService(),
            $this->getLocaleClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\LabelDictionaryReaderInterface
     */
    public function createLabelDictionaryReader()
    {
        return new LabelDictionaryReader($this->createDictionaryFactory());
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory
     */
    public function createDictionaryFactory()
    {
        return new DictionaryFactory();
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\ProductView\ProductViewExpanderInterface
     */
    public function createProductViewExpander(): ProductViewExpanderInterface
    {
        return new ProductViewExpander($this->createProductAbstractLabelStorageReader());
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductLabelStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStorageClientInterface
     */
    public function getStorage()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Dependency\Service\ProductLabelStorageToSynchronizationServiceBridge
     */
    public function getSynchronizationService()
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToLocaleClientInterface
     */
    public function getLocaleClient(): ProductLabelStorageToLocaleClientInterface
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \Spryker\Client\ProductLabelStorage\Dependency\Client\ProductLabelStorageToStoreClientInterface
     */
    public function getStoreClient(): ProductLabelStorageToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductLabelStorageDependencyProvider::CLIENT_STORE);
    }
}
