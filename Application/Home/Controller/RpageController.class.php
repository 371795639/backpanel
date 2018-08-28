<?php
/**
 *单页控制器
 */
namespace Home\Controller;
use Think\Controller;

class RpageController extends CommonController {

	//从子类中获取
	protected $model = "document"; //表名
	protected $model_view = "DocumentView"; //视图名

	protected function cms($name) {
		if ($this->model_view) {
			$oop = D($this->model_view);
		} else {
			$oop = M($this->model);
		}
		$con['doc_status'] = 1;
		$con['doc_unique'] = $name;
		$list = $oop->where($con)->find();
		if ($list) {
			$this->assign($list);
			$this->display($list['doc_tpl']);
		} else {
			$this->display("Public:error404");
		}
	}
}
