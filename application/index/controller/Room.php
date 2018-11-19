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

        //如果已经登录,首次进入,将客户信息写入session
        if($this->auth->id){
            //如果已经的登录，则把直播室所需相关信息写入session
            session('login_uid',$this->auth->id);
            session('login_user',$this->auth->username);
            session('login_nick',$this->auth->nickname);
            session('login_gid',$this->auth->group_id);
            session('login_sex',$this->auth->gender);
            session('onlines_state.time',time());
            $tuser = $this->userinfo(cookie('tg'), 'username');
            //随机找一个客服
            if (trim($tuser) == "") {
                $rowt = Db::table("zb_user")->where('group_id',3)->where('status','normal')->orderRaw('rand()')->limit(1)->find();
                $tuser =  $rowt['username'];
                cookie("tg",$rowt['id'], time() + 315360000, '/');
            }
        }

        //如果客户没有登录，且系统允许游客登录，则赋予游客身份并随机分配客服
        if (!session::has('login_uid') and ($this->cfg['config']['loginguest'] == "1")) {
            if ($this->gusetLogin()) {
                exit("<script>location.reload();</script>");
            }
        }

        $uid = session('login_uid');
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

        //是否有房间管理权限
        $this->assign('isadmin',$this->check_auth('room_admin'));

        //查询会员组
        $query = Db::table('zb_user_group')->order('ov desc')->select();
        foreach($query as $k=>$v){
            $query[$k]['rules'] = $this->replace_auth( $v['rules']);
            $query[$k]['title'] = $v['name'];
            $query[$k]['sn'] = $v['name'];
            $query[$k]['type'] = 0;
        }

        $group=array();
        $groupli = '';
        $grouparr = '';
        foreach($query as $row){
            $groupli .= "<div id='group_{$row['id']}'></div>";
            $grouparr .= "grouparr[{$row['id']}]=" . json_encode($row) . ";\n";
            $group["m" . $row['id']] = $row;
        }

        $this->assign('groupli',$groupli);
        $this->assign('grouparr',$grouparr);
        $this->assign('group',$group);
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
            $data['rid']=$this->cfg['config']['id']; //房间号
            $data['ugid'] = $userinfo['group_id'];      //用户组
            $data['uname'] = $userinfo['username'];
            $data['tuid'] = $this->cfg['config']['defvideo'];
            $data['tname']=$this->cfg['config']['defvideonick'];
            $data['mtime'] = time();
            $data['ip'] = request()->ip();
            $data['msg'] = '登录直播间';
            $data['type'] = '3';
            //插入数据库登录数据
            Db::table('zb_msg')->insert($data);
            session('room_' . $uid . '_' . $this->cfg['config']['id'],1);
        }

        //直播室权限检查
        $this->assign('ts',$ts);
        $this->assign('omsg',$omsg);
        $this->assign('cfg',$this->cfg);
        $this->assign('onlineip',request()->ip());
        $this->assign('userinfo',$userinfo);

        //左侧工具栏
        $apps = Db::table('zb_apps_manage')->where('s','0')->order('ov desc')->select();
        $this->assign('apps',$apps);
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
        //游客是否登陆
        if (!cookie::has('guest') || cookie('guest') == '' || cookie('guest')=='deleted') {
            $guest = "游客" . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
            $p = md5(md5('123123').'guest');

            $tuser =$this->userinfo(cookie('tg'), 'username');
            //随机找一个客服
            if (trim($tuser) == "") {
                $rowt = Db::table("zb_user")->where('group_id',3)->where('prevtime','>',time()-3600*24)->orderRaw('rand()')->limit(1)->find();
                $tuser = $rowt['username'];
                cookie("tg", $rowt['id'], time() + 315360000, '/');
            }
            $onlineip = request()->ip();
            //插入游客数据

            $data['username'] = $guest;
            $data['password'] = $p;
            $data['salt'] = 'guest';
            $data['gender'] = '2';
            $data['jointime']= time();
            $data['joinip'] = request()->ip();
            $data['logintime'] = time();
            $data['prevtime'] = time();
            $data['nickname']= $guest;
            $data['group_id'] = '0';
            $data['kuser'] = $tuser;
            $data['tuser'] = $tuser;
            $data['status'] = 'normal';
            $data['level'] = '0';

            $id = Db::table('zb_user')->insert($data);
            cookie("guest", $guest, time() + 315360000, "/");
        } else {
            //如果登录失败，则将游客信息置空，清空cookie，重新赋予游客身份
            if ($this->userLogin(cookie('guest'), '123123') != true) {
                cookie("guest", '', time() - 1, "/");
                return false;
            }
        }
        return true;
    }

    //直播室用户登录
    protected function userLogin($u,$p=''){
        if($this->auth->login($u, $p)){
            session('login_uid',$this->auth->id);
            session('login_user',$this->auth->username);
            session('login_nick',$this->auth->nickname);
            session('login_gid',$this->auth->group_id);
            session('login_sex',$this->auth->gender);
            session('onlines_state.time',time());
            $tuser = $this->userinfo(cookie('tg'), 'username');
            //随机找一个客服
            if (trim($tuser) == "") {
                $rowt = Db::table("zb_user")->where('group_id',3)->where('prevtime','>',time()-3600*24)->orderRaw('rand()')->limit(1)->find();
                $tuser = $this->auth->username;
                cookie("tg", $rowt['id'], time() + 315360000, '/');
            }
            return true;
        }
        return false;
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


    /*
     *直播室权限检查
     *@param $gid 会员组id
     *@ return  array  权限 name
     */
    protected function auth_group($gid)
    {

        $query =Db::table('zb_user_group')->where('id',$gid)->find();
        if($query) {
            $rules = explode(',', $query['rules']);
            $rulesArr = Db::table('zb_user_rule')->where('status', 'normal')->where('id', 'in', $rules)->field('name')->select();
            foreach($rulesArr as $rule){
                $rulesNames[] = $rule['name'];
            }
            return $rulesNames;
        }
        return NULL;
    }

    protected function check_auth($auth)
    {
        $auth_rules = $this->auth_group(session('login_gid'));
        if (in_array($auth,$auth_rules) !== false) {
            return true;
        }
        return false;
    }

    protected function check_user_auth($uid, $auth)
    {
        $auth_rules = $this->auth_group($this->userinfo($uid, 'group_id'));
        if (in_array($auth,$auth_rules) !== false) {
            return true;
        }
        return false;
    }

    /*
     * 权限id换成name
     */

    protected function replace_auth($rules){
        $rulestr = '';
        $rules = explode(',',$rules);
        $rulesArr = Db::table('zb_user_rule')->where('status', 'normal')->field('id,name')->select();
        foreach($rules as $rule){
            foreach($rulesArr as $v){
                if($rule == $v['id']){
                    $rulestr .= ','.$v['name'];
                }
            }
        }
        return trim($rulestr,',');
    }


    /*
     * 直播室查看个人信息
     */
    public function profile(){

        $uid = request()->param('uid');
        if($uid != session('login_uid')){
            exit('0');
        }
        $this->view->assign('uid',$uid);
        $userinfo = Db::table('zb_user')->find($uid);
        //检查是否具有管理权限
        $gl = $this->check_auth('user_info_gl');
        $this->assign('gl',$gl);
        $this->view->assign('title',(__('Profile')));
        $this->view->assign('userinfo',$userinfo);
        $this->view->fetch();
    }

}

?>