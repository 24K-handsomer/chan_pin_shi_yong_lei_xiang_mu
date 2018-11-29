<?php
namespace app\admin\controller;

use think\Controller;

class User extends Base
{
	public function userlist(){

		//判断是否是post提交
    	if (request()->isPost()) {
    		$where = array('name' => input('post.name'));
    	}
    	else{
    		$where = array();
    	}
		try {
			$product = db('order')->where($where)->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('list',$product);
		return $this->fetch();
	}

}