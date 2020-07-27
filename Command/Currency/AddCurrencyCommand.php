<?php

namespace BastSys\LocaleBundle\Command\Currency;

use BastSys\LocaleBundle\Command\TranslateCommand;
use BastSys\LocaleBundle\Entity\Country\Country;
use BastSys\LocaleBundle\Entity\Currency\Currency;
use BastSys\LocaleBundle\Repository\CurrencyRepository;
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
 * Class AddCurrencyCommand
 * @package BastSys\LocaleBundle\Command\Currency
 * @author mirkl
 */
class AddCurrencyCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'locale:currency:add';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var CurrencyRepository
     */
    private CurrencyRepository $currencyRepository;
    /**
     * @var TranslateCommand
     */
    private TranslateCommand $translateCommand;
    /**
     * @var DependencyFactory
     */
    private DependencyFactory $dependencyFactory;
    /**
     * @var ExecuteCommand
     */
    private ExecuteCommand $executeCommand;

    /**
     * AddCurrencyCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param CurrencyRepository $currencyRepository
     * @param TranslateCommand $translateCommand
     * @param DependencyFactory $dependencyFactory
     * @param ExecuteCommand $executeCommand
     */
    public function __construct(EntityManagerInterface $entityManager, CurrencyRepository $currencyRepository, TranslateCommand $translateCommand, DependencyFactory $dependencyFactory, ExecuteCommand $executeCommand)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->currencyRepository = $currencyRepository;
        $this->translateCommand = $translateCommand;
        $this->dependencyFactory = $dependencyFactory;
        $this->executeCommand = $executeCommand;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Adds a currency');
        $this->addArgument('code', InputArgument::REQUIRED, 'Currency code (e.g. EUR)');
        $this->addArgument('format', InputArgument::REQUIRED, 'Currency format (e.g. "€ {value}")');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $code = $input->getArgument('code');
        if ($this->currencyRepository->getObjectRepository()->findOneBy(['code' => $code])) {
            throw new \InvalidArgumentException("Currency code '$code' already exists");
        }
        if (!preg_match('/^[A-Z]{3}$/', $code)) {
            throw new \InvalidArgumentException('Currency code must consist of 3 uppercase characters');
        }

        $format = $input->getArgument('format');
        if (!preg_match('/{value}/', $format)) {
            throw new \InvalidArgumentException("Currency format does not contain '{value}' variable");
        }

        $tableName = $this->entityManager->getClassMetadata(Currency::class)->getTableName();
        $fqcn = $this->dependencyFactory->getClassNameGenerator()->generateClassName(
            key($this->dependencyFactory->getConfiguration()->getMigrationDirectories())
        );

        $id = Uuid::uuid4();
        (new Generator($this->dependencyFactory->getConfiguration()))->generateMigration($fqcn,
            "INSERT INTO `$tableName` (`code`, `format`) VALUES ('$code', '$format')",
            "DELETE FROM `$tableName` WHERE `id` = '$id'");

        $this->executeCommand->run(new ArrayInput(['up' => $fqcn]), $output);

        $output->writeln("Currency with code '$code' and format '$format' was created");

        return 0;
    }
}
