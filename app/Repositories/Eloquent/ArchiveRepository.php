<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\ArchiveRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class ArchiveRepository extends BaseRepository implements ArchiveRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.archive.archive.search');
    }
    public function model()
    {
        return config('model.archive.archive.model');
    }

}