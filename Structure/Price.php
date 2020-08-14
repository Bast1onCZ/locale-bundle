<?php
declare(strict_types=1);

namespace BastSys\LocaleBundle\Structure;

use BastSys\LocaleBundle\Entity\Currency\Currency;
use BastSys\UtilsBundle\Model\ICloneable;
use BastSys\UtilsBundle\Model\IEquatable;

/**
 * Class Price
 * @package BastSys\LocaleBundle\Structure
 * @author mirkl
 */
class Price implements IEquatable, ICloneable
{
    /**
     * Summarizes all given prices.
     * Null values are ignored.
     *
     * @param Price|null ...$prices
     * @return Price|null null if all given prices are null
     */
    public static function sum(?Price ...$prices): ?Price {
        $currency = array_reduce($prices, function(?Currency $currency, ?Price $item) {
            if(!$currency) {
                return $item ? $item->getCurrency() : null;
            } else {
                if($item && !$item->getCurrency()->equals($currency)) {
                    throw new \InvalidArgumentException('Contains prices in different currencies');
                }
                return $currency;
            }
        }, null);
        if(!$currency) {
            return null;
        }

        $sum = array_reduce($prices, function (float $sum, ?Price $item) {
            if($item) {
                return $sum + $item->getValue();
            }
            return $sum;
        }, 0);

        return new Price($sum, $currency);
    }

    /** @var float */
    private $value;
    /** @var Currency */
    private $currency;

    /**
     * Price constructor.
     *
     * @param float  $value
     * @param Currency $currency
     */
    public function __construct(float $value, Currency $currency)
    {
        $this->value = round($value, 2);
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * Adds given price to this price.
     * Creates a new instance.
     *
     * @param Price|null $price if not given, 0 is considered
     * @return Price new instance
     */
    public function add(?Price $price): Price {
        if(!$price) {
            return $this->clone();
        }

        if(!$this->currency->equals($price->getCurrency())) {
            throw new \InvalidArgumentException('Prices are not of the same currency');
        }

        return new Price($this->value + $price->getValue(), $this->currency);
    }

    /**
     * @param Price|null $price
     * @return Price
     */
    public function subtract(?Price $price): Price {
        if(!$price) {
            return $this->clone();
        }

        if(!$this->currency->equals($price->getCurrency())) {
            throw new \InvalidArgumentException('Prices are not of the same currency');
        }

        return new Price($this->value - $price->getValue(), $this->currency);
    }

    /**
     * Multiplies a price.
     * Creates a new instance.
     *
     * @param float $multiplier
     * @return Price new instance
     */
    public function multiply(float $multiplier): Price {
        $roundedValue = round($this->value * $multiplier, 2);

        return new Price($roundedValue, $this->currency);
    }

    /**
     * @return string - formatted currency price string
     */
    public function __toString()
    {
        return $this->currency->getPriceString($this->value);
    }

    /**
     * @param IEquatable $comparable
     * @return bool
     */
    public function equals($comparable): bool
    {
        return $comparable instanceof Price &&
            $this->value === $comparable->getValue() &&
            $this->currency->getId() === $comparable->getCurrency()->getId();
    }

    /**
     * @return Price
     */
    public function clone(): ICloneable
    {
        return new Price($this->value, $this->currency);
    }

}
