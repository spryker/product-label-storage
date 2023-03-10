<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Mapper;

use Generated\Shared\Transfer\ProductLabelCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToStoreFacadeInterface;

class ProductLabelDictionaryItemMapper implements ProductLabelDictionaryItemMapperInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToStoreFacadeInterface $storeFacade
     */
    public function __construct(ProductLabelStorageToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelCollectionTransfer $productLabelCollectionTransfer
     *
     * @return array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>>
     */
    public function mapProductLabelTransfersToProductLabelDictionaryItemTransfersByStoreNameAndLocaleName(
        ProductLabelCollectionTransfer $productLabelCollectionTransfer
    ): array {
        $productLabelDictionaryItemTransfers = [];
        $localeNameMapByStoreName = $this->getLocaleNameMapByStoreName();

        foreach ($productLabelCollectionTransfer->getProductLabels() as $productLabelTransfer) {
            foreach ($localeNameMapByStoreName as $storeName => $storeLocales) {
                $productLabelDictionaryItemTransfers = $this->mapProductLabelTransferToProductLabelDictionaryItemTransfersByStoreNameAndLocaleName(
                    $productLabelTransfer,
                    $storeName,
                    $storeLocales,
                    $productLabelDictionaryItemTransfers,
                );
            }
        }

        return $productLabelDictionaryItemTransfers;
    }

    /**
     * @return array<array<string>>
     */
    protected function getLocaleNameMapByStoreName(): array
    {
        $localeNameMapByStoreName = [];
        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($storeTransfers as $storeTransfer) {
            $localeNameMapByStoreName[$storeTransfer->getName()] = $storeTransfer->getAvailableLocaleIsoCodes();
        }

        return $localeNameMapByStoreName;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param string $storeName
     * @param array<string> $storeLocales
     * @param array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>> $productLabelDictionaryItemTransfers
     *
     * @return array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>>
     */
    protected function mapProductLabelTransferToProductLabelDictionaryItemTransfersByStoreNameAndLocaleName(
        ProductLabelTransfer $productLabelTransfer,
        string $storeName,
        array $storeLocales,
        array $productLabelDictionaryItemTransfers
    ): array {
        if (!$this->hasStoreRelation($productLabelTransfer, $storeName)) {
            return $productLabelDictionaryItemTransfers;
        }

        $mappedProductLabelLocalizedAttributesTransfer = $this->mapProductLabelLocalizedAttributeByLocale($productLabelTransfer);

        foreach ($storeLocales as $localeName) {
            $localizedProductLabelName = $this->getLocalizedProductLabelName(
                $productLabelTransfer,
                $mappedProductLabelLocalizedAttributesTransfer,
                $localeName,
            );

            $productLabelDictionaryItemTransfer = (new ProductLabelDictionaryItemTransfer())
                ->fromArray($productLabelTransfer->toArray(), true)
                ->setName($localizedProductLabelName)
                ->setIdProductLabel($productLabelTransfer->getIdProductLabel())
                ->setKey($productLabelTransfer->getName());

            $productLabelDictionaryItemTransfers[$storeName][$localeName][] = $productLabelDictionaryItemTransfer;
        }

        return $productLabelDictionaryItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param string $storeName
     *
     * @return bool
     */
    protected function hasStoreRelation(ProductLabelTransfer $productLabelTransfer, string $storeName): bool
    {
        foreach ($productLabelTransfer->getStoreRelation()->getStores() as $storeTransfer) {
            if ($storeName === $storeTransfer->getName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer>
     */
    protected function mapProductLabelLocalizedAttributeByLocale(
        ProductLabelTransfer $productLabelTransfer
    ): array {
        $mappedProductLabelLocalizedAttributesTransfer = [];

        foreach ($productLabelTransfer->getLocalizedAttributesCollection() as $productLabelLocalizedAttributesTransfer) {
            $localeName = $productLabelLocalizedAttributesTransfer->getLocale()->getLocaleName();
            $mappedProductLabelLocalizedAttributesTransfer[$localeName] = $productLabelLocalizedAttributesTransfer;
        }

        return $mappedProductLabelLocalizedAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     * @param array<\Generated\Shared\Transfer\ProductLabelLocalizedAttributesTransfer> $mappedProductLabelLocalizedAttributesTransfer
     * @param string $localeName
     *
     * @return string
     */
    protected function getLocalizedProductLabelName(
        ProductLabelTransfer $productLabelTransfer,
        array $mappedProductLabelLocalizedAttributesTransfer,
        string $localeName
    ): string {
        $productLabelLocalizedAttributesTransfer = $mappedProductLabelLocalizedAttributesTransfer[$localeName] ?? null;

        if ($productLabelLocalizedAttributesTransfer && $productLabelLocalizedAttributesTransfer->getName()) {
            return $productLabelLocalizedAttributesTransfer->getName();
        }

        return $productLabelTransfer->getName();
    }
}
