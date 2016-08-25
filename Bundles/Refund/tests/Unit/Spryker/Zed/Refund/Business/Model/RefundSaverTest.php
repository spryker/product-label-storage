<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Refund\Business\Model;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Orm\Zed\Refund\Persistence\SpyRefund;
use Orm\Zed\Sales\Persistence\SpySalesExpense;
use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Refund\Business\Model\RefundSaver;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Refund
 * @group Business
 * @group Model
 * @group RefundSaverTest
 */
class RefundSaverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var bool
     */
    protected $isCommitSuccessful = true;

    /**
     * @return void
     */
    public function testSaveRefundShouldReturnTrueIfRefundSaved()
    {
        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $refundEntity = $this->getRefundEntity(1);

        $refundSaver = $this->getRefundSaverMock($refundEntity, $salesQueryContainerMock);
        $refundTransfer = new RefundTransfer();

        $this->assertTrue($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldReturnFalseIfRefundNotSaved()
    {
        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaverMock($refundEntity, $salesQueryContainerMock);
        $refundTransfer = new RefundTransfer();

        $this->isCommitSuccessful = false;

        $this->assertFalse($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldSetCanceledAmountOnOrderItemEntities()
    {
        $salesOrderItemEntityMock = $this->getSalesOrderItemEntityMock();

        $salesOrderItemQueryMock = $this->getMock(SpySalesOrderItemQuery::class, ['findOneByIdSalesOrderItem']);
        $salesOrderItemQueryMock->method('findOneByIdSalesOrderItem')->willReturn($salesOrderItemEntityMock);

        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesQueryContainerMock->method('querySalesOrderItem')->willReturn($salesOrderItemQueryMock);

        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaverMock($refundEntity, $salesQueryContainerMock);

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(100);

        $itemTransfer = new ItemTransfer();
        $refundTransfer->addItem($itemTransfer);

        $this->assertTrue($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldSetCanceledAmountOnOrderExpenseEntities()
    {
        $salesExpenseEntityMock = $this->getSalesExpenseEntityMock();

        $salesExpenseQueryMock = $this->getMock(SpySalesExpenseQuery::class, ['findOneByIdSalesExpense']);
        $salesExpenseQueryMock->method('findOneByIdSalesExpense')->willReturn($salesExpenseEntityMock);

        $salesQueryContainerMock = $this->getSalesQueryContainerMock();
        $salesQueryContainerMock->method('querySalesExpense')->willReturn($salesExpenseQueryMock);

        $refundEntity = $this->getRefundEntity(0);

        $refundSaver = $this->getRefundSaverMock($refundEntity, $salesQueryContainerMock);

        $refundTransfer = new RefundTransfer();
        $refundTransfer->setAmount(100);

        $expenseTransfer = new ExpenseTransfer();
        $expenseTransfer->setRefundableAmount(100);
        $refundTransfer->addExpense($expenseTransfer);

        $this->assertTrue($refundSaver->saveRefund($refundTransfer));
    }

    /**
     * @return void
     */
    public function testSaveRefundShouldBuildRefundEntity()
    {
        $refundSaverMock = $this->getRefundSaverMock(null, $this->getSalesQueryContainerMock());

        $refundSaverMock->saveRefund(new RefundTransfer());
    }

    /**
     * @param \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Refund\Persistence\SpyRefund $refundEntity
     * @param \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface $salesQueryContainerMock
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Refund\Business\Model\RefundSaverInterface
     */
    protected function getRefundSaverMock($refundEntity, $salesQueryContainerMock)
    {
        if ($refundEntity) {
            $refundSaverMock = $this->getMock(RefundSaver::class, ['buildRefundEntity', 'findOneByIdSalesOrderItem'], [$salesQueryContainerMock]);
            $refundSaverMock->expects($this->once())->method('buildRefundEntity')->willReturn($refundEntity);
        } else {
            $refundSaverMock = $this->getMock(RefundSaver::class, ['saveRefundEntity', 'updateOrderItems', 'updateExpenses'], [$this->getSalesQueryContainerMock()]);
            $refundSaverMock->expects($this->once())->method('saveRefundEntity');
        }

        return $refundSaverMock;
    }

    /**
     * @param int $affectedColumns
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Refund\Persistence\SpyRefund
     */
    protected function getRefundEntity($affectedColumns)
    {
        $refundEntityMock = $this->getMock(SpyRefund::class);
        $refundEntityMock->method('save')->willReturn($affectedColumns);

        return $refundEntityMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function getSalesQueryContainerMock()
    {
        $salesQueryContainerMock = $this->getMock(SalesQueryContainerInterface::class);
        $salesQueryContainerMock->method('getConnection')->willReturn($this->getPropelConnectionMock());

        return $salesQueryContainerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Sales\Persistence\SpySalesOrderItem
     */
    protected function getSalesOrderItemEntityMock()
    {
        $salesOrderItemEntityMock = $this->getMock(SpySalesOrderItem::class, ['save', 'setCanceledAmount'], [], '', false);
        $salesOrderItemEntityMock->method('save')->willReturn(1);
        $salesOrderItemEntityMock->expects($this->once())->method('setCanceledAmount');

        return $salesOrderItemEntityMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Orm\Zed\Sales\Persistence\SpySalesExpense
     */
    protected function getSalesExpenseEntityMock()
    {
        $salesExpenseEntityMock = $this->getMock(SpySalesExpense::class, ['save', 'setCanceledAmount'], [], '', false);
        $salesExpenseEntityMock->method('save')->willReturn(1);
        $salesExpenseEntityMock->expects($this->once())->method('setCanceledAmount');

        return $salesExpenseEntityMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Propel\Runtime\Connection\ConnectionInterface
     */
    protected function getPropelConnectionMock()
    {
        $propelConnectionMock = $this->getMock(ConnectionInterface::class);
        $propelConnectionMock->method('commit')->willReturnCallback([$this, 'commit']);

        return $propelConnectionMock;
    }

    /**
     * @return bool
     */
    public function commit()
    {
        return $this->isCommitSuccessful;
    }

}
