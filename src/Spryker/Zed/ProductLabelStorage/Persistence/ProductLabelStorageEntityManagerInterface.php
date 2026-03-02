<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence;

use Generated\Shared\Transfer\ProductAbstractLabelStorageTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;

interface ProductLabelStorageEntityManagerInterface
{
    public function saveProductAbstractLabelStorage(
        ProductAbstractLabelStorageTransfer $productAbstractLabelStorageTransfer
    ): void;

    public function deleteAllProductLabelDictionaryStorageEntities(): void;

    public function deleteProductAbstractLabelStorageByProductAbstractId(int $productAbstractId): void;

    public function createProductLabelDictionaryStorage(ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer): void;

    public function updateProductLabelDictionaryStorage(ProductLabelDictionaryStorageTransfer $productLabelDictionaryStorageTransfer): void;

    public function deleteProductLabelDictionaryStorageById(int $idProductLabelDictionary): void;
}
