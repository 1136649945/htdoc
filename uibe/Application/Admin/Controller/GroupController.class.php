<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

/**
 * 后台配置控制器
 * @author yangweijie <yangweijiester@gmail.com>
 */
class GroupController extends AdminController {

    /**
     * 后台菜单首页
     * @return none
     */
    public function index(){
        $data = D('Group')->order(array('sort','id'))->select();
        $this->assign('data',$data);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);
        $this->meta_title = '导航分组管理';
        $this->display();
    }

    /**
     * 新增菜单
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function add(){
        if(IS_POST){
            $Group = D('Group');
            $data = $Group->create();
            if($data){
                $id = $Group->add();
                if($id){
                    session('ADMIN_MENU_LIST',null);
                    //记录行为
                    action_log('update_Group', 'Group', $id, UID);
                    $this->success('新增成功', Cookie('__forward__'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Group->getError());
            }
        } else {
            $this->meta_title = '新增导航分组';
            $this->display('edit');
        }
    }

    /**
     * 编辑配置
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function edit($id = -1){
        if(IS_POST){
            $Group = D('Group');
            $data = $Group->create();
            if($data){
                if($Group->save()!== false){
                    session('ADMIN_MENU_LIST',null);
                    //记录行为
                    action_log('update_Group', 'Group', $data['id'], UID);
                    $this->success('更新成功',Cookie('__forward__'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($Group->getError());
            }
        } else {
            /* 获取数据 */
            $info = D('Group')->field(true)->find($id);
            $this->assign('info', $info);
            $this->meta_title = '编辑后台菜单';
            $this->display();
        }
    }

    /**
     * 删除后台菜单
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function del(){
        if(IS_POST){
            $id = I('id',-1);
            if($id){
                $data = array();
                $id = I('id',-1);
                $group1=D('Channel')->where('block='.$id)->find();
                $group2=D('Channelpicture')->where('block='.$id)->find();
                $group3=D('Video')->where('block='.$id)->find();
                if($group1 || $group2 || $group3){
                    $data['status']=false;
                    $data['info']="禁止删除，分组已在使用！";
                    $this->ajaxReturn($data,"json");
                }else{
                    if(D('Group')->where(array(id=>$id))->delete()){
                        session('ADMIN_MENU_LIST',null);
                        //记录行为
                        action_log('update_Group', 'Group', $id, UID);
                        $data['status']=true;
                        $this->ajaxReturn($data,"json");
                    } else {
                        $data['status']=false;
                        $data['info']="删除失败！";
                        $this->ajaxReturn($data,"json");
                    }
                }
            }
        }
    }

}