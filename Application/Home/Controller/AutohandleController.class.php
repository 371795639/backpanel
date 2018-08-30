<?php
//自动处理方法
namespace Home\Controller;

use Think\Controller;

class AutohandleController extends CommonController {
	public function index() {
		$oop = M("autoperform");
		$con['isactive'] = 1;
		I('name') && $con['name'] = I('name');

		$list = $oop->where($con)->select();
		!$list && exit("unknow process method！");
		//启动多线程,这样就不用等待所有方法执行完成了
		$asyn = new \boss420\common\AsynHandle();
		echo "start<hr>";
		foreach ($list as $value) {
			$process_url = U('Autohandle/' . $value['name'] . '@' . $_SERVER['SERVER_NAME']);
			echo $asyn->Request($process_url, 1);
			dump($process_url);
		}

		exit("<hr>complete");
	}

	/**
	 * 检测该功能是否可用
	 */
	private function checkStatus() {
		$oop = M("autoperform");
		$con['isactive'] = 1;
		$con["name"] = ACTION_NAME;
		$list = $oop->where($con)->find();
		//echo $oop->_sql();
		if (!$list) {
			exit("not allowed or not found");
		}
		return $list;
	}

	/**
	 *自动备份数据库的功能
	 */
	public function autobackup() {
		$auto_result = $this->checkStatus();
		$result = parent::backup();
		//发送邮件通知
		$subject = $this->web_config['web_domain'] . "-自动备份-(" . date('Y-m-d H:i:s') . ")";
		$body = "备份结果：" . $result;
		if ($auto_result['email'] != 0) {
			$arr_to = explode(",", $auto_result['email']);
			foreach ($arr_to as $value) {
				parent::postmail($value, $subject, $body);
			}
		}
		exit($body);
	}

	/**
	 * 异步通知返回
	 */
	public function testSuccessReturn() {
		echo 100;
	}

}
