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
class ScholarshipController extends HomeController {

	//系统首页
    public function scholarship($category_id=124){
        //面包屑
        $channel = D('Channel')->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,11,39)");
        $this->assign('crumb',$this->crumb($channel,"1,11,39"));
        //奖学金菜单展示
        $channel = D('Channel')->lists("id,pid,url,category_id,".TITLE,"hide=0 and status=1 and (id=11 or block=27)");
        $this->assign('scholarship',$channel);
        //banner图
        $picture = D('Channelpicture')->picture("path","block=18 and hide=0");
        $this->assign("picture",$picture);
        $docinfo = D('Document')->docdetail("m.category_id=".$category_id." and m.display=1 and m.status=1 limit 0,1");
        $this->assign("category_id",$category_id);
        $this->assign("docinfo",$docinfo);
        //文章分类对应关系
        $category = array(124=>124,123=>124,122=>124,131=>128,130=>128,129=>128,128=>128);
        $this->assign("pcate",$category[$category_id]);
        //分类id
        $this->assign("category_id",$category_id);
        $this->display();
    }

}