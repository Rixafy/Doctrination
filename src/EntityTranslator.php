<?php

declare(strict_types=1);

namespace Rixafy\Doctrination;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use ReflectionObject;
use ReflectionProperty;
use \Rixafy\Doctrination\Language\Language;

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

    /**
     * @ORM\PostLoad
     * @throws Exception\UnsetLanguageException
     */
    public function loadTranslations()
    {
        $language = Doctrination::getLanguage();
        $translation = null;

        foreach ($this->getTranslatableColumns() as $key => $value) {
            if ($translation === null) {
                $criteria = Criteria::create()
                    ->where(Criteria::expr()->eq('language', $language))
                    ->setMaxResults(1);

                $translation = $this->getTranslations()->matching($criteria)->first();

                if (!$translation) {
                    $criteria = Criteria::create()
                        ->where(Criteria::expr()->eq('language', $this->fallback_language))
                        ->setMaxResults(1);

                    $translation = $this->getTranslations()->matching($criteria)->first();
                }
            }

            $this->$key = $translation->{'get' . str_replace('_', '', ucwords($key, '_'))}();
        }
    }

    /**
     * @return \Generator
     */
    public function getTranslatableColumns(): \Generator
    {
        $reflect = new ReflectionObject($this);

        foreach ($reflect->getProperties(ReflectionProperty::IS_PROTECTED) as $prop) {
            if (strpos($prop->getDocComment(), '@Translatable') !== false) {
                yield $prop->getName() => $this->{$prop->getName()};
            }
        }
    }

    /**
     * @param \Rixafy\Doctrination\Language\Language $language
     */
    protected function configureFallbackLanguage(Language $language)
    {
        if ($this->fallback_language === null) {
            $this->fallback_language = $language;
        }
    }

    /**
     * @return Selectable
     */
    public abstract function getTranslations();
}