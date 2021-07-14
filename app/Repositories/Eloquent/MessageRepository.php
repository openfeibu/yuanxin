<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\MessageRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class MessageRepository extends BaseRepository implements MessageRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.message.message.search');
    }
    public function model()
    {
        return config('model.message.message.model');
    }


}