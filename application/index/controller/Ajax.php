<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Lang;
//修改，读取数据库
use think\Db;
/**
 * Ajax异步请求接口
 * @internal
 */
class Ajax extends Frontend
{

    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
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


    /**
     * 加载语言包
     */
    public function lang()
    {
        header('Content-Type: application/javascript');
        $callback = $this->request->get('callback');
        $controllername = input("controllername");
        $this->loadlang($controllername);
        //强制输出JSON Object
        $result = 'define(' . json_encode(Lang::get(), JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE) . ');';
        return $result;
    }

    /**
     * 上传文件
     */
    public function upload()
    {
        return action('api/common/upload');
    }

    //机器人列表

    public function getrlist(){
        $data = $this->request->param();
        $r = explode("|", $data['r']);
        $r_max = rand($r[0], $r[1]);
        if ($r_max <= 0) {
            exit("");
        }
        $time = time();

        $count = Db::table('zb_rebots')->where('rid',$data['rid'])->where('losttime','>',$time)->count();
        if($count<=0){
            $row = Db::table('zb_rebots')->find(1);
            $rebots_arr = explode("\r\n", $row['rebots']);
            shuffle($rebots_arr);
            $roomListUserJsonStr = array("type" => "UonlineUser", "stat" => "OK");
            $roomListUser = array();
            $roomUser = array("roomid" => $_SERVER['HTTP_HOST'] . "." . $data['rid'], "chatid" => "", "ip" => "0.0.0.0", "qx" => "0", "cam" => "", "vip" => "0", "age" => "-", "gender" => "", "mood" => "", "state" => "0", "nick" => "", "color" => "0");
            $count = count($rebots_arr);
            for ($i = 0; $i < $count; $i++) {
                if (trim($rebots_arr[$i]) == "") {
                    continue;
                }
                $roomUser['chatid'] = 'x_r'. $i;
                $roomUser['gender'] = rand(0, 2);
                $roomUser['cam'] = rand(0, 2);
                $roomUser['nick'] = $rebots_arr[$i];
                $roomListUser[$i] = $roomUser;
                if ($i >= $r_max) {
                    break;
                }
            }
            $roomListUserJsonStr['roomListUser'] = $roomListUser;
            $rdata = base64_encode(json_encode($roomListUserJsonStr));
            $losttime = time() + 20 * 60;
            Db::table('zb_rebots')->where('rid',$data['rid'])->delete();
            $tmpdata = ['rid'=>$data['rid'],'rebots'=>$rdata,'losttime'=>$losttime];
            Db::table('zb_rebots')->insert($tmpdata);
            return json_encode($rebots_arr);
        }else{
            $row = Db::table('zb_rebots')->where('rid',$data['rid'])->where('losttime','>',$time)->find();
            $rdata = $row['rebots'];
        }
        return base64_decode($rdata);
    }

    //随机头像
    public function getFaceImg(){
        $t = $this->request->param('t');
        $p = $this->request->param('u');

        $u = './style/face/'.$t.'/'.$p.'.gif';
        //如果头像存在，则直接返回
        if(file_exists($u)){
            header("Content-Type:image/gif");
            readfile($u);
        }
        else
        {
            if($_GET['t']=='p1')
                $u="/style/face/rebot/".sprintf("%02d",rand(2,40)).".png";
             else
               $u="/style/face/p2/null.gif";
             header("Location: $u");
             exit();
        }
    }


    //客户与客服
    public function getmylist(){
        $data['state'] = 'false';
        $uid = session('login_uid');
        $rid = $this->request->param('rid');
        $user = $this->request->param('user');
        $userinfo = Db::name('user')->find($uid);
        $i = 0;
        if ($userinfo['group_id'] != '3') {
            if ($userinfo['kuser'] == "") {
                //如果没有专属客服，则分配当日值班客服
                $userinfo['kuser'] = $this->cfg['config']['defkf'];
            }
            $query = Db::name('user')->where('username',$userinfo['kuser'])->select();
            if(!empty($query)){
                foreach($query as $row){
                    $tmp['uid'] = $row['id'];
                    $tmp['chatid'] = $row['id'];
                    $tmp['nick'] = $row['nickname'];
                    $tmp['phone'] = $row['mobile'];
                    $tmp['qq'] = $row['qq'];
                    $tmp['gid'] = $row['group_id'];
                    $tmp['mood'] = $row['bio'];
                    $data['row'][$i++] = $tmp;
                    $data['state'] = 'true';
                }
            }
        } else {
            //我的客户
           $query = Db::name('user')->where('kuser',$user)->where('username','<>',$user)->select();

           if(!empty($query)){
               foreach($query as $row){
                   $tmp['uid'] = $row['id'];
                   $tmp['chatid'] = $row['id'];
                   $tmp['nick'] = $row['nickname'];
                   $tmp['phone'] = $row['mobile'];
                   $tmp['qq'] = $row['qq'];
                   $tmp['gid'] = $row['group_id'];
                   $data['row'][$i++] = $tmp;
                   $data['state'] = 'true';
               }
           }
        }
        return json($data);
    }

