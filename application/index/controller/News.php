<?php
/**
 * Created by PhpStorm.
 * User: xiaob
 * Date: 2019/3/26
 * Time: 14:51
 */

namespace app\index\controller;
use think\Controller;

class news extends Controller
{
    public function index()
    {
        $list = db('news')
            ->alias('a')
            ->join('cate b', 'a.typeid=b.id')
            ->field('a.*,b.typename')
            ->paginate(5);
        $this->assign('list',$list);
        $this->assign('count',db('news')->count());
        $this->assign('page', $list->render());//分页映射
        return $this->fetch();
    }


//    添加
    public  function  add()
    {
        $input = input('post.');
        if($input){
            $input['puttime'] = time();
            $info = db('news')->insert($input);
            return JSON($msg = ["code"=>"1"]);
        }else{
            $this->assign('list',lists());
            return $this->fetch('add');
        }
    }

//     修改
    public  function  upd(){
        $input = input('post.');
        if($input){
            $input['puttime'] = time();
            $info = db('news')->update($input);
            return JSON($msg = ["code"=>"1"]);
        }else{
            $id = input('id');
            $info=db('news')->where('id='.$id)->find();
            $this->assign('list',lists());
//            $this->assign('lis',model('Db')->getrolelist());
            $this->assign('info',$info);
        }
        return $this->fetch();
    }






//    删除
    public  function del(){
        $id = input('ids');
        $info = db('news')->delete($id);
        if($info){
            return $msg = ['code'=>'1'];
        }
    }

}