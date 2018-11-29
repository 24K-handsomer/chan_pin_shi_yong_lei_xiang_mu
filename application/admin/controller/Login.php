<?php
namespace app\admin\controller;

use think\Controller;

/**
* 
*/
class Login extends Base
{

    public function _initialize() {
    }
    public function index()
    {
        $isLogin = $this->isLogin();
        if($isLogin) {
            return $this->redirect('index/index');
        }else {
            // 如果后台用户已经登录了， 那么我们需要跳到后台页面
            return $this->fetch();
        }
    }

	/**
     * 登录相关业务
     */
    public function check() {
        if(request()->isPost()) {
            $data = input('post.');
            //halt($data['code']);
            if (!captcha_check($data['captcha'],1)) {
                $this->error('验证码不正确');
            }
            // 判定 username password
            // validate机制 小伙伴自行完成

            try {
                $user = db('admin_user')->where('username',$data['username'])->find();
            }catch (\Exception $e) {
                $this->error($e->getMessage());
            }

            if (!$user || $user['status'] == 0) {
                $this->error('改用户不存在');
            }

            // 再对密码进行校验
            if (md5($data['password'].'_#sing_ty') != $user['password']) {
                $this->error('密码不正确');
            }
            // 1 更新数据库 登录时间 登录ip
            $udata = [
                'last_login_time' => date("Y-m-d H:i:s"),
                'last_login_ip' => request()->ip(),
            ];

            try {
                db('admin_user')->where('id',$user['id'])->update($udata);
            }catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            // 2 session
            session('adminuser', $user);
            $this->success('登录成功', 'index/index');

            //halt($user);
        }else {
            $this->error('请求不合法');
        }
    }

	public function welcome() {
		return "hello api-admin";
	}


	/**
     * 退出登录的逻辑
     * 1、清空session
     * 2、 跳转到登录页面
     */
    public function logout() {
        session(null);
        // 跳转
        $this->redirect('login/index');
    }
}


