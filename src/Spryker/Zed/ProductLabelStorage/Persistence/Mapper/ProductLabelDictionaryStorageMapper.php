<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage;
use Propel\Runtime\Collection\Collection;

class ProductLabelDictionaryStorageMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorage> $productLabelDictionaryStorageEntities
     * @param array<\Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer> $productLabelDictionaryStorageTransfers
     *
     * @return array
     */
    public function mapProductLabelDictionaryStorageEntitiesToProductLabelDictionaryStorageTransfers(
        Collection $productLabelDictionaryStorageEntities,
        array $productLabelDictionaryStorageTransfers
    ): array {
        foreach ($productLabelDictionaryStorageEntities as $productLabelDictionaryStorageEntity) {
            $productLabelDictionaryStorageTransfers[] = $this->mapProductLabelDictionaryStorageEntityToProductLabelDictionaryStorageTransfer(
                $productLabelDictionaryStorageEntity,
                new ProductLabelDictionaryStorageTransfer(),
            );
        }

        return $productLabelDictionaryStorageTransfers;
    }

    public function mapProductLabelDictionaryStorageEntityToProductLabelDictionaryStorageTransfer(
        SpyProductLabelDictionaryStorage $productLabelDictionaryStorageEntity,
        ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer
    ): ProductLabelDictionaryStorageTransfer {
        $productLabelDictionaryStorageTransfer->fromArray($productLabelDictionaryStorageEntity->toArray(), true);

        $productLabelDictionaryStorageTransfer->setItems(
            $this->mapProductLabelDictionaryItemsToProductLabelDictionaryCollection(
                $productLabelDictionaryStorageEntity->getData()['items'],
                new ArrayObject(),
            ),
        );

        return $productLabelDictionaryStorageTransfer;
    }

    public function mapProductLabelDictionaryStorageTransferToProductLabelDictionaryStorageEntity(
        ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer,
        SpyProductLabelDictionaryStorage $productLabelDictionaryStorageEntity
    ): SpyProductLabelDictionaryStorage {
        $productLabelDictionaryStorageEntity->fromArray($productLabelDictionaryStorageTransfer->toArray());
        $productLabelDictionaryStorageEntity->setData(array_intersect_key($productLabelDictionaryStorageTransfer->modifiedToArray(), ['items' => []]));
        $productLabelDictionaryStorageEntity->setIsSendingToQueue(true);

        return $productLabelDictionaryStorageEntity;
    }

    /**
     * @param array $productLabelDictionaryItems
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer> $productLabelDictionaryCollection
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    protected function mapProductLabelDictionaryItemsToProductLabelDictionaryCollection(
        array $productLabelDictionaryItems,
        ArrayObject $productLabelDictionaryCollection
    ): ArrayObject {
        foreach ($productLabelDictionaryItems as $productLabelDictionaryItem) {
            $productLabelDictionaryCollection->append(
                $this->mapProductLabelDictionaryItemToProductLabelDictionaryItemTransfer(
                    $productLabelDictionaryItem,
                    new ProductLabelDictionaryItemTransfer(),
                ),
            );
        }

        return $productLabelDictionaryCollection;
    }

    protected function mapProductLabelDictionaryItemToProductLabelDictionaryItemTransfer(
        array $productLabelDictionaryItem,
        ProductLabelDictionaryItemTransfer $productLabelDictionaryItemTransfer
    ): ProductLabelDictionaryItemTransfer {
        return $productLabelDictionaryItemTransfer->fromArray($productLabelDictionaryItem, true);
    }
}
