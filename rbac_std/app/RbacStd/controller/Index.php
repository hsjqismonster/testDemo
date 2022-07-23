<?php
namespace app\RbacStd\controller;

use app\RbacStd\BaseController;
use think\facade\View;
use think\facade\Session;
use think\facade\Request;
use think\facade\Db;


class Index extends BaseController
{

    public function hello($name = 'ThinkPHP6')
    {
        return View::fetch();
    }

    public function index()
    {
		$tpl['username']=get_username();
        return view("Index/index", $tpl);
    }
	
	public function top_menu(){
		$access_list=get_access_list();
		$menu = array();
		foreach($access_list as $id=>$value){
			if($value['pid']==1){	
			//主菜单
				$value['id']=$id;
				$menu[]=$value;
			}
		}
		
		$sort = array(); 
		foreach ($menu as $item) { 
			$sort[] = $item['sort']; 
		}
		array_multisort($sort, SORT_ASC, $menu);
		$tpl['username']=get_username();
		$tpl['menu']	=$menu;
        return view("Index/top_menu", $tpl);
	}
	
	public function left_menu(){
		$pid=Request::param('pid');
		if($pid==null){
			$value["title"]="后台首页";
			$value["href"]=(string)url("Index/home");
			$menu[]=$value;
			$value["title"]="使用帮助";
			$value["href"]=(string)url("Index/help");
			$menu[]=$value;
		}else{
			$access_list=get_access_list();
			$menu = array();
			foreach($access_list as $id=>$value){
				if($value['pid']==$pid){	
				//辅菜单
					$value['id']=$id;
					$menu[]=$value;
				}
			}
			
			$sort = array(); 
			foreach ($menu as $item) { 
				$sort[] = $item['sort']; 
			}
			array_multisort($sort, SORT_ASC, $menu);
		}
		$tpl['username']=get_username();
		$tpl['menu']	=$menu;
        return view("Index/left_menu", $tpl);
	}


	public function home(){
		echo "今天是：". date("Y年m月d日")."</br></br>";
		$username = get_username();
	}



	public function help(){
		echo "我比较懒，没啥帮助的，请大家自己琢磨哈~";
		// $this->display();
	}

	public function change_password(){
		$username = get_username();
		$_post = input("");
		if(isset($_post['action'])&&($_post['action']=='submitted')){
			$condition = array();
			$condition['username']=$username;
			$mAdminUser = Db::name('AdminUser')->where($condition)->find();
			if(md5($_post['password'])===$mAdminUser['password']){
				$mAdminUser['password']=md5($_post['new_password']);
				if($num = Db::name('AdminUser')->save($mAdminUser)){
					$this->success('更新成功...', (string)url("home"), null, 3);
				}if($num == 0){
					$this->error('没有任何更新...', (string)url("home"), null, 3);
				}
				else{
					echo $AdminUser->getError();
				}
			}else{
				$this->error('旧密码错误...', (string)url("home"), null, 3);
			}
		}
		$tpl['username']=$username;
        return view("Index/change_password", $tpl);
	}
	

	
	private function getMenu(){
		$_access_list=$_SESSION['_ACCESS_LIST'];
		$menu = array();
		foreach($_access_list as $id=>$value){
			if($id!=0&&isset($value['title'])&&$value['title']!=''){
				if($value['pid']==0){	
				//主菜单
					$menu[$id]=$value;
				}else{
				//子菜单
					$menu[$value['pid']]['children'][$id]=$value;
				}
			}
		}
		return ($menu);
	}


}
