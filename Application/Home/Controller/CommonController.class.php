<?php
//前台页面公用类
namespace Home\Controller;
use Common\Controller\BaseController;

abstract class CommonController extends BaseController {

	protected $num = 15; //分页数
	//从子类中获取
	protected $model = null; //表名
	protected $model_view = null; //视图名
	protected $key = null; //排序键
	protected $title_index = null; //选项卡标题

	//域名相关配置
	protected $brand_config;

	public function _initialize() {
		//继承前面的配置
		parent::_initialize();
		//调用后台配置，启用设置主题
		C("DEFAULT_THEME", $this->web_config['web_theme']);
		//配置多语言
		//cookie("think_language", $this->web_config['web_default_lang']);
		$this->web_config['web_isopen'] || exit($this->web_config['web_close_desc']);

	}

	public function home($con = '1=1') {
		$this->model_view ? $oop = D($this->model_view) : $oop = M($this->model);
		$data = pageInfo($oop, $this->key . " desc", $con, $this->num);
		//echo $oop->_sql();
		//dump($data['list']);
		$this->assign("list", $data['list']);
		$this->assign("page", $data['show']);
		$this->assign("total_num", $data['total_num']);
		$this->assign("main_title", $this->title_index);
		$this->display();
	}
	//详情页
	public function details($con) {
		$this->model_view ? $oop = D($this->model_view) : $oop = M($this->model);
		$list = $oop->field(true)->where($con)->find();
		$list || $this->error(L("no_record"));
		$this->assign($list);
		$this->assign("main_title", $this->title_index);
		$this->display();
	}

	//保存
	public function updateHandle($con) {
		//dump(I());EXIT;
		try {
			$oop = M($this->model);
			$oop->create();
			if ($oop->where($con)->save()) {
				$this->success(L("update_success"));
			} else {
				$this->error(L("update_failed"));
			}

		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
	}

	//新增
	public function addHandle($data = array()) {
		try {
			$oop = M($this->model);
			if ($oop->add($data)) {
				$this->success(L("add_success"));
			} else {
				$this->error(L("add_failed"));
			}

		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}
	}

	//删除
	public function deleteHandle($con) {
		try {
			$oop = M($this->model);
			if ($oop->where($con)->delete()) {
				$this->success(L("delete_success"));
			} else {
				$this->error(L("delete_failed"));
			}

		} catch (\Exception $e) {
			$this->error($e->getMessage());
		}

	}

	//空控制器
	public function _empty() {
		if (I('path.1')) {
			$this->cms(I('path.1'));
		} else {
			$this->display("Public:error404");
		}

	}

}
