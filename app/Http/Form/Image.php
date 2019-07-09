<?php

namespace App\Http\Form;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image extends File
{
    use ImageField;

    /**
     * @param array|UploadedFile $image
     *
     * @return string
     */
    public function prepare($image)
    {
        if (request()->has('_file_del_')) {
            return $this->destroy();
        }

        $this->name = $this->getStoreName($image);

        $this->callInterventionMethods($image->getRealPath());

        return $this->uploadAndDeleteOriginal($image);
    }

}
