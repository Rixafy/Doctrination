<?php

declare(strict_types=1);

namespace Rixafy\Doctrination;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use ReflectionClass;
use Rixafy\Doctrination\Exception\TranslationNotFoundException;
use Rixafy\Doctrination\Language\Exception\LanguageNotProvidedException;
use Rixafy\Doctrination\Language\Language;
use Rixafy\Doctrination\Language\LanguageStaticHolder;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class EntityTranslator
{
    /**
     * Many Stores have One Language.
     * @ORM\ManyToOne(targetEntity="\Rixafy\Doctrination\Language\Language", inversedBy="entity")
     * @var \Rixafy\Doctrination\Language\Language
     */
    protected $fallback_language;

    /** @var object */
    protected $translation;

    /** @var ArrayCollection */
    protected $translations;

    /** @var Language */
    protected $translationLanguage;

    /**
     * @ORM\PostLoad
     * @throws LanguageNotProvidedException
     */
    public function injectDefaultTranslation(): void
    {
        $language = LanguageStaticHolder::getLanguage();

        if ($this->translation === null) {
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq('language', $language))
                ->setMaxResults(1);

            $this->translation = $this->getTranslations()->matching($criteria)->first();
            $this->translationLanguage = $language;

            if (!$this->translation) {
                $criteria = Criteria::create()
                    ->where(Criteria::expr()->eq('language', $this->fallback_language))
                    ->setMaxResults(1);

                $this->translation = $this->getTranslations()->matching($criteria)->first();
                $this->translationLanguage = $this->fallback_language;
            }
        }

        try {
            $this->injectFields();
        } catch (\ReflectionException | TranslationNotFoundException $e) {
        }
    }

    /**
     * @throws \ReflectionException
     * @throws TranslationNotFoundException
     */
    protected function injectFields(): void
    {
        if ($this->translation == null) {
            throw new TranslationNotFoundException('Translation for ' . get_class($this) . ' not found');
        }

        $reflection = new ReflectionClass($this->translation);

        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();

            if ($propertyName === 'id' || $propertyName == 'language' || $propertyName == 'entity') {
                continue;
            }

            $property->setAccessible(true);
            $this->{$propertyName} = $property->getValue($this->translation);
        }
    }

    /**
     * @deprecated
     *
     * @param \Rixafy\Doctrination\Language\Language $language
     */
    protected function configureFallbackLanguage(Language $language): void
    {
        if ($this->fallback_language === null) {
            $this->fallback_language = $language;
        }
    }

    public function addTranslation($dataObject, Language $language)
    {
        $class = get_class($this) . 'Translation';
        $translation = new $class($dataObject, $language, $this);

        $this->translations->add($translation);

        if ($this->fallback_language === null) {
            $this->fallback_language = $language;
        }

        return $translation;
    }

    public function editTranslation($dataObject, Language $language = null)
    {
        if ($language === null && isset($dataObject->language)) {
            $language = $dataObject->language;
        } elseif ($language === null) {
            return null;
        }

        if ($this->translation !== null && $language === $this->translationLanguage) {
            $this->updateTranslationFields($dataObject, $this->translation);
            try {
                $this->injectFields();
            } catch (\ReflectionException | TranslationNotFoundException $ignored) {
            }
        } else {
            if ($this->fallback_language === null) {
                $this->fallback_language = $language;
                $this->translation = $this->addTranslation($dataObject, $language);
                $this->translationLanguage = $language;
                try {
                    $this->injectFields();
                } catch (\ReflectionException | TranslationNotFoundException $ignored) {
                }
            } else {
                $translation = $this->getTranslation($language);

                if ($translation === null) {
                    $translation = $this->addTranslation($dataObject, $language);

                    try {
                        if ($language === LanguageStaticHolder::getLanguage()) {
                            $this->translation = $translation;
                            $this->translationLanguage = $language;
                            try {
                                $this->injectFields();
                            } catch (\ReflectionException | TranslationNotFoundException $ignored) {
                            }
                        }
                    } catch (LanguageNotProvidedException $ignored) {
                    }
                } else {
                    $this->updateTranslationFields($dataObject, $translation);
                }

                return $translation;
            }
        }

        return $this->translation;
    }

    public function getTranslation(Language $language) {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('language', $language))
            ->setMaxResults(1);

        return $this->getTranslations()->matching($criteria)->first();
    }

    /**
     * @return Selectable
     */
    public abstract function getTranslations();

    private function updateTranslationFields($dataObject, $translation): void
    {
        if (method_exists($translation, 'edit')) {
            $translation->edit($dataObject);
        } else {
            try {
                $reflection = new ReflectionClass($translation);

                foreach ($reflection->getProperties() as $property) {
                    $propertyName = $property->getName();
                    if ($propertyName == 'id' || $propertyName == 'language' || $propertyName == 'entity') {
                        continue;
                    }

                    $camelKey = lcfirst(str_replace('_', '', ucwords($propertyName, '_')));
                    if (isset($dataObject->{$camelKey})) {
                        $value = $dataObject->{$camelKey};
                        $property->setAccessible(true);
                        $property->setValue($translation, $value);
                    }
                }
            } catch (\ReflectionException $ignored) {
            }
        }
    }
}