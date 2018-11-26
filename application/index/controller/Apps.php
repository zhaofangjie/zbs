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
        $this->fetch();
    }


    //添加喊单
    public function appHdAdd($ktime,$ptime,$sp,$kcj,$lx,$cw,$zsj,$zyj,$username,$pcj,$sn){       
        
        $data['ktime'] = $ktime;
        $data['ptime'] = $ptime;
        $data['sp'] = $sp;
        $data['kcj'] = $kcj;
        $data['lx'] = $lx;
        $data['cw'] = $cw;
        $data['zsj'] = $zsj;
        $data['zyj'] = $zyj;
        $data['username'] = $username;
        $data['pcj'] = $pcj;
        $data['sn'] = $sn;
        $id = Db::name('apps_hd')->insertGetId($data);
        if($id) return $id;
    }

    //喊单列表 分页显示
    public function appHdList(){
        if(!session('login_uid')) exit("<script>top.layer.msg('没有权限查看喊单数据！请联系客服！');</script>");
        //操作
        $act = $this->request->param('act');
        $id = $this->request->param('id');
        switch($act){
            //点赞  10年之内点一次，哈哈
            case "z":
                if(session('z'.$id)=="" && cookie('z'.$id)==""){                    
                    Db::name('apps_hd')->where('id',$id)->setInc('z');
                    session('z'.$id,1);
                    cookie('z'.$id,'1',time()+315360000);
                }
                break;
            case "hd_del":
                //删除
                $db->query("delete from {$tablepre}apps_hd where username='$_SESSION[login_user]' and id='$id'");
                break;
            case "app_hd_pc":
                $db->query("update {$tablepre}apps_hd set pcj='{$pc_pcj}',ptime='".gdate()."' where id='{$pc_id}'");
                $str="<font style='border-bottom:1px solid #999; color:red;font-size:14px;'>[喊单提醒]</font><br>单号：$pc_id,$pc_lx,$pc_sp 平仓 [<font style='color:red;  cursor:pointer' onClick='$(\\\"#app_1\\\").trigger(\\\"click\\\")'>详细</font>]";
                exit('<script>top.app_sendmsg("'.$str.'");location.href="?"</script>');
                break;
            case "app_hd_add":
                //发布邯郸
                $ktime = time();
                $ptime='';
                $sp = $this->request->param('sp');
                $kcj = $this->request->param('kcj');
                $lx = $this->request->param('lx');
                $cw = $this->request->param('cw');
                $zsj = $this->request->param('zsj');
                $zyj = $this->request->param('zyj');
                $username = session('login_user');
                $pcj = '';
                $sn = '';
                $id = $this->appHdAdd($ktime,$ptime,$sp,$kcj,$lx,$cw,$zsj,$zyj,$username,$pcj,$sn);
                $str="<font style='border-bottom:1px solid #999; color:red;font-size:14px;'>[喊单提醒]</font><br>单号：$id,$lx,$sp …… [<font style='color:red;  cursor:pointer' onClick='$(\\\"#app_1\\\").trigger(\\\"click\\\")'>详细</font>]";
                exit('<script>top.app_sendmsg("'.$str.'");location.href="?"</script>');
                break;
                break;
        }

        $key  = $this->request->param('key');
        if($key!="") $sql.=" where uname like '%$key%'";

        $room = new Room;
        //权限检查
        $hdAdd = $room->check_auth('hd_add');
        $hdView = $room->check_auth('hd_view');
        $this->assign('hdAdd',$hdAdd);
        $this->assign('hdView',$hdView);

        // 查询状态为1的用户数据 并且每页显示20条数据
        $list = Db::name('apps_hd')->order('id desc')->paginate(10);
        // 把分页数据赋值给模板变量list
        $this->assign('list', $list);
        // 渲染模板输出
        return $this->fetch('handan');
    }
}
