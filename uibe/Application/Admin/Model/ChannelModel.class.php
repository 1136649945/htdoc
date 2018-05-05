<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 导航模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class ChannelModel extends Model {
    protected $_validate = array(
        array('title', 'require', '标题不能为空', self::MUST_VALIDATE , 'regex', self::MODEL_BOTH),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_INSERT),
    );
    /**
     * 获取频道导航树，指定分类则返回指定分类极其子分类，不指定则返回所有分类树
     * @param  integer $id    频道导航ID
     * @param  boolean $field 查询字段
     * @return array          频道导航树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function getTree($id = 0, $field = true){
        /* 获取所有频道导航 */
        $list = $this->field($field)->order(array('block','sort'))->select();
        $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);
    
        /* 获取返回数据 */
        if(isset($info)){ //指定分类则返回当前分类极其子分类
            $info['_'] = $list;
        } else { //否则返回所有分类
            $info = $list;
        }
        return $info;
    }
    /**
     * 获取导航列表
     * @param  boolean $field 要列出的字段
     * @return array          导航树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function getChannel($field = true,$where="1=1"){
        $data = $this->cache("UIBE",C('DATA_CACHE_TIME'))->field($field)->where($where)->order('sort')->select();
        $arr = array();
        if(is_array($data)){
            foreach ($data as $val){
                $arr[$val['id']] = $val['title'];
            }
        }
        return $arr;
    
    }
}
