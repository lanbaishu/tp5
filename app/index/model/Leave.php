<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/16 0016
 * Time: 下午 11:09
 */
namespace app\index\model;
use think\Model;

class Leave extends Model{
    protected $table='lev';
    public function getAddTimeAttr($v){
        return date('Y-m-d H:m',$v);
    }
}