<?php
namespace app\admin\controller;

use think\Controller;

class Application extends Base
{
	public function Applicationlist(){

		//判断是否是post提交
    	if (request()->isPost()) {
    		$where = array('name' => input('post.name'));
    	}
    	else{
    		if (request()->isGet()) {
    			$where = array('p_type' => input('get.p_type')); 
    			$file = input('get.p_type') == 2 ? 'Trialreportlist' : 'Applicationlist';
    		}
    		else{
    			$where = array();
    		}
    	}
		try {
			$application = db('application')->where($where)->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('list',$application);
		return $this->fetch($file);
	}

	public function Applicationshow(){
		if (request()->isGet()) {
			$paid = input('get.pa_id');
			$userid = input('get.use_id');
			try{
				$qulist = db('question')->where("pid = $paid AND status = 1")->select();
				$anlist = db('answer')->where("pa_id = $paid AND use_id = $userid")->select();
			}catch(\Exception $e){
				$this->error($e->getMessage());
			}

			foreach ($qulist as $v) {
				foreach ($anlist as $anv) {
					if ($v['qu_id'] == $anv['qu_id']) {
						$row = array(
							'qu_id' => $v['qu_id'],
							'qu_name' => $v['qu_name'],
							'qu_type' => $v['qu_type'],
							'select01' => $v['select01'],
							'select02' => $v['select02'],
							'select03' => $v['select03'],
							'select04' => $v['select04'],
							'select05' => $v['select05'],
							'select06' => $v['select06'],
							'content' => $v['content'],
							'isrequired' => $v['isrequired'],
							'an_content' => $anv['an_content'],
							'number' => $anv['number']
						);
						break;
					}
				}
				$arrlist[] = $row;
				unset($row);
			}

			$this->assign('arrlist',$arrlist);
			return $this->fetch();
		}

		
	}


	function applicationdel(){
		//判断是否是post提交
		$code = 1;
    	if (request()->isPost()) {
    		$id = input('post.app_id');  //变量获取
    		try {
    			$re = db('application')->where('app_id',$id)->delete();
    		} catch (\Exception $e) {
    			$msg = $e->getMessage();
    			$code = 0;
    		}
    		if ($re) {
    			$msg = "删除成功";
    		}
    		else{
    			$msg = "删除失败";
    			$code = 0;
    		}
    	}
    	else{
    		$msg = "后台未接收id";
    		$code = 0;
    	}

    	$datajson = [
			'code'=> $code,
			'msg' => $msg,
		];
		return json($datajson);
	}
	
	
	/*地址展示*/
	function ordershow(){
		if (request()->isGet()) {
			$orid = input("get.or_id");
			try{
				$order = db('order')->where("id",$orid)->find();
			}catch(\Exception $e){
				$this->error($e->getMessage());
			}
			
			$this->assign('order',$order);
			return $this->fetch();
		}
	}


	/*产品状态更改*/
	public function statuschange(){
		$code = 1;
    	if (request()->isPost()) {
    		$app_id = input('post.app_id');
    		$status = input('post.status');

        	try {
    			$re = db('application')->where('app_id', $app_id)->setField('status',$status);
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

	/*导出excel表单*/
	public function downloadexcel(){
		if (request()->isPost()) {
			$data = input("param.");

			if (empty($data['protitle'])){
				$this->error("需要填写产品名");
			}
			$where['pro_title'] = $data['protitle'];
			$where['p_type'] = (int)$data['p_type'];
			$filename = $data['protitle'].'_';

			if (!empty($data['starttime']) && !empty($data['endtime'])){
				$field = "create_time";
				$op = "between";
				$range = [$data['starttime'].' 00:00:00',$data['endtime'].' 23:59:59'];
				$filename .= $data['starttime'].'--'.$data['endtime'];
			} elseif (empty($data['starttime']) && !empty($data['endtime'])) {
				$field = "create_time";
				$op = "<=";
				$range = $data['endtime']." 23:59:59";
				$filename .= "小于".$data['endtime'];
			} elseif (!empty($data['starttime']) && empty($data['endtime'])) {
				$field = "create_time";
				$op = ">=";
				$range = $data['starttime']." 00:00:00";
				$filename .= "大于".$data['starttime'];
			} else{
				$field = "create_time";
				$op = "<=";
				$range = date('Y-m-d H:i:s');
				$filename .= "小于".date('Y-m-d H:i:s');
			}

			$applist = db("application")->where($where)->whereTime($field,$op,$range)->order("create_time desc")->select();

			$paper = db("paper")->where('pro_id',$applist[0]['pro_id'])->find();

			$where01['pid'] = $paper['p_id'];
			$where01['status'] = 1;
			$question = db("question")->where($where01)->select();

			$anlist = db("answer")->where('pa_id',$paper['p_id'])->whereTime($field,$op,$range)->order("create_time desc")->select();
			$letter = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			$header = array("姓名","时间");
			$letterarr = array('A','B');
			foreach ($question as $k => $v) {
				$header[] = $v['qu_name'];
				$letterarr[] = $letter[$k+2];
			}


			foreach ($applist as $app) {
				foreach ($anlist as $an) {
					if ($app['use_id'] == $an['use_id']) {
						$row[0] = $app['use_name'];
						$row[1] = $app['create_time'];
						$row[] = $an['an_content'];
					}
				}
				$arrlist[] = $row;
				unset($row);
			}

			/*echo "<pre>";
			var_dump($arrlist);
			var_dump($header);
			var_dump($letterarr);
			echo "</pre>";*/

			excelFileExport($filename,$arrlist,$header,$letterarr);

		}
		else{
			$this->error("需要填写产品名等信息");
		}

	}



}