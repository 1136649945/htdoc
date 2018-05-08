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
 * 后台频道控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class ChannelController extends AdminController {

    /**
     * 频道列表
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $pid = I('get.pid', 0);
        /* 获取频道列表 */
        $map  = array('status' => array('gt', -1), 'pid'=>$pid);
        $list = M('Channel')->where($map)->order('sort asc,id asc')->select();

        $this->assign('list', $list);
        $this->assign('pid', $pid);
        $this->meta_title = '导航管理';
        $this->display();
    }
    /**
     * 广告位置
     */
    public function channelgroup(){
        /* 获取频道列表 */
        $list = M('Channelgroup')->order('sort')->select();
        $this->assign('list', $list);
        $this->meta_title = '广告位置管理';
        $this->display();
    }

    /**
     * 添加频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function add(){
        if(IS_POST){
            $Channel = D('Channel');
            $data = $Channel->create();
            if($data){
                $id = $Channel->add();
                if($id){
                    $this->success('新增成功', U('index'));
                    //记录行为
                    action_log('update_channel', 'channel', $id, UID);
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($Channel->getError());
            }
        } else {
            $pid = I('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
                $parent = M('Channel')->where(array('id'=>$pid))->field('title')->find();
                $this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info',null);
            $this->meta_title = '新增导航';
            $this->display('edit');
        }
    }

    /**
     * 编辑频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function edit($id = 0){
        if(IS_POST){
            $Channel = D('Channel');
            $data = $Channel->create();
            if($data){
                if($Channel->save()){
                    //记录行为
                    action_log('update_channel', 'channel', $data['id'], UID);
                    $this->success('编辑成功', U('index'));
                } else {
                    $this->error('编辑失败');
                }

            } else {
                $this->error($Channel->getError());
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = M('Channel')->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }

            $pid = I('get.pid', 0);
            //获取父导航
            if(!empty($pid)){
            	$parent = M('Channel')->where(array('id'=>$pid))->field('title')->find();
            	$this->assign('parent', $parent);
            }

            $this->assign('pid', $pid);
            $this->assign('info', $info);
            $this->meta_title = '编辑导航';
            $this->display();
        }
    }

    /**
     * 删除频道
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function del(){
        $id = array_unique((array)I('id',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(M('Channel')->where($map)->delete()){
            //记录行为
            action_log('update_channel', 'channel', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * 导航排序
     * @author huajie <banhuajie@163.com>
     */
    public function sort(){
        if(IS_GET){
            $ids = I('get.ids');
            $pid = I('get.pid');

            //获取排序的数据
            $map = array('status'=>array('gt',-1));
            if(!empty($ids)){
                $map['id'] = array('in',$ids);
            }else{
                if($pid !== ''){
                    $map['pid'] = $pid;
                }
            }
            $list = M('Channel')->where($map)->field('id,title')->order('sort asc,id asc')->select();

            $this->assign('list', $list);
            $this->meta_title = '导航排序';
            $this->display();
        }elseif (IS_POST){
            $ids = I('post.ids');
            $ids = explode(',', $ids);
            foreach ($ids as $key=>$value){
                $res = M('Channel')->where(array('id'=>$value))->setField('sort', $key+1);
            }
            if($res !== false){
                $this->success('排序成功！');
            }else{
                $this->error('排序失败！');
            }
        }else{
            $this->error('非法请求！');
        }
    }
    /**
     * 新增广告位置
     * @param string $username
     * @param string $password
     * @param string $repassword
     * @param string $email
     */
    public function addgroup($id=0){
        $Channelgroup = M("Channelgroup");
        if(IS_POST){
            $return = array();
            if($id){
                $data = $Channelgroup->create();
                if($data){
                    $id = $Channelgroup->save($data);
                    if($id!==false){
                        $return['status']   =   1;
                        $return['info']     =   '编辑成功！';
                        $return['url'] = "/admin.php?s=/Channel/channelgroup";
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
                    $return['info']     =   $Channelgroup->getError();
                    $this->ajaxReturn($return,'json');
                    return ;
                }
            }else{
                $data = $Channelgroup->create();
                if($data){
                    $id = $Channelgroup->add($data);
                    if($id){
                        $return['status']   =   1;
                        $return['info']     =   '保存成功！';
                        $return['url'] = "/admin.php?s=/Channel/channelgroup";
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
                    $return['info']     =   $Channelgroup->getError();
                    $this->ajaxReturn($return,'json');
                    return ;
                }
            }
    
        } else {
            if($id){
                $this->assign("info",$Channelgroup->find($id));
                $this->meta_title = '编辑广告位置';
                $this->display();
            }else{
                $this->meta_title = '新增广告位置';
                $this->display();
            }
        }
    }
}