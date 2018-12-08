<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/11 0011
 * Time: 下午 4:31
 */
namespace app\admin\model;
use think\Model;
use app\admin\model\Category as CateModel;
class Article extends Model{
    //删除指定栏目下的所有文章
    public function delArt($cate_id){
        //查出该栏目的子孙栏目
        $cate=new CateModel();
        $res=$cate->select();
        $data=[];
        foreach($res as $v){
            $data[]=$v->toArray();
        }
        $arr=$cate->cateTree($data,$cate_id);
        $ids=[$cate_id];
        foreach($arr as $v){
            $ids[]=$v['cate_id'];
        }
        $str=implode(',',$ids);
        return self::where('cate_id','in',$str)->delete();
    }

    //将栏目id转化为栏目名
    public function getCateIdAttr($v){
        return CateModel::where('cate_id','eq',$v)->value('cate_name');
    }

    //将时间戳转化为日期
    public function getAddTimeAttr($v){
        return date('Y-m-d H:m',$v);
    }

}