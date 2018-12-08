<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12 0012
 * Time: 上午 12:39
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Link as LinkModel;
class Link extends base{
    public function index(){
        //分页显示友情链接
        $link=new LinkModel();
        $page=$link->paginate(5);
        $this->assign('page',$page);

        //查出连接个数
        $count=$link->count();
        $this->assign('count',$count);
        return view('link/link_list');
    }

    //添加界面渲染
    public function insert(){
        return view('link/link_add');
    }

    //添加链接
    public function add(){
        //接受post数据
        $request=request();
        $post=$request->post();

        //添加数据
        if(LinkModel::create($post,true)){
            $this->redirect('link/index');
        }else{
            $this->error('添加失败','link/index',3);
        }
    }

    //修改界面渲染
    public function update(){
        //接受get参数
        $request=request();
        $id=$request->get('link_id');

        //查出连接对应的链接信息
        $res=LinkModel::where('link_id','eq',$id)->find();
        $res=$res->toArray();
        $this->assign('link',$res);
        return view('link_update');
    }

    //修改链接
    public function alter(){
        //接受post数据
        $request=request();
        $name=$request->post('link_name');
        $url=$request->post('link_url');
        $id=$request->post('link_id');

        //修改链接
        if(LinkModel::where('link_id','eq',$id)->update([
            'link_name'=>$name,
            'link_url'=>$url
        ])){
            $this->redirect('link/index');
        }else{
            $this->error('修改失败','link/index',3);
        }
    }

    //删除链接
    public function delete(){
        //接受get参数
        $request=request();
        $id=$request->get('link_id');

        //删除链接
        if(LinkModel::where('link_id','eq',$id)->delete()){
            $this->redirect('link/index');
        }else{
            $this->error('删除失败','link/index',3);
        }
    }
}