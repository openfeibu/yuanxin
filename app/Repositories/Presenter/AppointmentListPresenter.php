<?php

namespace App\Repositories\Presenter;

use App\Repositories\Presenter\FractalPresenter;

class AppointmentListPresenter extends FractalPresenter
{

    /**
     * Prepare data to present
     *
     * @return \App\Repositories\Presenter\Api\ProjectShowTransformer
     */
    public function getTransformer()
    {
        return new AppointmentListTransformer();
    }
}
