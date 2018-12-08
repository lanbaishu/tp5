<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16 0016
 * Time: 下午 7:06
 */
namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Article as ArtModel;
use app\index\model\Category;
use app\index\model\Link;
use app\index\model\Comment;
use think\cache\driver\Memcache;
class Article extends Base{

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
        $art=new ArtModel();
        $res=$art->limit($num,8)->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('art',$data);

        //接受地址栏参数
        $request=request();
        $id=$request->get('art_id');
        $this->assign('id',$id);

        //查出id对应的文章
        $mem=new Memcache();
        $artinfo='artinfo'.$id;
        //给文章设置缓存
        if(empty($mem->get($artinfo))){
            $res=ArtModel::where('art_id','eq',$id)->find();
            $res=$res->toArray();
            $mem->set($artinfo,$res,3600);
            $this->assign('artinfo',$res);
        }else{
            $info=$mem->get($artinfo);
            $this->assign('artinfo',$info);
        }


        //查出下篇文章
        $res=ArtModel::where('art_id','eq',$id+1)->value('art_name');
        $this->assign('nextinfo',$res);

        //查出上一篇文章
        $res=ArtModel::where('art_id','eq',$id-1)->value('art_name');
        $this->assign('lastinfo',$res);

        //查出文章下所有评论
        $res=Comment::where('art_id','eq',$id)->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $this->assign('com',$data);

        //查出评论个数
        $count=Comment::where('art_id','eq',$id)->count();
        $this->assign('count',$count);
        return view('info/info');
    }

    //文章评论的添加
    public function insert(){
        //接受post数据
        $request=request();
        $post=$request->post();
        $id=$request->post('art_id');
        $post['add_time']=time();

        //添加评论
        if(Comment::create($post,true)){
            $this->redirect(url('article/index')."?art_id=$id");
        }
    }


}