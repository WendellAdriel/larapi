<?php

namespace LarAPI\Common\Requests;

use Illuminate\Foundation\Http\FormRequest;

use LarAPI\Common\Support\DTOs\CommonTableDTO;
use LarAPI\Common\Support\DTOs\DTOInterface;
use LarAPI\Common\Support\Formatter;

class CommonTableRequest extends FormRequest
{
    protected const PAGE     = 'page';
    protected const PER_PAGE = 'per_page';
    protected const SORT     = 'sort';
    protected const SEARCH   = 'search';
    protected const FORMAT   = 'format';

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
        return (int) $this->input(self::PAGE, CommonTableDTO::DEFAULT_PAGE);
    }

    /**
     * @return int|string
     */
    public function perPage()
    {
        return $this->format() === CommonTableDTO::FORMAT_JSON
            ? (int) $this->input(self::PER_PAGE, CommonTableDTO::DEFAULT_PER_PAGE)
            : CommonTableDTO::PER_PAGE_ALL; // Exports should be based on the entire result set
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
     * @param string $default
     * @return string
     */
    public function format($default = DTOInterface::FORMAT_JSON): string
    {
        $format = $this->route()->parameter(self::FORMAT, $default);
        return \ltrim($format, '.');
    }

    /**
     * @return DTOInterface
     */
    public function getDTO(): DTOInterface
    {
        $dto = new CommonTableDTO();

        return $dto->setPage($this->page())
            ->setPerPage($this->perPage())
            ->setSort($this->sort())
            ->setSearch($this->search())
            ->setFormat($this->format());
    }

    /**
     * @return Formatter
     */
    protected function formatter(): Formatter
    {
        return new Formatter();
    }
}
