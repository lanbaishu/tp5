<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/18 0018
 * Time: 下午 2:22
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\About as AboutModel;
class About extends Base{
    public function index(){
        //查出个人信息
        $res=AboutModel::where('about_id','eq',1)->find();
        $res=$res->toArray();
        $this->assign('about',$res);
        return view('about/about');
    }

    public function update(){
        //接受post数据
        $request=request();
        $text=$request->post('about_text');
        $icp=$request->post('about_icp');

        //修改数据
        if(AboutModel::where('about_id','eq',1)->update([
            'about_text'=>$text,
            'about_icp'=>$icp
        ])){
            $this->redirect('about/index');
        }else{
            $this->error('修改失败','about/index',3);
        }
    }
}