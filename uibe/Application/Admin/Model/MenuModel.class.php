<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;

/**
 * 插件模型
 * @author yangweijie <yangweijiester@gmail.com>
 */

class MenuModel extends Model {

    protected $_validate = array(
        array('title','require','标题必须填写'), 
        array('url','require','链接必须填写'), 
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('title', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );
    /**
     * 获取菜单树，指定菜单则返回指定菜单极其子菜单，不指定则返回所有菜单树
     * @param  integer $id    菜单ID
     * @param  boolean $field 查询字段
     * @return array          菜单树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function getTree($pid = -1,$id = 0, $field = true){
        /* 获取所有菜单 */
        $where = array('1=1');
        if($pid){
            $where['pid'] = $pid;
        }
        $list = $this->field($field)->where($where)->order(array('sort','block'))->select();
        $list = list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_', $root = $id);
        /* 获取返回数据 */
        if(isset($info)){ //指定菜单则返回当前菜单极其子菜单
            $info['_'] = $list;
        } else { //否则返回所有菜单
            $info = $list;
        }
    
        return $info;
    }
}