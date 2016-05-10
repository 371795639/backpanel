<?php
namespace Admin\Controller;

use Think\Controller;

class DocumentController extends CommonController {
	public function _initialize() {
		parent::_initialize();
		$this->model = "document";
		$this->model_view = "DocumentView";
		$this->key = "doc_id";
		$this->title_index = "单页列表";
		$this->title_details = "单页详情";
	}

	public function index() {
		$mist_arr = array('doc_title' => I("doc_title"));
		$accute_arr = array('doc_cat' => I("doc_cat"));
		$cookie_arr = array();
		$con = searchCon($mist_arr, $accute_arr, $cookie_arr);
		parent::index($con);
	}

	public function addHandle() {
		if (!empty($_FILES['file']['tmp_name'])) {
			$result = parent::uploadfile($exts = array('jpg', 'gif', 'png', 'jpeg'), $thumb = true);
			if (!$result['status']) {
				$this->error($result['info']);
			}
		}
		//dump(I());
		//dump($result);exit;
		try {
			$oop = M($this->model);
			$oop->create();
			$oop->doc_dir = $result['info']['file']['savepath'];
			$oop->doc_img = $result['info']['file']['savename'];
			if ($oop->add()) {
				$this->success(L("add_success"));
			} else {
				$this->error(L("add_failed"));
			}

		} catch (\Exception $e) {
			//dump($e);exit;
			if ($e->getCode() == 23000) {
				$this->error(L("unique_error"));
			}
		}
	}

}
