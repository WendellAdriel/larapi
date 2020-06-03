<?php

namespace LarAPI\Common\Support\DTOs;

interface DTOInterface
{
    public const DEFAULT_PAGE     = 1;
    public const DEFAULT_PER_PAGE = 20;
    public const PER_PAGE_ALL     = 'all';

    public const SORT_FIELD = 'field';
    public const SORT_ORDER = 'order';
    public const ORDER_ASC  = 'asc';
    public const ORDER_DESC = 'desc';

    public const FORMAT_JSON = 'json';
}
