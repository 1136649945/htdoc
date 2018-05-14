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
 * 问题图片，语音
 */
class ProblemgModel extends Model{
    /**
     * 问题图片，语音
     */
    public function getProblemg($arr){
        if(is_array($arr) && count($arr)){
            $cond['pid']=array('in',$arr);
            return $this->where($cond)->order("pid,doctype,id")->select();
        }
    }
}
