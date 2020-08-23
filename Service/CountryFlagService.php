<?php

namespace BastSys\LocaleBundle\Service;

use BastSys\LocaleBundle\Entity\Country\Country;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class CountryFlagService
 *
 * @package BastSys\LocaleBundle\Service
 * @author mirkl
 */
class CountryFlagService
{
    private Packages $packages;
    private RequestStack $requestStack;

    /**
     * CountryFlagService constructor.
     * @param Packages $packages
     * @param RequestStack $requestStack
     */
    public function __construct(Packages $packages, RequestStack $requestStack)
    {
        $this->packages = $packages;
        $this->requestStack = $requestStack;
    }

    /**
     * @param Country $country
     * @return string
     */
    public function generateFlagUrl(Country $country): string
    {
        $alpha2 = $country->getAlpha2();

        $baseUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $packagePath =  $this->packages->getUrl("bundles/locale/flag/$alpha2.png");

        return $baseUrl . $packagePath;
    }
}
