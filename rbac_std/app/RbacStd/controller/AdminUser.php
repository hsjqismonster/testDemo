<?php
namespace app\RbacStd\controller;

use app\RbacStd\BaseController;
use think\facade\View;
use think\facade\Session;
use think\facade\Request;
use think\facade\Db;


class AdminUser extends BaseController
{
	
	protected $need_auth_action = ["post"];

	public function admin_user($keyword=''){
		$admin_user_list = Db::name("AdminUser")->alias("u")->join("AdminGroup g", "u.admin_group_id=g.id")->order('u.id')->select();
		$tpl['admin_user_list']=$admin_user_list;
        return view("AdminUser/admin_user", $tpl);
	}
	
    public function create(){
		$admin_group_list = Db::name("AdminGroup")->order('id')->select();
		$tpl['admin_group_list']=$admin_group_list;
        return view("AdminUser/create", $tpl);
	}

    public function update(){
		$id=input('id');
		$admin_user = Db::name("AdminUser")->where('id='.$id)->find();
		$tpl['admin_user']=$admin_user;
		
		$admin_group_list = Db::name("AdminGroup")->order('id')->select();
		$tpl['admin_group_list']=$admin_group_list;

        return view("AdminUser/update", $tpl);
	}

    public function post(){
		$method = input('get.method');
		if($method == 'create'){
			$mAdminUser=input('post.');
			if($mAdminUser['username']==KEY_USER){
				$this->error('用户名不被许可...', (string)url("admin_user"), null, 2);
			}
			$mAdminUser['password']=md5($mAdminUser['password']);
			if(Db::name("AdminUser")->save($mAdminUser)){
				$this->success('添加成功...', (string)url("admin_user"), null, 2);
			}else{
				$this->error('没有任何更新...', (string)url("admin_user"), null, 2);
			}
		}
		else if($method == 'update'){
			$mAdminUser=input('post.');
			if($mAdminUser['username']==KEY_USER){
				$this->error('用户名不被许可...', (string)url("admin_user"), null, 2);
			}
			if($mAdminUser['password']== ''){
				unset($mAdminUser['password']);
			}
			else{
				$mAdminUser['password']=md5($mAdminUser['password']);
			}
			if($num = Db::name("AdminUser")->where('id='.input('get.id'))->save($mAdminUser)){
				$this->success('更新成功...', (string)url("admin_user"), null, 2);
			}else{
				$this->error('没有任何更新...', (string)url("admin_user"), null, 2);
			}
		}
		else if($method == 'delete'){
			if(Db::name("AdminUser")->where('id='.input('get.id'))->delete()){
				$this->success('删除成功...', (string)url("admin_user"), null, 2);
			}else{
				$this->error('没有任何更新...', (string)url("admin_user"), null, 2);
			}
		}else{
			$this->error('参数错误...', (string)url("admin_user"), null, 2);
		}
	}

}
