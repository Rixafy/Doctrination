<?php

declare(strict_types=1);

namespace Rixafy\Language;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use Rixafy\Doctrination\Language\Exception\LanguageNotFoundException;
use Rixafy\Doctrination\Language\Language;

class LanguageRepository
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return EntityRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository(Language::class);
    }

    /**
     * @param string $id
     * @return Language
     * @throws LanguageNotFoundException
     */
    public function get(string $id): Language
    {
        $language = $this->find($id);

        if ($language === null) {
            throw new LanguageNotFoundException('Language with id ' . $id . ' not found.');
        }

        return $language;
    }

    public function find(string $id): ?Language
    {
        return $this->getQueryBuilderForAll()
            ->andWhere('l.id = :id')->setParameter('id', Uuid::fromString($id))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQueryBuilderForAll(): QueryBuilder
    {
        return $this->getRepository()->createQueryBuilder('b')
            ->where('l.is_active = :active')->setParameter('active', true);
    }
}