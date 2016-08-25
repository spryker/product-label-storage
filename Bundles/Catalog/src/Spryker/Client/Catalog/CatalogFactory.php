<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Catalog;

use Spryker\Client\Catalog\KeyBuilder\ProductResourceKeyBuilder;
use Spryker\Client\Catalog\Model\Catalog as ModelCatalog;
use Spryker\Client\Catalog\Plugin\Elasticsearch\Query\CatalogSearchQueryPlugin;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\Kernel\Store;

class CatalogFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @param string $searchString
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     *
     * @deprecated Use getCatalogSearchQueryPlugin() method instead.
     */
    public function createCatalogSearchQueryPlugin($searchString)
    {
        return new CatalogSearchQueryPlugin($searchString);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryInterface
     */
    public function getCatalogSearchQueryPlugin()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_QUERY_PLUGIN);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     *
     * @deprecated Use getCatalogSearchQueryExpanderPlugins() method instead.
     */
    public function createCatalogSearchQueryExpanderPlugins()
    {
        return $this->getCatalogSearchQueryExpanderPlugins();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\QueryExpanderPluginInterface[]
     */
    public function getCatalogSearchQueryExpanderPlugins()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_QUERY_EXPANDER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     *
     * @deprecated Use getCatalogSearchResultFormatters() method instead.
     */
    public function createCatalogSearchResultFormatters()
    {
        return $this->getCatalogSearchResultFormatters();
    }

    /**
     * @return \Spryker\Client\Search\Dependency\Plugin\ResultFormatterPluginInterface[]
     */
    public function getCatalogSearchResultFormatters()
    {
        return $this->getProvidedDependency(CatalogDependencyProvider::CATALOG_SEARCH_RESULT_FORMATTER_PLUGINS);
    }

    /**
     * @return \Spryker\Client\Catalog\Model\Catalog
     *
     * @deprecated See \Spryker\Client\Catalog\Model\Catalog for more info.
     */
    public function createCatalogModel()
    {
        return new ModelCatalog(
            $this->createProductKeyBuilder(),
            $this->getStorageClient(),
            Store::getInstance()->getCurrentLocale()
        );
    }

    /**
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductKeyBuilder()
    {
        return new ProductResourceKeyBuilder();
    }

}
