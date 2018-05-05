<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 提问回答控制器
 */
class ProblemController extends AdminController{
    /**
     * 问题管理
     */
    public function index(){
        C('_SYS_GET_PROBLEM_TREE_', true); //标记系统获取分类树模板
        $field = array('p'=>'id,reqid,content,hide','a'=>'id,pid,content,hide');
        $tree = $this->getTreeList($field);
        $this->assign('tree', $tree['data']);
        $this->assign('page', $tree['show']);
        $this->meta_title = '问题中心';
        $this->display();
    }
    /**
     * 显示分类树，仅支持内部调
     * @param  array $tree 分类树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function tree($tree = null){
        C('_SYS_GET_PROBLEM_TREE_') || $this->_empty();
        $this->assign('tree', $tree);
        $this->display('tree');
    }
    
    
    public function getTreeList($field,$where,$order = 'id'){
        return D("Problem")->getTree($field,$where,$order = 'id');
    }
    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus(){
        $method = I("method");
        $id = I('id',0);
        if ( !$id) {
            $info=array('status'=>0,'info'=>'请选择要操作的数据');
            $this->ajaxReturn($info,'json');
            return ;
        }
        $data = array('hide'=>1);
        $map['id'] =   array('eq',$id);
        $info = null;
        switch ( strtolower($method) ){
            case 'p':
                $info = $this->ajaxEditRow('Problem',$data, $map );
                break;
            case 'a':
                $info = $this->ajaxEditRow('Problemanswer',$data, $map );
                break;
            default:
                $info=array('status'=>0,'info'=>'非法操作');
        }
        $this->ajaxReturn($info,'json');
    }
}
