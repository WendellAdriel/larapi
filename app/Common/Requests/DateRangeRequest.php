<?php

namespace LarAPI\Common\Requests;

use Illuminate\Support\Carbon;

use LarAPI\Common\Support\DTOs\DateRangeDTO;
use LarAPI\Common\Support\DTOs\DTOInterface;

class DateRangeRequest extends CommonTableRequest
{
    private const START_DATE = 'from';
    private const END_DATE   = 'to';

    /**
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            self::START_DATE => ['sometimes', 'string'],
            self::END_DATE   => ['sometimes', 'string']
        ]);
    }

    /**
     * @return Carbon|null
     */
    public function from(): ?Carbon
    {
        $from = $this->input(self::START_DATE);
        return \is_null($from) ? null : $this->formatter()->getCarbonFromString($from)->startOfDay();
    }

    /**
     * @return Carbon|null
     */
    public function to(): ?Carbon
    {
        $to = $this->input(self::END_DATE);
        return \is_null($to) ? null : $this->formatter()->getCarbonFromString($to)->endOfDay();
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
}
