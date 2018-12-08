<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/10 0010
 * Time: 下午 4:38
 */
namespace app\admin\model;
use think\Model;

class User extends Model{
   // protected $table='user';
    //使时间戳自动转化为对应的格式
    public  function getLastTimeAttr($v){
        return date('Y-m-d H:m',$v);
    }
}

?>