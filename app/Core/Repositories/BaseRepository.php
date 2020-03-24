<?php

namespace LarAPI\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseRepository
{

    /**
     * Gets the base model for the repository
     *
     * @return Model
     */
    abstract public function getModel(): Model;

    /**
     * Gets all models by the given attribute
     *
     * @param string $attribute
     * @param mixed  $value
     * @param string $compareType
     * @return Collection
     */
    public function getAllBy(string $attribute, $value, string $compareType = '='): Collection
    {
        return $this->getModel()
            ->query()
            ->where($attribute, $compareType, $value)
            ->get();
    }

    /**
     * Gets a model by the given attribute
     *
     * @param string $attribute
     * @param mixed  $value
     * @param string $compareType
     * @return Model|null
     */
    public function getBy(string $attribute, $value, string $compareType = '='): ?Model
    {
        return $this->getModel()
            ->where($attribute, $compareType, $value)
            ->first();
    }

    /**
     * Gets a model by the given attribute or throws an exception
     *
     * @param string $attribute
     * @param mixed  $value
     * @param string $compareType
     * @return Model
     */
    public function getByOrFail(string $attribute, $value, string $compareType = '='): Model
    {
        return $this->getModel()
            ->where($attribute, $compareType, $value)
            ->firstOrFail();
    }

    /**
     * Gets the table for the base model of the repository
     *
     * @return string
     */
    protected function getTable(): string
    {
        return $this->getModel()->getTable();
    }
}
