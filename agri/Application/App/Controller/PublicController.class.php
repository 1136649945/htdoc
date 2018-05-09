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
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class PublicController extends Controller {

	// 当前用户
    private $user;
    // 注册或更新用户
    public function login($username=-1,$password=-1) {
        if(IS_POST){
            $UcenterMember = D('UcenterMember');
            $info = $UcenterMember->login(1,$username, $password);
            if(is_array($info)){
                $this->ajaxReturn(array('status'=>1,'info'=>session_id()),'json');
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
     * $data['unionid']
     * $data['nickname']
     * $data['sex']
     * @param unknown $data
     */
    public function registOrUpdate($data) {
        if ($this->verify($data['unionid'])) {
            $this->update(['session_key' => $data->session_key], ['uid' => $this->user->uid]);
        } else {
            $this->regist($data);
        }
        $response = [
            'thirdSession' => $this->generate3rdSession()
        ];
        echo json_encode($response);
    }

    // 注册用户
    private function regist($data) {
        $this->db->insert('user', $data);
    }

    // 更新用户
    private function update($user, $condition) {
        $this->db->update('user', $user, $condition);
    }

    // 检测用户是否存在
    private function verify($unionid) {
       $id = D('UcenterMember')->verify($unionid);
        if ($id) {
            return true;
        }
        return false;
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