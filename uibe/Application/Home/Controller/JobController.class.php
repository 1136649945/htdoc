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
class JobController extends HomeController {

    //系统首页
    public function job($id=null,$category_id=111){
        //面包屑
        $channel = D('Channel')->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,14,51)");
        $this->assign('crumb',$this->crumb($channel,"1,14,51"));
        //实习就业菜单展示
        $channel = D('Channel')->lists("id,pid,category_id,url,".TITLE,"hide=0 and status=1 and (id=14 or block=27)");
        $this->assign('job',$channel);
        //banner图
        $picture = D('Channelpicture')->picture("path","block=21 and hide=0");
        $this->assign("picture",$picture);
        //文章分类对应关系
        $category = array(111=>111,112=>111,113=>111,116=>116,117=>116,118=>116,119=>119,120=>119);
        $this->assign("pcate",$category[$category_id]);
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