<?php
namespace app\Edubot\controller;

use app\Edubot\BaseController;

class Compiler extends BaseController
{
    public function index()
    {
        return 'None';
    }

//https://localhost/edubot/compiler/tp6/public/edubot/compiler/test?token=5
    public function test($token)
    {
		return dump($this->request->param("token"));
    }

    public function launche($token)
    {
		//verification with token;
		//check and update the user's information;
		//check the model reference;
		//create a ticket to compile in db;
		//return with ticket;
		return dump($this->request->param("token"));
    }
	
    public function query($token, $ticket)
    {
		//verification with token;
		//check the user's information;
		//check and return with ticket;
		
		return dump($this->request->param("token"));
    }

    public function pull_job($wtoken)
    {
		return dump($this->request->param("token"));
    }

    public function push_job($wtoken, $job_id)
    {
		return dump($this->request->param("token"));
    }

}
