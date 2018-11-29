<?php
namespace app\index\controller;

use think\Controller;
use think\Page;

class product extends Controller
{
    public function index()
    {	if (request()->isGet()) {
    		$pro_id = input('get.pro_id');
    		try {
				$product = db("product")->where('pro_id',$pro_id)->find();
			} catch (\Exception $e) {
				$this->error($e->getMessage());
			}
			$this->assign('prolist',$product);
			return $this->fetch();
    	}
    	
    }

    public function reportlist(){
        if (input('param.pro_id')) {
            $pro_id = input('param.pro_id');
            $p = input('param.p')?input('param.p'):1;
            $where['pro_id'] = $pro_id;
            $where['status'] = 1;

            $count = db("trial_report")->where($where)->count();
            $limit = 10;
            $page = new Page($count,$limit);
            $gethouzui = "?pro_id=".$pro_id;
            $show = $page->show($gethouzui);
            $this->assign("show",$show);

            try {
                $reportlist = db("trial_report")->where($where)->limit($page->firstRow,$page->listRows)->order('create_time desc')->select();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->assign('reportlist',$reportlist);

            try {
                $product = db("product")->where('pro_id',$pro_id)->find();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->assign('prolist',$product);

            if ($p == 1) {
                return $this->fetch('reportlistp1');
            }
            else{
                return $this->fetch();
            }

        }
    }


    public function reportshow(){
        if (request()->isGet()) {
            $re_id = input('get.re_id');

            $where['re_id'] = $re_id;
            try {
                $report = db("trial_report")->where($where)->find();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->assign('report',$report);

            try {
                $product = db("product")->field('status')->where('pro_id',$report['pro_id'])->find();
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            $this->assign('prolist',$product);

            return $this->fetch();
        }
    }

}