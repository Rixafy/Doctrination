<?php

declare(strict_types=1);

namespace Rixafy\Doctrination\Language;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="language")
 */
class Language
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid_binary", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $is_active = true;

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

    public function getId(): ?\Ramsey\Uuid\UuidInterface
    {
        return $this->id;
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

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * @param bool $is_active
     */
    public function setIsActive(bool $is_active): void
    {
        $this->is_active = $is_active;
    }
}