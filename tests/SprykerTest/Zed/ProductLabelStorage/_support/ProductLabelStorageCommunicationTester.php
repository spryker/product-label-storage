<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductLabelStorage;

use Codeception\Actor;
use DateTime;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductAbstractLabelStorageQuery;
use Orm\Zed\ProductLabelStorage\Persistence\SpyProductLabelDictionaryStorageQuery;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class ProductLabelStorageCommunicationTester extends Actor
{
    use _generated\ProductLabelStorageCommunicationTesterActions;

    public function clearProductAbstractLabelStorage(): void
    {
        SpyProductLabelDictionaryStorageQuery::create()->deleteAll();
    }

    public function deleteProductAbstractLabelStorageByIdProductAbstract(int $idProductAbstract): void
    {
        SpyProductAbstractLabelStorageQuery::create()
            ->filterByFkProductAbstract($idProductAbstract)
            ->delete();
    }

    public function getProductAbstractLabelStorageCount(): int
    {
        return SpyProductAbstractLabelStorageQuery::create()->count();
    }

    public function getProductLabelsCountByStoreNameAndLocaleName(string $storeName, string $localeName): int
    {
        return SpyProductLabelQuery::create()
            ->filterByValidTo(null)
            ->_or()
            ->filterByValidTo((new DateTime())->format('Y-m-d H:i:s'), Criteria::GREATER_EQUAL)
            ->useSpyProductLabelLocalizedAttributesQuery()
                ->useSpyLocaleQuery()
                    ->filterByLocaleName($localeName)
                ->endUse()
            ->endUse()
            ->useProductLabelStoreQuery()
                ->useStoreQuery()
                    ->filterByName($storeName)
                ->endUse()
            ->endUse()
            ->count();
    }
}
