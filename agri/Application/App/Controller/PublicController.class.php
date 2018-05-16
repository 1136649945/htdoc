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
    /**
     * 注册
     * $data['openid']
     * $data['nickname']
     * $data['sex']
     * @param unknown $data
     */
    public function regist() {
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
    public function session() {
        $this->ajaxReturn(array('session'=>session_id()),'json') ;
    }
    /**
     * 验证码
     */
    public function verify()
    {
        $config = array(
            'useCurve' => false, // 是否画混淆曲线
            'useNoise' => false, // 是否添加杂点
            'length' => 4, // 验证码位数
            'codeSet' => '2345678ABCDEFGHJKLMNPQRTUVWXY',
            'fontttf' => '2.ttf',
            'fontSize' => 15,
            'imageW' => 210,
            'imageH' => 30,
            'bg' => array(255, 255, 255)
        ); // 验证码字体，不设置随机获取
    
        $verify = new \Think\Verify($config);
        $verify->entry(1);
    }
}