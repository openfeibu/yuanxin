<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Services\ImageService;
use App\Traits\Upload;
use Filer,Input;
use Request;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class UploadController extends Controller
{
    use Upload;

    public function __construct(ImageService $image)
    {
        $this->image = $image;
    }
}
