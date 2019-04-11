<?php
/**
 * Created by PhpStorm.
 * User: xiaob
 * Date: 2019/3/26
 * Time: 14:51
 */

namespace app\index\controller;
use think\Controller;

class fenlei extends Controller
{
    public function index()
    {
        return $this->fetch();

    }
}