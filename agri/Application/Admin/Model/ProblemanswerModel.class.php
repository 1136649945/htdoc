<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 文档基础模型
 */
class ProblemanswerModel extends Model{
    /**
     * 获取回答
     * @param unknown $arr
     * @param string $order
     */
    public function getAnswer($arr){
        if(is_array($arr) && count($arr)){
            $cond['pid']=array('in',$arr);
            return $this->where($cond)->order("pid,id")->select();
        }
    }
}
