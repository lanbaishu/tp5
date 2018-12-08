<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/10 0010
 * Time: 下午 3:59
 */
namespace app\admin\common;
use think\Controller;
use app\admin\model\User;
use think\Session;
class Base extends Controller{
    public function _initialize(){
        //验证用户是否登录
        if(session('user_info') == null){
            $this->redirect('index/index');
        }

        //检测异地登录
        $sid=User::where('user_name','eq',session('user_info')['user_name'])->value('session_id');
        if($sid != session_id()){
            Session::delete('user_info');
            $this->error('检测到你的账号在其他设备登录，您被强制下线','index/index',2);
        }
    }

}