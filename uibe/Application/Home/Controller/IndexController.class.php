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
class IndexController extends HomeController {

	//系统首页
    public function index(){
        //首页轮播图
        $this->assign("scroll",D('Channelpicture')->picture("path","block=12 and hide=0"));
        //部门设置
        $channel = D('Channel')->lists("id,category_id,pid,url,gattr1,".TITLE,"hide=0 and status=1 and (id=25 or pid=25)",2);
        $this->assign('depset',$channel);
        $Doc = D('Document');
        //特色项目
        $document =  $Doc->doclists("m.category_id in (53,54,55,56,57,58) and m.display=1 and m.status=1","m.create_time desc","0,6");
        $this->assign('document',$document);
        //通知公告
        $document =  $Doc->doclists("m.category_id=73 and m.display=1 and m.status=1","m.create_time desc","0,6");
        $this->assign('document1',$document);
        //学院新闻
        $document =  $Doc->doclists("m.category_id=72 and m.display=1 and m.status=1","m.create_time desc","0,3");
        $this->assign('document2',$document);
        $this->display();
    }

}