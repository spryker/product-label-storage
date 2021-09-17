<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabelStorage\Storage;

use Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer;
use Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory;

class LabelDictionaryReader implements LabelDictionaryReaderInterface
{
    /**
     * @var \Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory
     */
    protected $dictionaryFactory;

    /**
     * @param \Spryker\Client\ProductLabelStorage\Storage\Dictionary\DictionaryFactory $dictionaryFactory
     */
    public function __construct(DictionaryFactory $dictionaryFactory)
    {
        $this->dictionaryFactory = $dictionaryFactory;
    }

    /**
     * @param array<int> $idsProductLabel
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    public function findSortedLabelsByIdsProductLabel(array $idsProductLabel, $localeName, string $storeName)
    {
        $productLabelCollection = $this->getProductLabelsFromDictionary($idsProductLabel, $localeName, $storeName);
        $productLabelCollection = $this->sortCollection($productLabelCollection);
        $productLabelCollection = $this->extractExclusive($productLabelCollection);

        return $productLabelCollection;
    }

    /**
     * @param int $idProductLabel
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByIdProductLabel($idProductLabel, $localeName, string $storeName)
    {
        return $this->dictionaryFactory
            ->createDictionaryByIdProductLabel()
            ->findLabel($idProductLabel, $localeName, $storeName);
    }

    /**
     * @param string $labelName
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByLocalizedName($labelName, $localeName, string $storeName)
    {
        return $this->dictionaryFactory
            ->createDictionaryByLocalizedName()
            ->findLabel($labelName, $localeName, $storeName);
    }

    /**
     * @param string $labelName
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer|null
     */
    public function findLabelByName($labelName, $localeName, string $storeName)
    {
        return $this->dictionaryFactory
            ->createDictionaryByName()
            ->findLabel($labelName, $localeName, $storeName);
    }

    /**
     * @param array<int> $idsProductLabel
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    protected function getProductLabelsFromDictionary(array $idsProductLabel, $localeName, string $storeName)
    {
        $dictionary = $this->dictionaryFactory
            ->createDictionaryByIdProductLabel()
            ->getDictionary($localeName, $storeName);

        $productLabelCollection = [];

        foreach ($idsProductLabel as $idProductLabel) {
            if (!array_key_exists($idProductLabel, $dictionary)) {
                continue;
            }

            $productLabelCollection[] = $dictionary[$idProductLabel];
        }

        return $productLabelCollection;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer> $productLabelDictionaryItemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    protected function extractExclusive(array $productLabelDictionaryItemTransfers)
    {
        if (count($productLabelDictionaryItemTransfers) <= 1) {
            return $productLabelDictionaryItemTransfers;
        }

        foreach ($productLabelDictionaryItemTransfers as $productLabelDictionaryItemTransfer) {
            if ($productLabelDictionaryItemTransfer->getIsExclusive()) {
                return [$productLabelDictionaryItemTransfer];
            }
        }

        return $productLabelDictionaryItemTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer> $productLabelDictionaryItemTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductLabelDictionaryItemTransfer>
     */
    protected function sortCollection(array $productLabelDictionaryItemTransfers)
    {
        if (count($productLabelDictionaryItemTransfers) <= 1) {
            return $productLabelDictionaryItemTransfers;
        }

        usort($productLabelDictionaryItemTransfers, function (
            ProductLabelDictionaryItemTransfer $productLabelTransferA,
            ProductLabelDictionaryItemTransfer $productLabelTransferB
        ) {
            return ($productLabelTransferA->getPosition() > $productLabelTransferB->getPosition()) ? 1 : -1;
        });

        return $productLabelDictionaryItemTransfers;
    }
}
