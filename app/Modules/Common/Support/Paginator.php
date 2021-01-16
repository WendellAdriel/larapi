<?php

namespace LarAPI\Modules\Common\Support;

use Illuminate\Support\Enumerable;
use LarAPI\Modules\Common\Support\DTOs\CommonTableDTO;

class Paginator
{
    /**
     * Manually paginates a collection of items
     *
     * @param Enumerable     $items - The filtered
     * @param int            $total - The unfiltered collection count
     * @param CommonTableDTO $dto   - The DTO with the page params
     * @return array
     */
    public static function manualPaginate(Enumerable $items, int $total, CommonTableDTO $dto): array
    {
        $itemsTotal = $items->count();
        $pageCount  = $dto->getAll() ? 1 : (int) \ceil($itemsTotal / $dto->getPerPage());
        $data       = $dto->getAll()
            ? $items->all()
            : $items->forPage($dto->getPage(), $dto->getPerPage())->values()->all();

        return [
            'data'       => $data,
            'pagination' => [
                'page_count' => $pageCount,
                'total'      => $itemsTotal,
                'total_all'  => $total,
            ],
        ];
    }

    /**
     * Manually paginates a collection of streamed items
     * Avoids to transform LazyCollections into arrays
     *
     * @param Enumerable     $items - The filtered
     * @param int            $total - The unfiltered collection count
     * @param CommonTableDTO $dto   - The DTO with the page params
     * @return array
     */
    public static function manualPaginateStream(Enumerable $items, int $total, CommonTableDTO $dto): array
    {
        $itemsTotal = $items->count();
        $pageCount  = $dto->getAll() ? 1 : (int) \ceil($itemsTotal / $dto->getPerPage());
        $data       = $dto->getAll()
            ? $items
            : $items->forPage($dto->getPage(), $dto->getPerPage())->values();

        return [
            'data'       => $data,
            'pagination' => [
                'page_count' => $pageCount,
                'total'      => $itemsTotal,
                'total_all'  => $total,
            ],
        ];
    }
}
