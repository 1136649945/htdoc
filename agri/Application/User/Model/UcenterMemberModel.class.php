<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace User\Model;
use Think\Model;
/**
 * 会员模型
 */
class UcenterMemberModel extends Model{
	/**
	 * 数据表前缀
	 * @var string
	 */
	protected $tablePrefix = UC_TABLE_PREFIX;

	/**
	 * 数据库连接
	 * @var string
	 */
	protected $connection = UC_DB_DSN;

	/**
	 * 用户登录认证
	 * @param  string  $username 用户名
	 * @param  string  $password 用户密码
	 * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
	 * @return integer           登录成功-用户ID，登录失败-错误编号
	 */
	public function login($username, $password,$type = 1){
		$map = array();
		switch ($type) {
			case 1:
				$map['username'] = $username;
				break;
			case 2:
				$map['email'] = $username;
				break;
			case 3:
				$map['mobile'] = $username;
				break;
			case 4:
				$map['id'] = $username;
				break;
			case 5:
			    $map['openid'] = $username;
			    break;
			default:
				return 0; //参数错误
		}

		/* 获取用户数据 */
		$user = $this->where($map)->find();
		if(is_array($user)){
			/* 验证用户密码 */
			if($type===5 || think_encrypt($password) === $user['password']){
			    $info = $this->info($user['id']);
			    if(1!=$info['status']){
			        //账号禁用，删除，审核
			        return array("status"=>$info['status'],"info"=>$this->showErrorMessage($info['status']));
			    }
				$this->updateLogin($user['id']); //更新用户登录信息
				$this->autoLogin($info);
				$info["info"] = "登录成功！";
				return $info; //登录成功，返回用户ID
			} else {
				//密码错误
			    return array("status"=>-3,"info"=>$this->showErrorMessage(-3));
			}
		} else {
			//用户不存
		    return array("status"=>-4,"info"=>$this->showErrorMessage(-4));
		}
	}
	
	/**
	 * 自动登录用户
	 * @param  integer $user 用户信息数组
	 */
	private function autoLogin($user){
	    /* 记录登录SESSION和COOKIES */
	    $auth = array(
	        'uid'             => $user['uid'],
	        'username'        => $user['nickname'],
	        'mlevel'        => $user['mlevel'],
	        'role'        => $user['role'],
	    );
	    S(session_id(),$auth,C("APP_SESSION"));
	    session('user_auth', $auth);
	    session('user_auth_sign', data_auth_sign($auth));
	
	}
	
	
	/**
	 * 注销当前用户
	 * @return void
	 */
	public function logout(){
	    session('user_auth', null);
	    session('user_auth_sign', null);
	    S(session_id(),null);
	}
	
	
	/**
	 * 
	 * @param unknown $data1
	 * @param unknown $data2
	 */
	public function register($data){
	    if($data){
	        $M = new Model();
	        $M->startTrans();
	        $roll = true;
	        try{
	           $data["reg_time"] = time_format();
	           $data["last_login_time"] = time_format();
    	       $id =  D("UcenterMember")->add($data);
    	       if(!$data["username"]){
        	       D("UcenterMember")->where(array("id"=>$id))->save(array("username"=>C("USERNAMEPIX").$id));
    	       }
    	       $data['code'] = substr($id.C("RECOMMEND"), 0,C("RECOMMENDLEN"));
    	       $data['uid'] = $id;
    	       $data['id'] = $id;
    	       D("Member")->add($data);
	        }catch (\Exception $e){
	            $roll = false;
	            $M->rollback();
	            $message = $e->getMessage();
	        }
	        if($roll){
	            $M->commit();
	            //用户注册完成清空缓存
	            S("MEMBER_CACHE",null);
	            return $data;
	        }else{
	            return array("id"=>0,"info"=>$message);
	        }
        }
        return array("id"=>0,"info"=>"没有传入数据");
	}
	/**
	 * 获取用户信息
	 * @param  string  $uid         用户ID或用户名
	 * @param  boolean $is_username 是否使用用户名查询
	 * @return array                用户信息
	 */
	public function info($uid){
		$user = $this->where("id=".$uid)->find();
		$userexd = D("Member")->where("uid=".$uid)->find();
		$info = array();
		if(is_array($user) && is_array($userexd)){
			foreach ($user as $key=>$val){
			    $info[$key] = $val;
			}
			foreach ($userexd as $key=>$val){
			    $info[$key] = $val;
			}
			return $info;
		} else {
			return -1; //用户不存在或被禁用
		}
	}
	/**
	 * 获取所有用户信息
	 * @param  string  $uid         用户ID或用户名
	 * @param  boolean $is_username 是否使用用户名查询
	 * @return array                用户信息
	 */
	public function infoAll(){
	    return D("Member")->cache("MEMBER_CACHE",C("DATA_CACHE_TIME"))->field("uid,nickname")->select();
	}
	/**
	 * 获取所有用户信息
	 * @param  string  $uid         用户ID或用户名
	 * @param  boolean $is_username 是否使用用户名查询
	 * @return array                用户信息
	 */
	public function getAll($sql){
	    return D("Member")->query($sql);
	}
	/**
	 * 签到
	 * @param unknown $uid
	 * @param unknown $score
	 */
	public function singin($uid){
        if($uid){
            $Member = D("Member");
            $info = $this->info($uid);
            if(is_array($info)){
                if( $info['status']==1){
                    $now = time_format(null,'Y-m-d');
                    if($now==$info['last_sing']){
                        return 2;
                    }else{
                        $Member->where(array("uid"=>$uid))->setInc("score",C("SING_IN_SCORE"));
                        $Member->where(array("uid"=>$uid))->setField("last_sing",$now);
                        M("Score")->add(array("gattr1"=>$uid,"gattr2"=>"s","uid"=>$uid,"create_time"=>time_format(),"remark"=>"签到","dv"=>1,"score"=>C("SING_IN_SCORE")));
                        return 1;
                    }
                }else{
                    return $info['status'];
                }
            }
        }
        return -1;
	}
	
