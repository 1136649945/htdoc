<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace App\Controller;

use Think\Model;
/**
 * 提问回答控制器
 */
class ProblemanswerController extends AppController{
    /**
     * 
     * @param unknown $field
     * @param unknown $where
     * @param string $order
     */
    public function answer($id,$cont,$img,$audio){
        if(IS_POST){
            define("UID", 2);
            $img = json_decode($img,true);
            $audio = json_decode($audio,true);
            if(is_array($img)){
                foreach ($img as &$val){
                    $val = substr($val, strlen(C("DNSADD")));
                }
            }
            if(is_array($audio)){
                foreach ($audio as &$val){
                    $val = substr($val["path"], strlen(C("DNSADD")));
                }
            }
            $Problem = M("problem")->find($id);
            $M = new Model();
            $M->startTrans();
            $rollback = false;
            try{
                $M->table(C('DB_PREFIX').'problemanswer')->add(array("content"=>$cont,"pid"=>$id,"answerid"=>UID));
                $data = array();
                if(is_array($img) && count($img)>0){
                    foreach ($img as $val){
                        array_push($data, array("doctype"=>3,"pid"=>$id,"path"=>$val));
                    }
                }
                if(is_array($audio) && count($audio)>0){
                    foreach ($audio as $val){
                        array_push($data, array("doctype"=>4,"pid"=>$id,"path"=>$val));
                    }
                }
                if(count($data)>0){
                   $M->table(C('DB_PREFIX').'problemg')->addAll($data);                    
                }
                if($Problem['uid']!=M("score")->where(array("gattr1"=>$id,"gattr2"=>'a',"uid"=>UID))->field("uid")->find()['uid'] && !$Problem["status"]){
                    $M->table(C('DB_PREFIX').'member')->where(array("uid"=>UID))->setInc("score",C("ANS_PROBLEM_SCORE"));
                    $M->table(C('DB_PREFIX')."score")->add(array("gattr1"=>$id,"gattr2"=>"a","uid"=>UID,"create_time"=>time_format(),"remark"=>"回答问题【".$Problem["title"]."】获得积分","dv"=>1,"score"=>C("ANS_PROBLEM_SCORE")));
                }
                $M->table(C('DB_PREFIX').'problem')->where(array("id"=>$id))->save(array("status"=>1));
            }catch (\Exception $e){
                $message = $e->getMessage();
                $rollback = true;
                $M->rollback();
            }
            if(!$rollback){
                $M->commit();
            }
            $this->ajaxReturn(array("status"=>!$rollback,"data"=>$message),"json");
        }
    }
    
}
