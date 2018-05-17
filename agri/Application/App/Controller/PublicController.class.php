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
            if(!check_verify($verify)){
                $this->ajaxReturn(array("status"=>0,"info"=>'验证码输入错误！'),"json");
            }else{
                $User = new UserApi();
                $info = $User->login($username, $password);
                $this->ajaxReturn(array("status"=>$info["status"],"nickname"=>$info["nickname"],"mlevel"=>$info["mlevel"],"role"=>$info["role"]),'json');
            }
        }
    }
    
    /**
     * 注册
     * $data['openid']
     * $data['nickname']
     * $data['sex']
     * @param unknown $data
     */
    public function regist($username=-1,$password=-1,$verify=-1,$pcode=-1) {
        if(IS_POST){
            /* 检测验证码 TODO: */
            if(!check_verify($verify)){
                $this->ajaxReturn(array("status"=>0,"info"=>'验证码输入错误！'),"json");
            }else{
                $this->ajaxReturn(array("username"=>$username,"password"=>$password,"verify"=>$verify,"pcode"=>$pcode),"json");
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
        $this->ajaxReturn(array('session'=>$this->createNoncestr().session_id().$this->createNoncestr()),'json') ;
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
    //作用：产生随机字符串，不长于32位
    private function createNoncestr($length = 4) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}