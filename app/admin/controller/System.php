<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12 0012
 * Time: 上午 1:03
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\System as SysModel;
class System extends Base{
    public function index(){
        //查出网站的系统设置
        $res=SysModel::where('sys_id','eq',1)->find();
        $res=$res->toArray();
        $this->assign('sys',$res);
        return view('system/system_list');
    }
    public function update(){
        //接受post数据
        $request=request();
        $name=$request->post('sys_name');
        $url=$request->post('sys_url');
        $keywords=$request->post('key_words');
        $info=$request->post('sys_info');
        $email=$request->post('sys_email');
        $ipc=$request->post('sys_ipc');
        $is_close=$request->post('is_close');
        //修改数据
        if(SysModel::where('sys_id','eq',1)->update([
            'sys_name'=>$name,
            'sys_url'=>$url,
            'key_words'=>$keywords,
            'sys_info'=>$info,
            'sys_email'=>$email,
            'sys_ipc'=>$ipc,
            'is_close'=>$is_close
            ])){
            $this->redirect('System/index');
        }
    }
}