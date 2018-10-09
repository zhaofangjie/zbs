<?php
namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\Msg;
use think\Config;
use think\Lang;
//修改，读取数据库
use think\Db;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;

class Room extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';

    protected $cfg;

    public function _initialize()
    {
        parent::_initialize();
        $auth = $this->auth;


        //读取房间配置
        //默认为第一个房间配置
        $this->cfg['config'] = Db::table('zb_room_config')->where('id',1)->find();
    }

    public function index(){


        //判断房间状态
        $url = $this->request->request('url');
        if ($this->cfg['config']['state'] == '0') {
            $this->error('系统处于关闭状态！请稍候……',$url);
        }

        //如果客户没有登录，且系统允许游客登录，则赋予游客身份并随机分配客服
        if (!($this->auth->id) and $this->cfg['config']['loginguest'] == "1") {
            dump($this->auth->_logind());
            if ($this->gusetLogin()) {
                dump($this->auth->isLogin());
                exit("<script>location.reload();</script>");
            }
        }

        dump($this->gusetLogin());
        exit();
        //没有登录不允许访问
        if (!($this->auth->id)) {
            exit("<script>location.href='/index/user/login'</script>");
            exit;
        }
        $uid = $this->auth->id;
        //更新用户ip
        Db::table('zb_user')->update(['joinip'=>request()->ip(),'id'=>$uid]);
        //查询用户相关信息
        $userinfo = Db::table('zb_user')->find($uid);
        if ($userinfo['kuser'] == "") {
            $userinfo['kuser'] = $this->userinfo(cookie('tg'), 'username');
        }
        session('login_gid', $userinfo['group_id']);
        //黑名单
        $query = Db::table('zb_ban')->where('(username = :username or ip = :ip) and losttime > :time',['username'=>$userinfo['username'],'ip'=>request()->ip(),'time'=>time()])->find();
        if($query){
            $msg='用户名或IP受限！过期时间'. date("Y-m-d H:i:s", $query['losttime']);
            $this->error($msg);
        }

        //用户权限
        $query = Db::table('zb_user_group')->order('id desc')->select();
        $group=array();
        $groupli = '';
        $grouparr = '';
        foreach($query as $row){
            $groupli .= "<div id='group_{$row['id']}'></div>";
            $grouparr .= "grouparr[{$row['id']}]=" . json_encode($row) . ";\n";
            $group["m" . $row['id']] = $row;
        }
        //历史聊天记录
        $omsg='';
        $query = Msg::where('rid','1')->where('p','false')->where('state','<>','1')->where('type','0')->order('id desc')->limit(0,20)->select();
        foreach ($query as $row){
            $row['msg'] = str_replace(array('&', '', '"', '<', '>'), array('&', "\\'", '"', '<', '>'), $row['msg']);
            if ($row['tuid'] != "ALL") {
                $omsg = "<div style='clear:both;'></div><div class='msg'  id='{$row['msgid']}'><div class='msg_head'><img src='ajax/getFaceImg/?t=p1&u={$row['uid']}'></div><div class='msg_content'><div><font class='u'  onclick='ToUser.set(\"{$row['uid']}\",\"{$row['uname']}\")'>{$row['uname']}</font> <img src='" . $group["m" . $row['ugid']]['ico'] . "' class='msg_group_ico' title='" . $group["m" . $row['ugid']]['name'] . "-" . $group["m" . $row['ugid']]['sn'] . "'>   <font class='dui'>对</font> <font class='u' onclick='ToUser.set(\"{$row['tuid']}\",\"{$row['tname']}\")'>{$row['tname']}</font> 说 <font class='date'>" . $row['mtime'] . "</font></div><div class='layim_chatsay' style='margin:5px 0px;'><font  style='{$row['style']};'>".html_entity_decode($row['msg'])."</font><em class='layim_zero'></em></div></div></div><div style='clear:both;'></div>" . $omsg;
            } else {
            $omsg = "<div style='clear:both;'></div><div class='msg' id='{$row['msgid']}'><div class='msg_head'><img src='ajax/getFaceImg/?t=p1&u={$row['uid']}'></div><div class='msg_content'><div><font class='u'  onclick='ToUser.set(\"{$row['uid']}\",\"{$row['uname']}\")'>{$row['uname']}</font> <img src='" . $group["m" . $row['ugid']]['ico'] . "' class='msg_group_ico' title='" . $group["m" . $row['ugid']]['name'] . "-" . $group["m" . $row['ugid']]['sn'] . "'> <font class='date'>" .$row['mtime'] . "</font></div><div class='layim_chatsay' style='margin:5px 0px;'><font  style='{$row['style']};'>".html_entity_decode($row['msg'])."</font><em class='layim_zero'></em></div></div></div><div style='clear:both;'></div>" . $omsg;
            }
        }

        //文字服务器  ip 端口
        $ts = explode(':', $this->cfg['config']['tserver']);
        //房间号写入session

        if (!Session::has('room_' . $uid . '_' . $this->cfg['config']['id'])) {
            Db::query("insert into zb_msg(rid,ugid,uid,uname,tuid,tname,mtime,ip,msg,`type`)values('{$this->cfg['config']['id']}','{$userinfo['group_id']}','{$userinfo['id']}','{$userinfo['username']}','{$this->cfg['config']['defvideo']}','{$this->cfg['config']['defvideonick']}','" .time(). "','{request()->ip()}','登陆直播间','3')");
            //插入数据库登录数据
            session('room_' . $uid . '_' . $this->cfg['config']['id'],1);
        }

        //用户权限
        //$rules = $this->auth->getRuleList();
        dump($this->auth->_user);
        exit();
        $this->assign('ts',$ts);
        $this->assign('omsg',$omsg);
        $this->assign('cfg',$this->cfg);
        $this->assign('onlineip',request()->ip());
        $this->assign('userinfo',$userinfo);
        return $this->fetch();
    }


    //创建桌面快捷方式
    public function ico(){
            $str='
            [DEFAULT]
            BASEURL=http://'.$_SERVER['HTTP_HOST'].'/index/room
            [{000214A0-0000-0000-C000-000000000046}]
            Prop3=19,2
            [InternetShortcut]
            IDList=
            URL=http://'.$_SERVER['HTTP_HOST'].'/index/room
            IconFile=http://'.$_SERVER['HTTP_HOST'].'/style/images/favio.ico
            IconIndex=1
            ';
        header("Content-type:application/octet-stream");
        header("Content-Disposition:attachment;filename=财运直播室.url");
        echo $str;
    }

    protected function gusetLogin(){
        if (!(Cookie::has('guest')) ||cookie('guest') == "" || cookie('guest') == "deleted") {
            $guest = "游客" . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
            $regtime = time();
            $p = md5(md5('123123').'guest');

            $tuser =$this->userinfo(cookie('tg'), 'username');
            //随机找一个客服
            if (trim($tuser) == "") {
                $rowt = Db::table("zb_user")->where('group_id',3)->where('prevtime','>',time()-3600*24)->orderRaw('rand()')->limit(1)->find();
                $tuser = $rowt['username'];
                cookie("tg", $rowt['id'], time() + 315360000, '/');
            }
            $onlineip = request()->ip();
            Db::query("insert into zb_user(username,password,salt,gender,email,jointime,joinip,logintime,prevtime,score,nickname,group_id,mobile,kuser,tuser,status,level)\tvalues('{$guest}','{$p}','guest','2','','{$regtime}','{$onlineip}','{$regtime}','{$regtime}','0','0','0','0','{$tuser}','{$tuser}','normal',0)");
            cookie("guest", $guest, time() + 315360000, "/");
        } else {
            //如果登录失败，则将游客信息置空，表示重新配置
            if ($this->auth->login(cookie('guest'), '123123') != true) {
                setcookie("guest", '', time() - 1, "/");
                return false;
            }
        }
        return true;
    }

    //直播室用户登录
    protected function userLogin($u,$p){

        if($this->auth->login($u, $p)){

            session('login_uid',$this->auth->id);
            session('login_user',$this->auth->username);
            session('login_nick',$this->auth->nickname);
            session('login_gid'.$this->auth->group_id);
            session('login_sex',$this->auth->gender);
            session('onlines_state.time',$time);
            $tuser = $this->userinfo(cookie('tg'), 'username');
            //随机找一个客服
            if (trim($tuser) == "") {
                $rowt = Db::table("zb_user")->where('group_id',3)->where('prevtime','>',time()-3600*24)->orderRaw('rand()')->limit(1)->find();
                $tuser = $rowt['username'];
                cookie("tg", $rowt['id'], time() + 315360000, '/');
            }
            return true;
        }
    }

    /*
     * 返回用户信息
     * @ $uid 用户id  init
     * $tpl 字段  array
     * return  str
     */
    protected function userinfo($uid,$tpl){
        if ($uid == "") {
            return "";
        }
        $query =  Db::table("zb_user")->find($uid);
        return $query[$tpl];
    }
}

?>