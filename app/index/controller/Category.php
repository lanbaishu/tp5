<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/15 0015
 * Time: 下午 10:13
 */
namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Article;
use app\index\model\Category as CateModel;
use app\index\model\Link;
class Category extends Base{
    public function index(){
        //接受cate_id
        $request=request();
        $id=$request->get('cate_id');

        //查出所有栏目
        $cate=new CateModel();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('cate',$data);

        //查出所有链接
        $link=new Link();
        $res=$link->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('link',$data);

        //随机取出八篇文章
        $num=mt_rand(1,5);
        $art=new Article();
        $res=$art->limit($num,8)->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('art',$data);

        //查出指定栏目下的所有文章
        $data=$art->cateArt($id);
        $this->assign('art',$data);
        return view('category/category');
    }
}