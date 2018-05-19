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
    public function getTreeList($field,$where,$order = 'id'){
        return D("Problem")->getTree();
    }
    /**
     * 获取待回答
     * @param number $size
     * @param string $order
     */
    public function toBeAnswered($size=5,$order="create_time asc"){
        define("UID", 2);
        $User = new UserApi();
        $data = int_to_string(D("Admin/Problem")->toBeAnswered(),array("reqid"=>arr2map($User->infoAll(),"uid","nickname")));
        $pid = array();
        foreach ($data as &$val){
            $val["create_time"] = time_format(strtotime($val["create_time"]),"Y-m-d");
            $val['img']="http://192.168.3.134:8080/agri/". D("Admin/Problemg")->field("path")->where(array("doctype"=>1,"pid"=>$val["id"]))->find()["path"];
        }
        $this->ajaxReturn($data,"json");
    }
}
