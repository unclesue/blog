<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/7/8
 * Time: 16:55
 */

namespace App\Http\Form;


class File extends Field
{

    use UploadField;

    /**
     * Create a new File instance.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->initStorage();

        parent::__construct($column, $arguments);
    }

    /**
     * Default directory for file to upload.
     *
     * @return mixed
     */
    public function defaultDirectory()
    {
        return 'files';
    }

}
