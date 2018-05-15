<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class PublicController extends Controller {

	/* 空操作，用于输出404页面 */
	public function _empty(){
	    $this->ajaxReturn(array("status"=>0,"info"=>"非法请求！"));
	}


    protected function _initialize(){
    /* 读取数据库中的配置 */
        /* 读取站点配置 */
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =   api('Config/lists');
            S('DB_CONFIG_DATA',$config,C("DATA_CACHE_TIME"));
        }
        C($config); //添加配置
        if(!C('WEB_SITE_CLOSE')){
            $this->ajaxReturn(array("status"=>0,"info"=>"站点已经关闭，请稍后访问~"));
        }
    }
    // 获取用户协议
    public function userProtocol() {
        if(IS_POST){
            $datail = D("Document")->detail(3);
            $this->ajaxReturn($datail,'json') ;
        }
    }
}
