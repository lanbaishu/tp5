<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/18 0018
 * Time: 下午 1:43
 */
namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Category;
use app\index\model\Link;
use app\index\model\Article;
use app\index\model\About as AboutModel;;
use think\cache\driver\Memcache;
class About extends Base{

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


        //$this->assign('about',$res);

        //将个人信息保存到缓存中
        $mem=new Memcache();
        if(empty($mem->get('info'))){
            //查出个人信息
            $res=AboutModel::where('about_id','eq',1)->find();
            $res=$res->toArray();
            $mem->set('info',$res,0);
            $info=$mem->get('info');
            $this->assign('about',$info);

        }else{
            $info=$mem->get('info');
            $this->assign('about',$info);
        }

         return view('about/about');
    }
}