<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Formatter\IntlMoneyFormatter;

use Generated\Shared\Transfer\MoneyTransfer;
use Money\Formatter\IntlMoneyFormatter as InnerFormatter;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\DataMapper\TransferToMoneyConverterInterface;
use Spryker\Shared\Money\Formatter\MoneyFormatterInterface;

abstract class AbstractIntlMoneyFormatter implements MoneyFormatterInterface
{

    /**
     * @var \Spryker\Shared\Money\DataMapper\TransferToMoneyConverterInterface
     */
    protected $converter;

    /**
     * @param \Spryker\Shared\Money\DataMapper\TransferToMoneyConverterInterface $transferToMoneyConverter
     */
    public function __construct(TransferToMoneyConverterInterface $transferToMoneyConverter)
    {
        $this->converter = $transferToMoneyConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function format(MoneyTransfer $moneyTransfer)
    {
        $locale = $this->getLocale($moneyTransfer);
        $formatter = $this->getInnerFormatter($locale);

        $money = $this->converter->convert($moneyTransfer);
        $formatted = $formatter->format($money);

        return $formatted;
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    protected function getLocale(MoneyTransfer $moneyTransfer)
    {
        if ($moneyTransfer->getLocale()) {
            return $moneyTransfer->getLocale()->getLocaleName();
        }

        return Store::getInstance()->getCurrentLocale();
    }

    /**
     * @param string $localeName
     *
     * @return \Money\Formatter\IntlMoneyFormatter
     */
    protected function getInnerFormatter($localeName)
    {
        return new InnerFormatter(
            $this->getNumberFormatter($localeName)
        );
    }

    /**
     * @param string $localeName
     *
     * @return \NumberFormatter
     */
    abstract protected function getNumberFormatter($localeName);

}
