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
        
        //开启游客登录
        if (!isset($_SESSION['login_uid']) and $cfg['config']['loginguest'] == "1") {
            if (gusetLogin()) {
                exit("<script>location.reload();</script>");
            }
        }
        if ($cfg['config']['state'] == '0') {
            exit("<script>location.href='error.php?msg=系统处于关闭状态！请稍候……'</script>");
            exit;
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

}

?>