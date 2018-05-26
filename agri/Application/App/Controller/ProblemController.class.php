<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace App\Controller;

use User\Api\UserApi;
/**
 * 提问回答控制器
 */
class ProblemController extends AppController{
    /**
     * 
     * @param unknown $field
     * @param unknown $where
     * @param string $order
     */
//     public function getTreeList($field,$where,$order = 'id'){
//         return D("Problem")->getTree();
//     }
    /**
     * 获取待回答
     * @param number $size
     * @param string $order
     */
    public function toBeAnswered($size=5,$order="create_time DESC"){
        if(IS_POST){
            define("UID", 2);
            $User = new UserApi();
            $data = int_to_string(D("Admin/Problem")->toBeAnswered($size,$order),array("reqid"=>arr2map($User->infoAll(),"uid","nickname")));
            $groupInfo = arr2map(D("Admin/Problemgroup")->getGroupCache("id,title","status=1"),"id","title");
            $pid = array();
            foreach ($data as &$val){
                if($val["block"]){
                    $block = explode("-", $val["block"]);
                    $str = "";
                    foreach ($block as $v){
                        $str .= $groupInfo[$v].".";
                    }
                    $val["block"] = substr($str, 0,strlen($str)-1);
                }
                $val["create_time"] = time_format(strtotime($val["create_time"]),"Y-m-d");
                $val['img']=C("DNSADD"). D("Admin/Problemg")->field("path")->where(array("doctype"=>1,"pid"=>$val["id"]))->find()["path"];
            }
            $this->ajaxReturn($data,"json");
        }
    }
    /**
     * 获取问题详情
     * @param unknown $id
     */
    public function detail($id=-1){
        if(IS_POST){
            define("UID", 2);
            $id = I("id",1);
            if($id){
                //用户信息
                $User = new UserApi();
                $User = $User->infoAll();
                $Problem = D("Admin/Problem")->getDeatil($id);
                //头像
                $photo = arr2map($User,"uid","photo")[$Problem["reqid"]];
                if(strpos("http",$photo)!== 0){
                    $photo = C("DNSADD").$photo;
                }
                //昵称
                $nickName = arr2map($User,"uid","nickname")[$Problem["reqid"]];
                //昵称
                $region = arr2map($User,"uid","region")[$Problem["reqid"]];
                //问题分类
                $groupInfo = arr2map(D("Admin/Problemgroup")->getGroupCache("id,title","status=1"),"id","title");
                if($Problem["block"]){
                    $block = explode("-", $Problem["block"]);
                    $str = "";
                    foreach ($block as $v){
                        $str .= $groupInfo[$v].".";
                    }
                    $Problem["block"] = substr($str, 0,strlen($str)-1);
                }
                $Problemg = D("Admin/Problemg")->getProblemg(array($id));
                $temp1 = array();//图片
                $temp2 = array();//语音
                foreach ($Problemg as $val){
                    if($Problem["id"]==$val["pid"]){
                        $val["path"] = C("DNSADD").$val["path"];
                        if($val["doctype"]==1){
                            array_push($temp1, $val);
                        }
                        if($val["doctype"]==2){
                            $val["stop"] = true;
                            $val["num"] = 13;
                            $val["total"] = 60;
                            array_push($temp2, $val);
                        }
                    }
                }
                $Problem["_img"] = $temp1;
                $Problem["_audio"] = $temp2;
                $this->ajaxReturn(array("nickname"=>$nickName,"photo"=>$photo,"region"=>$region,"data"=>$Problem),"json");
            }
        }
    }
}
