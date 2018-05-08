<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 图片模型
 * 负责图片的上传
 */

class ProblemgroupModel extends Model{
    /**
     * 获取分组
     * @param unknown $field
     * @param unknown $where
     * @param unknown $ord
     */
    public function getGroup($field=true,$where='1=1',$order="sort"){
       return $this->field($field)->where($where)->order($order)->select();
    }
    /**
     * 获取分组
     * @param unknown $field
     * @param unknown $where
     * @param unknown $ord
     */
    public function getGroupInfo($id=0,$field=true){
        return $this->field($field)->find($id);
    }
    /**
     * 获取分组
     * @param unknown $field
     * @param unknown $where
     * @param unknown $ord
     */
    public function getGroupCache($field=true,$where="1=1",$order="sort"){
       return $this->cache('AGRI',C('DATA_CACHE_TIME'))->field($field)->where($where)->order($order)->select();
    }
    /**
     * 获取分组
     * @param unknown $field
     * @param unknown $where
     * @param unknown $ord
     */
    public function getGroupCacheMap($k='id',$v='title',$field=true,$where="1=1",$order="sort"){
        $data = $this->getGroupCache($field,$where,$order);
        $map = array();
        foreach ($data as $val){
            $map[$val[$k]] = $val[$v];
        }
        return $map;
    }
}
