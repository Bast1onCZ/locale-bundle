<?php
declare(strict_types=1);

namespace BastSys\LocaleBundle\Command\Language;

use BastSys\LocaleBundle\Command\TranslateCommand;
use BastSys\LocaleBundle\Entity\Language\Language;
use BastSys\LocaleBundle\Repository\LanguageRepository;
use BastSys\UtilsBundle\Service\MigrationGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddLanguageCommand
 * @package BastSys\LocaleBundle\Command\Language
 * @author mirkl
 */
class AddLanguageCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'locale:language:add';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var LanguageRepository
     */
    private LanguageRepository $languageRepository;
    /**
     * @var TranslateCommand
     */
    private TranslateCommand $translateCommand;
    /**
     * @var MigrationGenerator
     */
    private MigrationGenerator $migrationGenerator;

    /**
     * AddLanguageCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param LanguageRepository $languageRepository
     * @param TranslateCommand $translateCommand
     * @param MigrationGenerator $migrationGenerator
     */
    public function __construct(EntityManagerInterface $entityManager, LanguageRepository $languageRepository, TranslateCommand $translateCommand, MigrationGenerator $migrationGenerator)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->languageRepository = $languageRepository;
        $this->translateCommand = $translateCommand;
        $this->migrationGenerator = $migrationGenerator;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Adds a language');
        $this->addArgument('code', InputArgument::REQUIRED, 'Language code (e.g. "cs"');
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
        if ($this->languageRepository->findById($code)) {
            throw new \InvalidArgumentException("Language with code '$code' already exists");
        }
        if (!preg_match('/^[a-z]{2}$/', $code)) {
            throw new \InvalidArgumentException("Language code must consist of 2 lowercase characters");
        }

        $tableName = $this->migrationGenerator->getTableName(Language::class);

        $this->migrationGenerator->addUpSql("INSERT INTO `$tableName` (`id`) VALUES ('$code')");
        $this->migrationGenerator->addDownSql("DELETE FROM `$tableName` WHERE `id` = '$code'");

        $this->migrationGenerator->generate();
        $this->migrationGenerator->execute($output);

        $output->writeln("Created language with code '$code'");

        $languageClass = Language::class;
        $output->writeln("Use 'php bin/console locale:entity:translate $languageClass'");

        return 0;
    }
}
