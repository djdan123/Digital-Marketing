<?php

namespace App\Repositories\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements \App\Repositories\Contracts\RepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    protected function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function all(array $columns = ['*'], array $relations = [], array $appends = []): Collection
    {
        $items = $this->query()->with($relations)->get($columns);

        if (!empty($appends)) {
            $items->each(fn (Model $item) => $item->append($appends));
        }

        return $items;
    }

    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = [], array $appends = []): LengthAwarePaginator
    {
        $paginator = $this->query()->with($relations)->paginate($perPage, $columns);

        if (!empty($appends)) {
            $paginator->getCollection()->each(fn (Model $item) => $item->append($appends));
        }

        return $paginator;
    }

    public function find(int $id, array $relations = []): ?Model
    {
        return $this->query()->with($relations)->find($id);
    }

    public function findOrFail(int $id, array $relations = []): Model
    {
        return $this->query()->with($relations)->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    public function findBy(array $criteria, array $relations = []): ?Model
    {
        return $this->query()->with($relations)->where($criteria)->first();
    }

    public function findAllBy(array $criteria, array $relations = []): Collection
    {
        return $this->query()->with($relations)->where($criteria)->get();
    }
}
