<?php
namespace app\RbacStd\controller;

use app\RbacStd\BaseController;
use think\facade\View;
use think\facade\Session;
use think\facade\Request;
use think\facade\Db;


class AdminNode extends BaseController
{
	protected $need_auth_action = ["post"];
	
	public function admin_node($keyword=''){
		$admin_node_list = Db::name("AdminNode")->order('level')->select();
		$tpl['admin_node_list']=$admin_node_list;
        return view("AdminNode/admin_node", $tpl);
	}
	
    public function create(){
		$condition=array();
		$condition[] = array('level','<=','2');
		$admin_node_list = Db::name("AdminNode")->where($condition)->order('id')->select();
		$tpl['admin_pnode_list']=$admin_node_list;

        return view("AdminNode/create", $tpl);
	}

    public function update(){
		$condition=array();
		$condition[] = array('level','<=','2');
		$admin_node_list = Db::name("AdminNode")->where($condition)->order('id')->select();
		$tpl['admin_pnode_list']=$admin_node_list;
		
		$id=input('id');
		$admin_node = Db::name("AdminNode")->where('id='.$id)->find();
		$tpl['node']=$admin_node;

        return view("AdminNode/update", $tpl);
	}

    public function post(){
		$method = input('get.method');
		if($method == 'create'){
			$mAdminNode=input('post.');
			$condition=array();
			$condition["id"] = $mAdminNode["pid"];
			$pnode = Db::name("AdminNode")->where($condition)->find();
			if($pnode==null) return;
			$mAdminNode["level"]=$pnode["level"]+1;

			if(Db::name("AdminNode")->save($mAdminNode)){
				$this->success('添加成功...', (string)url("admin_node"), null, 2);
			}else{
				echo $AdminNode->getError();
			}
		}
		else if($method == 'update'){
			$mAdminNode=input('post.');
			$condition=array();
			$condition["id"] = $mAdminNode["pid"];
			$pnode = Db::name("AdminNode")->where($condition)->find();
			if($pnode==null) return;
			$mAdminNode["level"]=$pnode["level"]+1;
			$mAdminNode["id"] = input("id");

			if($num = Db::name("AdminNode")->save($mAdminNode)){
				$this->success('更新成功...', (string)url("admin_node"), null, 2);
			}else{
				$this->error('没有任何更新...', (string)url("admin_node"), null, 2);
			}
		}
		else if($method == 'delete'){
			if(Db::name("AdminNode")->where('id='.input('get.id'))->delete()){
				$this->success('删除成功...', (string)url("admin_node"), null, 2);
			}else{
				echo $AdminNode->getError();
			}
		}else{
			$this->error('参数错误...', (string)url("admin_node"), null, 2);
		}
	}

}
