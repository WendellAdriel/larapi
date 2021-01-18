<?php

namespace LarAPI\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Enumerable;

abstract class BaseRepository
{
    public const ALL_COLUMNS = ['*'];

    /**
     * Gets the base model for the repository
     *
     * @return Model
     */
    abstract public function getModel(): Model;

    /**
     * @param array|string[] $columns
     * @return Collection
     */
    public function all(array $columns = self::ALL_COLUMNS): Collection
    {
        return $this->newQuery($columns)->get();
    }

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
     * Gets a model by some given attributes
     *
     * @param array  $params
     * @param string $compareType
     * @return Builder|Model|null
     */
    public function getByParams(array $params, string $compareType = '=')
    {
        return $this->getByParamsBase($params, $compareType)->first();
    }

    /**
     * Gets a model by some attributes or throws an exception
     *
     * @param array  $params
     * @param string $compareType
     * @return Builder|Model
     */
    public function getByParamsOrFail(array $params, string $compareType = '=')
    {
        return $this->getByParamsBase($params, $compareType)->firstOrFail();
    }

    /**
     * Gets all models by some given attributes
     *
     * @param array  $params
     * @param string $compareType
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllByParams(array $params, string $compareType = '=')
    {
        return $this->getByParamsBase($params, $compareType)->get();
    }

    /**
     * @param string $attribute
     * @param $value
     * @param array $updateFields
     * @return int
     */
    public function updateBy(string $attribute, $value, array $updateFields): int
    {
        $formattedValue = \is_array($value) || $value instanceof Enumerable ? $value : [$value];
        return $this->newQuery()
            ->whereIn($attribute, $formattedValue)
            ->update($updateFields);
    }

    /**
     * @param string $attribute
     * @param $value
     * @return mixed
     */
    public function deleteBy(string $attribute, $value)
    {
        $formattedValue = \is_array($value) || $value instanceof Enumerable ? $value : [$value];
        return $this->newQuery()
            ->whereIn($attribute, $formattedValue)
            ->delete();
    }

    /**
     * Create a new model
     *
     * @param array $args
     * @return \Illuminate\Database\Eloquent\Builder|Model
     */
    public function create(array $args)
    {
        return $this->getModel()->newQuery()->create($args);
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
     * Get table name of a  model
     *
     * @param Model $model
     * @param string|null $alias
     * @return string
     */
    protected function getModelTable(Model $model, string $alias = null): string
    {
        $table = $model->getTable();
        if (empty($alias)) {
            return $table;
        }
        return "$table AS $alias";
    }

    /**
     * Builds a new query
     *
     * @param array $columns
     * @return Builder
     */
    protected function newQuery(array $columns = self::ALL_COLUMNS): Builder
    {
        return $this->getModel()
            ->newQuery()
            ->select($columns);
    }

    /**
     * Build a query base
     *
     * @param array  $params
     * @param string $defaultCompareType
     * @return Model|Builder
     */
    private function getByParamsBase(array $params, string $defaultCompareType = '=')
    {
        /** @var Builder $query */
        $query = $this->getModel();
        foreach ($params as $param) {
            $compareType = count($param) === 2 ? $defaultCompareType : $param[2];
            if (mb_strtoupper($compareType) === 'IN') {
                $query = $query->whereIn($param[0], $param[1]);
            } else {
                $query = $query->where($param[0], $compareType, $param[1]);
            }
        }
        return $query;
    }
}
