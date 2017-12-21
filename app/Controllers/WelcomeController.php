<?php
/**
 * Created by PhpStorm.
 * User: sithara_s
 * Date: 11/8/2017
 * Time: 5:07 PM
 */

namespace App\Controllers;


class WelcomeController
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