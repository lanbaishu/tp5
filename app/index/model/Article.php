<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/15 0015
 * Time: 下午 8:49
 */
namespace app\index\model;
use think\Model;
use app\index\model\Category;
class Article extends Model{

    public function cateArt($id){
        //查出cate_id及其子孙树下的所有文章
        $cate=new Category();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $data=$cate->cateTree($data,$id,$lev=1);
        $arr=[$id];
        foreach($data as $v){
            $arr[]=$v['cate_id'];
        }
        $str=implode(',',$arr);

        $res=Article::where('cate_id','in',$str)->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        return $data;
    }

    //栏目id转换成栏目名
    public function getCateIdAttr($v){
        $name=Category::where('cate_id','eq',$v)->value('cate_name');
        return $name;
    }

    //将时间戳转化为日期
    public function getAddTimeAttr($v){
        return date('Y-m-d H:m',$v);
    }

}