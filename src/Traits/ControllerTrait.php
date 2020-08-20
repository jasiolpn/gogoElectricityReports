<?php

namespace App\Traits;

trait ControllerTrait
{
    protected function parseFilters(string $filters)
    {
        $result = [];
        $paginationData = [];
        $filtersData = [];

        $temp = preg_split('@/@', $filters, null, PREG_SPLIT_NO_EMPTY);

        foreach ($temp as $item)
        {
            $item = explode('=', $item);

            if (count($item) != 2)
                continue;

            if ($item[0] == 'page' || $item[0] == 'itemsPerPage')
                $paginationData[$item[0]] = intval($item[1]);
            else
            {
                if (strpos($item[1], ','))
                    $filtersData[$item[0]] = preg_split('@,@', $item[1], null, PREG_SPLIT_NO_EMPTY);
                else
                    $filtersData[$item[0]] = $item[1];
            }
        }

        $result['page'] = $paginationData['page'] ?? 1;
        $result['itemsPerPage'] = $paginationData['itemsPerPage'] ?? 10;
        $result['filters'] = $filtersData;

        return $result;
    }
}