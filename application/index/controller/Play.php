<?php
namespace app\index\controller;

use app\common\controller\Frontend;
use think\Db;

class Play extends Frontend
{
    protected $cfg;
    
    public function _initialize()
    {     
        //Ĭ��Ϊ��һ����������
        $this->cfg['config'] = Db::name('room_config')->where('id',1)->find();
    }
    
    public function index(){
        $type = $this->request->param('type');
        switch($type){
            case "pc":
                $video = tohtml($this->cfg['config']['livefp']).'<div class="gd"><marquee scrollamount="3">投资有风险 建议仅供参考</marquee></div>';
                break;
            case "m":
                $video = tohtml($this->cfg['config']['phonefp']);
                break;
        }
        $this->assign('video',$video);
        return $this->fetch();
    }
}