<?php
/**
 * Created by PhpStorm.
 * User: sithara_s
 * Date: 11/8/2017
 * Time: 4:38 PM
 */

namespace App\Controllers;


class SearchController
{
    protected $path;
    protected $data;

    public function view($name, $data=[])
    {
        $this->data = $data;

        $this->path = "app/views/{$name}.view.php";
    }

    public function getView()
    {
        return[
            'view' => $this->path,
            'data' => $this->data,
        ];
    }
}