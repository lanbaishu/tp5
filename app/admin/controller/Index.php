<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/10 0010
 * Time: 下午 3:59
 */
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User as UserModel;
use think\Session;
class Index extends Controller{
    public function index(){
        return view('admin/index');
    }

    public function login(){
        //验证用户登录
        $request=request();
        $username=$request->post('user_name',0);
        $pass=$request->post('user_pass',0);

        //验证用户名是否存在
        $name=UserModel::where('user_name','eq',$username) -> value('user_name');
        if($name == null){
            echo 1;
            //用户名不存在
            $this->redirect('index/index');
        }elseif(UserModel::where('user_name','eq',$username)->value('user_pass') == md5($pass)){

            //密码正确,修改上次登录时间和登陆次数
            $count=UserModel::where('user_name','eq',$username)->value('num');
            UserModel::where('user_name','eq',$username)->update([
                'last_time'=>time(),
                'num'=>$count + 1,
            ]);

            //将信息存到session中
            $res=UserModel::where('user_id','eq',1)->field('user_id,user_name,user_tel,user_email,last_time,num,user_ip')->find();
            $res=$res->toArray();
            session('user_info',$res);

            //更新session_id
            UserModel::where('user_name','eq',$username)->update([
                'session_id'=>session_id()
            ]);
            $this->redirect('main/index');
        }else{
            $this->redirect('index/index');
        }
    }

    //退出登录
    public function logout(){
        //清空session
        Session::delete('user_info');
        $this->redirect('index/index');
    }

    //修改管理员信息
    public function alter(){
        //接受post数据
        $request=request();
        $name=$request->post('user_name');
        $tel=$request->post('user_tel');
        $email=$request->post('user_email');
        $pass=$request->post('user_pass');

        //修改数据
        if(UserModel::where('user_id','eq',1)->update([
            'user_name'=>$name,
            'user_tel'=>$tel,
            'user_email'=>$email,
            'user_pass'=>md5($pass)
        ])){
            //修改session下的数据
            $res=UserModel::where('user_id','eq',1)->field('user_id,user_name,user_tel,user_email,last_time,num,user_ip')->find();
            $res=$res->toArray();
            session('user_info',$res);
        }
        $this->redirect('main/index');

    }
}