<?php
declare(strict_types=1);

namespace BastSys\LocaleBundle\Repository;

use BastSys\LocaleBundle\Entity\Currency\Currency;
use BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException;
use BastSys\UtilsBundle\Repository\AEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CurrencyRepository
 * @package BastSys\LocaleBundle\Repository
 * @author mirkl
 */
class CurrencyRepository extends AEntityRepository
{
    /**
     * CurrencyRepository constructor.
     * @param EntityManagerInterface|null $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager = null)
    {
        parent::__construct(Currency::class, $entityManager);
    }

    /**
     * @param string $id
     * @param bool $notFoundError
     * @return Currency|null
     * @throws EntityNotFoundByIdException
     */
    public function findById(string $id, bool $notFoundError = false): ?object
    {
        $currency = $this->getObjectRepository()->findOneBy([
            'code' => $id
        ]);
        if (!$currency && $notFoundError) {
            throw new EntityNotFoundByIdException(Currency::class, $id);
        }

        return $currency;
    }
}
