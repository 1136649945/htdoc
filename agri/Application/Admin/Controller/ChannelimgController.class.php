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
 *
 * @author yangweijie <yangweijiester@gmail.com>
 */
class ChannelimgController extends AdminController
{

    /**
     * 导航图片首页
     *
     * @return none
     */
    public function index()
    {
        $block = I("block",-1);
        $where = "1=1 ";
        if($block>-1){
            $where = $where." and block=".$block;
        }
        $Picture = D('Channelpicture');
        $count = $Picture->where($where)->count();
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig("prev", "上一页");
        $Page->setConfig("next", "下一页");
        $Page->setConfig("theme", '<span class="rows">共 %TOTAL_ROW% 条记录</span> %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $data = $Picture->where($where)->order(array('block','sort','id'))->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('data', $data);
        $group = M('Group')->where('hide=0 and (purpose=0 or purpose=1)')->order('sort')->field('id,title')->select();
        $this->assign("block", $group);
        $group = M('Group')->where('hide=0 and (purpose=0 or purpose=1)')->getField("id,title");
        $this->assign("group", $group);
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->meta_title = '导航图片管理';
        $this->display();
    }
    /**
     * 
     * 新增图片
     */
    public function add()
    {
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        if (IS_POST) {
            $Picture = D('Channelpicture');
            $pic_driver = C('PICTURE_UPLOAD_DRIVER');
            $info = $Picture->upload($_FILES, C('PICTURE_UPLOAD'), C('PICTURE_UPLOAD_DRIVER'), C("UPLOAD_{$pic_driver}_CONFIG"));
            $data = $Picture->create();
            $array = array();
            if ($info) {
                foreach ($info as $key => $value) {
                    $data['path'] = C('PICTURE_UPLOAD')['rootPath'] . $value['savepath'] . $value['savename'];
                    $data[$key] = $value[$key];
                    array_push($array, $data);
                }
                $Picture->addAll($array);
            }
        }
        echo "<script>location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
    }

    /**
     * 编辑配置
     *
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function edit($id = -1)
    {
        if (IS_POST) {
            $id = I("post.id");
            if ($id) {
                $Picture = D('Channelpicture');
                $data = $Picture->create();
                $pic_driver = C('PICTURE_UPLOAD_DRIVER');
                $info = $Picture->upload($_FILES, C('PICTURE_UPLOAD'), C('PICTURE_UPLOAD_DRIVER'), C("UPLOAD_{$pic_driver}_CONFIG"));
                if ($info) {
                    $path = D('Channelpicture')->field("path")->find($id);
                    $path = C('PICTURE_UPLOAD')['rootPath'] . $path['path'];
                    if (is_file($path)) {
                        unlink($path);
                    }
                    foreach ($info as $key => $value) {
                        $data['path'] = substr(C('PICTURE_UPLOAD'), 1) . $value['savepath'] . $value['savename'];
                        $data[$key] = $value[$key];
                    }
                }
                if ($data) {
                    if ($Picture->data($data)->save() !== false) {
                        $this->success('更新成功', Cookie('__forward__'));
                    } else {
                        $this->error('更新失败');
                    }
                } else {
                    $this->error($Picture->getError());
                }
            }
        } else {
            /* 获取数据 */
            $info = D('Channelpicture')->field(true)->find($id);
            $this->assign('info', $info);
            $group = M('Group')->where('hide=0 and (purpose=0 or purpose=1)')->order('sort')->field('id,title')->select();
            $this->assign("block", $group);
            $this->meta_title = '编辑后台菜单';
            $this->display();
        }
    }

    /**
     * 删除图片
     *
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function del()
    {
        $id = I('id', - 1);
        if ($id) {
            $path = D('Channelpicture')->field("path")->find($id);
            $path = C('PICTURE_UPLOAD')['rootPath'] . $path['path'];
            $data = D('Channelpicture')->delete($id);
            $msg = array_merge(array(
                'success' => '操作成功！',
                'error' => '操作失败！',
                'url' => '',
                'ajax' => IS_AJAX
            ), (array) $msg);
            if ($data) {
                if (is_file($path)) {
                    unlink($path);
                }
                $this->success($msg['success'], $msg['url'], $msg['ajax']);
            } else {
                $this->error($msg['error'], $msg['url'], $msg['ajax']);
            }
        }
    }
}