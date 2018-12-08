<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/11 0011
 * Time: 下午 4:56
 */
namespace app\admin\model;
use think\Model;
use app\admin\model\Article;
class Category extends Model{
    protected $table='cate';
    //查找子孙树
    public function cateTree($arr,$id=0,$lev=1){
        static $data=[];
        foreach($arr as $v){
            if($v['parent_id'] == $id){
                $v['lev']=$lev;
                $data[]=$v;
                $this->cateTree($arr,$v['cate_id'],$lev+1);
            }
        }
        return $data;
    }


}