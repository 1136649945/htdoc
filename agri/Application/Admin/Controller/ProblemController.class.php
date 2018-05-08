<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use User\Api\UserApi;
/**
 * 提问回答控制器
 */
class ProblemController extends AdminController{
    /**
     * 问题管理列表
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
     * 问题详情
     */
    public function detail(){
        $group = D("Problemgroup");
        $info = $group->getGroupCacheMap('id','title',"id,title","status=1");
        $this->assign("group",$info);
        $id = I("id",0);
        $tree = $this->getTreeList(null,'id='.$id)['data'];
        $id = array();
        foreach ($tree as $val){
            array_push($id, $val['reqid']);
            array_push($id, $val['uid']);
        }
        $this->assign('tree', $tree);
        if(count($id)>0){
            $User = new UserApi();
            $data = $User->infoAll('select u.id,m.nickname from __UCENTER_MEMBER__ u left join __MEMBER__ m on u.id=m.uid where u.id in('.arr2str($id,',').')');
            $info = array();
            foreach ($data as $val){
                $info[$val['id']] = $val['nickname'] ;
            }
            $this->assign("userinfo",$info);
        }
        $this->meta_title = '问题详情';
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
     * 问题分类列表
     */
    public function group(){
        $group = D("Problemgroup");
        $list = $group->getGroup();
        int_to_string($list,array('status'=>array(1=>'正常',0=>'禁用')));
        $this->assign("_list",$list);
        $this->meta_title = '问题分类';
        $this->display();
    }
    
    /**
     * 新增问题分类
     * @param string $username
     * @param string $password
     * @param string $repassword
     * @param string $email
     */
    public function addgroup($id=0){
        $Problemgroup = D("Problemgroup");
        if(IS_POST){
            $return = array();
            if($id){
                $data = $Problemgroup->create();
                if($data){
                    $id = $Problemgroup->save($data);
                    if($id!==false){
                        $return['status']   =   1;
                        $return['info']     =   '编辑成功！';
                        $return['url'] = "/admin.php?s=/Problem/group";
                        $this->ajaxReturn($return,'json');
                        return ;
                    }else{
                        $return['status']   =   0;
                        $return['info']     =   '编辑失败！';
                        $this->ajaxReturn($return,'json');
                        return ;
                    }
                }else{
                    $return['status']   =   0;
                    $return['info']     =   $Problemgroup->getError();
                    $this->ajaxReturn($return,'json');
                    return ;
                }
            }else{
                $data = $Problemgroup->create();
                if($data){
                    $id = $Problemgroup->add($data);
                    if($id!==false){
                        $return['status']   =   1;
                        $return['info']     =   '保存成功！';
                        $return['url'] = "/admin.php?s=/Problem/group";
                        $this->ajaxReturn($return,'json');
                        return ;
                    }else{
                        $return['status']   =   0;
                        $return['info']     =   '保存失败！';
                        $this->ajaxReturn($return,'json');
                        return ;
                    }
                }else{
                    $return['status']   =   0;
                    $return['info']     =   $Problemgroup->getError();
                    $this->ajaxReturn($return,'json');
                    return ;
                }
            }
    
        } else {
            if($id){
                $this->assign("info",$Problemgroup->getGroupInfo($id));
                $this->meta_title = '编辑问题分类';
                $this->display();
            }else{
                $this->meta_title = '新增问题分类';
                $this->display();
            }
        }
    }
    
    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus(){
        $method = I("method");
        $id = I('id',0);
        $hide = I('hide',0);
        if ( !$id) {
            $info=array('status'=>0,'info'=>'请选择要操作的数据');
            $this->ajaxReturn($info,'json');
            return ;
        }
        $data = array('hide'=>$hide);
        $map['id'] =   array('eq',$id);
        $info = null;
        switch ( strtolower($method) ){
            case 'p':
                $info = $this->ajaxEditRow('Problem',$data, $map );
                break;
            case 'a':
                $info = $this->ajaxEditRow('Problemanswer',$data, $map );
                break;
            case 'g':
                $info = $this->ajaxEditRow('Problemgroup',$data, $map );
                break;
            default:
                $info=array('status'=>0,'info'=>'非法操作');
        }
        $this->ajaxReturn($info,'json');
    }
    /**
     * 问题状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatu($method=null,$id=null){
        $status = I('status',0);
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $info=array('status'=>0,'info'=>'请选择要操作的数据');
            $this->ajaxReturn($info,'json');
            return ;
        }
        $map['id'] =   array('in',$id);
        $data = array('status'=>$status);
        $info = null;
        switch ( strtolower($method) ){
            case 'a':
                $info = $this->ajaxEditRow('Problemgroup',array('status'=>'1'), $map );
                break;
            case 'as':
                $info = $this->ajaxEditRow('Problemgroup',array('status'=>'0'), $map );
                break;
            case 'g':
                $info = $this->ajaxEditRow('Problemgroup',$data, $map );
                break;
            default:
                $info=array('status'=>0,'info'=>'非法操作'.$method);
        }
        $this->ajaxReturn($info,'json');
    }
}
