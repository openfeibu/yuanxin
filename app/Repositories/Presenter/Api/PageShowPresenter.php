<?php

namespace App\Repositories\Presenter\Api;

use App\Repositories\Presenter\FractalPresenter;

class PageShowPresenter extends FractalPresenter
{

    /**
     * Prepare data to present
     *
     * @return \App\Repositories\Presenter\Api\PageShowTransformer
     */
    public function getTransformer()
    {
        return new PageShowTransformer();
    }
}
