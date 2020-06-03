<?php

namespace LarAPI\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    public const COMPARE_DEFAULT = '=';
    public const ALL_COLUMNS     = ['*'];

    /**
     * Gets the base model for the repository
     *
     * @return Model
     */
    abstract public function getModel(): Model;

    /**
     * Builds a new query
     *
     * @param array $columns
     * @return Builder
     */
    public function newQuery(array $columns = self::ALL_COLUMNS): Builder
    {
        return $this->getModel()
            ->newQuery()
            ->select($columns);
    }

    /**
     * Gets builder by attribute
     *
     * @param string $attribute
     * @param mixed  $value
     * @param string $compareType
     * @param array  $columns
     * @return Builder
     */
    public function getBy(
        string $attribute,
        $value,
        string $compareType = self::COMPARE_DEFAULT,
        array $columns = self::ALL_COLUMNS
    ): Builder {
        return $this->getByBase([[$attribute, $value, $compareType]], $columns);
    }

    /**
     * Gets builder by multiple attributes
     *
     * @param array $params
     * @param array $columns
     * @return Builder
     */
    public function getByParams(array $params, array $columns = self::ALL_COLUMNS): Builder
    {
        return $this->getByBase($params, $columns);
    }

    /**
     * Gets builder by attribute in list of values
     *
     * @param string $attribute
     * @param array  $values
     * @param array  $columns
     * @return Builder
     */
    public function getByIn(string $attribute, array $values, array $columns = self::ALL_COLUMNS): Builder
    {
        return $this->getByInBase([[$attribute, $values]], $columns);
    }

    /**
     * Gets builder by list of attributes in list of values
     *
     * @param array $params
     * @param array $columns
     * @return Builder
     */
    public function getByParamsIn(array $params, array $columns = self::ALL_COLUMNS): Builder
    {
        return $this->getByInBase($params, $columns);
    }

    /**
     * Gets the table for the base model of the repository
     *
     * @param string $alias
     * @return string
     */
    protected function getTable(string $alias = null): string
    {
        $table = $this->getModel()->getTable();
        if (\is_null($alias)) {
            return $table;
        }
        return "$table AS $alias";
    }

    /**
     * Gets the table name of a model
     *
     * @param Model  $model
     * @param string $alias
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
     * Builds a base query
     *
     * @param array $params
     * @param array $columns
     * @return Builder
     */
    private function getByBase(array $params, array $columns): Builder
    {
        $query = $this->newQuery($columns);

        foreach ($params as $param) {
            $compareType = \count($param) === 2 ? self::COMPARE_DEFAULT : $param[2];
            $query       = $query->where($param[0], $compareType, $param[1]);
        }

        return $query;
    }

    /**
     * Builds a base wherein query
     *
     * @param array $params
     * @param array $columns
     * @return Builder
     */
    private function getByInBase(array $params, array $columns): Builder
    {
        $query = $this->newQuery($columns);

        foreach ($params as $param) {
            $query->whereIn($param[0], $param[1]);
        }

        return $query;
    }
}
