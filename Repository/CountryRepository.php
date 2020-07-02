<?php

namespace BastSys\LanguageBundle\Repository;

use BastSys\LanguageBundle\Entity\Country\Country;
use BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use BastSys\UtilsBundle\Repository\FetchAllRepository\IFetchAllRepository;
use BastSys\UtilsBundle\Repository\FetchAllRepository\TFetchAllRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CountryRepository
 * @package BastSys\LanguageBundle\Repository
 * @author mirkl
 */
class CountryRepository extends AEntityRepository implements IFetchAllRepository
{
    use TFetchAllRepository;

    /**
     * CountryRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(Country::class, $entityManager);
    }

    /**
     * @param string $id
     * @param bool $notFoundError
     * @return object|null
     * @throws EntityNotFoundByIdException
     */
    public function findById(string $id, bool $notFoundError = false): ?object
    {
        $country = $this->findByAlpha2Code($id);
        if (!$country && $notFoundError) {
            throw new EntityNotFoundByIdException(Country::class, $id);
        }

        return $country;
    }

    /**
     * @param string $alpha2Code
     *
     * @return Country|null
     */
    public function findByAlpha2Code(string $alpha2Code): ?Country
    {
        $this->tryFetchAll();
        /** @var Country|null $country */
        $country = $this->repository->findOneBy([
            'alpha2' => $alpha2Code
        ]);
        return $country;
    }
}
