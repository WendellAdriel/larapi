<?php

namespace LarAPI\Modules\Common\Requests;

use Illuminate\Support\Carbon;
use LarAPI\Modules\Common\Support\DTOs\DateRangeDTO;
use LarAPI\Modules\Common\Support\DTOs\DTOInterface;

class DateRangeRequest extends CommonTableRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $dto = new DateRangeDTO();
        return array_merge(parent::rules(), [
            $dto::START_DATE => ['sometimes', 'string'],
            $dto::END_DATE   => ['sometimes', 'string']
        ]);
    }

    /**
     * @return DTOInterface
     */
    public function getDTO(): DTOInterface
    {
        $dto = new DateRangeDTO();
        return $dto->setPage($this->page())
            ->setPerPage($this->perPage())
            ->setSort($this->sort())
            ->setSearch($this->search())
            ->setFormat($this->format())
            ->setFrom($this->from())
            ->setTo($this->to());
    }

    /**
     * @return Carbon|null
     */
    protected function from(): ?Carbon
    {
        $from = $this->input(DateRangeDTO::START_DATE);
        return \is_null($from) ? null : $this->formatter()->getCarbonFromString($from)->startOfDay();
    }

    /**
     * @return Carbon|null
     */
    protected function to(): ?Carbon
    {
        $to = $this->input(DateRangeDTO::END_DATE);
        return \is_null($to) ? null : $this->formatter()->getCarbonFromString($to)->endOfDay();
    }
}
