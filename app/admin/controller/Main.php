<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/10 0010
 * Time: 下午 5:02
 */
namespace app\admin\controller;
use app\admin\common\Base;
use app\admin\model\Article;
use app\admin\model\Leave;
use app\admin\model\Link;
use app\admin\model\User;
class Main extends Base{
    public function index(){
        //查出文章总条数
        $article=new Article();
        $art=$article->count();
        $this->assign('artnum',$art);
        //查出评论总条数
        $leave=new Leave();
        $lev=$leave->count();
        $this->assign('levnum',$lev);
        //查出友情链接总条数
        $link=new Link();
        $linknum=$link->count();
        $this->assign('linknum',$linknum);
        //管理员个数
        $user=new User();
        $usernum=$user->count();
        $this->assign('usernum',$usernum);
        return view('main/main');
    }
}