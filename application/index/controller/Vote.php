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
        //用户登录后的id，开发期间先给默认值
        //$uid = $Think.session.uid;
        $vid = $this->request->param('vid');
        $db = Db::table('zb_apps_vote');
        $rid = $this->request->param('rid');
        $v1=for_each($db->query("select count(*) as v1 from zb_apps_vote where v='0' and vid='$vid' and rid='$rid'"),'{v1}');
        $v2=for_each($db->query("select count(*) as v2 from zb_apps_vote where v='1' and vid='$vid' and rid='$rid'"),'{v2}');
        $v3=for_each($db->query("select count(*) as v3 from zb_apps_vote where v='2' and vid='$vid' and rid='$rid'"),'{v3}');
        $data["status"]=1;
        $data["msg"]="";
        $data["v1"]=(int)$v1;
        $data["v2"]=(int)$v2;
        $data["v3"]=(int)$v3;
        return json($data);

    }

    public function Vote(){
        $uid = 3307;
        $vid = $this->request->param('vid');
        $rid = $this->request->param('rid');
        $v = $this->request->param('v');
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


