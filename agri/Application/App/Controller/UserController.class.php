<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace App\Controller;

use User\Api\UserApi;
/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class UserController extends AppController {

    /**
	 * 签到
	 */
	public function signin(){
	    /**
	     * $auth = array(
	     'uid'             => $user['uid'],
	     'username'        => $user['nickname'],
	     'role'        => $user['role'],
	     'mlevel'        => $user['mlevel'],
	     );
	     */
	    $auth = S(session_id());
	    $auth = session("user_auth");
	    if($auth && $auth['uid']){
	        $uid = $auth['uid'];
	        $User = new UserApi();
	        $res = $User->singin($uid,C("SING_IN_SCORE"));
	        $this->ajaxReturn(array("status"=>$res),'json');
	    }else{
	        $this->ajaxReturn(array("status"=>0),'json');
	    }
	}

}
