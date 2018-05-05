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

	/* 用户模型自动验证 */
	protected $_validate = array(
		/* 验证用户名 */
	    array('username','/^[_0-9a-zA-Z]{6,10}$/',-1,0,'regex',3), //用户名长度不合法
		array('username', 'checkDenyMember', -2, self::EXISTS_VALIDATE, 'callback'), //用户名禁止注册
		array('username', '', -3, self::EXISTS_VALIDATE, 'unique'), //用户名被占用

		/* 验证密码 */
	    array('password','/^[_0-9a-zA-Z]{6,10}$/',-4,self::MUST_VALIDATE,'regex'), //密码长度不合法

// 		/* 验证邮箱 */
		array('email', 'email', -5, self::EXISTS_VALIDATE), //邮箱格式不正确
		array('email', '6,32', -6, self::EXISTS_VALIDATE, 'length'), //邮箱长度不合法
		array('email', 'checkDenyEmail', -7, self::EXISTS_VALIDATE, 'callback'), //邮箱禁止注册
		array('email', '', -8, self::EXISTS_VALIDATE, 'unique'), //邮箱被占用

		/* 验证手机号码 */
		array('mobile','/^1[34578]\d{9}$/',-9,self::VALUE_VALIDATE,'regex'), //手机格式不正确 TODO:
		array('mobile', 'checkDenyMobile', -10, self::EXISTS_VALIDATE, 'callback'), //手机禁止注册
		array('mobile', '',-11, self::VALUE_VALIDATE, 'unique'), //手机号被占用
	    /* 验证手机号码 */
	    array('telephone','/(^[0-9]{3,4}\-[0-9]{3,8}$)|(^[0-9]{3,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)|(^0{0,1}13[0-9]{9}$)/',-12,self::VALUE_VALIDATE,'regex'), //手机格式不正确 TODO:
	);

}
