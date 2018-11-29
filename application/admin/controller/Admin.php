<?php
namespace app\admin\controller;

use think\Controller;

class Admin extends controller
{
    public function add() {
    	//判断是否是post提交	
    	if (request()->isPost()) {
    		//dump(input('post.')); //打印提交的数据；halt(); => dump();exit;
    		//halt(input('post.'));
    		$data = input('post.');  //变量获取  

    		$validate = validate('AdminUser');
    		if (!$validate->check($data)) {
    			$this->error($validate->getError());
    		}
    		else{
                $data['password'] = md5($data['password'].config('app.password_pre_halt'));
    			$data['status'] = 1;
                
                try {
                    $id = model('AdminUser')->add($data);
                } catch (\Exception $e) {
                    $this->error($e->getMessage());
                }
                if ($id) {
                    $this->success("id=".$id."的用户新增成功");
                }
                else{
                    $this->error("用户增加失败");
                }
    		}
	
    	}
    	else{
    		return $this->fetch();
    	}
        
    }

}