<?php

namespace App\Service\Manager;

use App\Entity\LogEntry;
use App\Service\Manager\Base\AbstractResource;

/**
 * @method LogEntry[]    getList(array $filters = [], ?int $page = null, ?int $itemsPerPage = null)
 */
class LogEntryResource extends AbstractResource
{
    public function create(array $data)
    {
        $data['measurementTime'] = $this->getDataMapper()->dateTimeFromString($data['measurementTime']);

        parent::create($data);
    }

    protected function getEntityClassName(): string
    {
        return LogEntry::class;
    }
}