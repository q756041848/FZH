<?php
/**
 * Created by PhpStorm.
 * User: xiaob
 * Date: 2019/3/26
 * Time: 19:31
 */
namespace app\qiantai\controller;
use think\Controller;
use think\Url;
use think\Route;
class Qiantai extends Controller
{
//    =====================主页========================   //
    public function index(){
        $sel = input('sel');
        if ($sel){
            if ($sel=='hot'){
                $where['ishot']=1;
            }else{
                $where['title']=array('LIKE',"%$sel%");
            }
        }else{
            $where = "1=1";

        }
        $list = db('news')->where($where)->select();
        $this->assign('list',$list);
        $this->assign('sel',$sel);
        return $this->fetch('index');
    }

//  赞同
    public function dianzan(){
        $id = input('id');
        $info = db('news')->where('id',$id)->setInc('click');
        return $info;
    }

//  反对
    public function fandui(){
        $id = input('id');
        $as = input('as');
        $info = db('news')->where('id',$id)->setDec('click');
        return $info;
    }

//    评论对话
    public function pinglun(){
        return $this->fetch('pinglun');
    }

    // 评论全部
    public function pingluns(){
//   点击回复的时候获取到当前id，发表评论时将当前session id写入userid 将当前id所查询的user写入被评论userpid
        $id = input('id');
////        $order = input('order');  //默认是倒序，空值也是倒叙。有值非一是正序
        $order = input('order');
        $order?$order=input('order'):$order='asc';
        $info=db('comment')
            ->where(['pid'=>0])
            ->where(['newsid'=>22])
            ->order(['time'=>$order])
            ->select();
        foreach($info as $key=>&$v)
        {
            $v['list']=db('comment')->where('pid='.$v['id'])->order('time asc')->select();
        }
        $this->assign('info',$info);
        $this->assign('pl',db('comment')->where(['newsid'=>$id])->count());
        $this->assign('id',$id);
        return $this->fetch('pingluns');

    }
//    发布评论
        public function huifu(){
            $id = input('id');
            $sel = db('comment')->where(['id'=>$id])->find();
            if (input('status')=='1'){
                $insert['pid'] = $sel['id'];
            }else{
                $insert['pid'] = $sel['pid'];
            }
             $insert['content'] = input('cont');
             $insert['newsid'] = $sel['newsid'];
             $insert['time'] = time();
             $insert['userid'] = '当前session';
             $insert['userpid'] = $sel['userid'];
//
            $info = db('comment')->insert($insert);
            return '1';

        }

//        一级评论
        public function yiji(){
            $insert['newsid'] = input('id');
            $insert['content'] = input('cont');
            $insert['pid'] = 0;
            $insert['userid'] = '当前session';
            $insert['time'] = time();
            $info = db('comment')->insert($insert);
            return '1';
        }


//    ====================详情内容页面===============     //
    public function con(){
        $id = input('id');
        $this->assign('sel',input('sel'));
        if (input('sel')){
            $this->redirect('/qiantai/Qiantai/index?sel='.input('sel'));

        }
        $list = db('news')
            ->alias('a')
            ->join('cate b', 'a.typeid=b.id')
            ->field('a.*,b.typename')
            ->where('a.id',$id)
            ->select();
        $this->assign('pinglun',$this->pinglun($id));
        $this->assign('list',$list);
        return $this->fetch('cons');
    }


}