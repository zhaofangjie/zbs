<?php
namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\Msg;
use think\Lang;
//修改，读取数据库
use think\Db;

class Room extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';


    public function index(){
        //读取房间配置
        //默认为第一个房间配置
        $cfg['config'] = Db::table('zb_room_config')->where('id',1)->find();

        //判断房间状态
        $url = $this->request->request('url');
        if ($cfg['config']['state'] == '0') {
            $this->error('系统处于关闭状态！请稍候……',$url);
        }

        //如果客户没有登录，则赋予游客身份
        if (!isset($_SESSION['login_uid']) and $cfg['config']['loginguest'] == "1") {
            if ($this->gusetLogin()) {
                exit("<script>location.reload();</script>");
            }
        }
        $omsg='';
        $data = Msg::where('rid','1')->where('p','false')->where('state','<>','1')->where('type','0')->order('id desc')->limit(0,20)->select();
        foreach ($data as $msg){
            $msg->msg = str_replace(array('&', '', '"', '<', '>'), array('&', "\\'", '"', '<', '>'), $msg->msg);
            if ($msg->tuid != "ALL") {
                $omsg = "<div style='clear:both;'></div><div class='msg'  id='".$msg->msgid."'><div class='msg_head'><img src='ajax/getFaceImg/?t=p1&u={$msg->uid}'></div><div class='msg_content'><div><font class='u'  onclick='ToUser.set(\"{$msg->uid}\",\"$msg->uname\")'>$msg->uname</font> <img src='/uploads/day_150609/201506091905376720.png' class='msg_group_ico' title='游客-未知访客'>   <font class='dui'>对</font> <font class='u' onclick='ToUser.set(\"{$msg->tuid}\",\"{$msg->tname}\")'>{$msg->tname}</font> <font class='date'>" . $msg->mtime . "</font></div><div class='layim_chatsay' style='margin:5px 0px;'><font  style='{$msg->style};'>{$msg->msg}</font><em class='layim_zero'></em></div></div></div><div style='clear:both;'></div>" . $omsg;
            } else {
                $omsg = "<div style='clear:both;'></div><div class='msg' id='{$msg->msgid}'><div class='msg_head'><img src='ajax/getFaceImg/?t=p1&u={$msg->uid}'></div><div class='msg_content'><div><font class='u'  onclick='ToUser.set(\"{$msg->uid}\",\"{$msg->uname}\")'>{$msg->uname}</font> <img src='/uploads/day_150609/201506091905376720.png' class='msg_group_ico' title='游客-未知访客'> <font class='date'>" . $msg->mtime. "</font></div><div class='layim_chatsay' style='margin:5px 0px;'><font  style='{$msg->style};'>{$msg->msg}</font><em class='layim_zero'></em></div></div></div><div style='clear:both;'></div>" . $omsg;
            }
        }
        $this->assign('omsg',$omsg);
        $this->assign('cfg',$cfg);
        $this->assign('onlineip',request()->ip());
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
        session_start();
        if (!isset($_COOKIE['guest']) || $_COOKIE['guest'] == "" || $_COOKIE['guest'] == "deleted") {
            $guest = "游客" . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
            $regtime = time();
            $p = md5('123123');
           
                $tuser =$this->userinfo(cookie('tg'), 'username');
            
            
            //随机找一个客服
            if (trim($tuser) == "") {
                $rowt = Db::table("zb_user")->where('group_id',3)->where('prevtime','>',time())->orderRaw('rand()')->find();
                dump($rowt);
                $tuser = $rowt['username'];
                setcookie("tg", $rowt['id'], time() + 315360000, '/');
            }
            $onlineip = request()->ip();
            Db::query("insert into zb_user(username,password,gender,email,jointime,joinip,logintime,prevtime,score,nickname,group_id,mobile,kuser,tuser,status,level)\tvalues('{$guest}','{$p}','2','','{$regtime}','{$onlineip}','{$regtime}','{$regtime}','0','0','0','0','{$tuser}','{$tuser}','normal',0)");
            $uid = Db::table('zb_user')->getLastInsID();
            //$db->query("replace into {$tablepre}memberfields (uid,nickname)\tvalues('{$uid}','{$guest}')\t");
            cookie("guest", $guest, time() + 315360000, "/");
        } else {
            if (user_login($_COOKIE['guest'], '123123') !== true) {
                cookie("guest", '', time() - 1, "/");
                return false;
            }
        }
        return true;

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
    
    public function user_login($u, $p){
        global $db, $tablepre, $onlineip, $cfg;
        $query = $db->query("select * from {$tablepre}members where username='{$u}' and password='" . md5($p) . "'");
        while ($row = $db->fetch_row($query)) {
            if ($cfg['config']['regaudit'] == '1' && $row['state'] == '0') {
                return "用户未审核！";
            }
            $_SESSION['login_uid'] = $row['uid'];
            $_SESSION['login_user'] = $row['username'];
            $_SESSION['login_nick'] = $row['username'];
            $_SESSION['login_gid'] = $row['gid'];
            $_SESSION['login_sex'] = $row['sex'];
            $time = gdate();
            $_SESSION['onlines_state']['time'] = $time;
            $tuser = userinfo($_COOKIE['tg'], '{username}');
            if (trim($tuser) == "") {
                $rowt = $db->fetch_row($db->query("select uid,username from {$tablepre}members where  gid='3' and lastvisit>({$time}-180) order by rand() limit 1"));
                $tuser = $rowt['username'];
                setcookie("tg", $rowt['uid'], gdate() + 315360000, '/');
            }
            $db->query("update {$tablepre}members set fuser='{$tuser}',tuser='{$tuser}' where fuser='' and tuser='' and uid='{$row[uid]}'");
            $db->query("update {$tablepre}members set lastvisit=lastactivity,regip='{$onlineip}' where uid={$row[uid]}");
            $db->query("update {$tablepre}members set lastactivity={$time} where uid={$row[uid]}");
            $db->query("update {$tablepre}memberfields set logins=logins+1 where uid={$row[uid]}");
            $db->query("insert into  {$tablepre}msgs(rid,ugid,uid,uname,tuid,tname,mtime,ip,msg,`type`)\r\n\tvalues('{$cfg[config][id]}','{$row[gid]}','{$row[uid]}','{$row[username]}','{$cfg[config][defvideo]}','{$cfg[config][defvideonick]}','" . gdate() . "','{$onlineip}','用户登陆','1')\r\n\t\t");
            return true;
        }
        return "用户名或密码错误！";
    }

}

?>