<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace App\Controller;

/**
 * 提问回答控制器
 */
class ProblemController extends AppController{
    public function getTreeList($field,$where,$order = 'id'){
        return D("Problem")->getTree();
    }
    
}
