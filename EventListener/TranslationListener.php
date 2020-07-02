<?php

namespace BastSys\LocaleBundle\EventListener;

use BastSys\LanguageBundle\Entity\Translation\ITranslatable;
use BastSys\LanguageBundle\Entity\Translation\ITranslation;
use BastSys\LanguageBundle\Repository\LanguageRepository;
use BastSys\LanguageBundle\Service\ILocaleService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class TranslationListener
 * @package BastSys\LocaleBundle\EventListener
 * @author mirkl
 */
class TranslationListener
{
    /**
     * @var ILocaleService
     */
    private $localeService;

    /**
     * @var LanguageRepository
     */
    private $languageRepository;

    /**
     * TranslationListener constructor.
     *
     * @param ILocaleService $localeService
     * @param LanguageRepository $languageRepository
     */
    public function __construct(ILocaleService $localeService, LanguageRepository $languageRepository)
    {
        $this->localeService = $localeService;
        $this->languageRepository = $languageRepository;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
            Events::postLoad,
            Events::prePersist
        ];
    }

    /**
     * Maps doctrine ITranslatable and ITranslation relationships
     *
     * @param LoadClassMetadataEventArgs $event
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        $classMetadata = $event->getClassMetadata();
        $reflection = $classMetadata->getReflectionClass();

        if (empty($reflection) || $reflection->isAbstract()) {
            return;
        }

        if ($reflection->implementsInterface(ITranslatable::class)) {
            $this->mapTranslatable($classMetadata);
        }

        if ($reflection->implementsInterface(ITranslation::class)) {
            $this->mapTranslation($classMetadata);
        }
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    private function mapTranslatable(ClassMetadata $classMetadata)
    {
        $className = $classMetadata->getName();

        $classMetadata->mapOneToMany([
            'fieldName' => 'translations',
            'targetEntity' => $className . 'Translation',
            'mappedBy' => 'translatable',
            'fetch' => ClassMetadataInfo::FETCH_EXTRA_LAZY,
            'indexBy' => 'language_id',
            'cascade' => ['persist', 'merge', 'remove'],
            'orphanRemoval' => true,
        ]);
    }

    /**
     * @param ClassMetadata $classMetadata
     */
    private function mapTranslation(ClassMetadata $classMetadata)
    {
        $className = $classMetadata->getName();

        $classMetadata->mapManyToOne([
            'fieldName' => 'translatable',
            'targetEntity' => substr($className, 0, -strlen('Translation')),
            'inversedBy' => 'translations',
            'joinColumns' => [[
                'name' => 'translatable_id',
                'referencedColumnName' => 'id',
                'onDelete' => 'CASCADE',
                'nullable' => false,
            ]],
        ]);
    }

    /**
     * Passes translatable info to ITranslatable instances on postLoad
     *
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof ITranslatable) {
            $this->feedTranslatable($entity);
        }
    }

    /**
     * @param ITranslatable $translatable
     */
    private function feedTranslatable(ITranslatable $translatable)
    {
        $translatable->feedTranslatable($this->localeService, $this->languageRepository);
    }

    /**
     * Passes translatable info to ITranslatable instances on prePersist
     *
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof ITranslatable) {
            $this->feedTranslatable($entity);
        }
    }
}
