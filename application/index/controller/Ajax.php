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

    protected $noNeedLogin = ['lang','getrlist','getFaceImg','getmylist'];
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
            $roomUser = array("roomid" => $_SERVER['HTTP_HOST'] . "." . $data['rid'], "chatid" => "", "ip" => "0.0.0.0", "qx" => "0", "cam" => "", "vip" => "0", "age" => "-", "sex" => "", "mood" => "", "state" => "0", "nick" => "", "color" => "0");
            $count = count($rebots_arr);
            for ($i = 0; $i < $count; $i++) {
                if (trim($rebots_arr[$i]) == "") {
                    continue;
                }
                $roomUser['chatid'] = 'x_r'. $i;
                $roomUser['sex'] = rand(0, 2);
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
        $data = $this->request->param();
        $u = '/style/face/'.$data['t'].'/'.$data['u'].'.gif';
        if(file_exists($u)) header("Location: $u");
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
        //$userinfo = $db->fetch_row($db->query("select m.*,ms.* from {$tablepre}members m,{$tablepre}memberfields ms  where m.uid=ms.uid and m.uid='{$uid}'"));
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
                    $tmp['qq'] = $row['realname'];
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
                   $tmp['qq'] = $row['realname'];
                   $tmp['gid'] = $row['group_id'];
                   $data['row'][$i++] = $tmp;
                   $data['state'] = 'true';
               }
           }
        }
        return json($data);
    }
}
