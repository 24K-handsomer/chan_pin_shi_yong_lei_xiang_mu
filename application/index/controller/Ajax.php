<?php
namespace app\index\controller;

use think\Controller;
use think\Upload;

class Ajax extends Controller
{
    public function addproduct()
    {

    	//判断是否是post提交
    	$code = 1;
    	$product = array();
    	if (request()->isGet()) {
    		$where = input('get.catid') ? array('category' => input('get.catid'),'stage' => 1) : array('stage' => 1);
    		$pageid = (input('get.pageNo')-1) * 10;
    		
    		try {
				$product = db("product")->field('pro_id,pro_title,pro_thumbnail,trial_number,pro_applications,pro_price,category,status,stage,start_time,end_time,create_time')->where($where)->order('field(status,2,3,4,1,5),start_time desc,end_time desc')->limit($pageid,10)->select();
			} catch (\Exception $e) {
				$this->error($e->getMessage());
			}

			if ($product) {
				$msg = "查询成功";
				
			}
			else{
				$msg = "查询失败";
				$code = 0;
			}

    	}
    	else{
    		$msg = "后台未接收到传递的值";
			$code = 0;
    	}
		
		$datajson = [
			'resultList' => $product,
			'code' => $code,
			'msg' => $msg
		];
		return jsonp($datajson);
    }


    public function checklog()
    {
    	//判断是否是post提交
    	$code = 1;
    	$user = array();
    	if (request()->isPost()) {
    		$username = trim(input('post.username'));
    		$telephone = trim(input('post.mobile'));
    		$pro_id = input('post.pro_id');
    		$pro_title = input('post.pro_title');
    		$pro_status = strval(input('post.pro_status'));

    		$where = array('telephone' => $telephone);
    		try {
				$user = db("user")->where($where)->find();
			} catch (\Exception $e) {
				$this->error($e->getMessage());
			}

			if ($pro_status == "2") {
				# code...
				if ($user) {
					if ($username != $user['name']) {
						$msg = "姓名和手机号不同,该手机号码已被注册过";
						$code = 0;
					}
					else{
						/*$msg = "该申请人和手机号码已存在";
						$code = 1;*/
						$order = $this->ordercheck($user['id'],$pro_id,$pro_title); //正确返回数据id,失败返回0
						if ($order) {
							$app = $this->applicationcheck($user['id'],$pro_id,$order,1);
							switch ($app) {
								case 1:
									$code = 1.1;
									$msg = "该用户该产品下申请书通过审核";
									/*$trialreport = $this->newtrialreportcheck($user['id'],$pro_id,$order,2);//正确返回数据id,失败返回0 
									if ($trialreport) {
										$code = 1.11;
										$msg = "该用户该产品下已经提交试用报告";
										$user = $user;
									}
									else{
										$code = 1.12;
										$msg = "该用户该产品下没有提交试用报告,请填写试用报告";
										$user = array('user_id' => $user['id'],'name'=>$user['name'],'pro_id'=>$pro_id,'pro_title'=>$pro_title,'or_id'=>$order);
									}*/

									break;

								case 2:
									$code = 1.2;
									$msg = "该用户该产品下申请书未通过审核";
									break;

								case 3:
									$code = 1.3;
									$msg = "该用户该产品下申请书正在审核";
									$user = $user;
									break;
								
								default:
									$code = 1;
									$msg = "未找到该用户该产品下的申请,请写申请";
									$user = array('user_id' => $user['id'],'name'=>$user['name'],'pro_id'=>$pro_id,'pro_title'=>$pro_title,'or_id'=>$order);
									break;
							}

						}
						else{
							$code = 2;
							$user = $user;
							$msg = "未找到该用户该产品下的地址信息";
						}
					}
					
				}
				else{
					//没有该手机号码，注册
					$data = [
						'name' => $username,
						'telephone' => $telephone,
		            	'last_login_ip' => request()->ip(),
		            	'last_login_time' => date("Y-m-d H:i:s"),
		            	'create_time' => date("Y-m-d H:i:s")
					];
					$userid = db('user')->insertGetId($data);
					if ($userid) {
						$data['id'] = $userid;
						$user = $data;
						$msg = "添加用户成功";
						$code = 2;

					}
					else{
						$msg = "申请人和手机号未注册成功";
						$code = 0;
					}
				}
			}

			if ($pro_status == "4") {

				$order = $this->ordercheck($user['id'],$pro_id,$pro_title); //正确返回数据id,失败返回0
				if ($order) {
					$app = $this->applicationcheck($user['id'],$pro_id,$order,1);
					switch ($app) {
						case 1:
							$trialreport = $this->newtrialreportcheck($user['id'],$pro_id,$order,2);//正确返回数据id,失败返回0 
							if ($trialreport) {
								$code = 1.11;
								$msg = "该用户该产品下已经提交试用报告";
								$user = $user;
							}
							else{
								$code = 1.12;
								$msg = "该用户该产品下没有提交试用报告,请填写试用报告";
								$user = array('user_id' => $user['id'],'name'=>$user['name'],'pro_id'=>$pro_id,'pro_title'=>$pro_title,'or_id'=>$order);
							}
							break;
						case 2:
							$code = 1.2;
							$msg = "该用户该产品下申请书未通过审核";
							break;
						case 3:
							$code = 1.3;
							$msg = "该用户该产品下申请书正在审核";
							$user = $user;
							break;
						default:
							$code = 0.1;
							$msg = "申请试用结束，不在试用人员内";
							break;
					}
						
				}
				else{
					$msg = "申请试用结束，不在试用人员内";
					$code = 0.1;
				}
				
			}
    	}
    	else{
    		$msg = "后台未接收到传递的值";
			$code = 0;
    	}
		
		$datajson = [
			'code' => $code,
			'msg' => $msg,
			'resultList' => $user
		];
		return json($datajson);
    }


