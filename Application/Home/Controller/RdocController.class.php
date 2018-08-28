<?php
/**
 *文章控制器
 */
namespace Home\Controller;
use Think\Controller;

class RdocController extends CommonController {

	//从子类中获取
	protected $model = "article"; //表名
	protected $model_view = "ArticleView"; //视图名

	protected function cms($id) {
		$oop = D($this->model_view);
		$con['doc_status'] = 1;
		$con['doc_id'] = $id;
		$list = $oop->where($con)->find();
		if ($list) {
			//访问次数增加1
			M($this->model)->where($con)->setInc('doc_hit');
			$this->assign($list);
			$this->display($list['cat_details']);
		} else {
			$this->display("Public:error404");
		}
	}

	public function cat() {
		$oop = D($this->model_view);
		$con['cat_id'] = (int) I('path.2');
		$con['doc_status'] = 1;
		$data = pageInfo($oop, $this->sort . " desc", $con, $this->num);
		//echo $oop->_sql();
		//dump($data);
		if ($data['list']) {
			//dump($data);
			$this->assign("list", $data['list']);
			$this->assign("page", $data['show']);
			$this->display($data['list'][0]['cat_index']);
		} else {
			$this->display("index");
		}
	}

}
