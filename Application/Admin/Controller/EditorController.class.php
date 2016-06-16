<?php
namespace Admin\Controller;
use Think\Controller;

class EditorController extends CommonController {

	public function _initialize() {
		parent::_initialize();
		$this->model = "editor";
		$this->key = "ed_id";
		$this->title_index = "编辑组列表";
		$this->title_details = "编辑组详情";
	}

}