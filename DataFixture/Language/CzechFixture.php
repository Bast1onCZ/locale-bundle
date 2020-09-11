<?php
declare(strict_types=1);

namespace BastSys\LocaleBundle\DataFixture\Language;

use BastSys\LocaleBundle\Entity\Language\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CzechFixture
 * @package BastSys\LocaleBundle\DataFixture\Language
 * @author mirkl
 */
class CzechFixture extends Fixture
{
    const CODE = 'cs';

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        if($manager->find(Language::class, self::CODE)) {
            return;
        }

        $cs = new Language();
        $cs->setCode(self::CODE);

        $manager->persist($cs);
        $manager->flush();
    }
}
