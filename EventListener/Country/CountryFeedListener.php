<?php

namespace BastSys\LocaleBundle\EventListener\Country;

use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\LocaleBundle\Service\CountryFlagService;
use Doctrine\ORM\Event\LifecycleEventArgs;

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
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $this->feedCountry($args->getEntity());
    }

    /**
     * @param object $entity
     */
    private function feedCountry(object $entity)
    {
        if ($entity instanceof Country) {
            $entity->feed($this->countryFlagService);
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->feedCountry($args->getEntity());
    }
}
