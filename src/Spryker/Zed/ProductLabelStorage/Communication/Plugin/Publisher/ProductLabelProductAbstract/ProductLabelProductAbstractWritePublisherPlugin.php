<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Communication\Plugin\Publisher\ProductLabelProductAbstract;

use Spryker\Shared\ProductLabelStorage\ProductLabelStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelStorage\Communication\ProductLabelStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelStorage\ProductLabelStorageConfig getConfig()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 */
class ProductLabelProductAbstractWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes product label data by spy_product_label_product_abstract entity events.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeProductAbstractLabelStorageCollectionByProductLabelProductAbstractEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_UPDATE,
            ProductLabelStorageConfig::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE,
        ];
    }
}
