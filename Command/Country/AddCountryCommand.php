<?php
declare(strict_types=1);

namespace BastSys\LocaleBundle\Command\Country;

use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\LocaleBundle\Repository\CountryRepository;
use BastSys\LocaleBundle\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddCountryCommand extends Command
{
    protected static $defaultName = 'locale:country:add';

    private EntityManagerInterface $entityManager;
    private CountryRepository $countryRepository;
    private CurrencyRepository $currencyRepository;

    protected function configure()
    {
        $this->setDescription('Adds a country');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $country = new Country();
    }
}
