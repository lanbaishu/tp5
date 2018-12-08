<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/11 0011
 * Time: 下午 4:47
 */
namespace app\admin\model;
use think\Model;

class Leave extends Model{
    protected $table='lev';
    //将时间戳转化为日期
    public function getAddTimeAttr($v){
        return date("Y-m-d H:m",$v);
    }
}