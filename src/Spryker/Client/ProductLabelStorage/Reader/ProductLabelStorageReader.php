<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Reader;

use Generated\Shared\Transfer\ProductLabelStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelStorageTransfer;
use Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory;
use Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReaderInterface;

class ProductLabelStorageReader implements ProductLabelStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory
     */
    protected $dictionaryFactory;

    /**
     * @var \Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReaderInterface
     */
    protected $productAbstractLabelReader;

    /**
     * @param \Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory $dictionaryFactory
     * @param \Spryker\Client\ProductLabelStorage\Storage\ProductAbstractLabelReaderInterface $productAbstractLabelReader
     */
    public function __construct(DictionaryFactory $dictionaryFactory, ProductAbstractLabelReaderInterface $productAbstractLabelReader)
    {
        $this->dictionaryFactory = $dictionaryFactory;
        $this->productAbstractLabelReader = $productAbstractLabelReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageTransfer|null
     */
    public function findProductLabel(ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer): ?ProductLabelStorageTransfer
    {
        $productLabelStorageCollectionTransfer = $this->getProductLabelCollection($productLabelStorageCriteriaTransfer);

        if (!(array)$productLabelStorageCollectionTransfer->getProductLabels()) {
            return null;
        }

        return $productLabelStorageCollectionTransfer->getProductLabels()[0];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductLabelStorageCollectionTransfer
     */
    public function getProductLabelCollection(ProductLabelStorageCriteriaTransfer $productLabelStorageCriteriaTransfer): ProductLabelStorageCollectionTransfer
    {
        $productLabels = $this->dictionaryFactory->createDictionaryByIdProductLabel()->getDictionary(
            $productLabelStorageCriteriaTransfer->getLocaleName(),
            $productLabelStorageCriteriaTransfer->getStoreName()
        );
        $productLabelIds = $productLabelStorageCriteriaTransfer->getProductLabelIds();

        if ($productLabelStorageCriteriaTransfer->getProductAbstractIds()) {
            $productAbstractsProductLabelIds = $this->productAbstractLabelReader->getProductAbstractsProductLabelIds(
                $productLabelStorageCriteriaTransfer->getProductAbstractIds()
            );

            $productLabelIds = $this->getFilteringProductLabelIds($productLabelIds, $productAbstractsProductLabelIds);
        }

        if ($productLabelIds) {
            $productLabels = array_filter($productLabels, function ($productLabel) use ($productLabelIds) {
                return in_array($productLabel->getIdProductLabel(), $productLabelIds, true);
            });
        }

        $productLabelNames = $productLabelStorageCriteriaTransfer->getProductLabelNames();
        if ($productLabelNames) {
            $productLabels = array_filter($productLabels, function ($productLabel) use ($productLabelNames) {
                return in_array($productLabel->getName(), $productLabelNames, true);
            });
        }

        $productLabelStorageCollectionTransfer = new ProductLabelStorageCollectionTransfer();
        foreach ($productLabels as $productLabel) {
            $productLabelStorageCollectionTransfer->addProductLabel(
                (new ProductLabelStorageTransfer())->fromArray($productLabel->toArray())
            );
        }

        return $productLabelStorageCollectionTransfer;
    }

    /**
     * @param int[] $productLabelIds
     * @param int[] $productAbstractsProductLabelIds
     *
     * @return int[]
     */
    protected function getFilteringProductLabelIds(array $productLabelIds, array $productAbstractsProductLabelIds): array
    {
        if ($productLabelIds) {
            return array_intersect($productLabelIds, $productAbstractsProductLabelIds);
        }

        return $productAbstractsProductLabelIds;
    }
}