    public function ordercheck($user_id,$pro_id,$pro_title){
    	$where['user_id'] = $user_id;
    	$where['pro_id'] = $pro_id;
    	$where['pro_title'] = $pro_title;
  
    	try {
			$order = db("order")->where($where)->find();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		if ($order) {
			$code = $order['id'];
			$msg = "找到该用户该产品下的地址信息";
		}
		else{
			$code = 0;
			$msg = "未找到该用户该产品下的地址信息";
		}
		return $code;
    }

    public function ordercheck01($user_name,$telephone,$pro_id,$pro_title){
    	$where['name'] = $user_name;
    	$where['telephone'] = $telephone;
    	$where['pro_id'] = $pro_id;
    	//$where['pro_title'] = $pro_title;
  
    	try {
			$order = db("order")->where($where)->find();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		if ($order) {
			$code = $order['id'];
			$msg = "找到该用户该产品下的地址信息";
		}
		else{
			$code = 0;
			$msg = "未找到该用户该产品下的地址信息";
		}
		return $code;
    }


    public function applicationcheck($user_id,$pro_id,$or_id,$p_type){
    	$where['use_id'] = $user_id;
    	$where['pro_id'] = $pro_id;
    	$where['or_id'] = $or_id;
    	$where['p_type'] = $p_type;
  
    	try {
			$app = db("application")->where($where)->find();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		if ($app) {
			if ($app['status'] == '1') {
				$code = 1;
				$msg = "该用户该产品下申请书通过审核";
			}
			else if($app['status'] == '2'){
				$code = 2;
				$msg = "该用户该产品下申请书未通过审核";
			}
			else{
				$code = 3;
				$msg = "该用户该产品下申请书正在审核";
			}
		}
		else{
			$code = 0;
			$msg = "未找到该用户该产品下的申请";
		}
		return $code;
    }

    public function newtrialreportcheck($user_id,$pro_id,$or_id,$p_type){
    	$where['use_id'] = $user_id;
    	$where['pro_id'] = $pro_id;
    	$where['or_id'] = $or_id;
    	$where['p_type'] = $p_type;
  
    	try {
			$app = db("application")->where($where)->find();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		if ($app) {
			$code = $app['app_id'];
			$msg = "找到该用户该产品下的试用报告";
		}
		else{
			$code = 0;
			$msg = "未找到该用户该产品下的试用报告";
		}
		return $code;
    }

    public function trialreportcheck($user_id,$pro_id,$or_id,$p_type){
    	$where['use_id'] = $user_id;
    	$where['pro_id'] = $pro_id;
  
    	try {
			$report = db("trial_report")->where($where)->find();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		if ($report) {
			$code = $report['re_id'];
			$msg = "找到该用户该产品下的试用报告";
		}
		else{
			$code = 0;
			$msg = "未找到该用户该产品下的试用报告";
		}
		return $code;
    }

    public function orderadd(){
    	//判断是否是post提交
    	$code = 1;
    	$order = array();
    	if (request()->isPost()) {
    		$data = input("post.");
    		//var_dump(input("post.receiver"));
    		$ord['name'] = $data['receiver'];
    		$ord['telephone'] = $data['telephone'];
    		$ord['card'] = $data['card'];
    		$ord['province'] = $data['province'];
    		$ord['city'] = $data['city'];
    		$ord['address'] = $data['province'].$data['city'].$data['address'];
    		$ord['zipcode'] = $data['zipCode'];
    		$ord['beizu'] = $data['beizu'];
    		$ord['city'] = $data['city'];
    		$ord['user_id'] = $data['userid'];
    		$ord['pro_id'] = $data['proid'];
    		$ord['pro_title'] = $data['protitle'];
    		$ord['create_time'] = date("Y-m-d H:i:s");
    		
			$ord_id = db('order')->insertGetId($ord);
			if ($ord_id) {
				$ord['id'] = $ord_id;
				$order = $ord;
				$msg = "订单添加成功";

			}
			else{
				$msg = "订单添加失败";
				$code = 0;
			}


    	}
    	else{
    		$msg = "后台未接收到传递的值";
			$code = 0;
    	}
		
		$datajson = [
			'code' => $code,
			'msg' => $msg,
			'resultList' => $order
		];
		return json($datajson);
    }



    public function applicationadd(){
    	//判断是否是post提交
    	$code = 1;
    	$application = array();
    	if (request()->isPost()) {
    		$data = input("post.");

       		$app['app_content'] = $data['appcontent'];
       		$app['pro_id'] = $data['proid'];
       		$app['pro_title'] = $data['protitle'];
       		$app['use_id'] = $data['userid'];
       		$app['use_name'] = $data['username'];
       		$app['or_id'] = $data['orid'];
       		$app['status'] = 0;
    		$app['create_time'] = date("Y-m-d H:i:s");
    		
			$app_id = db('application')->insertGetId($app);
			if ($app_id) {
				$app['id'] = $app_id;
				$application = $app;
				$msg = "申请书添加成功";

			}
			else{
				$msg = "申请书添加失败";
				$code = 0;
			}


    	}
    	else{
    		$msg = "后台未接收到传递的值";
			$code = 0;
    	}
		
		$datajson = [
			'code' => $code,
			'msg' => $msg,
			'resultList' => $application
		];
		return json($datajson);
    }


    public function trialreportadd(){
    	//判断是否是post提交
    	$code = 1;
    	$report = array();
    	if (request()->isPost()) {
    		$data = input("post.");

       		$att['re_title'] = $data['retitle'];
       		$att['re_thumbnail'] = $data['rethumbnail'];
       		$att['re_content'] = $data['recontent'];
       		$att['create_time'] = date("Y-m-d H:i:s");
       		$att['use_id'] = $data['userid'];
       		$att['use_name'] = $data['username'];
       		$att['pro_id'] = $data['proid'];
       		$att['pro_title'] = $data['protitle'];
    		
			$re_id = db('trial_report')->insertGetId($att);
			if ($re_id) {
				$att['id'] = $re_id;
				$report = $att;
				$msg = "试用报告添加成功";

			}
			else{
				$msg = "试用报告添加失败";
				$code = 0;
			}


    	}
    	else{
    		$msg = "后台未接收到传递的值";
			$code = 0;
    	}
		
		$datajson = [
			'code' => $code,
			'msg' => $msg,
			'resultList' => $report
		];
		return json($datajson);
    }


    /*上传缩略图*/
	public function thumbnail(){
		
		$upload = new Upload();// 实例化上传类 
        $upload->maxSize = 3145728 ;// 设置附件上传大小 
        $upload->subName = time();
        $upload->rootPath = './';
        $upload->savePath = 'ueditor/php/upload/image/'.date("Ymd"); // 设置附件上传目录 
        // 上传文件 
        $code = 1;
        $info = $upload->uploadOne($_FILES['files']);
        if(!$info) {// 上传错误提示错误信息 
            $msg = $upload->getError(); 
            $code = 0;
        }
        else{// 上传成功 
        	$msg = "/".$info['savepath'].$info['savename'];
        }
        $datajson = [
			'code'=> $code,
			'msg' => $msg,
		];
		return json($datajson);

	}


	/*处理游客传递过来的paper答案*/
	public function DealWithPaper(){
		if (request()->isPost()) {
    		$data = json_decode(input('post.jsonstr'));
    		$datalen = count($data);
    		$answer = db("answer");
    		$answer->startTrans();
    		try{
    			for ($i=1; $i<$datalen; $i++) {
    				$arr[$i] = array(
    					'pa_id' => $data[$i]->pa_id,
    					'use_id' => $data[$i]->use_id,
    					'qu_id' => $data[$i]->qu_id, 
    					'number' => $data[$i]->number,
    					'qu_type' => $data[$i]->qu_type,
    					'an_content' => $data[$i]->an_content,
    					'status' => 1,
    					'create_time' => date('Y-m-d H:i:s')
    				);

    				$answer->insert($arr[$i]);
    			}

    			$answer->commit();   
    			$code = 1;
    			$msg = "插入答题内容，事务执行";

    		} catch(\Exception $e){
    			$answer->rollback();
    			$code = 0;
    			$msg = $e->getMessage();
    		}

    		if ($code == 1) {

    			$app = array(
    				'app_title' => $data[0]->papertitle,
    				'app_paperid' => $data[0]->paperid,
    				'status' => '0',
    				'use_id' => $data[0]->userid,
    				'use_name' => $data[0]->username,
    				'pro_id' => $data[0]->proid,
    				'pro_title' => $data[0]->protitle,
    				'or_id' => $data[0]->or_id,
    				'create_time'=> date('Y-m-d H:i:s'),
    				'p_type' => intval($data[0]->p_type)
    			);

    			try{
    				$a = db("application")->insert($app);
    			} catch(\Exception $e){
	    			$code = 0;
	    			$msg = $e->getMessage();
	    		}
	    		if ($a) {
	    			$code = 1;
	    			$msg = "添加成功";
	    		}
	    		else{
	    			$code = 1;
	    			$msg = "添加失败";
	    		}
    		}
    	}
    	else{
    		$code = 0;
	    	$msg = "后台未接收数据";
    	}

    	$datajson = [
			'code'=> $code,
			'msg' => $msg,
		];
		return json($datajson);
	}



}