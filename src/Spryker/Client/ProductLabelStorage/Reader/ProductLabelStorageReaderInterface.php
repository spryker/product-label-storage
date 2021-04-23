<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Reader;

use Generated\Shared\Transfer\ProductLabelStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelStorageTransfer;

interface ProductLabelStorageReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageTransfer|null
     */
    public function findProductLabel(ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer): ?ProductLabelStorageTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageCollectionTransfer
     */
    public function getProductLabelCollection(ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer): ProductLabelStorageCollectionTransfer;
}
