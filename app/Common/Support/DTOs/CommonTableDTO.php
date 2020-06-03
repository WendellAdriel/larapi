<?php

namespace LarAPI\Common\Support\DTOs;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Enumerable;

class CommonTableDTO implements DTOInterface, Arrayable
{
    /** @var int */
    protected $page = self::DEFAULT_PAGE;
    /** @var int|string */
    protected $perPage = self::DEFAULT_PER_PAGE;
    /** @var array */
    protected $sort = [];
    /** @var string|null */
    protected $search = null;
    /** @var string */
    protected $format = self::FORMAT_JSON;

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
        return (
            (\is_string($this->perPage) && \strtolower($this->perPage) === self::PER_PAGE_ALL)
            || $this->format !== self::FORMAT_JSON
        );
    }

    /**
     * @param Enumerable $list
     * @return Enumerable
     */
    public function applySort(Enumerable $list): Enumerable
    {
        if (empty($this->sort) || empty($this->sort[self::SORT_FIELD])) {
            return $list;
        }

        $sorted = $this->sort[self::SORT_ORDER] === self::ORDER_ASC
            ? $list->sortBy($this->sort[self::SORT_FIELD])
            : $list->sortByDesc($this->sort[self::SORT_FIELD]);

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
    public function toArray()
    {
        return [
            'page'     => $this->page,
            'per_page' => $this->perPage,
            'sort'     => $this->sort,
            'search'   => $this->search,
            'format'   => $this->format
        ];
    }
}
