<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Home\Controller;

/**
 * 后台配置控制器
 *
 * @author yangweijie <yangweijiester@gmail.com>
 */
class ActiveController extends HomeController
{

    /**
     * 提交活动
     *
     * @author yangweijie <yangweijiester@gmail.com>
     */
    public function add()
    {
        //   /Admin/Active/add
        if (IS_POST) {
            $Actreg = D('Actreg');
            $data = $Actreg->create();
            if ($data) {
                $info = array();
                if ($Actreg->add($data)!== false) {
                    $info['status'] = true;
                    $info['info'] = '提交成功';
                    $this->ajaxReturn($info, "json");
                } else {
                    $info['status'] = false;
                    $info['info'] = '提交失败';
                    $this->ajaxReturn($info, "json");
                }
            }
        }
    }

}