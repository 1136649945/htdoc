<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use User\Api\UserApi;

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class UserController extends HomeController {

	/* 用户中心首页 */
	public function index(){
		
	}

	/* 登录页面 */
	public function login($username = '', $password = '', $verify = ''){
		if(IS_POST){ //登录验证
			/* 检测验证码 */
			if(!check_verify($verify)){
				$this->error('验证码输入错误！');
			}

			/* 调用UC登录接口登录 */
			$user = new UserApi;
			$info = $user->login($username, $password);
			if(0 < $info["status"]){ //UC登录成功
				$this->success('登录成功！',U('Home/Index/index'));
			} else { //登录失败
				$this->error($info["info"]);
			}

		} else { //显示登录表单
			$this->display();
		}
	}
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
	    $auth = session("user_auth");
	    if($auth && $auth['uid']){
	        $uid = $auth['uid'];
	        $User = new UserApi;
	        $res = $User->singin($uid,C("SING_IN_SCORE"));
	        $this->ajaxReturn(array("status"=>$res),'json');
	    }else{
	        $this->ajaxReturn(array("status"=>0),'json');
	    }
	}
	
	/**
	 * 获取用户信息
	 */
	public function userInfo(){
	    if ( !is_login() ) {
	        $this->ajaxReturn(array("status"=>0,"info"=>"你还没有登录！"));
	    }
	    if ( IS_POST ) {
	        //获取参数
	        $uid        =   is_login();
	        if($uid){
	           $this->ajaxReturn((new UserApi())->info($uid));
	        }else{
	            $this->ajaxReturn(array("status"=>0,"info"=>"你还没有登录！"));
	        }
	    }
	}
	
	/* 退出登录 */
	public function logout(){
		if(is_login()){
			D('Member')->logout();
			$this->success('退出成功！', U('User/login'));
		} else {
			$this->redirect('User/login');
		}
	}

	/* 验证码，用于登录和注册 */
	public function verify(){
		$verify = new \Think\Verify();
		$verify->entry(1);
	}

	/**
	 * 获取用户注册错误信息
	 * @param  integer $code 错误编码
	 * @return string        错误信息
	 */
	private function showRegError($code = 0){
		switch ($code) {
			case -1:  $error = '用户名长度必须在16个字符以内！'; break;
			case -2:  $error = '用户名被禁止注册！'; break;
			case -3:  $error = '用户名被占用！'; break;
			case -4:  $error = '密码长度必须在6-30个字符之间！'; break;
			case -5:  $error = '邮箱格式不正确！'; break;
			case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
			case -7:  $error = '邮箱被禁止注册！'; break;
			case -8:  $error = '邮箱被占用！'; break;
			case -9:  $error = '手机格式不正确！'; break;
			case -10: $error = '手机被禁止注册！'; break;
			case -11: $error = '手机号被占用！'; break;
			default:  $error = '未知错误';
		}
		return $error;
	}


    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function profile(){
		if ( !is_login() ) {
			$this->error( '您还没有登陆',U('User/login') );
		}
        if ( IS_POST ) {
            //获取参数
            $uid        =   is_login();
            $password   =   I('post.old');
            $repassword = I('post.repassword');
            $data['password'] = I('post.password');
            empty($password) && $this->error('请输入原密码');
            empty($data['password']) && $this->error('请输入新密码');
            empty($repassword) && $this->error('请输入确认密码');

            if($data['password'] !== $repassword){
                $this->error('您输入的新密码与确认密码不一致');
            }

            $Api = new UserApi();
            $res = $Api->updateInfo($uid, $password, $data);
            if($res['status']){
                $this->success('修改密码成功！');
            }else{
                $this->error($res['info']);
            }
        }else{
            $this->display();
        }
    }
    
}
