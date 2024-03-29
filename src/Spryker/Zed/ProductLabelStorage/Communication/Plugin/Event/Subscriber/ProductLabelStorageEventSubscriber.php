<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Subscriber;

use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductLabel\Dependency\ProductLabelEvents;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelDictionaryStoragePublishListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelDictionaryStorageUnpublishListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelPublishStorageListener;
use Spryker\Zed\ProductLabelStorage\Communication\Plugin\Event\Listener\ProductLabelStorageListener;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductLabelStorage\Communication\Plugin\Publisher\ProductLabelProductAbstract\ProductLabelProductAbstractWritePublisherPlugin}
 *   or {@link \Spryker\Zed\ProductLabelStorage\Communication\Plugin\Publisher\ProductLabelDictionary\ProductLabelDictionaryDeletePublisherPlugin}
 *   or {@link \Spryker\Zed\ProductLabelStorage\Communication\Plugin\Publisher\ProductLabelDictionary\ProductLabelDictionaryWritePublisherPlugin}
 *   or {@link \Spryker\Zed\ProductLabelStorage\Communication\Plugin\Publisher\ProductLabelProductAbstract\ProductLabelProductAbstractWritePublisherPlugin} instead.
 *
 * @method \Spryker\Zed\ProductLabelStorage\Communication\ProductLabelStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelStorage\Business\ProductLabelStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelStorage\ProductLabelStorageConfig getConfig()
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageQueryContainerInterface getQueryContainer()
 */
class ProductLabelStorageEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection)
    {
        $this->addProductLabelPublishStorageListener($eventCollection);
        $this->addProductLabelUnpublishStorageListener($eventCollection);
        $this->addProductLabelCreateStorageListener($eventCollection);
        $this->addProductLabelUpdateStorageListener($eventCollection);
        $this->addProductLabelDeleteStorageListener($eventCollection);
        $this->addProductLabelDictionaryCreateStorageListener($eventCollection);
        $this->addProductLabelDictionaryUpdateStorageListener($eventCollection);
        $this->addProductLabelDictionaryDeleteStorageListener($eventCollection);
        $this->addProductLabelDictionaryPublishStorageListener($eventCollection);
        $this->addProductLabelDictionaryUnpublishStorageListener($eventCollection);
        $this->addProductLabelDictionaryLocalizedCreateStorageListener($eventCollection);
        $this->addProductLabelDictionaryLocalizedUpdateStorageListener($eventCollection);
        $this->addProductLabelDictionaryLocalizedDeleteStorageListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::PRODUCT_LABEL_PRODUCT_ABSTRACT_PUBLISH, new ProductLabelPublishStorageListener(), 0, null, $this->getConfig()->getProductAbstractLabelEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::PRODUCT_LABEL_PRODUCT_ABSTRACT_UNPUBLISH, new ProductLabelPublishStorageListener(), 0, null, $this->getConfig()->getProductAbstractLabelEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_CREATE, new ProductLabelStorageListener(), 0, null, $this->getConfig()->getProductAbstractLabelEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_UPDATE, new ProductLabelStorageListener(), 0, null, $this->getConfig()->getProductAbstractLabelEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_PRODUCT_ABSTRACT_DELETE, new ProductLabelStorageListener(), 0, null, $this->getConfig()->getProductAbstractLabelEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelDictionaryCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_CREATE, new ProductLabelDictionaryStoragePublishListener(), 0, null, $this->getConfig()->getProductLabelDictionaryEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelDictionaryUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_UPDATE, new ProductLabelDictionaryStoragePublishListener(), 0, null, $this->getConfig()->getProductLabelDictionaryEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelDictionaryDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_DELETE, new ProductLabelDictionaryStoragePublishListener(), 0, null, $this->getConfig()->getProductLabelDictionaryEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelDictionaryPublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::PRODUCT_LABEL_DICTIONARY_PUBLISH, new ProductLabelDictionaryStoragePublishListener(), 0, null, $this->getConfig()->getProductLabelDictionaryEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelDictionaryUnpublishStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::PRODUCT_LABEL_DICTIONARY_UNPUBLISH, new ProductLabelDictionaryStorageUnpublishListener(), 0, null, $this->getConfig()->getProductLabelDictionaryEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelDictionaryLocalizedCreateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_CREATE, new ProductLabelDictionaryStoragePublishListener(), 0, null, $this->getConfig()->getProductLabelDictionaryEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelDictionaryLocalizedUpdateStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_UPDATE, new ProductLabelDictionaryStoragePublishListener(), 0, null, $this->getConfig()->getProductLabelDictionaryEventQueueName());
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addProductLabelDictionaryLocalizedDeleteStorageListener(EventCollectionInterface $eventCollection)
    {
        $eventCollection->addListenerQueued(ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_DELETE, new ProductLabelDictionaryStoragePublishListener(), 0, null, $this->getConfig()->getProductLabelDictionaryEventQueueName());
    }
}
