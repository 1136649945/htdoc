<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace App\Controller;

use Think\Controller;
use User\Api\UserApi;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class PublicController extends Controller {

    // 注册或更新用户
    public function login($username=-1,$password=-1,$verify=-1) {
        if(IS_POST){
            $verify = think_decrypt($verify);
            if($verify!=session("verify")){
                $this->ajaxReturn(array('status'=>0,'info'=>"验证码错误"),'json');
                exit();
            }
            $User = new UserApi();
            $info = $User->login($username, $password);
            $info['session'] = session_id();
            $this->ajaxReturn($info,'json');
        }
    }
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
    
    // 注册或更新用户
    /**
     * 注册或更新用户
     * $data['openid']
     * $data['nickname']
     * $data['sex']
     * @param unknown $data
     */
    public function registOrUpdate() {
        if(IS_POST){
            $data = json_decode($_REQUEST['data'],true);
            $verify = think_decrypt($data['verify']);
            if($verify!=session("verify")){
                $this->ajaxReturn(array('status'=>0,'info'=>"验证码错误"),'json');
                exit();
            }
            $User = new UserApi();
            if ($User->existenceUser(array("openid"=>$data['openid']))) {
                $info = $User->login($data['openid'], null,5);
                $this->ajaxReturn($info,'json');
            } else {
                $data["status"] = -2;
                $data["role"] = 2;
                $data["nickname"] = msubstr($data['nickName'],0,30, "utf-8", false);
                $data["photo"] = $data['avatarUrl'];
                $field = array("openid","status","role","nickname","photo","gender");
                foreach ($data as $k=>$v)
                {
                   if(!in_array($k, $field))
                       unset($data[$k]);
                }
                $info = $User->register((array)$data);
                $this->ajaxReturn($info,"json");
            }
        }
    }
    // 获取四个随机数
    public function randCode() {
        if(IS_POST){
            $ychar="0,1,2,3,4,5,6,7,8,9,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
            $list=explode(",",$ychar);
            for($i=0;$i<4;$i++){
                $randnum=rand(0,35); // 10+26;
                $authnum.=$list[$randnum];
            }
            session("verify",$authnum);
            $this->ajaxReturn(array('verify'=>think_encrypt($authnum),'session'=>session_id()),'json') ;
        }
    }
   
}