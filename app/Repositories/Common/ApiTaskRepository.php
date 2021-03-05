<?php

namespace LarAPI\Repositories\Common;

use Illuminate\Database\Eloquent\Model;
use LarAPI\Core\Repositories\BaseRepository;
use LarAPI\Models\Common\ApiTask;

class ApiTaskRepository extends BaseRepository
{
    public function getModel(): Model
    {
        return new ApiTask();
    }
}
