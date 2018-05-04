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
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class MemberModel extends Model {

    protected $_validate = array(
        array('name', '0,5', -20, self::EXISTS_VALIDATE, 'length'),//姓名长度
        array('specialty', '0,10', -21, self::EXISTS_VALIDATE, 'length'),//专业长度
        array('worksplace', '0,25', -22, self::EXISTS_VALIDATE, 'length'),//工作地
        array('nickName', '0,10', -23, self::EXISTS_VALIDATE, 'length'),//昵称
        array('region', '0,25', -24, self::EXISTS_VALIDATE, 'length'),//地区
        
    );
    /* 自动完成规则 */
    protected $_auto = array(
         array('content', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
    );
   
    public function lists($status = 1, $order = 'uid DESC', $field = true){
        $map = array('status' => $status);
        return $this->field($field)->where($map)->order($order)->select();
    }

    /**
     * 登录指定用户
     * @param  integer $uid 用户ID
     * @return boolean      ture-登录成功，false-登录失败
     */
    public function login($user){
       
        //记录行为
        //action_log('user_login', 'member', $user['uid'], $user['uid']);

        /* 登录用户 */
        $this->autoLogin($user);
        return true;
    }

    /**
     * 注销当前用户
     * @return void
     */
    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
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
        );

        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));

    }
}
