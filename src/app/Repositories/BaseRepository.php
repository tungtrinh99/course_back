<?php

namespace App\Repositories;

use App\Exceptions\BadRequestException;
use App\Helper\Constant;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    protected $primaryKey;

    protected $msgNotFound = 'Not Found';

    protected $perPage = 10;

    // update timestamp(created_at, updated_at) in query builder
    protected $queryBuilderTimestamp = true;

    /**
     * EloquentRepository constructor.
     */
    public function __construct()
    {
        $this->setModel();
        $this->setPrimarykey();
    }

    /**
     * get model
     *
     * @return string<?php
     *
     * namespace App\Repositories;
     *
     * use App\Exceptions\BadRequestException;
     * use App\Helper\Constant;
     * use Closure;
     * use Illuminate\Database\Eloquent\ModelNotFoundException;
     * use Illuminate\Support\Facades\DB;
     *
     * abstract class BaseRepository
     * {
     * /**
     * @return string
     * /
     * abstract public function getModel();
     *
     * /**
     *  Set model
     * /
     * public function setModel()
     * {
     * $this->model = app()->make(
     * $this->getModel()
     * );
     * }
     *
     * /**
     *  Set Primarykey
     * /
     * public function setPrimarykey()
     * {
     * $this->primaryKey = $this->model->getKeyName();
     * }
     *
     * public function getObject($id)
     * {
     * try {
     * $model = $this->getModel();
     * if ($id instanceof $model) {
     * $result = $id;
     * } else {
     * $result = $this->findOrFail($id);
     * }
     *
     * return $result;
     * } catch (ModelNotFoundException $e) {
     * return null;
     * }
     * }
     *
     * /**
     *  Get All
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     * /
     * public function getAll()
     * {
     * return $this->model->get();
     * }
     *
     * /**
     *  Get one
     *
     * @return mixed
     * /
     * public function find($id)
     * {
     * $result = $this->model->find($id);
     *
     * return $result;
     * }
     *
     * public function getMany($ids)
     * {
     * return $this->model->whereIn($this->primaryKey, $ids)->get();
     * }
     *
     * public function filter($where)
     * {
     * return $this->model->where($where)->get();
     * }
     *
     * public function filterFirst($where)
     * {
     * return $this->model->where($where)->first();
     * }
     *
     * /**
     *  Check if model exists
     *
     * @return mixed
     * /
     * public function exists($id)
     * {
     * return $this->model->where($this->model->getKeyName(), $id)->exists();
     * }
     *
     * /**
     *  Get one
     *
     * @return mixed
     * /
     * public function findOrFail($id)
     * {
     * try {
     * $result = $this->model->findOrFail($id);
     * } catch (\Exception $e) {
     * throw new ModelNotFoundException($this->msgNotFound, 0);
     * }
     *
     * return $result;
     * }
     *
     * public function findOrFailByCompositeKey($id, array $relationshipKeys)
     * {
     * $model = $this->model->where($this->primaryKey, $id)
     * ->where($relationshipKeys)
     * ->first();
     * if (! $model) {
     * throw new ModelNotFoundException($this->msgNotFound, 0);
     * }
     *
     * return $model;
     * }
     *
     * /**
     *  Get one
     *
     * @return mixed
     * /
     * public function findOrBad($id)
     * {
     * try {
     * $result = $this->model->findOrFail($id);
     * } catch (\Exception $e) {
     * throw new BadRequestException($this->msgNotFound, 0);
     * }
     *
     * return $result;
     * }
     *
     * /**
     *  Get first
     *
     * @return mixed
     * /
     * public function first()
     * {
     * return $this->model->first();
     * }
     *
     * /**
     *  Create
     *
     * @return mixed
     * /
     * public function create(array $attributes)
     * {
     * return $this->model->create($attributes);
     * }
     *
     * /**
     *  Update
     *
     * @return bool|mixed
     * /
     * public function update($id, array $attributes)
     * {
     * $object = $this->getObject($id);
     * if ($object) {
     * $object->update($attributes);
     *
     * return $object;
     * }
     *
     * return false;
     * }
     *
     * public function bulkUpdate(array $values, array $attributes, $key = null)
     * {
     * $key = $key ?? $this->primaryKey;
     * if (! empty($attributes)) {
     * return $this->model->whereIn($key, $values)
     * ->update($attributes);
     * }
     *
     * return false;
     * }
     *
     * /**
     *  Delete
     * /
     * public function delete($id)
     * {
     * $result = $this->getObject($id);
     * if ($result) {
     * $result->delete();
     *
     * return true;
     * }
     *
     * return false;
     * }
     *
     * public function insertMany($params)
     * {
     * foreach ($params as $key => $param) {
     * $params[$key] = $this->updateTimestamp($param);
     * }
     *
     * return DB::table($this->model->getTable())->insert($params);
     * }
     *
     * public function insertGetId($param)
     * {
     * $param = $this->updateTimestamp($param);
     *
     * return DB::table($this->model->getTable())->insertGetId($param, $this->primaryKey);
     * }
     *
     * public function optimisticLockForUpdate($id, array $attributes, Closure $constrain = null)
     * {
     * do {
     * $record = $this->getObject($id);
     * if ($constrain) {
     * $constrain($record, $attributes);
     * }
     * $updated = $this->model->where($this->primaryKey, $id)
     * ->where('updated_at', '=', $record->updated_at)
     * ->update($attributes);
     * } while (! $updated);
     * }
     *
     * public function updateOrCreateData($condition, $values)
     * {
     * return $this->model->updateOrCreate($condition, $values);
     * }
     *
     * public function rollbackAndUpdate($id, array $attributes)
     * {
     * $object = $this->model->withTrashed()->find($id);
     * if ($object) {
     * $object->restore();
     * $object->update($attributes);
     *
     * return $object;
     * }
     *
     * return false;
     * }
     *
     * public function firstOrCreateData($condition, $values)
     * {
     * return $this->model->firstOrCreate($condition, $values);
     * }
     *
     * public function deleteByField($field, $values)
     * {
     * return $this->model->whereIn($field, $values)->delete();
     * }
     *
     * public function bulkDelete(array $values, $key = null)
     * {
     * $key = $key ?? $this->primaryKey;
     *
     * return $this->model->whereIn($key, $values)->delete();
     * }
     *
     * public function filterAndRollBackWithTrashed($conditions)
     * {
     * $object = $this->model->withTrashed()->where($conditions)->first();
     * if ($object) {
     * $object->restore();
     * }
     *
     * return true;
     * }
     *
     * public function updateByWhereAndWhereIn(array $where, array $whereIn, array $attributes)
     * {
     * if (! empty($attributes)) {
     * return $this->queryByWhereAndWhereIn($where, $whereIn)->update($attributes);
     * }
     *
     * return false;
     * }
     *
     * public function filterByWhereAndWhereIn(array $where, array $whereIn)
     * {
     * return $this->queryByWhereAndWhereIn($where, $whereIn)->get();
     * }
     *
     * public function queryByWhereAndWhereIn(array $where, array $whereIn)
     * {
     * $objects = $this->model;
     * if (! empty($where)) {
     * $objects = $this->model->where($where);
     * }
     *
     * if (! empty($whereIn)) {
     * foreach ($whereIn as $key => $values) {
     * $objects = $objects->whereIn($key, $values);
     * }
     * }
     *
     * return $objects;
     * }
     *
     * public function queryByWhere(array $where)
     * {
     * $objects = $this->model;
     * if (! empty($where)) {
     * $objects = $this->model->where($where);
     * }
     *
     * return $objects;
     * }
     *
     * public function updateByWhere(array $conditions, array $attributes)
     * {
     * if (! empty($attributes) && ! empty($conditions)) {
     * return $this->model->where($conditions)->update($attributes);
     * }
     *
     * return false;
     * }
     *
     * protected function updateTimestamp($data, $columns = ['created_at', 'updated_at'])
     * {
     * $now = date(Constant::FORMAT_DATETIME);
     * if ($this->queryBuilderTimestamp) {
     * foreach ($columns as $column) {
     * $data[$column] = $now;
     * }
     * }
     *
     * return $data;
     * }
     *
     * public function whereBetween($column, $range) {
     * return $this->model->whereBetween($column, $range);
     * }
     *
     * public function getMax($column) {
     * return $this->model->max($column);
     * }
     * }
     * @var \Illuminate\Database\Eloquent\Model
     * /
     * protected $model;
     *
     * protected $primaryKey;
     *
     * protected $msgNotFound = 'Not Found';
     *
     * protected $perPage = 10;
     *
     * // update timestamp(created_at, updated_at) in query builder
     * protected $queryBuilderTimestamp = true;
     *
     * /**
     *  EloquentRepository constructor.
     * /
     * public function __construct()
     * {
     * $this->setModel();
     * $this->setPrimarykey();
     * }
     *
     * /**
     *  get model
     *
     */
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Set Primarykey
     */
    public function setPrimarykey()
    {
        $this->primaryKey = $this->model->getKeyName();
    }

    public function getObject($id)
    {
        try {
            $model = $this->getModel();
            if ($id instanceof $model) {
                $result = $id;
            } else {
                $result = $this->findOrFail($id);
            }

            return $result;
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    /**
     * Get All
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->model->get();
    }

    /**
     * Get one
     *
     * @return mixed
     */
    public function find($id)
    {
        $result = $this->model->find($id);

        return $result;
    }

    public function getMany($ids)
    {
        return $this->model->whereIn($this->primaryKey, $ids)->get();
    }

    public function filter($where)
    {
        return $this->model->where($where)->get();
    }

    public function filterFirst($where)
    {
        return $this->model->where($where)->first();
    }

    /**
     * Check if model exists
     *
     * @return mixed
     */
    public function exists($id)
    {
        return $this->model->where($this->model->getKeyName(), $id)->exists();
    }

    /**
     * Get one
     *
     * @return mixed
     */
    public function findOrFail($id)
    {
        try {
            $result = $this->model->findOrFail($id);
        } catch (\Exception $e) {
            throw new ModelNotFoundException($this->msgNotFound, 0);
        }

        return $result;
    }

    public function findOrFailByCompositeKey($id, array $relationshipKeys)
    {
        $model = $this->model->where($this->primaryKey, $id)
            ->where($relationshipKeys)
            ->first();
        if (! $model) {
            throw new ModelNotFoundException($this->msgNotFound, 0);
        }

        return $model;
    }

    /**
     * Get one
     *
     * @return mixed
     */
    public function findOrBad($id)
    {
        try {
            $result = $this->model->findOrFail($id);
        } catch (\Exception $e) {
            throw new BadRequestException($this->msgNotFound, 0);
        }

        return $result;
    }

    /**
     * Get first
     *
     * @return mixed
     */
    public function first()
    {
        return $this->model->first();
    }

    /**
     * Create
     *
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update
     *
     * @return bool|mixed
     */
    public function update($id, array $attributes)
    {
        $object = $this->getObject($id);
        if ($object) {
            $object->update($attributes);

            return $object;
        }

        return false;
    }

    public function bulkUpdate(array $values, array $attributes, $key = null)
    {
        $key = $key ?? $this->primaryKey;
        if (! empty($attributes)) {
            return $this->model->whereIn($key, $values)
                ->update($attributes);
        }

        return false;
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        $result = $this->getObject($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }

    public function insertMany($params)
    {
        foreach ($params as $key => $param) {
            $params[$key] = $this->updateTimestamp($param);
        }

        return DB::table($this->model->getTable())->insert($params);
    }

    public function insertGetId($param)
    {
        $param = $this->updateTimestamp($param);

        return DB::table($this->model->getTable())->insertGetId($param, $this->primaryKey);
    }

    public function optimisticLockForUpdate($id, array $attributes, Closure $constrain = null)
    {
        do {
            $record = $this->getObject($id);
            if ($constrain) {
                $constrain($record, $attributes);
            }
            $updated = $this->model->where($this->primaryKey, $id)
                ->where('updated_at', '=', $record->updated_at)
                ->update($attributes);
        } while (! $updated);
    }

    public function updateOrCreateData($condition, $values)
    {
        return $this->model->updateOrCreate($condition, $values);
    }

    public function rollbackAndUpdate($id, array $attributes)
    {
        $object = $this->model->withTrashed()->find($id);
        if ($object) {
            $object->restore();
            $object->update($attributes);

            return $object;
        }

        return false;
    }

    public function firstOrCreateData($condition, $values)
    {
        return $this->model->firstOrCreate($condition, $values);
    }

    public function deleteByField($field, $values)
    {
        return $this->model->whereIn($field, $values)->delete();
    }

    public function bulkDelete(array $values, $key = null)
    {
        $key = $key ?? $this->primaryKey;

        return $this->model->whereIn($key, $values)->delete();
    }

    public function filterAndRollBackWithTrashed($conditions)
    {
        $object = $this->model->withTrashed()->where($conditions)->first();
        if ($object) {
            $object->restore();
        }

        return true;
    }

    public function updateByWhereAndWhereIn(array $where, array $whereIn, array $attributes)
    {
        if (! empty($attributes)) {
            return $this->queryByWhereAndWhereIn($where, $whereIn)->update($attributes);
        }

        return false;
    }

    public function filterByWhereAndWhereIn(array $where, array $whereIn)
    {
        return $this->queryByWhereAndWhereIn($where, $whereIn)->get();
    }

    public function queryByWhereAndWhereIn(array $where, array $whereIn)
    {
        $objects = $this->model;
        if (! empty($where)) {
            $objects = $this->model->where($where);
        }

        if (! empty($whereIn)) {
            foreach ($whereIn as $key => $values) {
                $objects = $objects->whereIn($key, $values);
            }
        }

        return $objects;
    }

    public function queryByWhere(array $where)
    {
        $objects = $this->model;
        if (! empty($where)) {
            $objects = $this->model->where($where);
        }

        return $objects;
    }

    public function updateByWhere(array $conditions, array $attributes)
    {
        if (! empty($attributes) && ! empty($conditions)) {
            return $this->model->where($conditions)->update($attributes);
        }

        return false;
    }

    protected function updateTimestamp($data, $columns = ['created_at', 'updated_at'])
    {
        $now = date(Constant::FORMAT_DATETIME);
        if ($this->queryBuilderTimestamp) {
            foreach ($columns as $column) {
                $data[$column] = $now;
            }
        }

        return $data;
    }

    public function whereBetween($column, $range) {
        return $this->model->whereBetween($column, $range);
    }

    public function getMax($column) {
        return $this->model->max($column);
    }
}
