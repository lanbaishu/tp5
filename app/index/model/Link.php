<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/15 0015
 * Time: 下午 8:03
 */
namespace app\index\model;
use think\Model;

class Link extends Model{

    //将时间戳转化为日期
    public function getAddTimeAttr($v){
        return date('Y-m-d H:m',$v);
    }
}