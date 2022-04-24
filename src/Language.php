<?php

declare(strict_types=1);

namespace Rixafy\Language;

use Doctrine\ORM\Mapping as ORM;
use Rixafy\DoctrineTraits\ActiveTrait;
use Rixafy\DoctrineTraits\UniqueUlidTrait;

#[ORM\Entity]
#[ORM\Table(name: 'language')]
class Language
{
	use UniqueUlidTrait;
    use ActiveTrait;

	#[ORM\Column(length: 63)]
    private string $name;

	#[ORM\Column(length: 63)]
    private string $nameOriginal;

	#[ORM\Column(length: 2, unique: true)]
    private string $iso;

    public function __construct(LanguageData $languageData)
    {
        $this->iso = $languageData->iso;
        $this->edit($languageData);
    }

    public function edit(LanguageData $languageData): void
    {
        $this->name = substr($languageData->name, 0, 63);
        $this->nameOriginal = substr($languageData->nameOriginal, 0, 63);
    }

    public function getData(): LanguageData
	{
		$data = new LanguageData();

		$data->name = $this->name;
		$data->nameOriginal = $this->nameOriginal;

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
        return $this->nameOriginal;
    }
}
