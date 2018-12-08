<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16 0016
 * Time: 下午 9:13
 */
namespace app\index\Model;
use think\Model;

class Comment extends Model{

    //将时间戳转化为日期
    public function getAddTimeAttr($v){
        return date('Y-m-d H:m');
    }
}