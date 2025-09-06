<?php

namespace App\Repositories;

class BaseRepositories
{
    protected $primaryKey = 'id';

    public function getBaseQuery($with = [])
    {
        return $this->model->query()
            ->when(count($with), fn($query) => $query->with($with));
    }

    public function findById($primaryKey, $with = [], ?callable $callback = null)
    {
        $query = $this->getBaseQuery($with);

        if ($callback) {
            $result = $callback($query);
            if ($result instanceof \Illuminate\Database\Eloquent\Builder) {
                $query = $result;
            }
        }

        return $query->find($primaryKey);
    }

    public function getAll(array $with = [], int $limit = 10, bool $paginate = true, ?callable $callback = null)
    {
        $query = $this->getBaseQuery($with);

        if ($callback) {
            $result = $callback($query);
            if ($result instanceof \Illuminate\Database\Eloquent\Builder) {
                $query = $result;
            }
        }

        if ($paginate) {
            return $query->paginate($limit);
        }

        if ($limit) {
            return $query->limit($limit)->get();
        }

        return $query->get();
    }


    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($primaryKey, $data)
    {
        $item = $this->findById($primaryKey);
        $item->update($data);

        return $item;
    }

    public function delete($primaryKey)
    {
        $item = $this->findById($primaryKey);
        $item->delete();

        return $item;
    }
}
