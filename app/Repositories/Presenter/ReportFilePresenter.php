<?php

namespace App\Repositories\Presenter;

use App\Repositories\Presenter\FractalPresenter;

class ReportFilePresenter extends FractalPresenter
{

    /**
     * Prepare data to present
     *
     * @return \App\Repositories\Presenter\ReportFileTransformer
     */
    public function getTransformer()
    {
        return new ReportFileTransformer();
    }
}
