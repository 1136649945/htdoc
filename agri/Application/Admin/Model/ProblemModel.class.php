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
 * 文档基础模型
 */
class ProblemModel extends Model{
    /**
     * 问题树列表
     * @param unknown $field
     * @param string $order
     * @param unknown $p
     * @param unknown $size
     */
    public function getTree($field,$order = 'id',$p,$size){
        $fieldp = true; 
        $data = array();
        if($field['p']){
            //获取问题的查询字段
            $fieldp = $field['p'];
        }
        //统计总数
        $count = $this->where("pid=0")->count();
        // 实例化分页类 传入总记录数和每页显示的记录数
        $Page = new \Think\Page($count,$size);
        $Page->setConfig("prev", "上一页");
        $Page->setConfig("next", "下一页");
        $Page->setConfig("theme", '<span class="rows">共 %TOTAL_ROW% 条记录</span> %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $data['show'] = $Page->show();// 分页显示输出
        //查询数据
        $data['data'] = $this->field($fieldp)->where("pid=0")->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
//         if(is_array($data['data']) && count($data['data'])>0){
//             $id = array();
//             foreach ($data['data'] as $val){
//                 //获取所有主表数据id
//                 array_push($id, $val['id']);
//             }
//             if(count($id)>0){
//                 $fielda = true;
//                 if($field['a']){
//                     //获取回答要查询字段
//                     $fielda = $field['a'];
//                 }
//                //查询子表数据
//                $answer = D("Problemanswer")->getAnswer($id);
//                if(is_array($answer) && count($answer)>0){
//                    foreach ($data['data'] as $key=>$val){
//                        $dataTemp = array();
//                        foreach ($answer as $aval){
//                            if($aval['pid']==$val['id']){
//                                //回答的是当前问题就取出来
//                                array_push($dataTemp, $aval);
//                                //移除
//                                array_shift($answer);
//                            }
//                        }
//                        $data['data'][$key]['_'] = $dataTemp;
//                    }
//                }
//             }
//         }
        return $data;
    }
    /**
     * 问题详情
     * @param unknown $id
     */
    public function getProblem($id){
        return $this->where("id=".$id." or pid=".$id)->select();
    }
}
