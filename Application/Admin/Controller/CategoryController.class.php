<?php
namespace Admin\Controller;

use Think\Controller;

class CategoryController extends CommonController {
	public function _initialize() {
		parent::_initialize();
		$this->model = "category";
		$this->key = "cat_id";
		$this->title_index = "文章分类";
		$this->title_details = "分章分类详情";
	}

	public function index() {
		$mist_arr = array('log_action' => I("log_action"));
		$accute_arr = array('log_admin' => I("log_admin"));
		$cookie_arr = array();
		$con = searchCon($mist_arr, $accute_arr, $cookie_arr);
		parent::index($con);
	}

}
