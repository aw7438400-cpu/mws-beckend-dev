<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait HasBasicSearch
{
    public function search(array $relation = [], int $perPage = 10, ?string $query = null, array $searchableColumns = []): LengthAwarePaginator
    {
        $queryBuilder = $this->model->with($relation);

        if (!empty($query) && !empty($searchableColumns)) {
            $queryBuilder->where(function ($q) use ($query, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', '%' . $query . '%');
                }
            });
        }

        return $queryBuilder->latest()->paginate($perPage);
    }

    // public function searchSchoolId(array $relation = [], int $perPage = 10, ?string $query = null, ?string $school_id = null, array $searchableColumns = []): LengthAwarePaginator
    // {
    //     $queryBuilder = $this->model->with($relation);

    //     // Jika school_id diberikan, tambahkan filter berdasarkan school_id
    //     if (!empty($school_id)) {
    //         $queryBuilder->where('school_id', $school_id);
    //     } else {
    //         throw new \InvalidArgumentException('School ID is required.');
    //     }

    //     // Pencarian berdasarkan query dan searchableColumns
    //     if (!empty($query) && !empty($searchableColumns)) {
    //         $queryBuilder->where(function ($q) use ($query, $searchableColumns) {
    //             foreach ($searchableColumns as $column) {
    //                 $q->orWhere($column, 'like', '%' . $query . '%');
    //             }
    //         });
    //     }

    //     // Return hasil dengan pagination
    //     return $queryBuilder->latest()->paginate($perPage);
    // }

    public function searchWithCompanyID(array $relation = [], int $perPage = 10, ?string $query = null, array $searchableColumns = [])
    {
        $companyId = auth()->user()->company_id;
        $queryBuilder = $this->model->with($relation)
            ->where('company_id', $companyId);

        if (!empty($query) && !empty($searchableColumns)) {
            $queryBuilder->where(function ($q) use ($query, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', '%' . $query . '%');
                }
            });
        }

        return $queryBuilder->latest()->paginate($perPage);
    }

    public function searchWithAuthUserId(array $relation = [], int $perPage = 10, ?string $query = null, array $searchableColumns = []): LengthAwarePaginator
    {
        $user_id = auth()->user()->id;
        $queryBuilder = $this->model->with($relation)->where('user_id', $user_id);

        if (!empty($query) && !empty($searchableColumns)) {
            $queryBuilder->where(function ($q) use ($query, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', '%' . $query . '%');
                }
            });
        }

        return $queryBuilder->latest()->paginate($perPage);
    }
}
