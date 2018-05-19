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
    public function login($username="qwe123",$password="123123",$verify=-1) {
        $User = new UserApi();
        $info = $User->login($username, $password);
        if(IS_POST){
            $secode = S(session_id());
            if(!$secode){
                $this->ajaxReturn(array("status"=>-6,"info"=>'验证码失效！'),"json");
                exit();
            }
            if($secode!=strtoupper($verify)){
                $this->ajaxReturn(array("status"=>-5,"info"=>'验证码错误！'),"json");
                exit();
            }else{
                $User = new UserApi();
                $info = $User->login($username, $password);
                $this->ajaxReturn(array("status"=>$info["status"],"info"=>$info["info"],"data"=>array("nickname"=>$info["nickname"]),"menu"=>$this->menu()),'json');
            }
        }
    }
    /**
     * 退出登录
     */
    private function menu(){
        $e = "{
          \"color\": \"#848484\",
          \"selectedColor\": \"#4ac372\",
          \"backgroundColor\": \"#f4f4f4\",
          \"position\": \"bottom\",
          \"borderStyle\": \"white\",
          \"list\": [{
            \"pagePath\": \"../../pages/system/system\",
            \"text\": \"专家系统\",
            \"iconPath\": \"../../images/system.png\",
            \"selectedIconPath\": \"../../images/system_cur.png\"
          }, {
              \"pagePath\": \"../../pages/problem/problem\",
            \"text\": \"问题中心\",
            \"iconPath\": \"../../images/problem.png\",
            \"selectedIconPath\": \"../../images/problem_cur.png\"
          }, {
              \"pagePath\": \"../../pages/learn/learn\",
            \"text\": \"学习园地\",
            \"iconPath\": \"../../images/learn.png\",
            \"selectedIconPath\": \"../../images/learn_cur.png\"
          }, {
              \"pagePath\": \"../../pages/personal/personal\",
            \"text\": \"个人中心\",
            \"iconPath\": \"../../images/personal.png\",
            \"selectedIconPath\": \"../../images/personal_cur.png\"
          }]
        }";
        $u = "{
          \"color\": \"#848484\",
          \"selectedColor\": \"#4ac372\",
          \"backgroundColor\": \"#f4f4f4\",
          \"position\": \"bottom\",
          \"borderStyle\": \"white\",
          \"list\": [{
            \"pagePath\": \"../../pages/ask/ask\",
            \"text\": \"提问\",
            \"iconPath\": \"../../images/ask.png\",
            \"selectedIconPath\": \"../../images/ask_cur.png\"
          }, {
              \"pagePath\": \"../../pages/problem/problem\",
            \"text\": \"问题中心\",
            \"iconPath\": \"../../images/problem.png\",
            \"selectedIconPath\": \"../../images/problem_cur.png\"
          }, {
              \"pagePath\": \"../../pages/learn/learn\",
            \"text\": \"学习园地\",
            \"iconPath\": \"../../images/learn.png\",
            \"selectedIconPath\": \"../../images/learn_cur.png\"
          }, {
              \"pagePath\": \"../../pages/personal/personal\",
            \"text\": \"个人中心\",
            \"iconPath\": \"../../images/personal.png\",
            \"selectedIconPath\": \"../../images/personal_cur.png\"
          }]
        }";
        //         $auth = array(
        //                 'uid'             => $user['uid'],
        //                 'username'        => $user['nickname'],
        //                 'mlevel'        => $user['mlevel'],
        //                 'role'        => $user['role'],
        //                 'status'       => $user['status'],
        //             );
        return $e;
        $auth = session('user_auth');
        if($auth){
            if($auth["status"]){
                if($auth["role"]==1){
                   return $e;
                }
                if($auth["role"]==2){
                    return $u;
                }
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
            $secode = S(session_id());
            if(!$secode){
                $this->ajaxReturn(array("status"=>-6,"info"=>'验证码失效！'),"json");
                exit();
            }
            if($secode!=$verify){
                $this->ajaxReturn(array("status"=>-5,"info"=>'验证码输入错误！'),"json");
                exit();
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
    public function verify($session_id=null)
    {
        if($session_id){
            $session_id = substr($session_id, 4,strlen($session_id)-8);
        }
        $config = array(
            'useCurve' => false, // 是否画混淆曲线
            'useNoise' => false, // 是否添加杂点
            'length' => 4, // 验证码位数
            'codeSet' => '2345678ABCDEFGHJKLMNPQRTUVWXY',
            'fontttf' => '2.ttf',
            'fontSize' => 15,
            'imageW' => 210,
            'imageH' => 30,
            'expire' => 600,
            'bg' => array(255, 255, 255),
            "session_id" =>$session_id
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