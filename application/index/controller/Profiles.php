<?php
namespace app\index\controller;

use app\common\controller\Frontend;

class Profiles extends Frontend
{
    protected $layout = '';
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    public function _initialize(){
        parent::_initialize();
    }

    //展示用户信息
    public function index(){
        dump($_GET);
        $uid = request()->param('uid');
        //$userinfo=$db->fetch_row($db->query("select m.*,ms.* from {$tablepre}members m,{$tablepre}memberfields ms  where m.uid=ms.uid and m.uid='{$uid}'"));
        $userinfo = Db::table('zb_user')->find($uid);
        $this->view->assign('title',(__('Profile')));
        $this->view->assign('userinfo',$userinfo);
        $this->fetch();
    }
}


