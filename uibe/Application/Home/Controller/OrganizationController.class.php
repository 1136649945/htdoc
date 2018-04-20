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
class OrganizationController extends HomeController {

    //系统首页
    public function organization($id=null,$category_id=48){
        //面包屑
        $Chan = D('Channel');
        $channel = $Chan->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,15,55)");
        $this->assign('crumb',$this->crumb($channel,"1,15,55"));
        //学生组织菜单展示
        $channel = $Chan->lists("id,pid,category_id,url,".TITLE,"hide=0 and status=1 and (id=15 or block=27)");
        $this->assign('organization',$channel);
        //banner图
        $Chapic = D('Channelpicture');
        $picture = $Chapic->picture("path","block=22 and hide=0");
        $this->assign("picture",$picture);
        //banner图
        $picture = $Chapic->picture("path","block=50 and hide=0");
        $this->assign("rollpicture",$picture);
        //分类id
        $this->assign("category_id",$category_id);
        //文章id
        $this->assign("id",$id);
        $Doc = D("Document");
        if($id>0){
            $this->assign("docinfo",$Doc->docdetail("m.id=".$id));
        }else{
            $data = $Doc->doclists("m.category_id=".$category_id." and m.display=1 and m.status=1","m.create_time desc");
            $this->assign("docarr",json_encode($data));
        }
        $this->display();
    }
}