<?php
namespace app\Index\controller;

use app\Index\BaseController;

class Index extends BaseController
{
    public function index()
    {
        return 'This is a test page. Welcome to use';
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }
}
