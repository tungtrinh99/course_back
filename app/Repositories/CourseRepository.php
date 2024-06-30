<?php

namespace App\Repositories;

use App\Models\Course;

class CourseRepository extends BaseRepository {
    public function getModel()
    {
        return Course::class;
    }

    public function getAllCourses($attributes)
    {
        $searchField = $attributes['search_field'] ?? null;
        $searchText = $attributes['search_text'] ?? null;
        $perPage = $attributes['per_page'] ?? $this->perPage;
        $sortBy = $attributes['sort_by'] ?? 'created_at';
        $sortOrder = $attributes['sort_order'] ?? 'desc';
        $releaseDate = $attributes['release_date'] ?? null;
        $dateComparison = $attributes['date_comparison'] ?? null;

        $query = $this->model->orderBy($sortBy, $sortOrder);

        if ($searchField && $searchText) {
            $query = $query->where($searchField, 'LIKE', "%{$searchText}%");
        }

        if ($releaseDate) {
            $query = $query->whereDate('release_date', $dateComparison , $releaseDate);
        }

        return $query->paginate($perPage);
    }
}
