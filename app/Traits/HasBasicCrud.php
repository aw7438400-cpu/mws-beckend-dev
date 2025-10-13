<?php

namespace App\Traits;

use App\Traits\HasHttpResponse;
use Illuminate\Pagination\LengthAwarePaginator;

trait HasBasicCrud
{
    use HasHttpResponse;

    // Mengambil semua entitas
    public function getAll(array $relation = [])
    {
        return $this->model->with($relation)->latest()->get();
    }

    public function getAllPaginate(array $relation = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with($relation)->latest()->paginate($perPage);
    }

    /**
     * Find a model by its ID with specified relations.
     *
     * @param string $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findById(int $id)
    {
        $result = $this->model->find($id);

        $this->handleResourceNotExist($result);

        return $result;
    }

    public function findByIdRelation(int $id, array $relation = [])
    {
        return $this->model->find($id)->with($relation);
    }

    /**
     * Find a model by its UUID with specified relations.
     *
     * @param string $uuid
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByUuid(string $uuid)
    {
        $result = $this->model->where('uuid', $uuid)->first();

        $this->handleResourceNotExist($result);

        return $result;
    }

    /**
     * Find a model by its SLUG with specified relations.
     *
     * @param string $slug
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findBySlug(string $slug, array $relations = [])
    {
        $result = $this->model->where('slug', $slug)->with($relations)->first();

        $this->handleResourceNotExist($result);

        return $result;
    }

    /**
     * Find a model by its UUID with specified relations.
     *
     * @param string $uuid
     * @param array $relations
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findByUuidWithRelation(string $uuid, array $relations)
    {
        $result = $this->model->where('uuid', $uuid)->with($relations)->first();

        $this->handleResourceNotExist($result);

        return $result;
    }

    // Membuat entitas baru
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    // Membuat beberapa entitas baru sekaligus
    public function createMultiple(array $dataArray)
    {
        foreach ($dataArray as $data) {
            $this->create($data);
        }
    }

    // Memperbarui entitas berdasarkan ID
    public function updateById(int $id, array $data)
    {
        $entity = $this->findById($id);

        $entity->update($data);

        return $entity;
    }

    // Memperbarui beberapa entitas berdasarkan ID
    public function updateMultipleById(array $dataArray)
    {
        foreach ($dataArray as $data) {
            $entity = $this->findById($data['id']);
            $entity->update($data);
        }
    }

    // Memperbarui entitas berdasarkan UUID
    public function updateByUuid(string $uuid, array $data)
    {
        $entity = $this->findByUuid($uuid);

        $entity->update($data);

        return $entity;
    }

    // Memperbarui beberapa entitas berdasarkan UUID
    public function updateMultipleByUuid(array $dataArray)
    {
        foreach ($dataArray as $data) {
            $entity = $this->findByUuid($data['uuid']);
            $entity->update($data);
        }
    }

    // Menghapus entitas berdasarkan ID
    public function destroyById(int $id)
    {
        return $this->findById($id)->delete();
    }

    // Menghapus beberapa entitas berdasarkan ID
    public function destroyMultipleById(array $ids)
    {
        foreach ($ids as $id) {
            $this->findById($id)->delete();
        }
    }

    // Menghapus entitas berdasarkan UUID
    public function destroyByUuid(string $uuid)
    {
        return $this->findByUuid($uuid)->delete();
    }

    // Menghapus beberapa entitas berdasarkan UUID
    public function destroyMultipleByUuid(array $uuids)
    {
        foreach ($uuids as $uuid) {
            $this->findByUuid($uuid)->delete();
        }
    }
}
