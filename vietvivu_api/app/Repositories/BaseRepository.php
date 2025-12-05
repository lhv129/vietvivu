<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    abstract public function model();

    public function __construct()
    {
        $this->model = app($this->model());
    }

    // CRUD chung
    public function getAll(array $columns = ['*'])
    {
        return $this->model->select($columns)->get();
    }


    public function findOneById($id, array $columns = ['*'])
    {
        $item = $this->model->find($id, $columns);
        return $item;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // Update: nhận trực tiếp Model ($item ở đây là bản ghi cần cập nhật, $data là dữ liệu cần cập nhật cho bản ghi đó)
    public function update(Model $item, array $data): Model
    {
        $item->update($data);
        return $item;
    }

    // Delete: nhận trực tiếp Model
    public function delete(Model $item): bool
    {
        return $item->delete();
    }

    // Dùng chung cho mọi repo có cột sort_order
    public function getMaxSortOrder(): int
    {
        return $this->model->max('sort_order') ?? 0;
    }
}
