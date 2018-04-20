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
class AbroadController extends HomeController {

	//系统首页
    public function abroad($id=null,$category_id=81){
        //面包屑
        $Chan = D('Channel');
        $channel = $Chan->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,12,44)");
        $this->assign('crumb',$this->crumb($channel,"1,12,44"));
        //留学贸大菜单展示
        $channel = $Chan->lists("id,pid,category_id,url,".TITLE,"hide=0 and status=1 and (id=12 or block=27)");
        $this->assign('abroad',$channel);
        //banner图
        $picture = D('Channelpicture')->picture("path","block=19 and hide=0");
        $this->assign("picture",$picture);
        //分类id
        $this->assign("category_id",$category_id);
        //文章id
        $this->assign("id",$id);
        //文章列表
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