<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace User\Model;
use Think\Model;
/**
 * 会员模型
 */
class UcenterMemberModel extends Model{

	/**
	 * 用户登录认证
	 * @param  string  $username 用户名
	 * @param  string  $password 用户密码
	 * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
	 * @return integer           登录成功-用户ID，登录失败-错误编号
	 */
	public function login($type = 1,$username, $password){
		$map = array();
		switch ($type) {
			case 1:
				$map['username'] = $username;
				break;
			case 2:
				$map['email'] = $username;
				break;
			case 3:
				$map['mobile'] = $username;
				break;
			case 4:
				$map['id'] = $username;
				break;
			case 5:
			    $map['id'] = $username;
			    break;
			default:
				return 0; //参数错误
		}

		/* 获取用户数据 */
		$user = $this->where($map)->find();
		if(is_array($user)){
			/* 验证用户密码 */
			if(think_encrypt($password) === $user['password']){
			    $info = $this->info($user['id']);
			    if(!$info['status']){
			        return $info['status']; //账号审核
			    }
				$this->updateLogin($user['id']); //更新用户登录信息
				$this->autoLogin($info);
				return $info; //登录成功，返回用户ID
			} else {
				return -3; //密码错误
			}
		} else {
			return -4; //用户不存在或被禁用
		}
	}
	
	/**
	 * 注销当前用户
	 * @return void
	 */
	public function logout(){
	    session('user_auth', null);
	    S(session_id(),null);
	}
	
	/**
	 * 自动登录用户
	 * @param  integer $user 用户信息数组
	 */
	private function autoLogin($user){
	    /* 记录登录SESSION和COOKIES */
	    $auth = array(
	        'uid'             => $user['uid'],
	        'username'        => $user['nickname'],
	        'role'        => $user['role'],
	        'mlevel'        => $user['mlevel'],
	    );
	    S(session_id(),$auth);
	    session('user_auth', $auth);
	}
	/**
	 * 获取用户信息
	 * @param  string  $uid         用户ID或用户名
	 * @param  boolean $is_username 是否使用用户名查询
	 * @return array                用户信息
	 */
	public function info($uid){
		$user = $this->where("id=".$uid)->find();
		$userexd = D("Member")->where("uid=".$uid)->find();
		$info = array();
		if(is_array($user) && is_array($userexd)){
			foreach ($user as $key=>$val){
			    $info[$key] = $val;
			}
			foreach ($userexd as $key=>$val){
			    $info[$key] = $val;
			}
			return $info;
		} else {
			return -1; //用户不存在或被禁用
		}
	}
	/**
	 * 获取用户信息
	 * @param  string  $uid         用户ID或用户名
	 * @param  boolean $is_username 是否使用用户名查询
	 * @return array                用户信息
	 */
	public function infoAll($sql){
	   //return $this->cache("MEMBER_CACHE",C('MEMBER_DATA_CACHE_TIME'))->field($field)->join('LEFT JOIN __MEMBER__ ON __UCENTER_MEMBER__.id = __MEMBER__.uid where '.$wher)->select();
	    return $this->query($sql);
	}
	/**
	 * 检测用户信息
	 * @param  string  $field  用户名
	 * @param  integer $type   用户名类型 1-用户名，2-用户邮箱，3-用户电话
	 * @return integer         错误编号
	 */
	public function checkField($field, $type = 1){
		$data = array();
		switch ($type) {
			case 1:
				$data['username'] = $field;
				break;
			case 2:
				$data['email'] = $field;
				break;
			case 3:
				$data['mobile'] = $field;
				break;
			default:
				return 0; //参数错误
		}

		return $this->create($data) ? 1 : $this->getError();
	}

	/**
	 * 更新用户登录信息
	 * @param  integer $uid 用户ID
	 */
	protected function updateLogin($uid){
	    $Member = D("Member");
		$data = array(
			'uid'              => $uid,
			'last_login_time' => date("Y-m-d H:i:s"),
		);
		$Member->save($data);
	}

	/**
	 * 更新用户信息
	 * @param int $uid 用户id
	 * @param string $password 密码，用来验证
	 * @param array $data 修改的字段数组
	 * @return true 修改成功，false 修改失败
	 * @author huajie <banhuajie@163.com>
	 */
	public function updateUserFields($uid, $password, $data){
		if(empty($uid) || empty($password) || empty($data)){
			$this->error = '参数错误！';
			return false;
		}

		//更新前检查用户密码
		if(!$this->verifyUser($uid, $password)){
			$this->error = '验证出错：密码不正确！';
			return false;
		}

		//更新用户信息
		$data = $this->create($data);
		if($data){
			return $this->where(array('id'=>$uid))->save($data);
		}
		return false;
	}
	/**
	 * 验证用户密码
	 * @param int $uid 用户id
	 * @param string $password_in 密码
	 * @return true 验证成功，false 验证失败
	 * @author huajie <banhuajie@163.com>
	 */
	protected function verifyUser($uid, $password_in){
	    $password = $this->getFieldById($uid, 'password');
	    if(think_encrypt($password_in) === $password){
	        return true;
	    }
	    return false;
	}
	/**
	 * 验证用户
	 * @param int $uid 用户id
	 * @param string $password_in 密码
	 * @return true 验证成功，false 验证失败
	 * @author huajie <banhuajie@163.com>
	 */
	public function verify($unionid=-1){
		return $this->field('id')->where(array('unionid'=>$unionid))->find()['id'];
	}

}
