<?php
namespace app\index\controller;

use app\common\controller\Frontend;

class Kefu extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    //展示客服
    public function index(){
        //客服信息
       // $list =
       return $this->fetch();

    }
}