<?php

declare(strict_types=1);

namespace Rixafy\Doctrination\Language;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\UniqueTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="language")
 */
class Language
{
    use UniqueTrait;
    use ActiveTrait;

    /**
     * @ORM\Column(type="string", length=31)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=31)
     * @var string
     */
    private $name_original;

    /**
     * @ORM\Column(type="string", length=2, unique=true)
     * @var string
     */
    private $iso;

    public function __construct(LanguageData $languageData)
    {
        $this->iso = $languageData->iso;
        $this->edit($languageData);
    }

    public function edit(LanguageData $languageData): void
    {
        $this->name = $languageData->name;
        $this->name_original = $languageData->nameOriginal;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIso(): string
    {
        return $this->iso;
    }

    public function getNameOriginal(): string
    {
        return $this->name_original;
    }
}