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

class PictureModel extends Model{
	/**
	 * 获取图片路径
	 * @param $data
	 */
	public function picture($field=true,$id=array()){
		return $this->cache(true,C('DATA_CACHE_TIME'))->field($field)->select($id);
	}

}
