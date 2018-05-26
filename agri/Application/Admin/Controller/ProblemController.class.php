<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;

use User\Api\UserApi;
/**
 * 提问回答控制器
 */
class ProblemController extends AdminController{
    /**
     * 问题管理列表
     */
    public function index($p=1){
        C('_SYS_GET_PROBLEM_TREE_', true); //标记系统获取分类树模板
        $field = array('p'=>'id,title,reqid,content,hide','a'=>'id,pid,content,hide');
        $tree = D("Problem")->getTree($field,$order = 'id',$p,C("LIST_ROWS"));
        $this->assign('tree', $tree['data']);
        $this->assign('page', $tree['show']);
        $this->meta_title = '问题中心';
        $this->display();
    }
    /**
     * 问题详情
     */
    public function detail(){
        $id = I("id",0);
        if($id){
            //问题分类
            $groupInfo = arr2map(D("Problemgroup")->getGroupCache("id,title","status=1"),"id","title");
            //用户信息
            $User = new UserApi();
            $data = $User->infoAll();
            $this->assign("userinfo",arr2map($data,"uid","nickname"));
            $Problem = D("Problem")->getProblem($id);
            $arr = array();
            foreach ($Problem as $k =>$val){
                if($val["block"]){
                      $block = explode("-", $val["block"]);
                      $str = "";
                      foreach ($block as $v){
                         $str .= $groupInfo[$v].".";
                      }
                      $Problem[$k]["block"] = substr($str, 0,strlen($str)-1);
                }
                array_push($arr, (int)$val["id"]);
            }
            $Problema = D("Problemanswer")->getAnswer(array_unique($arr));
            foreach ($Problema as $val){
                array_push($arr, (int)$val["id"]);
            }
            $Problemg = D("Problemg")->getProblemg(array_unique($arr));
            foreach ($Problem as &$v){
                $temp = array();//回答
                foreach ($Problema as $k=>&$va){
                    $temp1 = array();//图片
                    $temp2 = array();//语音
                    foreach ($Problemg as $val){
                        if($va["id"]==$val["pid"]){
                            if($val["doctype"]==3){
                                array_push($temp1, $val);
                            }
                            if($val["doctype"]==4){
                                array_push($temp2, $val);
                            }
                        }
                    }
                    $va["_img"] = $temp1;
                    $va["_audio"] = $temp2;
                    if($v["id"]==$va["pid"]){
                        array_push($temp, $va);
                    }
                }
                $v["_a"] = $temp;
                $temp1 = array();//图片
                $temp2 = array();//语音
                foreach ($Problemg as $val){
                    if($v["id"]==$val["pid"]){
                        if($val["doctype"]==1){
                            array_push($temp1, $val);
                        }
                        if($val["doctype"]==2){
                            array_push($temp2, $val);
                        }
                    }
                }
                $v["_img"] = $temp1;
                $v["_audio"] = $temp2;
            }
            $this->assign("tree",list_to_tree($Problem));
        }
        $this->meta_title = '问题详情';
        $this->display();
    }
    /**
     * 显示分类树，仅支持内部调
     * @param  array $tree 分类树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function tree($tree = null){
        C('_SYS_GET_PROBLEM_TREE_') || $this->_empty();
        $this->assign('tree', $tree);
        $this->display('tree');
    }
    /**
     * 显示分类树，仅支持内部调
     * @param  array $tree 分类树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function atree($list = null){
        $this->assign('list', $list);
        $this->display("atree");
    }
    /**
     * 显示分类树，仅支持内部调
     * @param  array $tree 分类树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function ptree($list = null){
        $this->assign('list', $list);
        $this->display("ptree");
    }
    /**
     * 显示分类树，仅支持内部调
     * @param  array $tree 分类树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function ctree($list = null){
        $this->assign('list', $list);
        $this->display("ctree");
    }
    
    /**
     * 问题分类列表
     */
    public function group(){
        $group = D("Problemgroup");
        $list = $group->getGroup();
        int_to_string($list,array('status'=>array(1=>'正常',0=>'禁用')));
        $this->assign("tree",list_to_tree($list));
        $this->meta_title = '问题分类';
        $this->display();
    }
    /**
     * 显示分类树，仅支持内部调
     * @param  array $tree 分类树
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function grouptree($list = null){
        $this->assign('tree', $list);
        $this->display("grouptree");
    }
    /**
     * 新增问题分类
     * @param string $username
     * @param string $password
     * @param string $repassword
     * @param string $email
     */
    public function addgroup($pid=0){
        $Problemgroup = D("Problemgroup");
        $group = $Problemgroup->getGroup("id,title,lev","lev<3");
        $this->assign("group",$group);
        $this->assign("pid",$pid);
        if(IS_POST){
            $return = array();
            $data = $Problemgroup->create();
            if($data){
                $groupMap = arr2map($group,"id","lev");
                $data["lev"] = 1+ $groupMap[$data["pid"]];
                $id = $Problemgroup->add($data);
                if($id!==false){
                    $return['status']   =   1;
                    $return['info']     =   '保存成功！';
                    $return['url'] = "/admin.php?s=/Problem/group";
                    $this->ajaxReturn($return,'json');
                    return ;
                }else{
                    $return['status']   =   0;
                    $return['info']     =   '保存失败！';
                    $this->ajaxReturn($return,'json');
                    return ;
                }
            }else{
                $return['status']   =   0;
                $return['info']     =   $Problemgroup->getError();
                $this->ajaxReturn($return,'json');
                return ;
            }
        } else {
            $this->meta_title = '新增问题分类';
            $this->display();
        }
    }
    /**
     * 新增问题分类
     * @param string $username
     * @param string $password
     * @param string $repassword
     * @param string $email
     */
    public function editgroup($id=-1,$pid=0){
        $Problemgroup = D("Problemgroup");
        $group = $Problemgroup->getGroup("id,title,lev","lev<3 and id<>".$id);
        $this->assign("group",$group);
        $this->assign("pid",$pid);
        if(IS_POST){
            $return = array();
            if($id){
                $data = $Problemgroup->create();
                $groupMap = arr2map($group,"id","lev");
                $data["lev"] = 1+ $groupMap[$data["pid"]];
                if($data){
                    $id = $Problemgroup->save($data);
                    if($id!==false){
                        $return['status']   =   1;
                        $return['info']     =   '编辑成功！';
                        $return['url'] = "/admin.php?s=/Problem/group";
                        $this->ajaxReturn($return,'json');
                        return ;
                    }else{
                        $return['status']   =   0;
                        $return['info']     =   '编辑失败！';
                        $this->ajaxReturn($return,'json');
                        return ;
                    }
                }else{
                    $return['status']   =   0;
                    $return['info']     =   $Problemgroup->getError();
                    $this->ajaxReturn($return,'json');
                    return ;
                }
            }
        } else {
            if($id){
                $this->assign("info",$Problemgroup->getGroupInfo($id));
                $this->meta_title = '编辑问题分类';
                $this->display();
            }
        }
    }
    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus(){
        $method = I("method");
        $id = I('id',0);
        $hide = I('hide',0);
        if ( !$id) {
            $info=array('status'=>0,'info'=>'请选择要操作的数据');
            $this->ajaxReturn($info,'json');
            return ;
        }
        $data = array('hide'=>$hide);
        $map['id'] =   array('eq',$id);
        $info = null;
        switch ( strtolower($method) ){
            case 'p':
                $info = $this->ajaxEditRow('Problem',$data, $map );
                break;
            case 'a':
                $info = $this->ajaxEditRow('Problemanswer',$data, $map );
                break;
            case 'g':
                $info = $this->ajaxEditRow('Problemgroup',$data, $map );
                break;
            default:
                $info=array('status'=>0,'info'=>'非法操作');
        }
        $this->ajaxReturn($info,'json');
    }
    /**
     * 问题状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatu($method=null,$id=null){
        $status = I('status',0);
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $info=array('status'=>0,'info'=>'请选择要操作的数据');
            $this->ajaxReturn($info,'json');
            return ;
        }
        $map['id'] =   array('in',$id);
        $data = array('status'=>$status);
        $info = null;
        switch ( strtolower($method) ){
            case 'a':
                $info = $this->ajaxEditRow('Problemgroup',array('status'=>'1'), $map );
                break;
            case 'as':
                $info = $this->ajaxEditRow('Problemgroup',array('status'=>'0'), $map );
                break;
            case 'g':
                $info = $this->ajaxEditRow('Problemgroup',$data, $map );
                break;
            default:
                $info=array('status'=>0,'info'=>'非法操作'.$method);
        }
        $this->ajaxReturn($info,'json');
    }
}
