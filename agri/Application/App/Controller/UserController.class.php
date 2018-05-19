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
     * 退出登录
     */
    public function loginout(){
        if(is_login()){
            $User = new UserApi();
            $User->logout();
            session('[destroy]');
            $this->success('退出成功！', U('login'));
        }
        if(!session('user_auth')){
            $this->ajaxReturn(array("status"=>1),'json');
        }
    }
}
