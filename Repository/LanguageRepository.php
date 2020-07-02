<?php

namespace BastSys\LanguageBundle\Repository;

use BastSys\LanguageBundle\Entity\Language\Language;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class LanguageRepository
 * @package App\CoreBundle\Model\Localisation
 * @author  mirkl
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
}
