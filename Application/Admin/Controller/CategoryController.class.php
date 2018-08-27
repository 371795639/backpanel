<?php
namespace Admin\Controller;

use Think\Controller;

class CategoryController extends CommonController {
	public function _initialize() {
		parent::_initialize();
		$this->model = "category";
		$this->key = "cat_id";
		$this->title_index = "分类";
		$this->title_details = "分类详情";
	}

	public function index() {
		//dump($list);
		$this->assign("main_title", $this->title_index);
		$arr = getCategoryList();
		//dump($arr);
		$this->assign("list", $arr);
		$this->display();
	}

	public function _after_addHandle() {
		S("category", null);
	}

	public function deleteHandle() {
		//如果有子分类，则不能删除
		$oop = M($this->model);
		$con['cat_parent'] = I("path.2");
		$list = $oop->where($con)->find();
		if ($list) {
			$this->error("请先手动删除子类！");
		}

		//删除分类，同时会删除分类下的所有文章
		M('document')->where(array('doc_cat' => I('path.2')))->delete();
		M('article')->where(array('doc_cat' => I('path.2')))->delete();
		parent::deleteHandle();
		S("category", null);
	}

	public function _after_updateHandle() {
		S("category", null);
	}

}
