<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/11 0011
 * Time: 下午 4:30
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Article as ArtModel;
use app\admin\model\Category;
use think\cache\driver\Memcache;
use think\Image;
use app\admin\model\Comment;
class Article extends Base{
    public function index(){
        //分页查出所有文章
        $art=new ArtModel();
        $page=$art->paginate(5);
        $this->assign('page',$page);
        //查处所有文章数量
        $count=$art->count();
        $this->assign('count',$count);
        return view('article/article_list');
    }

    //添加页面渲染
    public function insert(){
        //查出所有栏目
        $cate=new Category();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $cates=$cate->cateTree($data,$id=0);
        $this->assign('cate',$cates);
        return view('article/article_add');
    }

    //添加文章
    public function add(){
        //接受post数据
        $request=request();
        $post=$request->post();
        $post['add_time']=time();
        $id=$request->post('cate_id');

        //查出栏目id是否有子孙栏目
        $cate=new Category();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $cateTree=$cate->cateTree($data,$id);
        if(!empty($cateTree)){
            $this->error('文章不能存放在非底层栏目','article/insert',3);
        }

        //如果上传图片,则生成缩略图
        $omg=$request->post('ori_img',0);
        if(!empty($omg)){
            $img=Image::open(ROOT_PATH.'public'.$omg);
            $arr=explode('/',$omg);
            $tname='thumb_'.end($arr);//获取缩略图名;
            $dir=dirname($omg);//获取缩略图目录
            $img->thumb(200,100,1)->save(ROOT_PATH.'public/'.$dir.'/'.$tname);
            $post['thumb_img']=$dir.'/'.$tname;
        }

        //添加文章
        $art=new ArtModel();
        if(ArtModel::create($post,true)){
            $this->redirect('article/index');
        }else{
            $this->error('添加失败','article/index',3);
        }
    }

    //修改界面渲染
    public function update(){
        //查出所有栏目
        $cate=new Category();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $cates=$cate->cateTree($data,$id=0);
        $this->assign('cate',$cates);
        //接受地址栏参数
        $request=request();
        $id=$request->get('art_id',0);
        echo $id;
        //通过参数查出该文章的信息
        $res=ArtModel::where('art_id','eq',$id)->find();
        $res=$res->toArray();
        $this->assign('data',$res);
        return view('article/article_update');
    }

    //文章修改
    public function alter(){
        //接受post数据
        $request=request();
        $name=$request->post('art_name');
        $text=$request->post('text');
        $keyword=$request->post('keyword');
        $art_id=$request->post('art_id');
        $art_dep=$request->post('art_dep');
        $id=$request->post('cate_id');

        //查出栏目id是否有子孙栏目
        $cate=new Category();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $cateTree=$cate->cateTree($data,$id);
        if(!empty($cateTree)){
            $this->error('文章不能存放在非底层栏目','article/update',3);
        }

        //如果上传图片,则生成缩略图
        $omg=$request->post('ori_img','');
        if(!empty($omg)){
            $img=Image::open(ROOT_PATH.'public'.$omg);
            $arr=explode('/',$omg);
            $tname='thumb_'.end($arr);//获取缩略图名;
            $dir=dirname($omg);//获取缩略图目录
            $img->thumb(200,100,1)->save(ROOT_PATH.'public/'.$dir.'/'.$tname);
            $thumb_img=$dir.'/'.$tname;
        }else{
            $thumb_img='';
        }

        //修改文章
        $art=new ArtModel();
        if($art->where('art_id','eq',$art_id)->update([
            'art_name'=>$name,
            'text'=>$text,
            'keyword'=>$keyword,
            'art_dep'=>$art_dep,
            'cate_id'=>$id,
            'ori_img'=>$omg,
            'thumb_img'=>$thumb_img
        ])){
            $this->redirect('article/index');
        }else{
            $this->error('文章更新失败','article/index',3);
        }
    }

    //删除文章
    public function delete(){
        //接受地址栏参数
        $request=request();
        $id=$request->get('art_id');

        //删除文章和文章的图片
        $str='public';
        $omg=ArtModel::where('art_id','eq',$id)->value('ori_img');
        $tmb=ArtModel::where('art_id','eq',$id)->value('thumb_img');
        $med=ArtModel::where('art_id','eq',$id)->value('med_img');
        if($omg != null){
            unlink(ROOT_PATH.$str.$omg);
        }
        if($tmb != null){
            unlink(ROOT_PATH.$str.$tmb);
        }
        if($med != null){
            unlink(ROOT_PATH.$str.$med);
        }

        //删除文章下的评论
        Comment::where('art_id','eq',$id)->delete();

        if(ArtModel::where('art_id','eq',$id)->delete()){
            $this->redirect('article/index');
        }else{
            $this->error('文章删除失败','article/index',3);
        }
    }
}