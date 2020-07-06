<?php

namespace BastSys\LocaleBundle\Repository;

use BastSys\LocaleBundle\Entity\Language\Language;
use BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class LanguageRepository
 * @package BastSys\LocaleBundle\Repository
 * @author mirkl
 */
class LanguageRepository extends AEntityRepository
{
    /**
     * LanguageRepository constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct(Language::class, $entityManager);
    }

    /**
     * @param string $id
     * @param bool $notFoundError
     * @return Language|null
     * @throws EntityNotFoundByIdException
     */
    public function findById(string $id, bool $notFoundError = false): ?object
    {
        $language = $this->getObjectRepository()->findOneBy([
            'code' => $id
        ]);
        if (!$language && $notFoundError) {
            throw new EntityNotFoundByIdException(Language::class, $id);
        }

        return $language;
    }
}
