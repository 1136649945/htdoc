<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class ContactController extends HomeController {

	//系统首页
    public function contact(){
        $picture = D('Channelpicture')->picture("path","block=16 and hide=0");
        $this->assign("picture",$picture);//banner图
        $channel = D('Channel')->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,8)");
        $this->assign('crumb',$this->crumb($channel, "1,8"));//面包屑
        $channel = D('Channel')->getChannel("id,".TITLE,"hide=0 and status=1 and pid=85");
        $this->assign('activetype',$channel);//活动报名-活动类型
        $channel = D('Channel')->getChannel("id,".TITLE,"hide=0 and status=1 and pid=86");
        $this->assign('activement',$channel);//活动报名-部门
        $channel = D('Channel')->getChannel("id,".TITLE,"hide=0 and status=1 and pid=87");
        $this->assign('activtratype',$channel);//活动报名-培训种类
        $this->display();
    }
}