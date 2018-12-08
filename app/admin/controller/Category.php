<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/11 0011
 * Time: 下午 4:52
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Category as CateModel;
use app\admin\model\Article;

class Category extends Base{
    public function index(){
        //查出所有栏目的子孙树
        $cate=new CateModel();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $arr=$cate->cateTree($data);
        $this->assign('cate',$arr);
        //分页显示栏目
        $page=$cate->paginate(5);
        $this->assign('page',$page);
        //查出栏目总数
        $count=$cate->count();
        $this->assign('count',$count);
        return view('category/category_list');
    }

    //栏目添加
    public function insert(){
        //接受post数据
        $request=request();
        $post=$request->post();
        $pid=$request->post('parent_id');
        //查出父栏目下是否有文章
        $res=Article::where('cate_id','eq',$pid)->select();
        if(!empty($res)){
            $this->error('存在文章的栏目不能作为父栏目','category/index',3);
        }
        //添加数据
        CateModel::create($post,true);
        $this->redirect('category/index');
    }

    //修改界面渲染
    public function update(){
        //查出子孙树
        $cate=new CateModel();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $arr=$cate->cateTree($data);
        $this->assign('data',$arr);
        //接受cate_id
        $request=request();
        $id=$request->get('cate_id');
        //查出id的栏目信息
        $res=CateModel::where('cate_id','eq',$id)->find();
        $res=$res->toArray();
        $this->assign('cate',$res);
        return view('category/category_update');
    }

    //修改数据
    public function alter(){
        //接受post数据
        $request=request();
        $id=$request->post('cate_id');
        $name=$request->post('cate_name');
        $byname=$request->post('byname');
        $pid=$request->post('parent_id');
        $keyword=$request->post('keyword');
        $intro=$request->post('intro');

        //判断父栏目是否是自己
        if($pid == $id){
            $this->error('不能将自己修改为自己的父栏目','category/index',3);
        }

        //判断父栏目是否是自己的子孙栏目
        $cate=new CateModel();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $arr=$cate->cateTree($data,$id);
        foreach($arr as $v){
            if($v['cate_id'] == $pid){
                $this->error('不能将子孙栏目修改为自己的父栏目','category/index',3);
            }
        }

        //判断父栏目是否存在文章
        $res=Article::where('cate_id','eq',$pid)->select();
        if(!empty($res)){
            $this->error('父栏目存在文章','category/index',3);
        }

        //更新栏目信息
        CateModel::where('cate_id','eq',$id)->update([
            'cate_name'=>$name,
            'byname'=>$byname,
            'parent_id'=>$pid,
            'keyword'=>$keyword,
            'intro'=>$intro
        ]);
        $this->redirect('category/index');

    }

    //删除数据
    public function delete(){
        //接受get参数
        $request=request();
        $id=$request->get('cate_id');

        //删除该栏目下的所有文章
        $art=new Article();
        $art->delArt($id);

        //查出该栏目的子孙栏目
        $cate=new CateModel();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $arr=$cate->cateTree($data,$id);
        //循环删除该栏目的子孙栏目
        foreach($arr as $v){
            CateModel::where('cate_id','eq',$v['cate_id'])->delete();
        }
        CateModel::where('cate_id','eq',$id)->delete();
        $this->redirect('category/index');
    }
}