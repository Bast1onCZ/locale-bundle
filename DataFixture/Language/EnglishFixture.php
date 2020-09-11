<?php
declare(strict_types=1);

namespace BastSys\LocaleBundle\DataFixture\Language;

use BastSys\LocaleBundle\Entity\Language\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class EnglishFixture
 * @package BastSys\LocaleBundle\DataFixture\Language
 * @author mirkl
 */
class EnglishFixture extends Fixture
{
    const CODE = 'en';

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        if($manager->find(Language::class, self::CODE)) {
            return;
        }

        $en = new Language();
        $en->setCode(self::CODE);

        $manager->persist($en);
        $manager->flush();
    }
}
