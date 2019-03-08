<?php

declare(strict_types=1);

namespace Rixafy\Doctrination;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Selectable;
use ReflectionClass;
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

    protected $translation;

    /**
     * @ORM\PostLoad
     * @throws Exception\UnsetLanguageException
     * @throws \ReflectionException
     */
    public function injectTranslation()
    {
        $language = Doctrination::getLanguage();

        if ($this->translation === null) {
            $criteria = Criteria::create()
                ->where(Criteria::expr()->eq('language', $language))
                ->setMaxResults(1);

            $this->translation = $this->getTranslations()->matching($criteria)->first();

            if (!$this->translation) {
                $criteria = Criteria::create()
                    ->where(Criteria::expr()->eq('language', $this->fallback_language))
                    ->setMaxResults(1);

                $this->translation = $this->getTranslations()->matching($criteria)->first();
            }
        }

        $this->injectFields();
    }

    /**
     * @throws \ReflectionException
     */
    protected function injectFields()
    {
        $reflection = new ReflectionClass($this->translation);

        foreach($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $this->{$property->getName()} = $property->getValue();
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