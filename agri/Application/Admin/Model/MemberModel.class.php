<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;

/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class MemberModel extends Model {

    protected $_validate = array(
        array('name', '0,5', -20, self::EXISTS_VALIDATE, 'length'),//姓名长度
        array('specialty', '0,10', -21, self::EXISTS_VALIDATE, 'length'),//专业长度
        array('education', '0,25', -22, self::EXISTS_VALIDATE, 'length'),//学历
        array('nickName', '0,10', -23, self::EXISTS_VALIDATE, 'length'),//昵称
        array('region', '0,25', -24, self::EXISTS_VALIDATE, 'length'),//地区
        
    );
    /* 自动完成规则 */
    protected $_auto = array(
         array('content', 'htmlspecialchars', self::MODEL_BOTH, 'function'),
    );
   
}
