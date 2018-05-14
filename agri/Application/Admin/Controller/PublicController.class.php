<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use User\Api\UserApi;

/**
 * 后台首页控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.como>
 */
class PublicController extends \Think\Controller {

    /**
     * 后台用户登录
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function login($username = null, $password = null, $verify = null){
        if(IS_POST){
            /* 检测验证码 TODO: */
            if(!check_verify($verify)){
                $this->error('验证码输入错误！');
            }
            /* 调用UC登录接口登录 */
            $User = new UserApi;
            $info = $User->login($username, $password);
            if($info["status"]){
                if($info['uid']!=C("USER_ADMINISTRATOR")){
                    $this->error("禁止登录！");
                }else{
                    $this->success('登录成功！', U('Index/index'));
                }
            }else{
                $this->error($info["info"].$info["status"]);
            }
        } else {
            if(is_login()){
                $this->redirect('Index/index');
            }else{
                /* 读取数据库中的配置 */
                $config	=	S('DB_CONFIG_DATA');
                if(!$config){
                    $config	=	D('Config')->lists();
                    S('DB_CONFIG_DATA',$config,C("DATA_CACHE_TIME"));
                }
                C($config); //添加配置
                $this->display();
            }
        }
    }

    /* 退出登录 */
    public function logout(){
        if(is_login()){
            $User = new UserApi;
            $User->logout();
            session('[destroy]');
            $this->success('退出成功！', U('login'));
        } else {
            $this->redirect('login');
        }
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
            'fontttf' => '2.ttf'
        ); // 验证码字体，不设置随机获取
        
        $verify = new \Think\Verify($config);
        $verify->entry(1);
    }
    /**
     * 清除用户信息查询缓存
     */
    public function clearMemberCache(){
        S('MEMBER_CACHE',null);
    }
    
}
