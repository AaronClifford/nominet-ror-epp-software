<?php

namespace App\System;
class Config
{

    public function __construct()
    {
        return $this->data();
    }

    public function data()
    {
        $this->config = include("config/Config.php");

        return $this->config;
    }

}