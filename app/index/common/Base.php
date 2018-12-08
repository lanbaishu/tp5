<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/10 0010
 * Time: 下午 3:59
 */
namespace app\index\common;
use think\Controller;
use app\admin\model\System;
class Base extends Controller{
    public function _initialize(){
        //获取配置信息
        $config=$this->getSys();
        //是否关闭网站
        $this->getStatic($config);
    }

    //获取网站配置
    public function getSys(){
        $res=System::where('sys_id','eq',1)->find();
        return $res->toArray();
    }

    //判断是否需要关闭网站
    public function getStatic($config){
        $request=request();
        if($request->module() != 'admin'){
            if($config['is_close'] == 1){
                exit('网站暂时关闭');
            }
        }
    }

}