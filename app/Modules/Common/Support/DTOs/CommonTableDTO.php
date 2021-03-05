<?php

namespace LarAPI\Modules\Common\Support\DTOs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Enumerable;

class CommonTableDTO implements DTOInterface
{
    public const PAGE     = 'page';
    public const PER_PAGE = 'per_page';
    public const SORT     = 'sort';
    public const SEARCH   = 'search';
    public const FORMAT   = 'format';

    public const DEFAULT_PAGE     = 1;
    public const DEFAULT_PER_PAGE = 20;
    public const PER_PAGE_ALL     = 'all';

    public const SORT_FIELD = 'field';
    public const SORT_ORDER = 'order';
    public const ORDER_ASC  = 'asc';
    public const ORDER_DESC = 'desc';

    public const FORMAT_JSON = 'json';

    protected int $page       = self::DEFAULT_PAGE;
    protected array $sort     = [];
    protected ?string $search = null;
    protected string$format   = self::FORMAT_JSON;

    /** @var int|string */
    protected $perPage = self::DEFAULT_PER_PAGE;

    /**
     * @param int $page
     * @return CommonTableDTO
     */
    public function setPage(int $page): CommonTableDTO
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int|string $perPage
     * @return CommonTableDTO
     */
    public function setPerPage($perPage): CommonTableDTO
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param array $sort
     * @return CommonTableDTO
     */
    public function setSort(array $sort): CommonTableDTO
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return array
     */
    public function getSort(): array
    {
        return $this->sort;
    }

    /**
     * @param string|null $search
     * @return CommonTableDTO
     */
    public function setSearch(?string $search): CommonTableDTO
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param string $format
     * @return CommonTableDTO
     */
    public function setFormat(string $format): CommonTableDTO
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return bool
     */
    public function getAll(): bool
    {
        return \is_string($this->perPage) && \strtolower($this->perPage) === self::PER_PAGE_ALL;
    }

    /**
     * @return bool
     */
    public function hasSort(): bool
    {
        return !empty($this->sort) && !empty($this->sort[self::SORT_FIELD]);
    }

    /**
     * @param Enumerable $list
     * @return Enumerable
     */
    public function applySort(Enumerable $list): Enumerable
    {
        if (!$this->hasSort()) {
            return $list;
        }

        $sorted = $this->sort[self::SORT_ORDER] === self::ORDER_ASC
            ? $list->sortBy($this->sort[self::SORT_FIELD], SORT_NATURAL | SORT_FLAG_CASE)
            : $list->sortByDesc($this->sort[self::SORT_FIELD], SORT_NATURAL | SORT_FLAG_CASE);

        return $sorted->values();
    }

    /**
     * @param Enumerable $list
     * @param array      $fieldsToSearch
     * @return Enumerable
     */
    public function applyFilter(Enumerable $list, array $fieldsToSearch): Enumerable
    {
        $search = $this->search;
        if (empty($search) || empty($fieldsToSearch)) {
            return $list;
        }

        $filtered = $list->filter(function ($item) use ($search, $fieldsToSearch) {
            foreach ($fieldsToSearch as $field) {
                $itemArray = $item instanceof Model ? $item->toArray() : \json_decode(\json_encode($item), true);
                $found     = \stripos($itemArray[$field], $search) !== false;
                if ($found) {
                    return true;
                }
            }
            return false;
        });

        return $filtered->values();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            self::PAGE     => $this->page,
            self::PER_PAGE => $this->perPage,
            self::SORT     => $this->sort,
            self::SEARCH   => $this->search,
            self::FORMAT   => $this->format
        ];
    }
}
