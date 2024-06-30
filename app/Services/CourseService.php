<?php

namespace App\Services;

use App\Repositories\CourseRepository;

class CourseService
{
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAllCourses($attributes)
    {
        return $this->courseRepository->getAllCourses($attributes);
    }

    public function findOneCourse($id)
    {
        return $this->courseRepository->findOrFail($id);
    }
}
