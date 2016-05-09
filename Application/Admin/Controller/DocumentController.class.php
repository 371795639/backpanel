<?php
namespace Admin\Controller;

use Think\Controller;

class DocumentController extends CommonController {
	public function _initialize() {
		parent::_initialize();
		$this->model = "document";
		$this->key = "doc_id";
		$this->title_index = "文章列表";
		$this->title_details = "文章详情";
	}

	public function index() {
		$mist_arr = array('log_action' => I("log_action"));
		$accute_arr = array('log_admin' => I("log_admin"));
		$cookie_arr = array();
		$con = searchCon($mist_arr, $accute_arr, $cookie_arr);
		parent::index($con);
	}

}
