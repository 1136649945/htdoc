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
        $User = new UserApi();
        $this->ajaxReturn(int_to_string(D("Admin/Problem")->toBeAnswered(),array("reqid"=>arr2map($User->infoAll(),"uid","nickname"))),"json");
    }
}
