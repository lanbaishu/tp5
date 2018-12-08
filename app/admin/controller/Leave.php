<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/11 0011
 * Time: 下午 4:42
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Leave as LevModel;
use think\cache\driver\Memcache;
class Leave extends Base{
    public function index(){
        //分页查出所有留言
        $lev=new LevModel();
        $page=$lev->paginate(5);
        $this->assign('page',$page);
/*
        $mem=new Memcache();
        $mem->set('love',5,0);
        $mem->dec('love',5);
        $str=$mem->get('love');
        echo $str;
        exit;*/

        //查出留言个数
        $count=$lev->count();
        $this->assign('count',$count);
        return view('leave/leave_list');

    }

    //删除留言
    public function delete(){
        //接受地址栏参数
        $request=request();
        $id=$request->get('lev_id');
        //删除留言
        $mem=new Memcache();
        $mem->rm('levinfo');
        if(LevModel::where('lev_id','eq',$id)->delete()){
            $this->redirect('leave/index');
        }else{
            $this->error('删除失败','leave/index',3);
        }
    }
}