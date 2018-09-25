<?php
namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\Msg;
use think\Lang;
//修改，读取数据库
use think\Db;
use think\Cookie;

use think\Session;

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
        if (!(Cookie::has('uid')) and $this->cfg['config']['loginguest'] == "1") {
            if ($this->gusetLogin()) {
                exit("<script>location.reload();</script>");
            }
        }

        //没有登录不允许访问
        if (!(Cookie::has('uid'))) {
            exit("<script>location.href='/index/user/login'</script>");
            exit;
        }
        $uid = cookie('uid');
        //更新用户ip
        if ($userinfo['fuser'] == "") {
            $userinfo['fuser'] = userinfo($_COOKIE['tg'], '{username}');
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
        $this->assign('cfg',$this->cfg);
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
        if (!(Cookie::has('guest')) ||cookie('guest') == "" || cookie('guest') == "deleted") {
            $guest = "游客" . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
            $regtime = time();
            $p = md5(md5('123123').'guest');

            $tuser =$this->userinfo(cookie('tg'), 'username');
            //随机找一个客服
            if (trim($tuser) == "") {
                $rowt = Db::table("zb_user")->where('group_id',3)->where('prevtime','>',time()-3600*24)->orderRaw('rand()')->limit(1)->find();
                $tuser = $rowt['username'];
                setcookie("tg", $rowt['id'], time() + 315360000, '/');
            }
            $onlineip = request()->ip();
            Db::query("insert into zb_user(username,password,salt,gender,email,jointime,joinip,logintime,prevtime,score,nickname,group_id,mobile,kuser,tuser,status,level)\tvalues('{$guest}','{$p}','guest','2','','{$regtime}','{$onlineip}','{$regtime}','{$regtime}','0','0','0','0','{$tuser}','{$tuser}','normal',0)");
            $uid = Db::table('zb_user')->getLastInsID();
            //$db->query("replace into {$tablepre}memberfields (uid,nickname)\tvalues('{$uid}','{$guest}')\t");
            cookie("guest", $guest, time() + 315360000, "/");
            cookie("uid", $uid, time() + 315360000, "/");
        } else {
            //如果登录失败，则将游客信息置空，表示重新配置
            if ($this->auth->login(cookie('guest'), '123123') != true) {
                setcookie("guest", '', time() - 1, "/");
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
}

?>