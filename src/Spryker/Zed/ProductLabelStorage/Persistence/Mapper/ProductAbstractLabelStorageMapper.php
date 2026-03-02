<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorage;
use Propel\Runtime\Collection\Collection;

class ProductAbstractLabelStorageMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorage> $productAbstractLabelStorageEntities
     * @param array<\Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer> $productAbstractLabelStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer>
     */
    public function mapProductAbstractLabelStorageEntitiesToProductAbstractLabelStorageTransfers(
        Collection $productAbstractLabelStorageEntities,
        array $productAbstractLabelStorageTransfers
    ): array {
        foreach ($productAbstractLabelStorageEntities as $productAbstractLabelStorageEntity) {
            $productAbstractLabelStorageTransfers[] = $this->mapProductAbstractLabelStorageEntityToProductAbstractLabelStorageTransfer(
                $productAbstractLabelStorageEntity,
                new ProductAbstractLabelStorageTransfer(),
            );
        }

        return $productAbstractLabelStorageTransfers;
    }

    protected function mapProductAbstractLabelStorageEntityToProductAbstractLabelStorageTransfer(
        SpyProductAbstractLabelStorage $productAbstractLabelStorageEntity,
        ProductAbstractLabelStorageTransfer $productAbstractLabelStorageTransfer
    ): ProductAbstractLabelStorageTransfer {
        $productAbstractLabelStorageTransfer->fromArray($productAbstractLabelStorageEntity->toArray(), true);
        $productAbstractLabelStorageTransfer->setIdProductAbstract($productAbstractLabelStorageEntity->getFkProductAbstract());

        return $productAbstractLabelStorageTransfer;
    }
}
