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
        $rid = $this->request->param('rid');
        $this->assign('rid',$rid);
       return $this->fetch();
    }

    public function show(){
        $vid = $this->request->param('vid');
        $rid = $this->request->param('rid');
        $v1=Db::table('zb_apps_vote')->where(['v'=>'0','rid'=>$rid,'vid'=>$vid])->count();
        $v2=Db::table('zb_apps_vote')->where(['v'=>'1','rid'=>$rid,'vid'=>$vid])->count();
        $v3=Db::table('zb_apps_vote')->where(['v'=>'2','rid'=>$rid,'vid'=>$vid])->count();
        $data["status"]=1;
        $data["msg"]="";
        $data["v1"]=(int)$v1;
        $data["v2"]=(int)$v2;
        $data["v3"]=(int)$v3;
        return json($data);
    }

    public function Vote(){
        //用户登录后的id，开发期间先给默认值
        $uid = session('login_uid');
        $uid = 3307;
        $vid = $this->request->param('vid');
        $rid = $this->request->param('rid');
        $v = $this->request->param('v');
        if($uid<1){
            $data["status"]=0;
            $data["msg"]="未登录，不能投票";
            return json($data);
        }

        if(count(Db::query("select * from zb_apps_vote where uid='$uid' and vid='$vid' and FROM_UNIXTIME(time,'%Y%m%d')='".date('Ymd',time())."'"))>0){
            $data["status"]=0;
            $data["msg"]="你今日已经投过了！明日再投";
            return json($data);
        }
        $tmpdata['rid'] = $rid;
        $tmpdata['vid'] = $vid;
        $tmpdata['uid'] = $uid;
        $tmpdata['v'] =$v;
        $tmpdata['time'] = time();
        if(Db::table('zb_apps_vote')->insert($tmpdata)){
            $data["status"]=1;
            $data["msg"]="投票成功";
            return json($data);
        }
    }
}


