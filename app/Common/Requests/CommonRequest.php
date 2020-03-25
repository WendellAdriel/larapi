<?php

namespace LarAPI\Common\Requests;

use Illuminate\Foundation\Http\FormRequest;

use LarAPI\Common\Requests\Traits\HasFormat;
use LarAPI\Common\Support\DTOs\CommonDTO;
use LarAPI\Common\Support\DTOs\DTOInterface;

class CommonRequest extends FormRequest
{
    use HasFormat;

    protected const PAGE     = 'page';
    protected const PER_PAGE = 'per_page';
    protected const SORT     = 'sort';
    protected const SEARCH   = 'search';

    /**
     * @return array
     */
    public function rules()
    {
        return [
            self::PAGE     => ['sometimes', 'int'],
            self::PER_PAGE => ['sometimes'],
            self::SORT     => ['sometimes', 'array'],
            self::SEARCH   => ['sometimes', 'string']
        ];
    }

    /**
     * @return int
     */
    public function page(): int
    {
        return (int) $this->input(self::PAGE, DTOInterface::DEFAULT_PAGE);
    }

    /**
     * @return int|string
     */
    public function perPage()
    {
        return $this->format() === 'json'
            ? (int) $this->input(self::PER_PAGE, DTOInterface::DEFAULT_PER_PAGE)
            : 'all'; // Exports should be based on the entire result set
    }

    /**
     * @param array $default
     * @return array
     */
    public function sort(array $default = []): array
    {
        $sort = $this->input(self::SORT, $default);
        if ($sort) {
            return (array) $sort;
        }

        return $default;
    }

    /**
     * @return string|null
     */
    public function search(): ?string
    {
        return $this->input(self::SEARCH);
    }

    /**
     * @return DTOInterface
     */
    public function getDTO(): DTOInterface
    {
        $dto = new CommonDTO();

        return $dto->setPage($this->page())
            ->setPerPage($this->perPage())
            ->setSort($this->sort())
            ->setSearch($this->search())
            ->setFormat($this->format());
    }
}
