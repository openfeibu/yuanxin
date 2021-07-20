<?php

namespace App\Repositories\Presenter\Api;

use App\Repositories\Presenter\FractalPresenter;

class AppointmentDatePresenter extends FractalPresenter
{

    /**
     * Prepare data to present
     *
     * @return \App\Repositories\Presenter\Api\ProjectShowTransformer
     */
    public function getTransformer()
    {
        return new AppointmentDateTransformer();
    }
}
