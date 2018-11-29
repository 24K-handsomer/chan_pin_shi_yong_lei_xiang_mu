<?php
namespace app\index\controller;

use think\Controller;
use think\Page;

class Index extends Controller
{
    public function index()
    {	
    	try {
			$product = db("product")->field('pro_id,pro_title,status')->order('field(status,2,3,4,1,5),start_time desc,end_time desc')->limit(0,8)->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('prolist',$product);

    	try {
			$cat = db('category')->order("sortID asc")->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('list',$cat);
		return $this->fetch();
    }

	
	public function trialend(){
    	/*$catid = 0;
    	$p = 1;
        if(request()->isGet()){
            $catid = input('get.cat');
            $page = input('get.p');
        }*/
        $catid = input('param.cat')?input('param.cat'):0;
        $p = input('param.p')?input('param.p'):1;
        $this->assign('catid',$catid);
        $this->assign('p',$p);

        try {
			$cat = db('category')->order("sortID asc")->select();
		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
		$this->assign('catlist',$cat);

  		
  		$field = "pro_id,pro_title,pro_thumbnail,trial_number,pro_applications,pro_price,start_time,end_time";
  		$where = "status = 4";
  		if ($catid != 0) {
  			$where = "status = 4 AND category = ".$catid;
  		}

		$count = db("product")->where($where)->count();
		$limit = 10;
		$page = new Page($count,$limit);
		$gethouzui = "?cat=".$catid;
        $show = $page->show($gethouzui);
        $this->assign("show",$show);

		$product = db("product")->field($field)->where($where)->order('start_time desc,end_time desc')->limit($page->firstRow,$page->listRows)->select();

		$this->assign('prolist',$product);

		$relist = array();
		foreach ($product as $vo) {
			$report = db("trial_report")->field("re_id,re_title,use_name,pro_id")->where("pro_id",$vo['pro_id'])->order('create_time desc')->limit(2)->select();
			$relist[] = $report;
		}
		$this->assign('relist',$relist);

		return $this->fetch();
    }
}
