<?php

use think\facade\Db;
use think\facade\Session;
use think\facade\Request;

	function get_access_list(){
		$app_name = app("http")->getName();
		$app_auth_list = $app_name."AccessList";
		$auth_list=json_decode(Session::get($app_auth_list),true);
		if($auth_list==null) $auth_list=array();
		return $auth_list;
	}
	
	function get_username(){
		$app_name = app("http")->getName();
		$app_username = $app_name."Username";
		return Session::get($app_username);
	}
	
	function set_access_list($list){
		$app_name = app("http")->getName();
		$app_auth_list = $app_name."AccessList";
		$auth_list=json_decode(Session::get($app_auth_list),true);
		if($auth_list==null) $auth_list=array();
		return $auth_list;
	}

	function authenticate($sensitive=false){
		$app_name = app("http")->getName();
		$app_username = $app_name."Username";
		$app_auth_list = $app_name."AccessList";
		
		$condition=array();
		$condition['controller']=request()->controller(true);
		if($sensitive){
			$condition['method']=request()->action();
		}
		$node = Db::name('AdminNode')->where($condition)->find();//挑选有权限的node
		
		$username=Session::get($app_username);
		$auth_list=json_decode(Session::get($app_auth_list),true);
		
		if($node==null){
			return false; 
		};

		$id = $node['id'];
		if(!isset($username)){
			return url('Login/index'); 
		}else{
			if(!isset($auth_list[$id]['unicode'])){
				return false;
			}else{
				return true;
			}
		}
	}
	
	function get_resource_path(){
		$app_name = app("http")->getName();
		$base_file=Request::baseFile();
		$base_path = str_replace("index.php", "", $base_file);
		$resource_path = $base_path."/".$app_name;
		return $resource_path;
	}
