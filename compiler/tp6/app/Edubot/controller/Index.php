<?php
namespace app\Edubot\controller;

use app\Edubot\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return 'This is a index page. Welcome to use';
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
