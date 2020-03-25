<?php

namespace LarAPI\Common\Support\DTOs;

use LarAPI\Common\Support\DTOs\Traits\HasFormat;

class CommonDTO implements DTOInterface
{
    use HasFormat;

    private const PER_PAGE_ALL = 'all';

    /** @var int */
    protected $page = self::DEFAULT_PAGE;
    /** @var int|string */
    protected $perPage = self::DEFAULT_PER_PAGE;
    /** @var array */
    protected $sort = [];
    /** @var string|null */
    protected $search = null;

    /**
     * @param int $page
     * @return CommonDTO
     */
    public function setPage(int $page): CommonDTO
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
     * @return CommonDTO
     */
    public function setPerPage($perPage): CommonDTO
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
     * @return CommonDTO
     */
    public function setSort(array $sort): CommonDTO
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
     * @return CommonDTO
     */
    public function setSearch(?string $search): CommonDTO
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
     * @return bool
     */
    public function getAll(): bool
    {
        return \is_string($this->perPage) && \strtolower($this->perPage) === self::PER_PAGE_ALL;
    }
}
