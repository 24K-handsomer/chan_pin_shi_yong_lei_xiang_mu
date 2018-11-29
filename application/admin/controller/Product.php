<?php
namespace app\admin\controller;

use think\Controller;
use think\Model;
use think\Upload;

class Product extends Base
{
	function productlist(){

		try {
			$category = db('category')->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('catlist',$category);

		try {
			$status = db('status')->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('statuslist',$status);
		//判断是否是post提交
    	if (request()->isPost()) {
    		$where = array('name' => input('post.name'));
    	}
    	else{
    		$where = array();
    	}
		try {
			$product = db('product')->where($where)->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('list',$product);
		return $this->fetch();
	}


	/*上传缩略图*/
	public function productthumbnail(){
		
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


	function productadd(){
		//判断是否是post提交
		try {
			$category = db('category')->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('catlist',$category);

    	if (request()->isPost()) {
    		$data = json_decode(input('post.pro'))[0];
    		/*dump($data);*/
    		$pro['pro_title'] = $data->pro_title;
    		$pro['pro_thumbnail'] = $data->pro_thumbnail;
    		$pro['trial_number'] = $data->trial_number;
    		$pro['pro_applications'] = $data->pro_applications;
    		$pro['pro_price'] = $data->pro_price;
    		$pro['activity_rules'] = $data->activity_rules;
    		$pro['trial_requirements'] = $data->trial_requirements;
    		$pro['report_content'] = $data->report_content;
    		$pro['activity_time'] = $data->activity_time;
    		$pro['pro_introduction'] = $data->pro_introduction;
    		$pro['category'] = $data->category;
            $pro['start_time'] = $data->start_time;
            $pro['end_time'] = $data->end_time;
    		$pro['create_time'] = date("Y-m-d H:i:s");
    		$pro['stage'] = $data->stage;

    		$code = 1;
    		if ($data->pro_id == 0) {   

				try {
					$id = db('product')->insertGetId($pro);
				} catch (\Exception $e) {
					$msg = $e->getMessage();
					$code = 0;
				}
				if ($id) {
                    $msg = "id=".$id."的产品新增成功";
                }
                else{
                    $msg = "产品增加失败";
                    $code = 0;
                }

			}
			else{
				try {
					$re = db('product')->where('pro_id', $data->pro_id)->update($pro);
				} catch (\Exception $e) {
					$msg = $e->getMessage();
					$code = 0;
				}
				if ($re) {
                    $msg = "id=".$data->pro_id."的产品修改成功";
                }
                else{
                    $msg = "产品修改失败";
                    $code = 0;
                }
			}
    		
			$datajson = [
    			'code'=> $code,
    			'msg' => $msg,
    		];
    		return json($datajson);

    	}
    	else{
    		$id = input('get.pro_id');
    		if (!empty($id)) {
    			$dataattr = db('product')->where('pro_id',$id)->find();
    		}
    		else{
    			$dataattr = null;
    		}
    		$this->assign('dataattr',$dataattr);
    		return $this->fetch();
    	}
		
	}


	function productcategory(){
		//判断是否是post提交
    	if (request()->isPost()) {
    		$where = array('name' => input('post.name'));
    	}
    	else{
    		$where = array();
    	}
		try {
			$category = db('category')->where($where)->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('list',$category);
		return $this->fetch();
	}

	function productcategoryadd(){

		//判断是否是post提交
    	if (request()->isPost()) {
    		$data = input('post.');  //变量获取 
    		//halt($data);
    		$validate = validate('Category');

    		$code = 1;
    		if (!$validate->check($data)) {
    			$msg = $validate->getError();
    			$code = 0;
    		}
    		else{
    			$cat['sortID'] = $data['sortID'];
    			$cat['name'] = $data['name'];

    			if ($data['id'] == 0) {   

    				try {
    					$id = db('category')->insertGetId($cat);
    				} catch (\Exception $e) {
    					$msg = $e->getMessage();
    					$code = 0;
    				}
    				if ($id) {
	                    $msg = "id=".$id."的分类新增成功";
	                }
	                else{
	                    $msg = "分类增加失败";
	                    $code = 0;
	                }

    			}
    			else{
    				try {
    					$re = db('category')->where('id', $data['id'])->update($cat);
    				} catch (\Exception $e) {
    					$msg = $e->getMessage();
    					$code = 0;
    				}
    				if ($re) {
	                    $msg = "id=".$data['id']."的分类修改成功";
	                }
	                else{
	                    $msg = "分类修改失败";
	                    $code = 0;
	                }
    			}
    		}

    		$datajson = [
    			'code'=> $code,
    			'msg' => $msg,
    		];
    		return json($datajson);

    	}
    	else{
    		$id = input('get.id');
    		if (!empty($id)) {
    			$category = db('category')->where('id',$id)->find();
    			/*return $this->fetch('',[
    				'catid' => empty($category['id']) ? 0 : $category['id'],
    				'catsortID' => empty($category['sortID']) ? '' : $category['sortID'],
    				'catname' => empty($category['name']) ? '' : $category['name'],
    			]);*/
    		}
    		else{
    			$category = null;
    			/*return $this->fetch();*/
    		}
    		return $this->fetch('',[
						'id' => empty($category['id']) ? 0 : $category['id'],
						'sortID' => empty($category['sortID']) ? '' : $category['sortID'],
						'name' => empty($category['name']) ? '' : $category['name'],
					]);
    		
    	}
		
	}

	function productcategorydel(){
		//判断是否是post提交
		$code = 1;
    	if (request()->isPost()) {
    		$id = input('post.id');  //变量获取
    		try {
    			$re = db('category')->where('id',$id)->delete();
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

	function productdel(){
		//判断是否是post提交
		$code = 1;
    	if (request()->isPost()) {
    		$id = input('post.pro_id');  //变量获取
    		try {
    			$re = db('product')->where('pro_id',$id)->delete();
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

	/*批量删除*/
	public function datadel(){
		//判断是否是post提交
		$code = 1;
    	if (request()->isPost()) {
    		$tab = input('post.tab');
    		$field = input('post.field');
    		$str = input('post.str');

    		$attr = explode("|",$str);
        	//$tj = implode($attr,"','");
        
        	try {
    			$re = db($tab)->where($field,'in',$attr)->delete();
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


	/*产品阶段*/
	public function productstatuc(){
		//判断是否是productstatuc.html
		
    	if (request()->isPost()) {
    		$pro_id = input('post.pro_id');
    		$sta['status'] = input('post.status');
    		$code = 1;
			try {
				$re = db('product')->where('pro_id', $pro_id)->update($sta);
			} catch (\Exception $e) {
				$msg = $e->getMessage();
				$code = 0;
			}
			if ($re) {
                $msg = "id=".$pro_id."阶段修改成功";
            }
            else{
                $msg = "阶段修改失败";
                $code = 0;
            }

			$datajson = [
    			'code'=> $code,
    			'msg' => $msg,
    		];
    		return json($datajson);

    	}
    	else{
    		$pro_id = input('get.pro_id');
    		$status = input('get.status');

    		try {
				$sta = db('status')->select();
			} catch (\Exception $e) {
				$this->error($e->getMessage());
			}
			$this->assign('statuslist',$sta);
    		$this->assign('pro_id',$pro_id);
    		$this->assign('status',$status);
    		return $this->fetch();
    	}
		
	}

	/*产品状态更改*/
	public function stagechange(){
		$code = 1;
    	if (request()->isPost()) {
    		$pro_id = input('post.pro_id');
    		$stage['stage'] = input('post.stage');

        	try {
    			$re = db('product')->where('pro_id', $pro_id)->update($stage);
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