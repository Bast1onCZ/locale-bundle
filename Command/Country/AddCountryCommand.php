<?php
declare(strict_types=1);

namespace BastSys\LocaleBundle\Command\Country;

use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\LocaleBundle\Repository\CountryRepository;
use BastSys\LocaleBundle\Repository\CurrencyRepository;
use BastSys\LocaleBundle\Repository\LanguageRepository;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Generator\Generator;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddCountryCommand
 * @package BastSys\LocaleBundle\Command\Country
 * @author mirkl
 */
class AddCountryCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'locale:country:add';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var CountryRepository
     */
    private CountryRepository $countryRepository;
    /**
     * @var CurrencyRepository
     */
    private CurrencyRepository $currencyRepository;
    /**
     * @var LanguageRepository
     */
    private LanguageRepository $languageRepository;
    /**
     * @var DependencyFactory
     */
    private DependencyFactory $dependencyFactory;
    /**
     * @var ExecuteCommand
     */
    private ExecuteCommand $migrationExecuteCommand;

    /**
     * AddCountryCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param CountryRepository $countryRepository
     * @param CurrencyRepository $currencyRepository
     * @param LanguageRepository $languageRepository
     * @param DependencyFactory $dependencyFactory
     * @param ExecuteCommand $migrationExecuteCommand
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CountryRepository $countryRepository,
        CurrencyRepository $currencyRepository,
        LanguageRepository $languageRepository,
        DependencyFactory $dependencyFactory,
        ExecuteCommand $migrationExecuteCommand
    )
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->countryRepository = $countryRepository;
        $this->currencyRepository = $currencyRepository;
        $this->languageRepository = $languageRepository;
        $this->dependencyFactory = $dependencyFactory;
        $this->migrationExecuteCommand = $migrationExecuteCommand;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Adds a country');
        $this->addArgument('code', InputArgument::REQUIRED, 'Country 3 digit code');
        $this->addArgument('alpha2', InputArgument::REQUIRED, 'Country alpha 2 code');
        $this->addArgument('alpha3', InputArgument::REQUIRED, 'Country alpha 3 code');
        $this->addArgument('currencyCode', InputArgument::REQUIRED, 'Currency 3 character code');
        $this->addArgument('mainLanguageCode', InputArgument::REQUIRED, 'Language 3 character code');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \BastSys\UtilsBundle\Exception\Entity\EntityNotFoundByIdException
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $code = $input->getArgument('code');
        $alpha2 = $input->getArgument('alpha2');
        $alpha3 = $input->getArgument('alpha3');
        $currencyCode = $input->getArgument('currencyCode');
        $mainLanguageCode = $input->getArgument('mainLanguageCode');

        if (!preg_match('/^[0-9-]{1,5}$/', $code)) {
            throw new \InvalidArgumentException("Code '$code' must consist of 3 digit characters");
        }
        if (!preg_match('/^[A-Z]{2}$/', $alpha2)) {
            throw new \InvalidArgumentException("Alpha2 '$alpha2' must consist of 2 uppercase characters");
        }
        if (!preg_match('/^[A-Z]{3}$/', $alpha3)) {
            throw new \InvalidArgumentException("Alpha3 '$alpha3' must consist of 3 uppercase characters");
        }
        if (!file_exists(__DIR__ . "/../../Resources/public/flag/$alpha2.png")) {
            throw new \InvalidArgumentException("No flag was found for country '$alpha2' was found. Please contact the author to add this flag.");
        }
        if ($this->countryRepository->findById($alpha2)) {
            throw new \InvalidArgumentException("Country with alpha2 '$alpha2' already exists");
        }

        $currency = $this->currencyRepository->findById($currencyCode, true);
        $language = $this->languageRepository->findById($mainLanguageCode, true);

        $tableName = $this->entityManager->getClassMetadata(Country::class)->getTableName();
        $fqcn = $this->dependencyFactory->getClassNameGenerator()->generateClassName(
            key($this->dependencyFactory->getConfiguration()->getMigrationDirectories())
        );

        $id = Uuid::uuid4();
        $currencyId = $currency->getId();
        $mainLanguageId = $language->getId();
        (new Generator($this->dependencyFactory->getConfiguration()))->generateMigration($fqcn,
            "INSERT INTO `$tableName` (`id`, `alpha2`, `alpha3`, `code`, `currency_id`, `main_language_id`) VALUES ('$id', '$alpha2', '$alpha3', '$code', '$currencyId', '$mainLanguageId')",
            "DELETE FROM `$tableName` WHERE `id` = '$id'");

        $this->migrationExecuteCommand->run(new ArrayInput(['up' => $fqcn]), $output);

        $output->writeln("Created country with alpha2 '$alpha2'");

        return 0;
    }
}
