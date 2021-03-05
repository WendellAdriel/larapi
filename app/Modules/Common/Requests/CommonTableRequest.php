<?php

namespace LarAPI\Modules\Common\Requests;

use LarAPI\Modules\Common\Support\DTOs\CommonTableDTO;
use LarAPI\Modules\Common\Support\DTOs\DTOInterface;
use LarAPI\Modules\Common\Support\Formatter;
use LarAPI\Core\Http\BaseRequest;

class CommonTableRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $dto = new CommonTableDTO();
        return [
            $dto::PAGE     => ['sometimes', 'int'],
            $dto::PER_PAGE => ['sometimes'],
            $dto::SORT     => ['sometimes', 'array'],
            $dto::SEARCH   => ['sometimes', 'string']
        ];
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
     * @return int
     */
    protected function page(): int
    {
        return (int) $this->input(CommonTableDTO::PAGE, CommonTableDTO::DEFAULT_PAGE);
    }

    /**
     * @return int|string
     */
    protected function perPage()
    {
        return (int) $this->input(CommonTableDTO::PER_PAGE, CommonTableDTO::DEFAULT_PER_PAGE);
    }

    /**
     * @param array $default
     * @return array
     */
    protected function sort(array $default = []): array
    {
        $sort = $this->input(CommonTableDTO::SORT, $default);
        if ($sort) {
            return (array) $sort;
        }
        return $default;
    }

    /**
     * @return string|null
     */
    protected function search(): ?string
    {
        return $this->input(CommonTableDTO::SEARCH);
    }

    /**
     * @param string $default
     * @return string
     */
    public function format($default = CommonTableDTO::FORMAT_JSON): string
    {
        $format = $this->route()->parameter(CommonTableDTO::FORMAT, $default);
        return \ltrim($format, '.');
    }

    /**
     * @return Formatter
     */
    protected static function formatter(): Formatter
    {
        return new Formatter();
    }
}
