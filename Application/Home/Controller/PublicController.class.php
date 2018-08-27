<?php
namespace Home\Controller;
use Think\Controller;

class PublicController extends CommonController {
	public function login() {
		session("mid") ? $this->redirect("Index/index"):$this->display("login_mode");
	}

	/**
	 *登录账户
	 */
	public function checkin() {
		//先核对验证码
		$this->checkCode();
		$con['mh_id'] = I('mid');
		$con['mh_isactive'] = 1;
		$result = M("merchant")->where($con)->find();
		//echo md5(I('pwd').$result['mh_salt']);exit;
		$cid = parent::addLog(I('mid'));
		if (!$result || md5(I('pwd') . $result['mh_salt']) != $result['mh_pwd']) {
			parent::updateLog($cid, 0, L("invalid_login"));
			$this->error(L("invalid_login"));
		}

		//查看商户的代理商，以及是否指定域名或LOGO
		$oop = D("Resellermerchant2View");
		$con1['mh_id'] = $result['mh_id'];
		$con1['aff_status'] = 1;
		$con1['aff_super_mode'] = 1;
		$con1['aff_domain'] = array("neq", "");
		$list = $oop->where($con1)->find();
		//域名匹配
		if ($list) {
			//对比下登录的域名
			if ($_SERVER['SERVER_NAME'] != $list['aff_domain']) {
				parent::updateLog($cid, 0, L("invalid_login2"));
				$this->error(L("invalid_login2"));
			}
		}

		parent::updateLog($cid, 1, "success");
		session("mid", $result['mh_id']);

		$this->redirect("Merchant/index");
	}

	public function postmail() {
		$data['status'] = 0;
		$data['data'] = 0;
		//如果发送过，则禁止120秒内重发
		if (cookie("v_code")) {
			$data['info'] = L("repeat_error");
			$this->ajaxReturn($data);
		}
		//防止远程提交
		if (!I("id") || !I('hash_code') || !session("hash_code") || I("hash_code") != session("hash_code")) {
			$data['info'] = L("invalid_submit");
			$this->ajaxReturn($data);
		}

		$verify_code = makePass(5);
		//查看商户是否存在
		if (I("type") == 1) {
			$list = M("merchant")->find(I("id"));
			$to = $list['mh_email'];
		} else {
			$list = M("affiliate")->find(I("id"));
			$to = $list['aff_email'];
		}
		if (!$list) {
			$data['info'] = L("invalid_submit");
			$this->ajaxReturn($data);
		}

		$body = "Your code:" . $verify_code . "<br>" . L("change_pwd_verify_code_info") . "<p>" . $this->web_config['web_domain'] . "</p>";
		cookie("v_code", md5($verify_code . I('id') . I('type')), 120);

		$result = parent::postmail($to, "[" . L("web_name") . "]" . L("email_verify_code"), $body);
		if ($result) {
			$data['status'] = 1;
			$data['info'] = L("send_success");
			$this->ajaxReturn($data);
		} else {
			$data['info'] = L("send_error");
			$this->ajaxReturn($data);
		}

	}

	public function updatePwd() {
		//dump(I());EXIT;
		if (cookie('v_code') != md5(I('code') . I('id') . I('type')) || I('hash_code') != session('hash_code') || strlen(I('pwd')) < 6) {
			$this->error(L("invalid_submit"));
		}

		if (I('type') == 1) {
			$oop = M("merchant");
			$list = $oop->find(I('id'));
			$data['mh_id'] = I("id");
			$data['mh_pwd'] = md5(I('pwd') . $list['mh_salt']);

			if ($data['mh_pwd'] == $list['mh_pwd']) {
				$this->error(L("same_pwd"));
			}
		} else {
			$oop = M("affiliate");
			$list = $oop->find(I('id'));
			$data['aff_id'] = I("id");
			$data['aff_pwd'] = md5(I('pwd') . $list['aff_salt']);
			if ($data['aff_pwd'] == $list['aff_pwd']) {
				$this->error(L("same_pwd"));
			}
		}
		//dump($data);
		if ($oop->save($data)) {
			$this->success(L("action_success"));
		} else {
			$this->error(L("action_failed"));
		}
	}

	public function logout() {
		session("mid", null);
		session("mh_logo", null);
		session("mh_domain", null);
		session("mh_brand", null);
		session("mh_super_mode", null);
		$this->redirect("Index/index");
	}

//根据国家代码返回州
	public function getState() {
		I("iso") == 3 ? $iso = 3 : $iso = 2;
		$this->ajaxReturn(parent::getState(I("country_name"), $iso));
	}

//异步通知测试地址
	public function asyn() {
		$oop = M("innotice");
		$data['in_oid'] = $_POST['pid'] . "|432";
		$data['in_title'] = $_POST['pid'] . "|555";
		$data['in_content'] = dump(I(), false);
		$oop->add($data);
		echo 100;
	}

	public function testfsockopen() {
		echo (function_exists("fsockopen"));
		$asyn = new \boss420\common\AsynHandle();
		echo $process_url = U('Autohandle/autorefund@' . $_SERVER['SERVER_NAME']);
		$result = $asyn->Get($process_url, 0);
		dump($result);
	}
}