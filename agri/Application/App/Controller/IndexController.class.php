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
class IndexController extends Controller {

	//系统首页
    public function index($session=null){
        $this->assign("session",$session);
        $this->assign("value",S($session));
        var_dump("sss");
        $this->display();
    }

}