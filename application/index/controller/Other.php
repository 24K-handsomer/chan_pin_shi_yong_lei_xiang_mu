<?php
namespace app\index\controller;

use think\Controller; 

class Other extends Controller
{
    public function showlog()
    {
    	$data = input('get.');
    	$this->assign('pro_id',$data['pro_id']);
    	$this->assign('pro_title',$data['pro_title']);
        $this->assign('pro_status',$data['pro_status']);
    	return $this->fetch();
    }

    public function addressadd(){
    	if (request()->isGet()) {
    		$user_id = input("get.userid");
    		$name = input("get.name");
    		$telephone = input("get.telephone");

    		$this->assign('user_id',$user_id);
    		$this->assign('name',$name);
    		$this->assign('telephone',$telephone);
    		return $this->fetch();
    	}
    	
    }


    public function addresslist(){
    	if (request()->isGet()) {
    		$user_id = input("get.userid");
    		$name = input("get.name");
    		$telephone = input("get.telephone");

    		$addresslist = db("order")->distinct(true)->field('address')->where('user_id',$user_id)->select();

    		$this->assign('addresslist',$addresslist);
    		$this->assign('user_id',$user_id);
    		$this->assign('name',$name);
    		$this->assign('telephone',$telephone);
    		return $this->fetch();
    	}
    	
    }



    public function paper() {
    	if (request()->isGet()) {
    		$data = input("get.");  //userid、username、proid、protitle、or_id；
    		$this->assign('getdata',$data);

            $where['pro_id'] = $data['proid'];
            $where['p_type'] = $data['p_type'];
            $file = $data['p_type'] == 1 ? 'paper' : 'paper_2';

            $paper = db("paper")->where($where)->find();
            $this->assign('paper',$paper);

            $where01['pid'] = $paper['p_id'];
            $where01['status'] = 1;
            $questionlist = db("question")->where($where01)->select();
            $this->assign('questionlist',$questionlist);
    		return $this->fetch($file); 
    	}
    }


    public function trialreport() {
    	if (request()->isGet()) {
    		$data = input("get.");
    		$product = db('product')->field('trial_requirements')->where('pro_id',$data['proid'])->find();

    		$this->assign('trial_requirements',$product['trial_requirements']);
    		$this->assign('user_id',$data['userid']);
    		$this->assign('user_name',$data['username']);
    		$this->assign('pro_id',$data['proid']);
    		$this->assign('pro_title',$data['protitle']);
    		return $this->fetch();
    	}


    }


    public function showmingdan(){
        if (request()->isGet()) {
            $proid = input("get.pro_id");
            $uselist = db('application')->field('use_id')->where("pro_id = $proid AND status = 1")->select();

            foreach ($uselist as $useid) {
                $row = db('user')->field('name,telephone')->where('id',$useid['use_id'])->find();
                $name = splitName($row['name']);
                $n['name'] = $name[0].preg_replace('/([\x80-\xff]*)/i','*',$name[1]);
                $n['tele'] = substr_replace($row['telephone'], '****', 3, 4);
                $list[] = $n;
                unset($row);
                unset($name);
                unset($n);
            }
            $this->assign("uselist",$list);
            return $this->fetch();
        }
    }

}