<?php
declare(strict_types=1);

namespace BastSys\LocaleBundle\Structure;

use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\UtilsBundle\Entity\Validation\IValidatable;
use BastSys\UtilsBundle\Model\ICloneable;
use BastSys\UtilsBundle\Model\IEquatable;

/**
 * Interface IAddress
 * @package BastSys\LocaleBundle\Structure
 * @author mirkl
 */
interface IAddress extends IEquatable, IValidatable, ICloneable
{
    /**
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void;

    /**
     * @return Country|null
     */
    public function getCountry(): ?Country;

    /**
     * @param Country|null $country
     */
    public function setCountry(?Country $country): void;

    /**
     * @return string|null
     */
    public function getStreet(): ?string;

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void;

    /**
     * @return string|null
     */
    public function getDescriptiveNumber(): ?string;

    /**
     * @param string|null $descriptiveNumber
     */
    public function setDescriptiveNumber(?string $descriptiveNumber): void;

    /**
     * @return string|null
     */
    public function getZip(): ?string;

    /**
     * @param string|null $zip
     */
    public function setZip(?string $zip): void;
}
