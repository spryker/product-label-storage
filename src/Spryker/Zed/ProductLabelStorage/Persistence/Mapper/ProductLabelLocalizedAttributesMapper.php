<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Persistence\Mapper;

use Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelLocalizedAttributes;

class ProductLabelLocalizedAttributesMapper
{
    public function mapProductLabelLocalizedAttributesEntityToProductLabelLocalizedAttributesTransfer(
        SpyProductLabelLocalizedAttributes $productLabelLocalizedAttributesEntity,
        ProductLabelLocalizedAttributesTransfer $productLabelLocalizedAttributesTransfer
    ): ProductLabelLocalizedAttributesTransfer {
        return $productLabelLocalizedAttributesTransfer->fromArray($productLabelLocalizedAttributesEntity->toArray(), true);
    }
}
