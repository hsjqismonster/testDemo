<?php
namespace app\RbacStd\controller;

use app\RbacStd\BaseController;
use think\facade\View;
use think\facade\Session;
use think\facade\Request;
use think\facade\Db;


class Login extends BaseController
{
	protected $need_auth = false;
	
    public function index(){
		return view("Login/index");
    }

	public function logout(){
		$app_name = app("http")->getName();
		$app_username = $app_name."Username";
		$app_auth_list = $app_name."AccessList";
		Session::set($app_auth_list,null);
		Session::set($app_username,null);
		return redirect((string)url('Login/index'));
	}
	
	public function verify(){
		if(!Request::isAjax()){
			return("页面不存在");
		}else{
			$_post =input('');
			if(!empty($_post['username']) && !empty($_post['password'])){
				if($_post['username']==KEY_USER){
					if(md5($_post['password'])===md5(KEY_PASSWORD)){
						$this->set_access_list(KEY_USER);
						$data['status'] = 1;  
						$data['info'] = "登陆成功";  
						$data['url'] = (string)url('Index/index');
					}
				}else{
					$condition = array();
					$condition['username']=$_post['username'];
					$condition['password']=md5($_post['password']);
					$mAdminUser = Db::name('AdminUser')->where($condition)->find();

					if($mAdminUser){
						$mAdminUser['last_login_time']=date('Y-m-d H:i:s');
						$mAdminUser['last_login_ip']=get_real_ip();
						$mAdminUser['login_num']=$mAdminUser['login_num']+1;
						
						$condition=array();
						$condition["id"]=$mAdminUser["id"];
						Db::name('AdminUser')->where($condition)->save($mAdminUser);
						
						$ret = $this->set_access_list($_post['username']);
						
						$data['status'] = 1;  
						$data['info'] = "登陆成功";  
						$data['url'] = (string)url('Index/index');
					}
					else{
						$data['status'] = 0;
						$data['info'] = '账号或密码错误';  
						$data['url'] = (string)url('Login/index');
					}
		
				}
			}
			else{
				$data['status'] = 0;  
				$data['info'] = '参数为空';  
				$data['url'] = (string)url('Login/index');
			}
			return json()->data($data);
		}
	}


	private function set_access_list($username){
		$app_name = app("http")->getName();
		$app_username = $app_name."Username";
		$app_auth_list = $app_name."AccessList";

		Session::set($app_username,$username);
		Session::set($app_auth_list,null);			
		$auth_list = array();
		if($username==KEY_USER){
			$admin_node_list = Db::name("AdminNode")->order('id')->select();
			foreach($admin_node_list as $key=>$value){
				//检测name，name非空则表示拥有权限
				$auth_list[$value['id']]['unicode']=$value['unicode'];
				
				//存储title的二级树结构，用于生成菜单
				$auth_list[$value['id']]['title']=$value['title'];
				$auth_list[$value['id']]['pid']=$value['pid'];
				$auth_list[$value['id']]['sort']=$value['sort'];
				$URL=$value['controller'].'/'.$value['method'];
				$auth_list[$value['id']]['href']=(string)url($URL);
			}
		}else{
			$condition = array();
			$condition['username']=$username;

			$admin_group_id = Db::name('AdminUser')->where($condition)->value('admin_group_id');
			$condition = array();
			$condition['id']=$admin_group_id;
			$admin_group = Db::name('AdminGroup')->where($condition)->find();
			$admin_node_list = Db::name("AdminNode")->where("id","in", $admin_group["rules"])->order('id')->select();

			foreach($admin_node_list as $key=>$value){
				//检测name，name非空则表示拥有权限
				$auth_list[$value['id']]['unicode']=$value['unicode'];
				
				//存储title的二级树结构，用于生成菜单
				$auth_list[$value['id']]['title']=$value['title'];
				$auth_list[$value['id']]['pid']=$value['pid'];
				$auth_list[$value['id']]['sort']=$value['sort'];
				$URL=$value['controller'].'/'.$value['method'];
				$auth_list[$value['id']]['href']=(string)url($URL);
			}
		}
		Session::set($app_auth_list,json_encode($auth_list));
		return;
	}
}
