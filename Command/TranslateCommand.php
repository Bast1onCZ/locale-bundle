<?php

namespace BastSys\LocaleBundle\Command;

use BastSys\LocaleBundle\Entity\Language\Language;
use BastSys\LocaleBundle\Entity\Translation\ITranslatable;
use BastSys\LocaleBundle\Entity\Translation\ITranslation;
use BastSys\LocaleBundle\Repository\LanguageRepository;
use BastSys\UtilsBundle\Model\Arrays;
use BastSys\UtilsBundle\Model\Strings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class TranslateCommand
 * @package BastSys\LocaleBundle\Command
 * @author mirkl
 */
class TranslateCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'locale:entity:translate';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var LanguageRepository
     */
    private LanguageRepository $languageRepo;

    /**
     * TranslateCommand constructor.
     * @param EntityManagerInterface $entityManager
     * @param LanguageRepository $languageRepo
     */
    public function __construct(EntityManagerInterface $entityManager, LanguageRepository $languageRepo)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->languageRepo = $languageRepo;
    }

    /**
     *
     */
    protected function configure()
    {
        $this->setDescription('Starts a session to translate all entities of one entity class');
        $this->addArgument('entityClass', InputArgument::REQUIRED, 'Which entity class instanced should be translated');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Doctrine\ORM\Mapping\MappingException
     * @throws \ReflectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $translatableClass = $input->getArgument('entityClass');
        $translationClass = $translatableClass . 'Translation';

        $repo = $this->entityManager->getRepository($translatableClass);
        if (!$repo) {
            throw new \InvalidArgumentException('Given class does not exist or is not a valid entity class');
        }

        $translatableRef = new \ReflectionClass($translatableClass);
        if (!$translatableRef->implementsInterface(ITranslatable::class)) {
            throw new \InvalidArgumentException('Entity class does not implement ITranslatable');
        }

        $translationRef = new \ReflectionClass($translationClass);
        if (!$translationRef->implementsInterface(ITranslation::class)) {
            throw new \InvalidArgumentException("Translation entity  '$translationClass' does not implement ITranslation");
        }

        $translationFieldNames = [];
        $translationMeta = $this->entityManager->getClassMetadata($translationClass);
        foreach ($translationMeta->getFieldNames() as $fieldName) {
            $fieldMeta = $translationMeta->getFieldMapping($fieldName);
            if ($fieldMeta['type'] === 'string') {
                $translationFieldNames[] = $fieldName;
            }
        }

        /** @var ITranslatable[] $entities */
        $entities = $repo->findAll();
        /** @var Language[] $languages */
        $languages = $this->languageRepo->findAll();

        $questionHelper = $this->getHelper('question');

        foreach ($entities as $entity) {
            $translations = $entity->getTranslations();
            $emptyLanguages = array_filter($languages, function (Language $language) use ($translations) {
                return !Arrays::some($translations, function (ITranslation $translation) use ($language) {
                    return $translation->getLanguage()->equals($language);
                });
            });
            foreach ($emptyLanguages as $emptyLanguage) {
                $translations[] = $entity->getTranslation($emptyLanguage->getCode());
            }

            if (
                !count($emptyLanguages) &&
                Arrays::all($translations, function (ITranslation $translation) use ($translationFieldNames) {
                    return Arrays::all($translationFieldNames, function (string $translationFieldName) use ($translation) {
                        $getter = Strings::getGetterName($translationFieldName);
                        return !!$translation->$getter();
                    });
                })
            ) {
                continue;
            }

            $output->writeln("Translating entity '$translatableClass' with:");
            $translatableMeta = $this->entityManager->getClassMetadata($translatableClass);
            foreach ($translatableMeta->getFieldNames() as $fieldName) {
                $getter = Strings::getGetterName($fieldName);
                if (method_exists($translatableClass, $getter)) {
                    $output->writeln(" - $fieldName: " . $entity->$getter());
                }
            }

            foreach ($translations as $translation) {
                foreach ($translationFieldNames as $translationFieldName) {
                    $getter = Strings::getGetterName($translationFieldName);
                    $value = $translation->$getter();

                    if (!$value) {
                        $translationLanguageCode = $translation->getLanguage()->getCode();
                        $translationValue = $questionHelper->ask($input, $output,
                            new Question("$translationFieldName ($translationLanguageCode): ")
                        );
                        $setter = Strings::getSetterName($translationFieldName);
                        $output->writeln("Setting: $translationValue");
                        $translation->$setter($translationValue);
                    }
                }
            }

            $output->writeln('Entity is now fully translated');
            $output->writeln('--------------------------------');
        }

        if ($this->entityManager->isOpen()) {
            $this->entityManager->flush();
        }

        if (count($entities) > 0) {
            $output->writeln('Translated all entities');
        } else {
            $output->writeln('There is no entity with missing translation');
        }

        return 0;
    }
}
