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
        return $this->getByParamsBase([[$attribute, $value, $compareType]])->get();
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
        return $this->getByParamsBase([[$attribute, $value, $compareType]])->first();
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
        return $this->getByParamsBase([[$attribute, $value, $compareType]])->firstOrFail();
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

    /**
     * Gets a model by some given attributes
     *
     * @param array $params
     * @param string $compareType
     * @return mixed
     */
    public function getFirstByParams(array $params, string $compareType = '='): ?Model
    {
        return $this->getByParamsBase($params, $compareType)->first();
    }

    /**
     * Gets a model by some attributes or throws an exception
     *
     * @param array $params
     * @param string $compareType
     * @return mixed
     */
    public function getFirstOrFailByParams(array $params, string $compareType = '='): Model
    {
        return $this->getByParamsBase($params, $compareType)->firstOrFail();
    }

    /**
     * Gets all models by some given attributes
     *
     * @param array $params
     * @param string $compareType
     * @return mixed
     */
    public function getAllByParams(array $params, string $compareType = '='): Collection
    {
        return $this->getByParamsBase($params, $compareType)->get();
    }

    /**
     * Build a query base
     *
     * @param array $params
     * @return Model
     */
    private function getByParamsBase(array $params)
    {
        $query = $this->getModel();
        foreach ($params as $param) {
            $compareType = \count($param) === 2 ? '=' : $param[2];
            $query = $query->where($param[0], $compareType, $param[1]);
        }
        return $query;
    }
}
