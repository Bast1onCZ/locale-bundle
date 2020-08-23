<?php

namespace BastSys\LocaleBundle\EventListener\Country;

use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\LocaleBundle\Service\CountryFlagService;

/**
 * Class CountryFeedListener
 * @package BastSys\LocaleBundle\EventListener\Country
 * @author mirkl
 */
class CountryFeedListener
{
    /**
     * @var CountryFlagService
     */
    private CountryFlagService $countryFlagService;

    /**
     * CountryFeedListener constructor.
     * @param CountryFlagService $countryFlagService
     */
    public function __construct(CountryFlagService $countryFlagService)
    {
        $this->countryFlagService = $countryFlagService;
    }

    /**
     * @param Country $country
     */
    public function postLoad(Country $country)
    {
        $this->feedCountry($country);
    }

    /**
     * @param Country $country
     */
    public function postPersist(Country $country)
    {
        $this->feedCountry($country);
    }

    /**
     * @param Country $entity
     */
    private function feedCountry(Country $entity)
    {
        $entity->feed($this->countryFlagService);
    }
}
