<?php
namespace app\index\controller;

use app\common\controller\Frontend;
use app\index\controller\Room as Room;
use think\Db;


/*
 * 工具类 投票 客服 喊单
 */
class Apps extends Frontend
{
    protected $layout = '';
    protected $noNeedLogin = ['kefu','vote','rank','appHdList'];
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
        $om = $this->request->param('om');
        if(!isset($om))$om=$m;

        if($this->request->param('act')=="add"){
            $uid = $this->request->param('teacher');
            if($vuid<1){
                $data["status"]=0;
                $data["msg"]="未登录，不能投票";
                return json($data);
            }
            if(Db::name('apps_rank')->where('vuid',$vuid)->whereTime('vtime','today')->count()>0){
                $data["status"]=2;
                $data["msg"]="你今日已经投过他了！";
               return json($data);
            }
            $data['uid'] = $uid;
            $data['vuid'] = $vuid;
            $data['vtime'] = time();
            Db::name('apps_rank')->insert($data);
            $data["status"]=1;
            $data["msg"]="";
            return json($data);
        }

        $data = Db::query("select m.*,(select count(*) from  zb_apps_rank where uid=m.id and FROM_UNIXTIME(vtime,'%Y%m')='".date("{$om}",time())."') as sum,(select count(*) from  zb_apps_rank where uid=m.id and FROM_UNIXTIME(vtime,'%Y%m%d')='".date("Ymd",time())."') as dsum from zb_user m where m.group_id=4 order by sum desc");

        if(!empty($data)){
            $list='';
            $sum='';
            foreach($data as $row){
                $vote_none="";
                if($om!=date('Ym',time()))$vote_none=" style='display:none'";
                $list.="
                <li id='t".$row["id"]."'><a href='javascript:vote(".$row['id'].")'".$vote_none."></a>
                <p class='v_name'><i class='percent'></i>".$row['nickname']."</p>
                <p class='percent_container'><span class='percent_line'></span></p>
                <p class='v_text' ><span>今日获赞：<i class='count'>".$row['dsum']."</i></span><span>月累计：<i class='amount'>".$row['sum']."</i></span></p></li>
                ";
                $sum=$sum+$row['sum'];
            }
        }else{
            $sum=$list='';
            $vote_none=" style='display:none'";
        }
        $this->assign('om',$om);
        $this->assign('vote_none',$vote_none);
        $this->assign('sum',$sum);
        $this->assign('list',$list);
        return $this->fetch();

    }

    //喊单
    public function handan(){
        $act = $this->request->param('act');
        switch($act){
            case "z":
                if($_SESSION['z'.$id]==""&&$_COOKIE['z'.$id]==""){
                    $db->query("update {$tablepre}apps_hd set z=z+1 where id='$id' ");
                    $_SESSION['z'.$id]=1;
                    setcookie('z'.$id, '1', gdate()+315360000);
                }
                break;
            case "hd_del":
                $db->query("delete from {$tablepre}apps_hd where username='$_SESSION[login_user]' and id='$id'");
                break;
            case "app_hd_pc":
                $db->query("update {$tablepre}apps_hd set pcj='{$pc_pcj}',ptime='".gdate()."' where id='{$pc_id}'");
                $str="<font style='border-bottom:1px solid #999; color:red;font-size:14px;'>[喊单提醒]</font><br>单号：$pc_id,$pc_lx,$pc_sp 平仓 [<font style='color:red;  cursor:pointer' onClick='$(\\\"#app_1\\\").trigger(\\\"click\\\")'>详细</font>]";
                exit('<script>top.app_sendmsg("'.$str.'");location.href="?"</script>');
                break;
            case "app_hd_add":
                app_hd_add($ktime,$ptime,$sp,$kcj,$lx,$cw,$zsj,$zyj,$username,$pcj,$sn);
                $id=$db->insert_id();
                $str="<font style='border-bottom:1px solid #999; color:red;font-size:14px;'>[喊单提醒]</font><br>单号：$id,$lx,$sp …… [<font style='color:red;  cursor:pointer' onClick='$(\\\"#app_1\\\").trigger(\\\"click\\\")'>详细</font>]";
                exit('<script>top.app_sendmsg("'.$str.'");location.href="?"</script>');
                break;
        }
        $this->fetch();
    }


    //添加喊单
    public function appHdAdd($ktime,$ptime,$sp,$kcj,$lx,$cw,$zsj,$zyj,$username,$pcj,$sn){
        global $db,$tablepre;
        $time=gdate();
        $ktime=strtotime($ktime);
        $ptime=strtotime($ptime);
        $username=$_SESSION['login_user'];
        $db->query("insert into {$tablepre}apps_hd(ktime,sp,kcj,lx,cw,zsj,zyj,username,sn,ttime)values('$ktime','$sp','$kcj','$lx','$cw','$zsj','$zyj','$username','$sn','$time')");
    }

    //喊单列表 分页显示
    public function appHdList(){

        $key  = $this->request->param('key');
        if($key!="") $sql.=" where uname like '%$key%'";

        $room = new Room;
        //权限检查
        $hdAdd = $room->check_auth('hd_add');
        $hdView = $room->check_auth('hd_view');
        $this->assign('hdAdd',$hdAdd);
        $this->assign('hdView',$hdView);

        // 查询状态为1的用户数据 并且每页显示20条数据
        $list = Db::name('apps_hd')->paginate(20,true);
        $this->assign('list', $list);
        // 把分页数据赋值给模板变量list
        $this->assign('list', $list);
        // 渲染模板输出
        return $this->fetch('handan');

        $count=$db->num_rows($db->query($sql));
        pageft($count,$num,"");
        $sql.=" order by id desc";
        $sql.=" limit $firstcount,$displaypg";
        $query=$db->query($sql);
        while($row=$db->fetch_row($query)){
            $t=$tpl;
            if($row['username']==$_SESSION['login_user']&&$row['pcj']==""){
                $t=str_replace('{pcj}',"<a href=\"javascript:bt_hd_pc('{$row[id]}','{$row[lx]}','{$row[sp]}')\">平仓</a>",$t);
            }
            if($row['username']==$_SESSION['login_user']){
                $t=str_replace('{username}',"{username} <a href=\"javascript:bt_hd_del('{$row[id]}','{$row[lx]}','{$row[sp]}')\">删</a>",$t);
            }
            if(strpos($row[lx],'买')&&$row['pcj']!=""){
                $t=str_replace('{yld}',round($row['pcj']-$row['kcj'],2),$t);
            }
            else if(strpos($row[lx],'卖')&&$row['pcj']!=""){
                $t=str_replace('{yld}',round($row['kcj']-$row['pcj'],2),$t);
            }else{
                $t=str_replace('{yld}','',$t);
            }
            foreach($row as $k=>$value){
                $t=str_replace('{'.$k.'}',$value,$t);
            }
            $str.=$t;

        }
        return $str;
    }
}
