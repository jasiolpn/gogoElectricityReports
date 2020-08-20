<?php

namespace App\Controller\Rest;

use App\Service\Manager\LogEntryResource;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 * Class ElectricityGenerator
 * @package App\Controller\Rest
 */
class ElectricityGenerator extends AbstractController
{
    /**
     * @Route("/log-entries", name="log_entries_create", methods={"POST"})
     * @param Request $request
     * @param LogEntryResource $resource
     * @return Response
     * @throws Exception
     */
    public function create(Request $request, LogEntryResource $resource)
    {
        $json = json_decode($request->getContent(), true);

        if (null === $json)
            return $this->json([
                'status'    => 'Error',
                'info'      => 'Invalid data format'
            ], Response::HTTP_BAD_REQUEST);

        $resource->create($json);

        return $this->json([
            'status' => 'OK'
        ]);
    }
}