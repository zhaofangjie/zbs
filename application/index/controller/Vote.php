<?php
namespace app\index\controller;

use app\common\controller\Frontend;

use think\Lang;
use think\Db;

/*
 * 投票接口
 */
class Vote extends Frontend
{
    protected $noNeedLogin = ['*']; //不登录无法投票，前期不做设置
    protected $noNeedRight = ['*'];
    protected $layout = '';

    public function index(){
        //用户登录后的id，开发期间先给默认值
        //$uid = $Think.session.uid;
        $uid = 3307;
        if($this->request->has('act','post')){
            $rid = $this->request->param('rid');
            $act = $this->request->param('act');
            switch ($act){
                case 'vote':
                    if($uid<1){
                        $data["status"]=0;
                        $data["msg"]="未登录，不能投票";
                       return json_encode($data);
                    }

                    if(count(Db::query("select * from zb_apps_vote where uid='$uid' and vid='$vid' and FROM_UNIXTIME(time,'%Y%m%d')='".date('Ymd',gdate())."'"))>0){
                        $data["status"]=0;
                        $data["msg"]="你今日已经投过了！明日再投";
                        return json($data);
                    }
                    $tmpdata['rid'] = $rid;
                    $tmpdata['vid'] = $vid;
                    $tmpdata['uid'] = $uid;
                    $tmpdata['v'] =$v;
                    $tmpdata['time'] = gdate();
                   if(Db::table('zb_apps_vote')->insert($tmpdata)){
                       $data["status"]=1;
                       $data["msg"]="投票成功";
                       return json($data);
                   }

            }

        }

       return $this->fetch();
    }

}


