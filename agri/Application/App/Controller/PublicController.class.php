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
use Think\Model;
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
            $UcenterMember = D('UcenterMember');
            $info = $UcenterMember->login(1,$username, $password);
            if(is_array($info)){
                $info['session'] = session_id();
                $this->ajaxReturn($info,'json');
            }else{
                $this->ajaxReturn(array('status'=>$info,'info'=>$this->showErrorMessage($info)),'json');
            }
        }
    }
    /**
     * 退出登录
     */
    public function loginout(){
        $UcenterMember = D('UcenterMember');
        $UcenterMember->logout();
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
            if ($this->verifyUse($data['openid'])) {
                $UcenterMember = D('UcenterMember');
                $info = $UcenterMember->login(5,$data['openid']);
                if(is_array($info)){
                    $this->ajaxReturn(array('status'=>1,'info'=>session_id()),'json');
                }else{
                    $this->ajaxReturn(array('status'=>$info,'info'=>$this->showErrorMessage($info)),'json');
                }
            } else {
                $uid = $this->regist($data);
                if($uid){
                    $this->ajaxReturn(array("status"=>-2,"info"=>"帐号审核"),"json");
                }else{
                    $this->ajaxReturn(array("status"=>-2,"info"=>"帐号审核"),"json");
                }
            }
        }
    }

    // 注册用户
    private function regist($data) {
        /*  "nickName":" 周鹏辉"
         *  "gender":1
         *  "language":"zh_CN"
         *  "province":"Beijing"
         *  "country":"China"
         *  "province":"Beijing"
         *  "openid":"oytIb5BLWeNueVydCxiCVVE_XLmg"
         *  "avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eoD7n7hPKo7IoW2enDDrBbvhzlXicbUsBgDDbDGWLrHicKsd8FbKiaj3v5NnDarymjypU3rnhVDCULlw/132"
         */
        $data1 = array("openid"=>$data['openid']);
        $M = new Model();
        $roolback = true;
        $M->startTrans();
        try{
            $id = D('UcenterMember')->add($data1);
            D('UcenterMember')->where(array("id"=>$id))->save(array("username"=>C("USERNAMEPIX").$id));
            if($id){
                $data2 = array("uid"=>$id,'code'=>substr($id.C("RECOMMEND"), 0,C("RECOMMENDLEN")),"gender"=>$data['gender'],"nickname"=>msubstr($data['nickName'],0,30, "utf-8", false),"gender"=>$data['gender'],"photo"=>$data['avatarUrl'],"role"=>2,"reg_time"=>time_format(),"last_login_time"=>time_format(),"status"=>-2);
                $uid = D("Member")->add($data2);
                if($uid){
                    M()->commit();
                    return $uid;
                }else{
                    M()->rollback();
                    return 0;
                }
            }else{
                M()->rollback();
                return 0;
            }
        }catch (\Exception $e){
            $roolback = false;
            $M->rollback();
            return 0;
        }
    }

    // 检测用户是否存在
    private function verifyUse($openid) {
       $id = D('UcenterMember')->verify($openid);
        if ($id) {
            return true;
        }
        return false;
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
   
    /**
     * 登录状态信息
     * @param unknown $mode
     */
    private function showErrorMessage($mode){
        $error = '未知错误！';
        switch($mode) {
            case 0: $error = '账号被禁用！'; break; //系统级别禁用
            case -1: $error = '账号被删除！'; break; //系统级别禁用
            case -2: $error = '账号审核中！'; break;
            case -3: $error = '密码错误！'; break;
            case -4: $error = '账号不存在！'; break;
            default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
        }
        return $error;
    }
}