    //历史消息
    public function mymsgold(){
        $uid =session('login_uid');
        $tuid = $this->request->param('tuid');
        $list = Db::name('msg')->where(function($query) use($uid,$tuid) {
            $query->where('uid',$uid)->where('tuid',$tuid);
        })->whereOr(function($query) use($tuid,$uid){
             $query->where('uid',$tuid)->where('tuid',$uid);
        })->where('type','0')->order('id desc')->limit(20)->select();
       $msgold='';
       if(!empty($list)){
           foreach ($list as $row) {
               $str1 = '
				<li class="layim_chate[me]"><div class="layim_chatuser"><span class="layim_chattime">[date]</span><span class="layim_chatname">[uname]</span><img src="ajax/getFaceImg?t=p1&u=[uid]"></div><div class="layim_chatsay"><font style="color:#000">[msg]</font><em class="layim_zero"></em></div></li>
				';
               $str2 = '
				<li class="layim_chate[me]"><div class="layim_chatuser"><img src="ajax/getFaceImg?t=p1&u=[uid]"><span class="layim_chatname">[uname]</span><span class="layim_chattime">[date]</span></div><div class="layim_chatsay"><font style="color:#000">[msg]</font><em class="layim_zero"></em></div></li>
				';
               if ($row['uid'] == $uid) {
                   $str = str_replace("[me]", "me", $str1);
               } else {
                   $str = str_replace("[me]", "he", $str2);
               }
               $str = str_replace("[uid]", $row['uid'], $str);
               $str = str_replace("[uname]", $row['uname'], $str);
               $str = str_replace("[msg]", tohtml($row['msg']), $str);
               $str = str_replace("[date]", date("Y-m-d H:i:s", $row['mtime']), $str);
               $msgold = $str . $msgold;
           }
       }
        $userinfo = Db::name('user')->find($tuid);
        $data['qq'] = $userinfo['qq'];
        $data['kfmsg'] = tohtml($userinfo['kfmsg']);
        $data['tuid'] = $tuid;
        $data['msg'] = $msgold;
        return json($data);
    }

    //聊天记录

    public function putmsg(){
        //数据未过滤
        if(!session('login_uid')) return;
        $msgtip = $this->request->param('msgtip');
        $rid = $this->request->param('rid');
        $muid = $this->request->param('muid');
        $tid = $this->request->param('tid');
        $uname = $this->request->param('uname');
        $tname = $this->request->param('tname');
        $privacy = $this->request->param('privacy');
        $style = $this->request->param('style');
        $msg = $_POST['msg'];
        $msgid = $this->request->param('msgid');

        if ($this->cfg['config']['msgaudit'] == '1') {
            $state = '1';
        }
        if(!isset($magtip)){
            $state = '0';
        }
        if ($msgtip == "2") {
            $state = '2';
        }
        if ($msgtip == "3") {
            $state = '3';
        }
        //组装数据
        $data['rid'] = $rid;
        $data['uid'] = $muid;
        $data['tuid'] = $tid;
        $data['uname'] = $uname;
        $data['tname'] = $tname;
        $data['p'] = $privacy;
        $data['style'] = $style;
        $data['msg'] = $msg;
        $data['mtime'] = time();
        $data['ugid'] = session('login_gid');
        $data['msgid'] = $msgid;
        $data['ip'] = $this->request->ip();
        $data['state'] = $state;
        Db::name('msg')->insert($data);
    }
    
    /*
     * 踢出房间
     */
    
    public function kick(){
        //权限检查
            $aid = $this->request->param('aid');
            $ktime = $this->request->param('ktime');
            $losttime = $ktime * 60 + time();
            $u = $this->request->param('u');
            $onlineip = $this->request->ip();
            $losttime = $this->request->param('losttime');
            $cause = $this->request->param('cause');
            $data['username'] = $u;
            $data['ip'] = $onlineip;
            $data['losttime'] = $losttime;
            $data['sn'] = $cause; 
            Db::name('ban')->insert($data);
            $state['state'] = 'yes';
            return json($state);
    }
}
