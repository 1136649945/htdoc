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
class ActiveController extends AdminController
{

    /**
     * 后台菜单首页
     *
     * @return none
     */
    public function index()
    {
        $Actreg = D('Actreg');
        $count = $Actreg->count();
        $Page = new \Think\Page($count,15);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->setConfig("prev", "上一页");
        $Page->setConfig("next", "下一页");
        $Page->setConfig("theme", '<span class="rows">共 %TOTAL_ROW% 条记录</span> %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $data = $Actreg->order("id")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('data', $data);
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        $channel = D('Channel')->getChannel("id,title","pid in (85,86,87)");
        $this->assign('active',$channel);//活动报名-活动类型
        // 记录当前列表页的cookie
        $this->meta_title = '活动管理';
        $this->display();
    }
}