<?php

namespace App\Service\Manager\Base;

use App\Service\DataMapper;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractResource
{
    /** @var ManagerRegistry */
    private $doctrine;

    /** @var DataMapper */
    private $dataMapper;

    abstract protected function getEntityClassName(): string;

    public function getList(array $filters = [], ?int $page = null, ?int $itemsPerPage = null)
    {
        $offset = null !== $page ? ($page - 1) * $itemsPerPage : null;

        return $this->doctrine->getRepository($this->getEntityClassName())->findBy($filters, null, $itemsPerPage, $offset);
    }

    public function create(array $data)
    {
        $entity = $this->dataMapper->toEntity($this->getEntityClassName(), $data);

        $this->doctrine->getManager()->persist($entity);
        $this->doctrine->getManager()->flush();
    }

    public function count(array $filters = []): int
    {
        return $this->doctrine->getRepository($this->getEntityClassName())->count($filters);
    }

    public function paginationData(int $page, int $itemsPerPage, array $filters = []): array
    {
        $count = $this->count($filters);

        $pages = intval($count / $itemsPerPage);

        if ($count % $itemsPerPage != 0)
            ++$pages;

        return [
            'totalCount'        => $count,
            'page'              => $page,
            'pages'             => $pages,
        ];
    }

    /**
     * @required
     * @param ManagerRegistry $doctrine
     */
    public function setServices(ManagerRegistry $doctrine, DataMapper $mapper)
    {
        $this->doctrine = $doctrine;
        $this->dataMapper = $mapper;
    }

    protected function getDataMapper(): DataMapper
    {
        return $this->dataMapper;
    }
}
