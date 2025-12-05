<?php

namespace App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseService
{
    protected $repository;

    abstract public function repository();

    public function __construct()
    {
        $this->repository = app($this->repository());
    }

    public function getAll(array $columns = ['*'])
    {
        return $this->repository->getAll($columns);
    }

    public function findOneById($id)
    {
        $model = $this->repository->findOneById($id);

        if (!$model) {
            throw new ModelNotFoundException("ID bản ghi không tồn tại, vui lòng kiểm tra lại");
        }

        return $model;
    }

    public function create(array $data)
    {
        // Xử lý sort_order
        if (empty($data['sort_order'])) {
            $maxStt = $this->repository->getMaxSortOrder();
            $data['sort_order'] = $maxStt ? $maxStt + 1 : 1;
        }
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->repository->findOneById($id);

        if (!$model) {
            throw new ModelNotFoundException("ID bản ghi không tồn tại, vui lòng kiểm tra lại");
        }

        return $this->repository->update($model, $data);
    }

    public function delete($id)
    {
        $model = $this->repository->findOneById($id);

        return $this->repository->delete($model);
    }
}
