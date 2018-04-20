<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;

/**
 * 图片模型
 * 负责图片的上传
 */

class ChannelpictureModel extends Model{

    /**
     * 获得所有图片
     * @param string $files
     * @param string $where
     */
    public function picture($files=true,$where="1=1"){
        /* 上传文件 */
       return $this->cache(true,C('DATA_CACHE_TIME'))->field($files)->where($where)->order('sort')->select();
    }

}
