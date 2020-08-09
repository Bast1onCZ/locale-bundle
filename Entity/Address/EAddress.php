<?php
declare(strict_types=1);

namespace BastSys\LocaleBundle\Entity\Address;

use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\LocaleBundle\Structure\IAddress;
use BastSys\UtilsBundle\Entity\EntityManagerAware\IEntityManagerAware;
use BastSys\UtilsBundle\Model\ICloneable;
use BastSys\UtilsBundle\Model\ICopyable;
use BastSys\UtilsBundle\Model\IEquatable;
use BastSys\UtilsBundle\Model\Strings;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class EAddress
 * @package BastSys\LocaleBundle\Entity\Address
 * @author mirkl
 *
 * @ORM\Embeddable()
 * @ORM\HasLifecycleCallbacks()
 */
class EAddress implements IAddress, IEntityManagerAware, ICopyable
{
    const alpha2RE = '/^[A-Z]{2}$/';
    const cityRE = '/^[\w ]+$/';
    const descriptiveNumberRE = '/^[\d\w\/\-]+$/';
    const streetRE = self::cityRE;
    const zipRE = '/^\d{5,}$/';

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $street;
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $descriptiveNumber;
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $zip;
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $countryId;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * EAddress constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function injectEntityManager(EntityManagerInterface $entityManager): void {
        $this->entityManager = $entityManager;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string|null
     */
    public function getDescriptiveNumber(): ?string
    {
        return $this->descriptiveNumber;
    }

    /**
     * @param string|null $descriptiveNumber
     */
    public function setDescriptiveNumber(?string $descriptiveNumber): void
    {
        $this->descriptiveNumber = $descriptiveNumber;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     */
    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }

    /**
     * @param Country|null $country
     */
    public function setCountry(?Country $country): void
    {
        $this->countryId = $country ? $country->getId() : null;
    }

    /**
     * @return Country|null
     */
    public function getCountry(): ?Country
    {
        if(!$this->countryId) {
            return null;
        }
        $countryRepository = $this->entityManager->getRepository(Country::class);
        return $countryRepository->find($this->countryId);
    }

    /**
     * @param IEquatable $comparable
     * @return bool
     */
    public function equals($comparable): bool
    {
        return $comparable instanceof IAddress &&
            $this->city === $comparable->getCity() &&
            $this->street === $comparable->getStreet() &&
            $this->descriptiveNumber === $comparable->getDescriptiveNumber() &&
            $this->zip === $comparable->getZip() &&
            $this->countryId === $comparable->getCountry()->getId();
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->city && $this->street && $this->descriptiveNumber && $this->zip && $this->getCountry() &&
            preg_match(self::cityRE, Strings::accentFold($this->city)) &&
            preg_match(self::streetRE, Strings::accentFold($this->street)) &&
            preg_match(self::descriptiveNumberRE, $this->descriptiveNumber) &&
            preg_match(self::zipRE, $this->zip);
    }

    /**
     * Clones this instance to a new Address entity
     *
     * @return EAddress
     */
    public function clone(): ICloneable
    {
        $address = new EAddress();
        $address->setCountry($this->getCountry());
        $address->setCity($this->city);
        $address->setStreet($this->street);
        $address->setDescriptiveNumber($this->descriptiveNumber);
        $address->setZip($this->zip);
        return $address;
    }

    /**
     * @param ICopyable $instance
     */
    function copyInto(ICopyable $instance): void
    {
        if(!($instance instanceof EAddress)) {
            throw new \InvalidArgumentException();
        }

        $instance->setStreet($this->street);
        $instance->setDescriptiveNumber($this->descriptiveNumber);
        $instance->setCity($this->city);
        $instance->setZip($this->zip);
        $instance->countryId = $this->countryId; // copy countryId, do not try to find country
    }
}

