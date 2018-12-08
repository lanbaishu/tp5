<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16 0016
 * Time: 下午 10:53
 */
namespace app\index\controller;
use app\index\common\Base;
use app\index\model\Category;
use app\index\model\Link;
use app\index\model\Article;
use app\index\model\Leave as LevModel;
use think\cache\driver\Memcache;
class Leave extends Base{
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

        //查出所有留言并保存到缓存
        $mem=new Memcache();
        if(empty($mem->get('levinfo'))){
            $lev=new LevModel();
            $res=$lev->select();
            $data=[];
            foreach($res as $v){
                $data[]=$v->toArray();
            }
            $data=array_reverse($data);
            $mem->set('levinfo',$data,3600);
            $this->assign('leave',$data);
        }else{
            $data=$mem->get('levinfo');
            $this->assign('leave',$data);
        }
        return view('leave/leave');
    }

    //添加留言
    public function insert(){
        //接受post数据
        $request=request();
        $post=$request->post();
        $post['add_time']=time();

        //添加留言
        $mem=new Memcache();
        $mem->rm('levinfo');
        LevModel::create($post,true);
        $this->redirect('leave/index');
    }
}