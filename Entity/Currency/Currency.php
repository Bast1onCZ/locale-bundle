<?php

namespace BastSys\LanguageBundle\Entity\Currency;

use BastSys\UtilsBundle\Entity\Identification\IIdentifiableEntity;
use BastSys\UtilsBundle\Model\IEquatable;

/**
 * Class Currency
 * @package BastSys\LanguageBundle\Entity\Currency
 * @author  mirkl
 *
 * @ORM\Entity()
 * @ORM\Table(name="bastsys_locale_bundle__currency")
 */
class Currency implements IIdentifiableEntity, IEquatable
{
    /**
     * @var string
     * @ORM\Column(name="id", type="string", length=3, unique=true)
     * @ORM\Id()
     */
    private $code;

    /**
     * @var string - pattern for the price show - e.g. '{value} Kč' || '€ {value}' || '$ {value}'
     * @ORM\Column(type="string", length=255)
     */
    private $pattern;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     */
    public function setPattern(string $pattern): void
    {
        $this->pattern = $pattern;
    }

    /**
     * @param float $price
     *
     * @return string
     */
    public function getPriceString(float $price): string
    {
        $priceStr = number_format($price, 2, ',', ' ');
        $priceStrWithCurrency = str_replace('{value}', $priceStr, $this->pattern);

        return $priceStrWithCurrency;
    }

    /**
     * @param IEquatable $equatable
     * @return bool
     */
    public function equals($equatable): bool
    {
        return $equatable instanceof Currency && $this->getId() === $equatable->getId();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->code;
    }

}
