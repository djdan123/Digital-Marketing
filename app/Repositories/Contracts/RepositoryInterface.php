<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = [], array $appends = []): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = [], array $appends = []): LengthAwarePaginator;

    public function find(int $id, array $relations = []): ?Model;

    public function findOrFail(int $id, array $relations = []): Model;

    public function create(array $data): Model;

    public function update(Model $model, array $data): Model;

    public function delete(Model $model): bool;

    public function findBy(array $criteria, array $relations = []): ?Model;

    public function findAllBy(array $criteria, array $relations = []): Collection;
}
