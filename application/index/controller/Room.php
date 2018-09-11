<?php
namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\model\Msg;

class Room extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = '';
   
    
    public function index(){
        //聊天记录
       // $query = $db->query("select * from {$tablepre}msgs where rid='" . $cfg['config']['id'] . "' and p='false' and state!='1' and `type`='0' order by id desc limit 0,20 ");
        $omsg='';
        $data = Msg::where('rid','1')->where('p','false')->where('state','<>','1')->where('type','0')->order('id desc')->limit(0,20)->select();
        foreach ($data as $msg){
            $msg->msg = str_replace(array('&', '', '"', '<', '>'), array('&', "\\'", '"', '<', '>'), $msg->msg);
            if ($msg->tuid != "ALL") {
                $omsg = "<div style='clear:both;'></div><div class='msg'  id='".$msg->msgid."'><div class='msg_head'><img src='ajax/getFaceImg/?t=p1&u={$msg->uid}'></div><div class='msg_content'><div><font class='u'  onclick='ToUser.set(\"{$msg->uid}\",\"$msg->uname\")'>$msg->uname</font> <img src='/upload/upfile/day_150609/201506091905376720.png' class='msg_group_ico' title='未知访客'>   <font class='dui'>对</font> <font class='u' onclick='ToUser.set(\"{$msg->tuid}\",\"{$msg->tname}\")'>{$msg->tname}</font> 说 <font class='date'>" . $msg->mtime . "</font></div><div class='layim_chatsay' style='margin:5px 0px;'><font  style='{$msg->style};'>{$msg->msg}</font><em class='layim_zero'></em></div></div></div><div style='clear:both;'></div>" . $omsg;
            } else {
                $omsg = "<div style='clear:both;'></div><div class='msg' id='{$msg->msgid}'><div class='msg_head'><img src='ajax/getFaceImg/?t=p1&u={$msg->uid}'></div><div class='msg_content'><div><font class='u'  onclick='ToUser.set(\"{$msg->uid}\",\"{$msg->uname}\")'>{$msg->uname}</font> <img src='/upload/upfile/day_150609/201506091905376720.png' class='msg_group_ico' title='未知访客'> <font class='date'>" . $msg->mtime. "</font></div><div class='layim_chatsay' style='margin:5px 0px;'><font  style='{$msg->style};'>{$msg->msg}</font><em class='layim_zero'></em></div></div></div><div style='clear:both;'></div>" . $omsg;
            }
        }
        $this->assign('omsg',$omsg);
        return $this->fetch();
    }

}

?>