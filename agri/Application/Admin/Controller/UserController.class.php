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
use Think\Model;

/**
 * 后台用户控制器
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
class UserController extends AdminController {

    /**
     * 会员管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function index(){
        $nickname       =   I('get.nickname');
        $map['role']  =   array('eq',2);
        if($nickname){
            if(is_numeric($nickname)){
                $map['uid|nickname']=   array(intval($nickname),array('like','%'.$nickname.'%'),'_multi'=>true);
            }else{
                $map['nickname']    =   array('like', '%'.(string)$nickname.'%');
            }
        }
        $list   = $this->lists('Member', $map);
        int_to_string($list,array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',-2=>'待审核')));
        $this->assign('_list', $list);
        $this->meta_title = '用户信息';
        $this->display();
    }
    /**
     * 专家管理首页
     * @author 麦当苗儿 <zuojiazi@vip.qq.com>
     */
    public function expert(){
        $nickname       =   I('nickname');
        $map['status']  =   array('egt',0);
        $map['uid']  =   array('neq',C("USER_ADMINISTRATOR"));
        $map['role']  =   array('eq',1);
        if($nickname){
            if(is_numeric($nickname)){
                $map['uid|nickname']=   array(intval($nickname),array('like','%'.$nickname.'%'),'_multi'=>true);
            }else{
                $map['nickname']    =   array('like', '%'.(string)$nickname.'%');
            }
        }
        $group = D("Problemgroup")->getGroupCache("id,title","status=1");
        $list   = $this->lists('Member', $map);
        $map = arr2map($group,"id","title");
        $map[0] = "全部";
        int_to_string($list,array('status'=>array(1=>'正常',-1=>'删除',0=>'禁用',-2=>'待审核'),'areas'=>$map));
        $this->assign('_list', $list);
        $this->meta_title = '专家信息';
        $this->display();
    }
    
    /**
     * 专家新增方法
     * @param string $username
     * @param string $password
     * @param string $repassword
     * @param string $email
     */
    public function expertadd($id=null,$username=null,$password=null,$repassword=null,$verify=null){
        if(IS_POST){ 
            //注册专家
            $return = array();
            /* 检测验证码 */
            //             if(!check_verify($verify)){
            //                 $this->error('验证码输入错误！');
            //                 $return['info']     =   '验证码输入错误！';
            //                 $return['status']   =   0;
            //                 $this->ajaxReturn($return,'json');
            //             }
            if(!$username){
                $return['status']   =   0;
                $return['info']     =   '用户名不能为空！';
                $this->ajaxReturn($return,'json');
                return ;
            }
            if(!$password){
                $return['status']   =   0;
                $return['info']     =   '密码不能为空！';
                $this->ajaxReturn($return,'json');
                return ;
            }
            /* 检测密码 */
            if($password != $repassword){
                $return['status']   =   0;
                $return['info']     =   '密码和确认密码不一致！';
                $this->ajaxReturn($return,'json');
                return ;
            }
            if($id){
                $UcenterMember = D("UcenterMember");
                $Member = D("Member");
                $data1 = $UcenterMember->create();
                $data2 = $Member->create();
                if($data1 && $data2){
                    M()->startTrans();
                    $data1['password'] = think_encrypt( $data1['password'], C("DATA_AUTH_KEY"));
                    $id = $UcenterMember->save($data1);
                    if($id!==false){
                        $uid = $Member->save($data2);
                        if($uid!==false){
                            M()->commit();
                            $return['status']   =   1;
                            $return['info']     =   '编辑成功！';
                            $return['url'] = "/admin.php?s=/User/expert";
                            $this->ajaxReturn($return,'json');
                            return ;
                        }else{
                            M()->rollback();
                            $return['status']   =   0;
                            $return['info']     =   '编辑失败！';
                            $this->ajaxReturn($return,'json');
                            return ;
                        }
                    }else{
                        M()->rollback();
                        $return['status']   =   0;
                        $return['info']     =   '编辑失败！';
                        $this->ajaxReturn($return,'json');
                        return ;
                    }
                }else{
                    if(!$data1){
                        $return['status']   =   0;
                        $return['info']     =   $this->showRegError($UcenterMember->getError());
                        $this->ajaxReturn($return,'json');
                        return ;
                    }else {
                        $return['status']   =   0;
                        $return['info']     =   $this->showRegError($Member->getError());
                        $this->ajaxReturn($return,'json');
                        return ;
                    }
                }
            }else{
                $UcenterMember = D("UcenterMember");
                $Member = D("Member");
                $data1 = $UcenterMember->create();
                $data2 = $Member->create();
                if($data1 && $data2){
                    M()->startTrans();
                    $data1['password'] = think_encrypt( $data1['password']);
                    $id = $UcenterMember->add($data1);
                    if($id){
                        $data2['uid'] = $id;
                        $data2['role'] = 1;
                        $data2['code'] = substr($id.C("RECOMMEND"), 0,C("RECOMMENDLEN"));
                        $data2['reg_time'] = date("Y-m-d H:i:s");
                        $data2['last_login_time'] = date("Y-m-d H:i:s");
                        $uid = $Member->add($data2);
                        if($uid){
                            M()->commit();
                            D('AuthGroup')->addToGroup($id,4);
                            $return['status']   =   1;
                            $return['info']     =   '新增成功！';
                            $return['url'] = "/admin.php?s=/User/expert";
                            $this->ajaxReturn($return,'json');
                            return ;
                        }else{
                            M()->rollback();
                            $return['status']   =   0;
                            $return['info']     =   '新增失败！';
                            $this->ajaxReturn($return,'json');
                            return ;
                        }
                    }else{
                        M()->rollback();
                        $return['status']   =   0;
                        $return['info']     =   '新增失败！';
                        $this->ajaxReturn($return,'json');
                        return ;
                    }
                }else{
                    if(!$data1){
                        $return['status']   =   0;
                        $return['info']     =   $this->showRegError($UcenterMember->getError());
                        $this->ajaxReturn($return,'json');
                        return ;
                    }else {
                        $return['status']   =   0;
                        $return['info']     =   $this->showRegError($Member->getError());
                        $this->ajaxReturn($return,'json');
                        return ;
                    }
                }
            }
            
        } else {
            $group = D("Problemgroup");
            $this->assign("group",$group->getGroupCache("id,title","status=1"));
            if($id){
                $this->meta_title = '编辑专家';
                $User = new UserApi;
                $info = $User->info($id);
                if(is_array($info)){
                    $info['password'] = think_decrypt($info['password']);
                    $this->assign("info",$info);
                }
                $this->display();
            }else{
                $this->meta_title = '新增专家';
                $this->display();
            }
        }
    }
    
    /**
     * 修改昵称初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updateNickname(){
        $nickname = M('Member')->getFieldByUid(UID, 'nickname');
        $this->assign('nickname', $nickname);
        $this->meta_title = '修改昵称';
        $this->display('updatenickname');
    }

    /**
     * 修改昵称提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitNickname(){
        //获取参数
        $nickname = I('post.nickname');
        $password = I('post.password');
        empty($nickname) && $this->error('请输入昵称');
        empty($password) && $this->error('请输入密码');

        //密码验证
        $User   =   new UserApi();
        $uid    =   $User->login(UID, $password, 4);
        ($uid == -2) && $this->error('密码不正确');

        $Member =   D('Member');
        $data   =   $Member->create(array('nickname'=>$nickname));
        if(!$data){
            $this->error($Member->getError());
        }

        $res = $Member->where(array('uid'=>$uid))->save($data);

        if($res){
            $user               =   session('user_auth');
            $user['username']   =   $data['nickname'];
            session('user_auth', $user);
            session('user_auth_sign', data_auth_sign($user));
            $this->success('修改昵称成功！');
        }else{
            $this->error('修改昵称失败！');
        }
    }

    /**
     * 修改密码初始化
     * @author huajie <banhuajie@163.com>
     */
    public function updatePassword(){
        $this->meta_title = '修改密码';
        $this->display('updatepassword');
    }

    /**
     * 修改密码提交
     * @author huajie <banhuajie@163.com>
     */
    public function submitPassword(){
        //获取参数
        $password   =   I('post.old');
        empty($password) && $this->error('请输入原密码');
        $data['password'] = I('post.password');
        empty($data['password']) && $this->error('请输入新密码');
        $repassword = I('post.repassword');
        empty($repassword) && $this->error('请输入确认密码');

        if($data['password'] !== $repassword){
            $this->error('您输入的新密码与确认密码不一致');
        }

        $Api    =   new UserApi();
        $res    =   $Api->updateInfo(UID, $password, $data);
        if($res['status']){
            $this->success('修改密码成功！');
        }else{
            $this->error($res['info']);
        }
    }

    /**
     * 用户行为列表
     * @author huajie <banhuajie@163.com>
     */
    public function action(){
        //获取列表数据
        $Action =   M('Action')->where(array('status'=>array('gt',-1)));
        $list   =   $this->lists($Action);
        int_to_string($list);
        // 记录当前列表页的cookie
        Cookie('__forward__',$_SERVER['REQUEST_URI']);

        $this->assign('_list', $list);
        $this->meta_title = '用户行为';
        $this->display();
    }

    /**
     * 新增行为
     * @author huajie <banhuajie@163.com>
     */
    public function addAction(){
        $this->meta_title = '新增行为';
        $this->assign('data',null);
        $this->display('editaction');
    }

    /**
     * 编辑行为
     * @author huajie <banhuajie@163.com>
     */
    public function editAction(){
        $id = I('get.id');
        empty($id) && $this->error('参数不能为空！');
        $data = M('Action')->field(true)->find($id);

        $this->assign('data',$data);
        $this->meta_title = '编辑行为';
        $this->display('editaction');
    }

    /**
     * 更新行为
     * @author huajie <banhuajie@163.com>
     */
    public function saveAction(){
        $res = D('Action')->update();
        if(!$res){
            $this->error(D('Action')->getError());
        }else{
            $this->success($res['id']?'更新成功！':'新增成功！', Cookie('__forward__'));
        }
    }

    /**
     * 会员状态修改
     * @author 朱亚杰 <zhuyajie@topthink.net>
     */
    public function changeStatus($method=null){
        $id = array_unique((array)I('id',0));
        if( in_array(C('USER_ADMINISTRATOR'), $id)){
            $this->error("不允许对超级管理员执行该操作!");
        }
        $id = is_array($id) ? implode(',',$id) : $id;
        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }
        $map['uid'] =   array('in',$id);
        switch ( strtolower($method) ){
            case 'forbiduser':
                $this->forbid('Member', $map );
                break;
            case 'resumeuser':
                $this->resumeuser($id );
                break;
            case 'deleteuser':
                $this->delete('Member', $map );
                break;
            default:
                $this->error('参数非法');
        }
    }
    /**
     * 会员审核通过
     * @param unknown $id
     */
    private function resumeuser($id){
        $msg   = array_merge( array( 'success'=>'操作成功！', 'error'=>'操作失败！', 'url'=>'' ,'ajax'=>IS_AJAX) , (array)$msg );
        $id  = is_array($id) ? $id : explode(',',$id);
        $Member = D("Member");
        $uidpcode = arr2map($Member->field("uid,pcode")->select(),"uid","pcode");
        $uidcode = arr2map($Member->field("uid,code")->select(),"code","uid");
        $M = new Model();
        $M->startTrans();
        $roolback = true;
        try {
            foreach ($id as $val){
                $res = $M->table(C('DB_PREFIX').'member')->where(array("uid"=>$val))->save(array('status'=>1));
                if($res){
                    $pcode = $uidpcode[$val];
                    if($pcode){
                        $res = $M->table(C('DB_PREFIX')."score")->field("id")->where(array("gattr1"=>$pcode."|".$val,"gattr2"=>"r"))->find();
                        if(!$res){
                            $res = $M->table(C('DB_PREFIX').'member')->where(array("code"=>$pcode))->setInc("score",C("AGRI_RECOMMEND_SCORE"));
                            if($res){
                                if($uidcode[$pcode]){
                                    $M->table(C('DB_PREFIX')."score")->add(array("gattr1"=>$pcode."|".$val,"gattr2"=>"r","uid"=>$uidcode[$pcode],"create_time"=>time_format(),"remark"=>"推荐用户注册，用户注册通过","dv"=>1,"score"=>C("AGRI_RECOMMEND_SCORE")));
                                }
                            }
                        }
                    }
                }
            }
        }catch (\Exception $e){
            $roolback = false;
            $M->rollback();
            $this->error($e->getMessage(),$msg['url'],$msg['ajax']);
        }
        if($roolback){
            $M->commit();
        }
        $this->success($msg['success'],$msg['url'],$msg['ajax']);
    }
    /**
     * 会员
     * @param unknown $id
     */
    public function add($id=-1){
        if($id){
            $User = new UserApi;
            $info = $User->info($id);
            if(is_array($info)){
                $this->assign("info",$info);
            }
            $this->display();
            $this->meta_title = '用户详情';
            $this->display();
        }
    }
    
    /**
     *  验证码，用于登录和注册
     */
    public function verify()
    {
        $config = array(
            'useCurve' => false, // 是否画混淆曲线
            'useNoise' => false, // 是否添加杂点
            'length' => 4, // 验证码位数
            'codeSet' => '2345678ABCDEFGHJKLMNPQRTUVWXY',
            'fontttf' => '2.ttf'
        ); // 验证码字体，不设置随机获取
        
        $verify = new \Think\Verify($config);
        $verify->entry(1);
    }
    
    /**
     * 获取用户注册错误信息
     * @param  integer $code 错误编码
     * @return string        错误信息
     */
    private function showRegError($code = 0){
        switch ($code) {
            case -1:  $error = '用户名格式为6-10个数字和字母组成的字符！'; break;
            case -2:  $error = '用户名被禁止注册！'; break;
            case -3:  $error = '用户名被占用！'; break;
            case -4:  $error = '密码格式为6-10个数字和字母组成的字符！'; break;
            case -5:  $error = '邮箱格式不正确！'; break;
            case -6:  $error = '邮箱长度必须在1-32个字符之间！'; break;
            case -7:  $error = '邮箱被禁止注册！'; break;
            case -8:  $error = '邮箱被占用！'; break;
            case -9:  $error = '手机格式不正确！'; break;
            case -10: $error = '手机被禁止注册！'; break;
            case -11: $error = '手机号被占用！'; break;
            case -12: $error = '电话格式不正确！'; break;
            case -20: $error = '姓名长度在5个汉字内！'; break;
            case -21: $error = '专业长度在10个汉字内！'; break;
            case -22: $error = '工作地长度在25个汉字内！'; break;
            case -23: $error = '昵称在10个汉字内！'; break;
            case -24: $error = '地区在25个汉字内！'; break;
            default:  $error = '未知错误';
        }
        return $error;
    }

}