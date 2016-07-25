<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Money\Converter;

use Spryker\Shared\Money\Exception\InvalidConverterArgumentException;

class DecimalToCentConverter implements DecimalToCentConverterInterface
{

    const PRICE_PRECISION = 100;

    /**
     * @param float $value
     *
     * @return int
     */
    public function convert($value)
    {
        if (!is_float($value)) {
            throw new InvalidConverterArgumentException(sprintf(
                'Only float values allowed for conversion to int. Current type is "%s"',
                gettype($value)
            ));
        }

        return (int)($value * static::PRICE_PRECISION);
    }

}
