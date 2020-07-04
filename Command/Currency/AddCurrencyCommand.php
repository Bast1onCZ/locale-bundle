<?php

namespace BastSys\LocaleBundle\Command\Currency;

use BastSys\LocaleBundle\Command\TranslateCommand;
use BastSys\LocaleBundle\Entity\Currency\Currency;
use BastSys\LocaleBundle\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
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
    private TranslateCommand $translateCommand;

    /**
     * AddCurrencyCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param CurrencyRepository $currencyRepository
     * @param TranslateCommand $translateCommand
     */
    public function __construct(EntityManagerInterface $entityManager, CurrencyRepository $currencyRepository, TranslateCommand $translateCommand)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->currencyRepository = $currencyRepository;
        $this->translateCommand = $translateCommand;
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

        $currency = new Currency();
        $currency->setCode($code);
        $currency->setFormat($format);

        $this->entityManager->persist($currency);
        $this->entityManager->flush();

        $output->writeln("Currency with code '$code' and format '$format' was created");

        return 0;
    }
}
