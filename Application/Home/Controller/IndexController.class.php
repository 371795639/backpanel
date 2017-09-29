<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends CommonController {

	public function _initialize() {
		//继承前面的配置
		parent::_initialize();
		$this->redirect("Public/login");
	}

	public function index() {
		$this->display();
	}

}
