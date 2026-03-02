<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStoragePersistenceFactory getFactory()
 */
class ProductLabelStorageEntityManager extends AbstractEntityManager implements ProductLabelStorageEntityManagerInterface
{
    public function saveProductAbstractLabelStorage(ProductAbstractLabelStorageTransfer $productAbstractLabelStorageTransfer): void
    {
        $productAbstractLabelStorageEntity = $this->getFactory()
            ->createSpyProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract($productAbstractLabelStorageTransfer->getIdProductAbstract())
            ->findOneOrCreate();

        $productAbstractLabelStorageEntity->setData($productAbstractLabelStorageTransfer->toArray());
        $productAbstractLabelStorageEntity->save();
    }

    public function deleteAllProductLabelDictionaryStorageEntities(): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $productLabelDictionaryStorageCollection */
        $productLabelDictionaryStorageCollection = $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery()
            ->find();

        $productLabelDictionaryStorageCollection->delete();
    }

    public function deleteProductAbstractLabelStorageByProductAbstractId(int $productAbstractId): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $productAbstractLabelStorageCollection */
        $productAbstractLabelStorageCollection = $this->getFactory()
            ->createSpyProductAbstractLabelStorageQuery()
            ->filterByFkProductAbstract($productAbstractId)
            ->find();

        $productAbstractLabelStorageCollection->delete();
    }

    public function createProductLabelDictionaryStorage(ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer): void
    {
        $productLabelDictionaryStorageEntity = $this->getFactory()->createProductLabelDictionaryStorageMapper()
            ->mapProductLabelDictionaryStorageTransferToProductLabelDictionaryStorageEntity(
                $productLabelDictionaryStorageTransfer,
                new SpyProductLabelDictionaryStorage(),
            );

        $productLabelDictionaryStorageEntity->save();
    }

    public function updateProductLabelDictionaryStorage(ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer): void
    {
        $productLabelDictionaryStorageEntity = $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery()
            ->filterByIdProductLabelDictionaryStorage($productLabelDictionaryStorageTransfer->getIdProductLabelDictionaryStorage())
            ->findOne();

        $productLabelDictionaryStorageEntity = $this->getFactory()->createProductLabelDictionaryStorageMapper()
            ->mapProductLabelDictionaryStorageTransferToProductLabelDictionaryStorageEntity(
                $productLabelDictionaryStorageTransfer,
                $productLabelDictionaryStorageEntity ?? new SpyProductLabelDictionaryStorage(),
            );

        $productLabelDictionaryStorageEntity->save();
    }

    public function deleteProductLabelDictionaryStorageById(int $idProductLabelDictionary): void
    {
        $this->getFactory()
            ->createSpyProductLabelDictionaryStorageQuery()
            ->filterByIdProductLabelDictionaryStorage($idProductLabelDictionary)
            ->delete();
    }
}
