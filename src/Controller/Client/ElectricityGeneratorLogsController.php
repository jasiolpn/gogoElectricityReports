<?php

namespace App\Controller\Client;

use App\Service\DataMapper;
use App\Service\Manager\LogEntryResource;
use App\Traits\ControllerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ElectricityGeneratorLogsController extends AbstractController
{
    use ControllerTrait;

    /**
     * @Route("/log-entries/{filtersString}", name="log_entities_list", requirements={"filtersString"=".+"})
     * @param DataMapper $dataMapper
     * @param Request $request
     * @param LogEntryResource $resource
     * @param string $filtersString
     * @return Response
     */
    public function listEntries(DataMapper $dataMapper, Request $request, LogEntryResource $resource, string $filtersString = "")
    {
        $filters = $this->parseFilters($filtersString);

        $generatorId = $filters['filters']['generatorId'] ?? '';

        if (null !== $generatorId)
        {
            if (!is_array($generatorId))
                $generatorId = [$generatorId];
        }

        return $this->render('log-entries-list.html.twig', [
            'data'          => $resource->getList($filters['filters'], $filters['page'], $filters['itemsPerPage']),
            'url'           => $request->getHost() . ':' . $request->getPort() . $this->generateUrl('log_entities_list'),
            'pagination'    => $resource->paginationData($filters['page'], $filters['itemsPerPage'], $filters['filters']),
            'filters'       => [
                'generatorId'   => $generatorId ?? [],
                'dateFrom'      => isset($filters['filters']['dateFrom']) ? $dataMapper->dateTimeFromString($filters['filters']['dateFrom']) : '',
                'dateTo'      => isset($filters['filters']['dateTo']) ? $dataMapper->dateTimeFromString($filters['filters']['dateTo']) : '',
            ]
        ]);
    }
}