<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/12 0012
 * Time: 下午 2:21
 */
namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Category;
use app\index\model\Link;
use app\index\model\Article;
class Index extends Base{
    public function index(){
        //查出所有栏目
        $cate=new Category();
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

        //取出所有文章
        $res=$art->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('data',$data);
        return view('index/index');
    }
}