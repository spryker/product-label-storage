<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelStorage\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ProductAbstractLabelStorageBuilder;
use Generated\Shared\DataBuilder\ProductLabelDictionaryStorageBuilder;
use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class ProductLabelStorageHelper extends Module
{
    use DataCleanupHelperTrait;

    public function haveProductLabelDictionaryStorage(array $seedData = []): ProductLabelDictionaryStorageTransfer
    {
        $productLabelDictionaryStorageTransfer = (new ProductLabelDictionaryStorageBuilder($seedData))->build();
        $productLabelDictionaryStorageTransfer = $this->persistProductLabelDictionaryStorage($productLabelDictionaryStorageTransfer);

        return $productLabelDictionaryStorageTransfer;
    }

    public function haveProductAbstractLabelStorage(array $seedData = []): ProductAbstractLabelStorageTransfer
    {
        $productAbstractLabelTransfer = (new ProductAbstractLabelStorageBuilder($seedData))->build();
        $this->persistProductAbstractLabelStorage($productAbstractLabelTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productAbstractLabelTransfer): void {
            $this->cleanupProductAbstractLabelStorage($productAbstractLabelTransfer);
        });

        return $productAbstractLabelTransfer;
    }

    protected function persistProductLabelDictionaryStorage(
        ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
    ): ProductLabelDictionaryStorageTransfer {
        $productLabelDictionaryStorageEntity = $this->createProductLabelDictionaryStorageQuery()
            ->filterByLocale($productLabelDictionaryStorageTransfer->getLocale())
            ->filterByStore($productLabelDictionaryStorageTransfer->getStore())
            ->findOneOrCreate();

        $productLabelDictionaryStorageEntity->fromArray($productLabelDictionaryStorageTransfer->modifiedToArray());
        $productLabelDictionaryStorageEntity->setData($productLabelDictionaryStorageTransfer->getItems()->getArrayCopy());
        $productLabelDictionaryStorageEntity->save();

        return $productLabelDictionaryStorageTransfer->fromArray($productLabelDictionaryStorageEntity->toArray(), true);
    }

    protected function persistProductAbstractLabelStorage(ProductAbstractLabelStorageTransfer $productAbstractLabelStorageTransfer): void
    {
        $productAbstractLabelStorageEntity = $this->createProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract($productAbstractLabelStorageTransfer->getIdProductAbstract())
            ->findOneOrCreate();

        $productAbstractLabelStorageEntity->setData($productAbstractLabelStorageTransfer->toArray());
        $productAbstractLabelStorageEntity->save();
    }

    protected function cleanupProductAbstractLabelStorage(ProductAbstractLabelStorageTransfer $productAbstractLabelStorageTransfer): void
    {
        $this->createProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract($productAbstractLabelStorageTransfer->getIdProductAbstract())
            ->delete();
    }

    protected function createProductLabelDictionaryStorageQuery(): SpyProductLabelDictionaryStorageQuery
    {
        return SpyProductLabelDictionaryStorageQuery::create();
    }

    protected function createProductAbstractLabelStorageQuery(): SpyProductAbstractLabelStorageQuery
    {
        return SpyProductAbstractLabelStorageQuery::create();
    }
}