	/**
	 * 更新用户登录信息
	 * @param  integer $uid 用户ID
	 */
	protected function updateLogin($uid){
	    $Member = D("Member");
		$data = array(
			'uid'              => $uid,
			'last_login_time' => date("Y-m-d H:i:s"),
		);
		$Member->save($data);
	}

	/**
	 * 更新用户信息
	 * @param int $uid 用户id
	 * @param string $password 密码，用来验证
	 * @param array $data 修改的字段数组
	 * @return true 修改成功，false 修改失败
	 * @author huajie <banhuajie@163.com>
	 */
	public function updateUserFields($id, $data){
		if($id){
		   return $this->where(array("id"=>$id))->save($data);
		}
		return true;
	}
	/**
	 * 更新用户信息
	 * @param int $uid 用户id
	 * @param string $password 密码，用来验证
	 * @param array $data 修改的字段数组
	 * @return true 修改成功，false 修改失败
	 * @author huajie <banhuajie@163.com>
	 */
	public function updateMemberFields($uid, $data){
	    if($uid){
	       return D("Member")->where(array("uid"=>$uid))->save($data);
	    }
	    return true;
	}
	
	/**
	 * 验证用户是否存在
	 * @param int $uid 用户id
	 * @param string $password_in 密码
	 * @return true 验证成功，false 验证失败
	 * @author huajie <banhuajie@163.com>
	 */
	public function existenceUser($where){
	    return $this->where($where)->find();
	}

	/**
	 * 验证用户密码
	 * @param int $uid 用户id
	 * @param string $password_in 密码
	 * @return true 验证成功，false 验证失败
	 * @author huajie <banhuajie@163.com>
	 */
	protected function verifyUser($uid, $password_in){
		$password = $this->getFieldById($uid, 'password');
		if(think_encrypt($password_in) === $password){
			return true;
		}
		return false;
	}
	/**
	 * 登录状态信息
	 * @param unknown $mode
	 */
	private function showErrorMessage($mode){
	    $error = '未知错误！';
	    switch($mode) {
	        case 0: $error = '账号被禁用！'; break; //系统级别禁用
	        case -1: $error = '账号被删除！'; break; //系统级别删除
	        case -2: $error = '账号审核中！'; break;
	        case -3: $error = '密码错误！'; break;
	        case -4: $error = '账号不存在！'; break;
	        default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
	    }
	    return $error;
	}

}
