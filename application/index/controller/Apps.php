<?php
namespace app\index\controller;

use app\common\controller\Frontend;
use think\Db;


/*
 * 工具类 投票 客服 喊单
 */
class Apps extends Frontend
{
    protected $layout = '';
    protected $noNeedLogin = ['kefu','vote','rank'];
    protected $noNeedRight = ['*'];

    public function _initialize(){
        parent::_initialize();
        $auth = $this->auth;
    }

    //展示客服
    public function kefu(){
        //客服信息  调用QQ企业客服  调用客服的qq信息，客服组id号为3
        $data = Db::table('zb_user')->where('group_id','3')->select();
        $list='';
        foreach ($data as $row){
            $list.="
            <div class='li' id='$row[id]'>
            <div class='li_img'><img src='ajax/getFaceImg/?t=p2&u=$row[id]'></div>
            <div class='li_qq'><a href='http://wpa.qq.com/msgrd?v=3&uin=$row[qq]&site=qq&menu=yes' title='点击这里联系 $row[nickname] QQ：$row[qq]' target='_blank'>
            $row[nickname]</a></div>
            <div class='li_phone'>$row[mobile]</div>
            </div>
            ";
        }
        $this->assign('list',$list);
        return $this->fetch();

    }

    //讲师排行
    public function rank(){
        //用户id
        $vuid=session('login_uid');
        $m=date('Ym',time());
        if(!isset($om))$om=$m;
        
        if($this->request->param('act') and $act=="add"){
            if($vuid<1){
                $data["status"]=0;
                $data["msg"]="未登录，不能投票";
                exit($json->encode($data));
            }
            if(Db::table('apps_rank')->where('vuid',$vuid)->whereTime('vtime','today')->count()>0){
                $data["status"]=2;
                $data["msg"]="你今日已经投过他了！";
                exit($json->encode($data));
            }
            $db->query("insert into {$tablepre}apps_rank(uid,vuid,vtime)values('$teacher','$vuid',".gdate().")");
            $data["status"]=1;
            $data["msg"]="";
            exit($json->encode($data));
        }
        
        $data = Db::query("select m.*,ms.*,(select count(*) from  zb_apps_rank where uid=m.uid and FROM_UNIXTIME(vtime,'%Y%m')='".date("{$om}",time())."') as sum,(select count(*) from  zb_apps_rank where uid=m.uid and FROM_UNIXTIME(vtime,'%Y%m%d')='".date("Ymd",time())."') as dsum from zb_members m,zb_memberfields ms  where m.uid=ms.uid and m.gid=4 order by sum desc");       
 
       
        if(!empty($data)){
            foreach($data as $row){
                if($om!=date('Ym',time()))$vote_none=" style='display:none'";
                $list.="
                <li id='t{$row[uid]}'><a href='javascript:vote({$row[uid]})' {$vote_none}></a>
                <p class='v_name'><i class='percent'></i>{$row[nickname]}</p>
                <p class='percent_container'><span class='percent_line'></span></p>
                <p class='v_text' ><span>今日获赞：<i class='count'>{$row[dsum]}</i></span><span>月累计：<i class='amount'>{$row[sum]}</i></span></p></li>
                ";
                $vote_none="";
                $sum=$sum+$row['sum'];
            }
        }else{
            $om=$sum=$list='';
            $vote_none=" style='display:none'";
        }
        $this->assign('om',time());
        $this->assign('vote_none',$vote_none);
        $this->assign('sum',$sum);
        $this->assign('list',$list);       
        return $this->fetch();

    }
}
