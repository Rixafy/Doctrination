<?php

declare(strict_types=1);

namespace Rixafy\Language;

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
     * @ORM\Column(type="string", length=63)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=63)
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
        $this->name = substr($languageData->name, 0, 63);
        $this->name_original = substr($languageData->nameOriginal, 0, 63);
    }

    public function getData(): LanguageData
	{
		$data = new LanguageData();

		$data->name = $this->name;
		$data->nameOriginal = $this->name_original;

		return $data;
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
