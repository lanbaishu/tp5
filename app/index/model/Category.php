<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/15 0015
 * Time: 下午 7:43
 */
namespace app\index\model;
use think\Model;

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