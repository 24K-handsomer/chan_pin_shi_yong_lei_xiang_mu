<?php
namespace app\admin\controller;

use think\Controller;

/*试用报告*/
class Report extends Base
{
	public function Reportlist(){

		//判断是否是post提交
    	if (request()->isPost()) {
    		$where = array('name' => input('post.name'));
    	}
    	else{
    		$where = array();
    	}
		try {
			$report = db('trial_report')->where($where)->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('list',$report);
		return $this->fetch();
	}

	
	public function reportshow(){
		$id = input('get.re_id');
		if (!empty($id)) {
			$dataattr = db('trial_report')->where('re_id',$id)->find();
		}
		else{
			$dataattr = null;
		}
		$this->assign('dataattr',$dataattr);
		return $this->fetch();
	}

	/*产品状态更改*/
	public function statuschange(){
		$code = 1;
    	if (request()->isPost()) {
    		$re_id = input('post.re_id');
    		$status = input('post.status');

        	try {
    			$re = db('trial_report')->where('re_id', $re_id)->setField('status',$status);
    		} catch (\Exception $e) {
    			$msg = $e->getMessage();
    			$code = 0;
    		}
    		if ($re) {
    			$msg = "更改成功";
    		}
    		else{
    			$msg = "更改失败";
    			$code = 0;
    		}

    	}
    	else{
    		$msg = "后台未接收数据";
    		$code = 0;
    	}
    	$datajson = [
			'code'=> $code,
			'msg' => $msg,
		];
		return json($datajson);
	}

}