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
class WordsController extends AdminController
{

    /**
     * 后台菜单首页
     *
     * @return none
     */
    public function index()
    {
        $Wordes = D('Words');
        $count = $Wordes->count();
        $Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig("prev", "上一页");
        $Page->setConfig("next", "下一页");
        $Page->setConfig("theme", '<span class="rows">共 %TOTAL_ROW% 条记录</span> %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $data = $Wordes->order("id")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('data', $data);
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        $this->meta_title = '单词管理';
        $this->display();
    }
    /**
     * 新增菜单
     *
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function add()
    {
        // 记录当前列表页的cookie
        Cookie('__forward__', $_SERVER['REQUEST_URI']);
        if (IS_POST) {
            $Words = D('Words');
            $data = $Words->create();
            if ($data) {
                if ($Words->add($data)!== false) {
                    $this->success('添加成功', U('index'));
                } else {
                    $this->error('添加失败');
                }
            }
        }
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
                $Words = D('Words');
                $data = $Words->create();
                if ($data) {
                    if ($Words->save($data) !== false) {
                        $this->success('更新成功', Cookie('__forward__'));
                    } else {
                        $this->error('更新失败');
                    }
                } 
            }
        } else {
            /* 获取数据 */
            $info = D('Words')->find($id);
            $this->assign('info', $info);
            $this->meta_title = '编辑单词';
            $this->display();
        }
    }

}