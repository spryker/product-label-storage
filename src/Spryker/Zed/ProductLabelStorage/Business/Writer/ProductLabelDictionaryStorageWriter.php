<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\ProductLabelCollectionTransfer;
use Generated\Shared\Transfer\ProductLabelConditionsTransfer;
use Generated\Shared\Transfer\ProductLabelCriteriaTransfer;
use Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface;
use Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface;

class ProductLabelDictionaryStorageWriter implements ProductLabelDictionaryStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface
     */
    protected $productLabelStorageRepository;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface
     */
    protected $productLabelStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper
     */
    protected $productLabelDictionaryItemMapper;

    /**
     * @param \Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface $productLabelFacade
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageRepositoryInterface $productLabelStorageRepository
     * @param \Spryker\Zed\ProductLabelStorage\Persistence\ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager
     * @param \Spryker\Zed\ProductLabelStorage\Business\Mapper\ProductLabelDictionaryItemMapper $productLabelDictionaryItemMapper
     */
    public function __construct(
        ProductLabelStorageToProductLabelFacadeInterface $productLabelFacade,
        ProductLabelStorageRepositoryInterface $productLabelStorageRepository,
        ProductLabelStorageEntityManagerInterface $productLabelStorageEntityManager,
        ProductLabelDictionaryItemMapper $productLabelDictionaryItemMapper
    ) {
        $this->productLabelFacade = $productLabelFacade;
        $this->productLabelStorageRepository = $productLabelStorageRepository;
        $this->productLabelStorageEntityManager = $productLabelStorageEntityManager;
        $this->productLabelDictionaryItemMapper = $productLabelDictionaryItemMapper;
    }

    /**
     * @return void
     */
    public function writeProductLabelDictionaryStorageCollection(): void
    {
        $productLabelCollectionTransfer = $this->getProductLabelCollection();

        if (!count($productLabelCollectionTransfer->getProductLabels())) {
            $this->productLabelStorageEntityManager->deleteAllProductLabelDictionaryStorageEntities();

            return;
        }

        $productLabelDictionaryItemTransfersMappedByStoreAndLocale = $this->productLabelDictionaryItemMapper
            ->mapProductLabelTransfersToProductLabelDictionaryItemTransfersByStoreNameAndLocaleName(
                $productLabelCollectionTransfer,
            );

        $productLabelDictionaryStorageTransfers = $this->productLabelStorageRepository
            ->getProductLabelDictionaryStorageTransfers();
        $productLabelDictionaryStorageTransfers = $this->filterAndDeleteEmptyProductLabelDictionaryStorageTransfers(
            $productLabelDictionaryStorageTransfers,
            $productLabelDictionaryItemTransfersMappedByStoreAndLocale,
        );
        $productLabelDictionaryItemTransfersMappedByStoreAndLocale = $this->filterAndUpdateExistingProductLabelDictionaryStorageData(
            $productLabelDictionaryStorageTransfers,
            $productLabelDictionaryItemTransfersMappedByStoreAndLocale,
        );

        $this->createProductLabelDictionaryStorageData($productLabelDictionaryItemTransfersMappedByStoreAndLocale);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer> $productLabelDictionaryStorageTransfers
     * @param array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>> $productLabelDictionaryItemTransfersMappedByStoreAndLocale
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer>
     */
    protected function filterAndDeleteEmptyProductLabelDictionaryStorageTransfers(
        array $productLabelDictionaryStorageTransfers,
        array $productLabelDictionaryItemTransfersMappedByStoreAndLocale
    ): array {
        foreach ($productLabelDictionaryStorageTransfers as $dataKey => $productLabelDictionaryStorageTransfer) {
            $storeName = $productLabelDictionaryStorageTransfer->getStore();
            $localeName = $productLabelDictionaryStorageTransfer->getLocale();

            if (isset($productLabelDictionaryItemTransfersMappedByStoreAndLocale[$storeName][$localeName])) {
                continue;
            }

            $this->productLabelStorageEntityManager->deleteProductLabelDictionaryStorageById(
                $productLabelDictionaryStorageTransfer->getIdProductLabelDictionaryStorage(),
            );
            unset($productLabelDictionaryStorageTransfers[$dataKey]);
        }

        return $productLabelDictionaryStorageTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductLabelDictionaryStorageTransfer> $productLabelDictionaryStorageTransfers
     * @param array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>> $productLabelDictionaryItemTransfersMappedByStoreAndLocale
     *
     * @return array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>>
     */
    protected function filterAndUpdateExistingProductLabelDictionaryStorageData(
        array $productLabelDictionaryStorageTransfers,
        array $productLabelDictionaryItemTransfersMappedByStoreAndLocale
    ): array {
        foreach ($productLabelDictionaryStorageTransfers as $productLabelDictionaryStorageTransfer) {
            $storeName = $productLabelDictionaryStorageTransfer->getStore();
            $localeName = $productLabelDictionaryStorageTransfer->getLocale();

            $productLabelDictionaryStorageTransfer->setItems(
                new ArrayObject($productLabelDictionaryItemTransfersMappedByStoreAndLocale[$storeName][$localeName]),
            );
            $this->productLabelStorageEntityManager->updateProductLabelDictionaryStorage(
                $productLabelDictionaryStorageTransfer,
            );
            unset($productLabelDictionaryItemTransfersMappedByStoreAndLocale[$storeName][$localeName]);
        }

        return $productLabelDictionaryItemTransfersMappedByStoreAndLocale;
    }

    /**
     * @param array<array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>> $productLabelDictionaryItemTransfersMappedByStoreAndLocale
     *
     * @return void
     */
    protected function createProductLabelDictionaryStorageData(
        array $productLabelDictionaryItemTransfersMappedByStoreAndLocale
    ): void {
        foreach ($productLabelDictionaryItemTransfersMappedByStoreAndLocale as $storeName => $productLabelDictionaryItemTransfersMappedByLocale) {
            foreach ($productLabelDictionaryItemTransfersMappedByLocale as $localeName => $productLabelDictionaryItemTransfers) {
                $productLabelDictionaryStorageTransfer = (new ProductLabelDictionaryStorageTransfer())
                    ->setStore($storeName)
                    ->setLocale($localeName)
                    ->setItems(new ArrayObject($productLabelDictionaryItemTransfers));

                $this->productLabelStorageEntityManager->createProductLabelDictionaryStorage(
                    $productLabelDictionaryStorageTransfer,
                );
            }
        }
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelCollectionTransfer
     */
    protected function getProductLabelCollection(): ProductLabelCollectionTransfer
    {
        $productLabelCriteriaTransfer = (new ProductLabelCriteriaTransfer())
            ->setProductLabelConditions(
                (new ProductLabelConditionsTransfer())
                    ->setIsActive(true),
            )
            ->setSortCollection(new ArrayObject([
                (new SortTransfer())->setField(ProductLabelTransfer::IS_EXCLUSIVE)->setIsAscending(false),
                (new SortTransfer())->setField(ProductLabelTransfer::POSITION)->setIsAscending(true),
            ]))
            ->setWithProductLabelStores(true)
            ->setWithProductLabelLocalizedAttributes(true)
            ->setWithProductLabelProductAbstracts(true);

        return $this->productLabelFacade->getProductLabelCollection($productLabelCriteriaTransfer);
    }
}
