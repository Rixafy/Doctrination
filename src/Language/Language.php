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

    public function __construct(string $iso)
    {
        $this->iso = $iso;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIso(): string
    {
        return $this->iso;
    }

    /**
     * @param string $iso
     */
    public function setIso(string $iso): void
    {
        $this->iso = $iso;
    }

    /**
     * @return string
     */
    public function getNameOriginal(): string
    {
        return $this->name_original;
    }

    /**
     * @param string $name_original
     */
    public function setNameOriginal(string $name_original): void
    {
        $this->name_original = $name_original;
    }
}