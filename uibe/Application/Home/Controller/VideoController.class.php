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
class VideoController extends HomeController {

	//系统首页
    public function video($block=45){
        //面包屑
        $channel = D('Channel')->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,3,28)");
        $this->assign('crumb',$this->crumb($channel, "1,3,28"));
        //视频菜单展示
        $channel = D('Channel')->lists("id,pid,url,gattr1,".TITLE,"hide=0 and status=1 and (id=3 or block=27)");
        $this->assign('video',$channel);
        $this->assign("block",$block);
        $this->assign("videolist",$this->videolist($block));
        //相关连接展示
        $channel = D('Channel')->lists("id,pid,target,url,".TITLE,"hide=0 and status=1 and (id=19 or block=25)");
        $this->assign('relate',$channel);
        //banner图
        $picture = D('Channelpicture')->picture("path","block=14 and hide=0");
        $this->assign("picture",$picture);
        //相关连接banner图
        $picture = D('Channelpicture')->picture("path","block=25 and hide=0");
        $this->assign("relatepicture",$picture);
        $this->display();
    }
    //视频列表
    public function videolist($block){
        return D("Video")->lists("title,path,imgpath","hide=0 and block=".$block);
    }
}