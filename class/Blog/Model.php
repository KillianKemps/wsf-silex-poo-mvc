<?php
namespace Blog;

Class Model
{
    public $app;
    public $sql;

    public function __construct($app)
    {
        $this->app = $app;
        $this->sql = $app['sql'];
    }
}
