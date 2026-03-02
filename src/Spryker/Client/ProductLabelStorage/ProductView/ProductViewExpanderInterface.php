<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\ProductView;

use Generated\Shared\Transfer\ProductViewTransfer;

interface ProductViewExpanderInterface
{
    public function expand(ProductViewTransfer $productViewTransfer, string $localeName, string $storeName): ProductViewTransfer;
}
