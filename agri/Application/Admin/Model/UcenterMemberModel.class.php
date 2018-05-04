<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
/**
 * 会员模型
 */
class UcenterMemberModel extends Model{

// 	/* 用户模型自动验证 */
// 	protected $_validate = array(
// 		/* 验证用户名 */
// 		array('username', '6,10', -1, self::EXISTS_VALIDATE, 'length'), //用户名长度不合法
// 		array('username', 'checkDenyMember', -2, self::EXISTS_VALIDATE, 'callback'), //用户名禁止注册
// 		array('username', '', -3, self::EXISTS_VALIDATE, 'unique'), //用户名被占用

// 		/* 验证密码 */
// 		array('password', '6,10', -4, self::EXISTS_VALIDATE, 'length'), //密码长度不合法

// 		/* 验证邮箱 */
// 		array('email', 'email', -5, self::EXISTS_VALIDATE), //邮箱格式不正确
// 		array('email', '1,32', -6, self::EXISTS_VALIDATE, 'length'), //邮箱长度不合法
// 		array('email', 'checkDenyEmail', -7, self::EXISTS_VALIDATE, 'callback'), //邮箱禁止注册
// 		array('email', '', -8, self::EXISTS_VALIDATE, 'unique'), //邮箱被占用

// 		/* 验证手机号码 */
// 		array('mobile','/^1[34578]\d{9}$/','手机号码不对..1！',0,'regex',3), //手机格式不正确 TODO:
// 		array('mobile', 'checkDenyMobile', -10, self::EXISTS_VALIDATE, 'callback'), //手机禁止注册
// 		array('mobile', '','手机号码格式错误！', self::VALUE_VALIDATE, 'unique'), //手机号被占用
// 	);

// 	/* 用户模型自动完成 */
// 	protected $_auto = array(
// 		array('reg_time', NOW_TIME, self::MODEL_INSERT),
// 		array('update_time', NOW_TIME),
// 		array('status', 'getStatus', self::MODEL_BOTH, 'callback'),
// 	);

	/**
	 * 检测用户名是不是被禁止注册
	 * @param  string $username 用户名
	 * @return boolean          ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyMember($username){
		return true; //TODO: 暂不限制，下一个版本完善
	}

	/**
	 * 检测邮箱是不是被禁止注册
	 * @param  string $email 邮箱
	 * @return boolean       ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyEmail($email){
		return true; //TODO: 暂不限制，下一个版本完善
	}

	/**
	 * 检测手机是不是被禁止注册
	 * @param  string $mobile 手机
	 * @return boolean        ture - 未禁用，false - 禁止注册
	 */
	protected function checkDenyMobile($mobile){
		return true; //TODO: 暂不限制，下一个版本完善
	}

	/**
	 * 根据配置指定用户状态
	 * @return integer 用户状态
	 */
	protected function getStatus(){
		return true; //TODO: 暂不限制，下一个版本完善
	}
}
