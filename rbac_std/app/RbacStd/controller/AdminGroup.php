<?php
namespace app\RbacStd\controller;

use app\RbacStd\BaseController;
use think\facade\View;
use think\facade\Session;
use think\facade\Request;
use think\facade\Db;


class AdminGroup extends BaseController
{
	
	protected $need_auth_action = ["set_auth"];

	
	public function admin_group($keyword=''){
		$condition = array();
		$admin_group_list = Db::name("AdminGroup")->where($condition)->order('id')->select();
		$tpl['admin_group_list']=$admin_group_list;
        return view("AdminGroup/admin_group", $tpl);
	}
	
    public function create(){
        return view("AdminGroup/create");
	}

    public function update(){
		$id=input('id');
		$admin_group = Db::name("AdminGroup")->where('id='.$id)->find();
		$tpl['admin_group']=$admin_group;
        return view("AdminGroup/update", $tpl);
	}

    public function authority($show_hidden=false){
		$id=input('id');
		$admin_group = Db::name("AdminGroup")->where('id='.$id)->find();
		$rules = explode(",",$admin_group['rules']);
		if($show_hidden){
			$admin_node_list = Db::name("AdminNode")->order('level')->select();//挑选有权限的node
		}else{
			$admin_node_list = Db::name("AdminNode")->where("hidden",0)->order('level')->select();//挑选有权限的node
		}
		$admin_node_list_checked = array();
		foreach($admin_node_list as $key=>$value){
			$value['checked']='';
			if (in_array($value['id'], $rules)){
				$value['checked'] = 'checked';
			}
			$admin_node_list_checked[]=$value;
		}
		$admin_node_array = array();
		foreach($admin_node_list_checked as $node){
			switch($node['level']){
				case 0:
					$root_id = $node['id'];
					break;
				case 1:
					$admin_node_array[$node['id']]=$node;
					break;  
				case 2:
					$admin_node_array[$node['pid']]['children'][$node['id']]=$node;
					break;
				case 3:
					$admin_node_array[0]['children'][$node['id']]=$node;
					break;
				default:
			}
		}
		$admin_node_array[0]['id']=-1;
		$admin_node_array[0]['checked']=false;
		$admin_node_array[0]['unicode']='sensitive_node';
		$admin_node_array[0]['title']='敏感节点';

		foreach($admin_node_array as $key=>$value){
			if(!isset($value["children"])){
				unset($admin_node_array[$key]);
			}
		}

		$tpl['node_array']=$admin_node_array;
		$tpl['admin_group']=$admin_group;
		$tpl['root_id']=$root_id;
		return view("AdminGroup/authority", $tpl);
	}
	
	public function set_auth(){
		$_post=input('post.');
		$id = input('get.id');
		$rules='';
		foreach($_post as $key=>$value){
			if($key==$value && $key>0){
				$rules = $rules.','.$value;
			}
		}
		$rules=substr($rules, 1);
		$mAdminGroup['rules']=$rules;
		$mAdminGroup['id']=$id;
		if($num = Db::name('AdminGroup')->save($mAdminGroup)){
			$this->success('权限更新成功，重新登陆生效...', (string)url("admin_group"), null, 2);
		}else{
			$this->error('没有任何更新...', (string)url("admin_group"), null, 2);
		}
	}
	
    public function post(){
		$method = input('get.method');
		$AdminGroup = Db::name("AdminGroup");
		if($method == 'create'){
			$mAdminGroup=input('post.');
			$AdminNode=Db::name('AdminNode');
			$root_node = $AdminNode->where('level=0')->order('level')->find();//挑选有权限的node
			$mAdminGroup['rules']=$root_node['id'];
			if(Db::name("AdminGroup")->save($mAdminGroup)){
				$this->success('添加成功...', (string)url("admin_group"), null, 2);
			}else{
				$this->error('没有任何更新...', (string)url("admin_group"), null, 2);
			}
		}
		else if($method == 'update'){
			$mAdminGroup=input('post.');
			if($num = Db::name("AdminGroup")->where('id='.input('get.id'))->save($mAdminGroup)){
				$this->success('更新成功...', (string)url("admin_group"), null, 2);
			}else{
				$this->error('没有任何更新...', (string)url("admin_group"), null, 2);
			}
		}
		else if($method == 'delete'){
			if(Db::name("AdminGroup")->where('id='.I('get.id'))->delete()){
				$this->success('删除成功...', (string)url("admin_group"), null, 2);
			}else{
				$this->error('没有任何更新...', (string)url("admin_group"), null, 2);
			}
		}else{
			$this->error('参数错误...', (string)url("admin_group"), null, 2);
		}
	}

}
