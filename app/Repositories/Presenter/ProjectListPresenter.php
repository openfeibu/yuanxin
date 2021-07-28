<?php

namespace App\Repositories\Presenter;

use App\Repositories\Presenter\FractalPresenter;

class ProjectListPresenter extends FractalPresenter
{

    /**
     * Prepare data to present
     *
     * @return \App\Repositories\Presenter\Api\ProjectListTransformer
     */
    public function getTransformer()
    {
        return new ProjectListTransformer();
    }
}
