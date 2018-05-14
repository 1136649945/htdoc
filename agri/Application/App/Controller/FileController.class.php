<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace App\Controller;

use Think\Upload;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */

class FileController extends AppController {
	/* 文件上传 */
	public function upload(){
	    if(empty($_FILES["file"]['tmp_name'])){
	        $this->ajaxReturn( array("status"=>0,"info"=>"没有上传文件！"));
	    }
	    $Upload = new Upload(C("ATTACHMENT_UPLOAD"));
	    $info   = $Upload->upload($_FILES);
	    if($info){
	        return $this->ajaxReturn(array("status"=>1,"info"=>C("ATTACHMENT_UPLOAD")['rootPath'].$info["file"]["savepath"]. $info["file"]["savename"]),"json");
	    }else{
	        /* 返回JSON数据 */
	        $this->ajaxReturn( array("status"=>0,"info"=>$Upload->getError()));
	    }

		
	}

	/* 下载文件 */
	public function download($id = null){
		if(empty($id) || !is_numeric($id)){
			$this->error('参数错误！');
		}

		$logic = D('Download', 'Logic');
		if(!$logic->download($id)){
			$this->error($logic->getError());
		}
		
	}
}
