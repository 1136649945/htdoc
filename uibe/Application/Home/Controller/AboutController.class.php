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
class AboutController extends HomeController {

	//系统首页
    public function about($id=null,$category_id=74){
        $Chan = D('Channel');
        $channel = $Chan->getChannel("id,pid,url,".TITLE,"hide=0 and status=1 and id in (1,2,24)");
        $this->assign('crumb',$this->crumb($channel,"1,2,24"));//面包屑
        $channel = $Chan->lists("id,category_id,pid,url,".TITLE,"hide=0 and status=1 and (id=2 or block=27)");
        $this->assign('about',$channel);//关于我们菜单展示
        $picture = D('Channelpicture')->picture("path","block=13 and hide=0");
        $this->assign("picture",$picture);//banner图
        $Doc = D('Document');
        $document = $Doc->doclists("m.category_id=99 and m.display=1 and m.status=1");
        $this->assign('document',$document);//校院一角
        //分类id
        $this->assign("category_id",$category_id);
        //文章id
        if($id){
            $this->assign("docinfo",$Doc->docdetail("m.id=".$id));
        }
        //学校简介
        if(74==$category_id){
           $docinfo = $Doc->docdetail("m.category_id=74 and m.display=1 and m.status=1 limit 0,1");
           $id = $docinfo[0]['id'];
           $this->assign("docinfo",$docinfo);
        }
        //历史回顾
        if(76==$category_id){
            $docinfo = $Doc->docdetail("m.category_id=76 and m.display=1 and m.status=1 limit 0,1");
            $id = $docinfo[0]['id'];
            $this->assign("docinfo",$docinfo);
        }
        //校园风光
        if(77==$category_id && $id==null){
            $docarr = $Doc->doclists("m.category_id=77 and m.display=1 and m.status=1","m.create_time desc");
            $this->assign("docarr",json_encode($docarr));
        }
        //部门设置
        if(in_array_case($category_id,array(101,102,103,104,105,106,107,108,109))){
            $this->assign("parcat",101);
            if($id==null){
                $data = $Chan->cache(true,C('DATA_CACHE_TIME'))->query("select m.".TITLE." as title,m.category_id as cat_id,g.path as path,h.id as id from ".C("DB_PREFIX")."channel m left join ".C("DB_PREFIX")."channelpicture g on m.title=g.title left join ".C("DB_PREFIX")."category h  on g.title=h.title where m.pid=25");
                foreach ($data as $key=>$val){
                    $data[$key]['content'] = $Doc->doclists("m.category_id=".$data[$key]['id']." and m.display=1 and m.status=1","m.create_time desc");
                }
                $this->assign("mentcont",$data);
            }
        }
        $this->assign("id",$id);
        $this->display();
    }

}