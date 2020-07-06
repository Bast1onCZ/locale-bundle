<?php

namespace BastSys\LocaleBundle\Service;

use BastSys\LocaleBundle\Entity\Country\Country;
use Symfony\Component\Asset\Packages;

/**
 * Class CountryFlagService
 *
 * @package BastSys\LocaleBundle\Service
 * @author mirkl
 */
class CountryFlagService
{
    private Packages $packages;

    /**
     * CountryFlagService constructor.
     * @param Packages $packages
     */
    public function __construct(Packages $packages)
    {
        $this->packages = $packages;
    }

    /**
     * @param Country $country
     * @return string
     */
    public function generateFlagUrl(Country $country): string
    {
        $alpha2 = $country->getAlpha2();

        return $this->packages->getUrl("bundles/locale/flag/$alpha2.png");
    }
}
