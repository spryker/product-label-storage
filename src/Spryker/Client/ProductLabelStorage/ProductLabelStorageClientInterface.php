<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage;

use Generated\Shared\Transfer\ProductLabelStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelStorageTransfer;

interface ProductLabelStorageClientInterface
{
    /**
     * Specification:
     * - TODO: add specification
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabelsByIdProductAbstract($idProductAbstract, $localeName);

    /**
     * Specification:
     * - TODO: add specification
     *
     * @api
     *
     * @param array $idProductLabels
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer[]
     */
    public function findLabels(array $idProductLabels, $localeName);

    /**
     * Specification:
     * - Retrieves label for given label name, locale and store name.
     * - Forward compatibility (from the next major): only label assigned with passed $storeName will be returned.
     *
     * @api
     *
     * @param string $labelName
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByName($labelName, $localeName, ?string $storeName = null);

    /**
     * Specification:
     * - Returns null if no productLabelIds or storeName or localeName in ProductLabelStorageCriteriaTransfer exist
     * - Retrieves ProductLabels from storage and returns correct ProductLabelStorage
     * - Filters by:
     *
     * - Returns null if requested ProductLabel is not found
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageTransfer|null
     */
    public function findOne(ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer): ?ProductLabelStorageTransfer;

    /**
     * Specification:
     * - Returns ProductLabelStorageCollectionTransfer based on given ProductLabelStorageCriteriaTransfer
     * - Filters by:
     *      - localeName
     *      - storeName
     *      - productLabelIds
     *      - productAbstractIds
     *      - productLabelNames
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageCollectionTransfer
     */
    public function get(ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer): ProductLabelStorageCollectionTransfer;
}
