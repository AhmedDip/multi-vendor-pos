<?php

namespace App\Helpers;

class PaginationHelper
{
    public static function generatePaginationData($paginatedData)
    {
        return [
            'current_page'   => $paginatedData->currentPage(),
            'first_page_url' => $paginatedData->url(1),
            'from'           => $paginatedData->firstItem(),
            'last_page'      => $paginatedData->lastPage(),
            'last_page_url'  => $paginatedData->url($paginatedData->lastPage()),
            'next_page_url'  => $paginatedData->nextPageUrl(),
            'path'           => $paginatedData->url(1),
            'per_page'       => $paginatedData->perPage(),
            'prev_page_url'  => $paginatedData->previousPageUrl(),
            'to'             => $paginatedData->lastItem(),
            'total'          => $paginatedData->total(),
        ];
    }
}
