<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:68:"D:\xampp\htdocs\zbs\public/../application/index\view\room\index.html";i:1537510677;}*/ ?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta name="renderer" content="webkit">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
<title>财运金融直播室</title>
<meta content="" name="keywords">
<meta content="" name="description">
<link rel="shortcut icon" type="image/x-icon" href="/style/images/favio.ico" />
<link href="/style/room/skins/qqxiaoyou/css.css" rel="stylesheet" type="text/css"  />
<link href="/style/room/images/layim.css" rel="stylesheet" type="text/css"  />
<script src="/style/room/script/jquery.min.js"></script>
<script src="/style/room/script/jquery.cookie.js"></script>
<script src="/style/room/script/WebSocket.js"></script>
<script src="/style/room/script/layer.js"></script>
<script src="/style/room/script/jquery.nicescroll.min.js"></script>
<script src="/style/room/script/pastepicture.js"></script>
<script src="/style/room/script/swfobject.js" type="text/javascript" charset="utf-8"></script>
<script src="/style/room/script/function.js?{time()}" type="text/javascript" charset="utf-8"></script>
<script src="/style/room/script/init.js?{time()}" type="text/javascript" charset="utf-8"></script>
<script src="/style/room/script/device.min.js"></script>
<!--系统开发：QQ78519123-->
<script>
var UserList;
var ToUser;
var VideoLoaded=false;
var My={dm:'192.168.1.98',rid:'1',roomid:'192.168.1.98/1',chatid:'6',name:'游客133318',nick:'游客133318',sex:'2',age:'0',fuser:'lafang518',qx:'0',ip:'192.168.1.98',vip:'',color:'0',cam:'0',state:'0',mood:'',rst:'',camState:'1',key:'AzUBalNhXnhZMAw1DjoLKAUxWi4HbwliUG5QagZj'}

var RoomInfo={loginTip:'1',Msglog:'1',msgBlock:'1',msgAudit:'0',defaultTitle:document.title,MaxVideo:'10',VServer:'',VideoQ:'',TServer:'192.168.1.98',TSPort:'7272',PVideo:'1',AutoPublicVideo:'0',AutoSelfVideo:'0',type:'1',PVideoNick:'',OtherVideoAutoPlayer:'1',r:'80|100'}
var grouparr=new Array();
grouparr['14']={"id":"14","title":"\u84dd\u94bb\u4f1a\u5458 ","rules":"wt_view,wt_add,scpl_view,msg_send,jyts_view,hd_view,files_view","sn":"50\u4e07\u2264\u5b9e\u76d8\u5165\u91d1\uff1c100\u4e07","ico":"\/upfiles\/day_150609\/201506091903246505.png","type":"0","ov":"10"};
grouparr['13']={"id":"13","title":"\u7eff\u94bb\u4f1a\u5458","rules":"wt_view,wt_add,scpl_view,msg_send,jyts_view,hd_view,files_view","sn":"10\u4e07\u2264\u5b9e\u76d8\u5165\u91d1\uff1c50\u4e07","ico":"\/upload\/upfile\/day_150609\/201506091901139196.png","type":"0","ov":"9"};
grouparr['12']={"id":"12","title":"\u9ec4\u94bb\u4f1a\u5458","rules":"wt_view,wt_add,scpl_view,msg_send,jyts_view,hd_view,files_view","sn":"5\u4e07\u2264\u5b9e\u76d8\u5165\u91d1\uff1c10\u4e07","ico":"\/upload\/upfile\/day_150609\/201506091901023356.png","type":"0","ov":"8"};
grouparr['11']={"id":"11","title":"\u7ea2\u94bb\u4f1a\u5458","rules":"wt_view,wt_add,scpl_view,msg_send,jyts_view,hd_view,files_view","sn":"0\uff1c\u5b9e\u76d8\u5165\u91d1\uff1c5\u4e07","ico":"\/upload\/upfile\/day_150609\/201506091900277659.png","type":"0","ov":"7"};
grouparr['15']={"id":"15","title":"\u666e\u901a\u4f1a\u5458","rules":"wt_view,wt_add,scpl_view,msg_send,jyts_view,files_view","sn":"\u5f00\u6237\u7528\u6237","ico":"\/upload\/upfile\/day_150609\/201506091858517138.png","type":"0","ov":"6"};
grouparr['4']={"id":"4","title":"\u8bb2\u5e08","rules":"apps_wt,apps_scpl,apps_jyts,apps_hd,apps_files,adminlogin,wt_view,wt_re,user_kick,scpl_view,scpl_add,msg_send,msg_block,msg_audit,jyts_view,jyts_add,hd_view,hd_add,files_view,files_add,def_videosrc","sn":"\u9ad8\u7ea7\u91d1\u878d\u5206\u6790\u5e08","ico":"\/upload\/upfile\/day_150609\/201506091905105798.gif","type":"0","ov":"4"};
grouparr['3']={"id":"3","title":"\u52a9\u7406","rules":"sys_ban,adminlogin,wt_view,wt_re,user_kick,user_info,scpl_view,room_admin,rebots_msg,msg_send,msg_ptp,msg_priv,msg_block,msg_audit,jyts_view,hd_view,files_view","sn":"\u52a9\u7406\u91d1\u878d\u5206\u6790\u5e08","ico":"\/upload\/upfile\/day_150530\/201505301010256819.gif","type":"0","ov":"3"};
grouparr['2']={"id":"2","title":"\u7ba1\u7406","rules":"users_group,users_del,users_admin,tongji_reg,sys_server,sys_notice,sys_log,sys_base,sys_ban,apps_wt,apps_scpl,apps_manage,apps_jyts,apps_hd,apps_files,adminlogin,wt_view,wt_re,wt_add,user_kick,user_info_gl,user_info,scpl_view,scpl_add,room_admin,rebots_msg,msg_send,msg_ptp,msg_priv,msg_block,msg_audit,jyts_view,jyts_add,hd_view,hd_add,files_view,files_add,def_videosrc","sn":"\u7cfb\u7edf\u7ba1\u7406\u5458","ico":"\/upload\/upfile\/day_150609\/201506091905376720.png","type":"0","ov":"2"};
grouparr['0']={"id":"0","title":"\u6e38\u5ba2","rules":"wt_view,msg_send,msg_priv","sn":"\u672a\u77e5\u8bbf\u5ba2","ico":"\/uploads\/day_150530\/201505301011416492.png","type":"0","ov":"0"};
grouparr['1']={"id":"1","title":"\u8bd5\u7528\u4f1a\u5458 ","rules":"wt_view,wt_add,scpl_view,msg_send,jyts_view","sn":"\u6ce8\u518c\u7528\u6237","ico":"\/upload\/upfile\/day_150530\/201505301049413884.png","type":"0","ov":"0"};
grouparr['16']={"id":"16","title":"\u7d2b\u94bb\u4f1a\u5458","rules":"wt_view,wt_add,scpl_view,msg_send,jyts_view,hd_view,files_view","sn":"100\u4e07\u2264\u5b9e\u76d8\u5165\u91d1\uff1c500\u4e07 ","ico":"\/upload\/upfile\/day_150609\/201506091903586499.png","type":"0","ov":"0"};
grouparr['17']={"id":"17","title":"\u7687\u51a0\u4f1a\u5458","rules":"wt_view,wt_add,scpl_view,msg_send,jyts_view,hd_view,files_view","sn":"500\u4e07\u2264\u5b9e\u76d8\u5165\u91d1","ico":"\/upload\/upfile\/day_150609\/201506091904403677.gif","type":"0","ov":"0"};
var ReLoad;
var isIE=document.all;
var aSex=['<span class="sex-womon"></span>','<span class="sex-man"></span>',''];
var aColor=['#FFF','#FFF','#FFF'];
var msg_unallowable="黑平台|返佣|日返|高返佣|头寸|打包|手续费|刷单|套利|黑公司|QQ|私聊|群|加群|返佣|黑平台|代理|代客操盘|违规操作";
</script>
</head>
<body onresize="OnResize()" onUnload="OnUnload()" style="background:url(/uploads/day_150529/201505292232163512.jpg) repeat 0 0 #408080; ">
<div id="UI_MainBox" >
<script>if (!device.desktop()){window.location = './m';}</script>
  <div id="UI_Head">
    <div class="head" style=" background:#000" >
      <div id="head_box" class="head_box">
        <div class="logo_bg" style="BACKGROUND: url(/uploads/day_180814/201808141324549530.png) no-repeat"> <a href="<?php echo url('room/ico'); ?>" target="_blank" class="zmurl"><img src="/style/room/images/icon1.png">桌面快捷</a> &nbsp;&nbsp; <a href="javascript:void(0)" class="kefu" onClick="openWin(2,'客服列表','kefu',810,500)"><img src="/style/room/images/icon_qq.png">客服列表</a> </div>
        <div class="head_user">
                    <?php if($user): ?>
<a href="javascript:void(0)" class="userinfo" onClick="openWin(2,false,'profiles.php?uid=<?php echo $user->id; ?>',460,600)">
<img src="<?php echo url('/index/ajax/getFaceImg'); ?>?t=p1&u=<?php echo $user->id; ?>" border="0" class="userimg"/><?php echo $user->nickname; ?>▼</a>
<a href="mlogin/logout" class="userlogout">退出</a>
                    <?php else: ?>
 <a href="javascript:void(0)" class="reg" onClick="openWin(2,false,'/index/mlogin/?a=0',390,380)">注册</a> <a href="javascript:void(0)" class="login" onClick="openWin(2,false,'/index/mlogin/',390,310)">登录</a>
                    <?php endif; ?>
                  </div>
      </div>
    </div>
  </div>
  <div id="UI_Left">
    <div id="UI_Left1" class="bg_png1">

		<a href='javascript://' class='appico' onClick='openApp({"id":"6","title":"\u8d22\u7ecf\u65e5\u5386","ico":"\/upload\/upfile\/day_150430\/201504302308103419.png","url":"http:\/\/www.jin10.com\/jin10.com.html","w":"850","h":"630","target":"POPWin","s":"0","ov":"100"})' id='app_6'>
		<img src='/uploads/day_150430/201504302308103419.png' /><br>
		<span>财经日历</span>
		</a>

		<a href='javascript://' class='appico' onClick='openApp({"id":"1","title":"\u558a\u5355\u63d0\u9192","ico":"\/upload\/upfile\/day_150430\/201504302308191061.png","url":"\/apps\/hd.php","w":"1000","h":"600","target":"POPWin","s":"0","ov":"99"})' id='app_1'>
		<img src='/uploads/day_150430/201504302308191061.png' /><br>
		<span>喊单提醒</span>
		</a>

		<a href='javascript://' class='appico' onClick='openApp({"id":"2","title":"\u5728\u7ebf\u7b54\u7591","ico":"\/upload\/upfile\/day_150430\/201504302320344261.png","url":"\/apps\/wt.php","w":"1000","h":"600","target":"POPWin","s":"0","ov":"98"})' id='app_2'>
		<img src='/uploads/day_150430/201504302320344261.png' /><br>
		<span>在线答疑</span>
		</a>

		<a href='javascript://' class='appico' onClick='openApp({"id":"8","title":"\u8bfe\u7a0b\u8868","ico":"\/upload\/upfile\/day_150430\/201504302259205356.png","url":"\/upload\/upfile\/day_150530\/201505301141396060.png","w":"1000","h":"600","target":"QPWin","s":"0","ov":"94"})' id='app_8'>
		<img src='/uploads/day_150430/201504302259205356.png' /><br>
		<span>课程表</span>
		</a>

		<a href='javascript://' class='appico' onClick='openApp({"id":"5","title":"\u5171\u4eab\u6587\u6863","ico":"\/upload\/upfile\/day_150430\/201504302322063407.png","url":"\/apps\/files.php","w":"700","h":"500","target":"POPWin","s":"0","ov":"12"})' id='app_5'>
		<img src='/uploads/day_150430/201504302322063407.png' /><br>
		<span>共享文档</span>
		</a>

		<a href='javascript://' class='appico' onClick='openApp({"id":"4","title":"\u6838\u5fc3\u5185\u53c2","ico":"\/upload\/upfile\/day_150430\/201504302321202271.png","url":"\/apps\/scpl.php","w":"1000","h":"600","target":"POPWin","s":"0","ov":"11"})' id='app_4'>
		<img src='/uploads/day_150430/201504302321202271.png' /><br>
		<span>核心内参</span>
		</a>

		<a href='javascript://' class='appico' onClick='openApp({"id":"3","title":"\u8d8b\u52bf\u8ddf\u8e2a","ico":"\/upload\/upfile\/day_150430\/201504302308415495.png","url":"\/apps\/jyts.php","w":"1000","h":"600","target":"POPWin","s":"0","ov":"10"})' id='app_3'>
		<img src='/uploads/day_150430/201504302308415495.png' /><br>
		<span>趋势跟踪</span>
		</a>

		<a href='javascript://' class='appico' onClick='openApp({"id":"7","title":"\u8bb2\u5e08\u699c","ico":"\/upload\/upfile\/day_150430\/201504302303453733.png","url":"\/apps\/rank.php","w":"820","h":"600","target":"POPWin","s":"0","ov":"0"})' id='app_7'>
		<img src='/uploads/day_150430/201504302303453733.png' /><br>
		<span>讲师榜</span>
		</a>
		    </div>
    <div id="UI_Left2"  class="bg_png1">
    <div class="bg_png1">
        <iframe height="71" width="100%" allowTransparency="true" marginwidth="0" marginheight="0"  frameborder="0" scrolling="no" src="http://192.168.1.98/index/vote/?rid=1"></iframe>
      </div>
      <div  class="title_tab"> <a href="javascript:void(0)" class="bg_png2" onClick="bt_SwitchListTab('User')" id="listTab1">在线会员(<span id="OnlineUserNum"></span>)</a> <a href="javascript:void(0)" onClick="bt_SwitchListTab('Other')" id="listTab2">我的客服(<span id="OnlineOtherNum"></span>)</a> </div>
      <div style="clear:both"></div>
      <div id="OnlineUser_Find" style="height:25px; margin:0px; padding:2px; overflow:hidden; line-height:25px; border:1px solid #999" class="bg_png2">
        <input name="" type="image" title="找人" onClick="bt_FindUser()" src="/style/room/images/search.png" style="float:right; margin:5px;" />
        <input name="finduser" type="text" id="finduser"  style="border:0px; width:150px; height:25px; line-height:25px; padding:0px; background:none; color:#FFF; "/>
      </div>
      <div id="OnLineUser_OhterList" class="OnLineUser" style="height:50px;display:none" >
        <div id="group_myuser"></div>
      </div>
      <div id="OnLineUser_FindList" class="OnLineUser" style="height:50px;display:none" ></div>
      <div id="OnLineUser" class="OnLineUser"  style="height:50px;">
        <div id="group_my"></div>
        <div id='group_14'></div><div id='group_13'></div><div id='group_12'></div><div id='group_11'></div><div id='group_15'></div><div id='group_4'></div><div id='group_3'></div><div id='group_2'></div><div id='group_0'></div><div id='group_1'></div><div id='group_16'></div><div id='group_17'></div>        <div id="group_rebots"></div>
      </div>
      <div style="height:100px; background:#fff"><img src="/uploads/day_180814/201808141408256617.jpg" width=190></div>
    </div>

  </div>
  <div id="UI_Right" class="bg_png">
    <div id="RoomMV">
      <div class="title_bar">
		<span id="Div_VN1">直播视频</span> [<a href='javascript:showLive()'>刷新</a>]
		<span id="defvideosrc">当前讲师:系统管理员</span> <span id="bt_defvideosrc" style="display:none"> [<a href='javascript:bt_defvideosrc()'>上课</a>]</span>
		<a href="javascript://" onClick="openWin(2,'讲师榜','/apps/rank.php',820,600)" style="float:right; color:#FF0">《为讲师点赞》</a></div>
      <div id="OnLine_MV">
      <span style="font-size:18px">您还没有安装flash播放器,请点击<a href="http://www.adobe.com/go/getflash" target="_blank"  style="font-size:18px;color:#090">这里</a>安装</span>

      </div>
    </div>
    <div class="bg_png2 NoticeList">
            <div class="tab bg_png1">
          <a href='javascript:void(0)' id='notice_1' class='notice_tab'>活动公告</a>          <a href='javascript:void(0)' id='notice_12' class='notice_tab'>财运直播介绍</a>          <div style=" clear:both"></div>
        </div>
      <div id="NoticeList" style="height:50px;">
        <div id='notice_1_div' class='notice_div' style='display:none'><div style="height:250px; width:100%; overflow:hidden" id="gd_ad"><!--这里开始广告代码   其他勿动-->        <a href="javascript://" target="_blank"><img src="/uploads/day_150526/11111.png" width="100%" height="100%" alt="" /></a><a href="javascript://" target="_blank"><img src="/uploads/day_150526/201505261637226033.jpg" width="100%" height="100%" alt="" /></a>  <!--这里结束广告代码   其他勿动--></div>                                                     </div>        <div id='notice_12_div' class='notice_div' style='display:none'><span style="font-family:Microsoft YaHei;"><span style="font-size: 16px;">跟着老师不挣钱，80%以上都是贪心所致，另外20%是胆小</span></span></div>      </div>
<script>
	$('.tab a:first').addClass('active');
	$('.notice_div:first').css('display','');
	$('.tab a').on('click',function(){
		$('.tab a').removeClass('active');
		$(this).addClass('active');
		$('.notice_div').css('display','none');
		$('#'+$(this)[0].id+'_div').css('display','');
	});
	function SpreadSccroll()
	{
		setTimeout(function(){
		var $myul = $("#gd_ad");
		if($myul.find("a").length < 2)
		{
			return;
		}
		$actli=$myul.find("a:first");
		$actli.fadeToggle(function(){
			$myul.find("a:last").prependTo($myul);
			SpreadSccroll();
		});
		$actli.fadeToggle();
		},5000);
	}
	SpreadSccroll();
</script>
    </div>
  </div>
  <div id="UI_Central"  class="bg_png">
    <div class="title_bar">
    <marquee scrollamount="3" id="msg_tip_show">
    我是帅哥
    	    </marquee>
    </div>
    <div id="MsgBox" style="position:relative;">
      <div id="Video_List"></div>
      <div id="MsgBox1" style="overflow:auto; height:50px; padding:0px 10px 0px 10px;position:relative" >
      <?php echo $omsg; ?>
      </div>
       <div class="drag_skin" id="drag_bar" style=" display:none"></div>
      <div id="MsgBox2" style="height:100px; overflow:auto;  padding:0px 10px 0px 10px;position:relative; display:none" ></div>
    </div>
    <div id="UI_Control" class="tool_bar" >
    <div style="background:#FFF; height:30px; line-height:30px; overflow: hidden; font-size:14px;">
    <marquee scrollamount="3" id="msg_tip_admin_show">
    <span style="color:red">我是美女</span>
        </marquee>
    </div>
    <select id="ToUser">
          <option value="ALL">大家</option>
    </select>
    <span >

    	<a href="javascript:void(0)" class="bar_6 bar"  id="openPOPChat" style="float:right" >我的私聊</a>
        <a href="javascript:void(0)" class="bar_7 bar" id="bt_myimage" onClick="bt_FontBar(this)" >字体</a>
		<a href="javascript:void(0)" id="bt_face" class="bar_2 bar" onclick="showFacePanel(this,'#Msg');" isface="2">表情</a>
		<a href="javascript:void(0)" class="bar_3 bar" id="bt_caitiao">彩条</a>
		<a href="javascript:void(0)" class="bar_1 bar" id="bt_myimage" onclick="bt_insertImg('#Msg')">图片</a>
		<a href="javascript:void(0)" class="bar_4 bar" id="bt_qingping"  onClick="bt_MsgClear();">清屏</a>
		<a href="javascript:void(0)" class="bar_5 bar" id="bt_gundong" select="true"  onClick="bt_toggleScroll()" >滚动</a>
	</span>

        <!--  <input name="Personal" value="false" type="image" id="Personal" title="公聊/私聊" bt_Personal(this)" src="images/Personal_false.gif"/>-->
        <input  type="hidden" name="Personal" id="Personal" value="false" />
        <!--<input name="BTFont" type="image" id="BTFont" title="设置字体颜色和格式" onClick="bt_FontBar(this)" src="images/Font.gif"/>
        <input type="image" title="视频密聊" src="images/Vlove.gif" onclick="if(My.qx!=0||My.vip!=0)VideoList.Connect(ToUser.id,ToUser.name,0);else alert('你不是VIP用户！不能使用该功能');"/>

        <input type="image" title="声音提示" src="images/So.gif" id="toggleaudio" onClick="bt_toggleAudio();"/>-->
        <!--<input type="image" title="分屏" src="images/FP_false.gif" onClick="bt_fenping()" id="btfp"/>
        <input name="BTFont" type="image" id="BTFont" title="送礼物" onClick="bt_gifts(this)" src="images/lw.gif" />-->
    </div>
    <div style="position:relative; height:90px; overflow:hidden; padding-top:1px; background:#FFF">
      <div id="Msg" contentEditable="true" style="height:80px; overflow:auto; margin:5px;font-size:12pt; color:#000; padding-right:130px; line-height:40px;" onClick="HideMenu();"></div>
      <div style="text-align:right;top:-45px; left:100%;position:relative; margin-left:-115px; width:70px;">
        <input name="Send_bt" type="image" src="/style/room/images/send.png" id="Send_bt" title="发送消息" />
        <input type="hidden" name="Send_key" id="Send_key" value="1" />
        <!--
    <div style="text-align:right;top:-20px; left:100%;position:relative; margin-left:-77px; width:60px;"><input name="Send_bt" type="image" src="images/Send.gif" id="Send_bt" title="发送消息" onclick="SysSend.msg()"/><input name="Send_key" type="image" id="Send_key" src="images/Send_C.gif" / value="1" title="发送消息快捷键设置" onclick="bt_Send_key_option(this)">
    -->
      </div>
    </div>
    <div id="manage_div" style="background: #E1E1E1; margin:0px; color:#333; height:25px; line-height:23px; display:none; padding-left:5px;">
      <select id="chat_type" style="display:none">
        <option value="me" selected>发言人-自己</option>
        <option value="he" title="当前聊天">发言人-他人</option>
      </select>
      <label>
      <input type="checkbox" id="msg_tip">置顶公告</label>
      <input type="checkbox" id="msg_tip_admin">管理提示</label>
    </div>
  </div>
</div>
</div>
<div id="FontBar" style="display:none">
  <select name="FontName" id="FontName" onChange="getId('Msg').style.fontFamily=this[this.selectedIndex].value">
    <option selected="selected">字体</option>
    <option value="SimSun" style="font-family: SimSun">宋体</option>
    <option value="SimHei" style="font-family: SimHei">黑体</option>
    <option value="KaiTi_GB2312" style="font-family: KaiTi_GB2312">楷体</option>
    <option value="FangSong_GB23122" style="font-family:FangSong_GB2312">仿宋</option>
    <option value="Microsoft YaHei" style="font-family: Microsoft YaHei">微软雅黑</option>
    <option value="Arial">Arial</option>
    <option value="MS Sans Serif">MS Sans Serif</option>
    <option value="Wingdings">Wingdings</option>
  </select>
  <select name="FontSize"  id="FontSize" onChange="getId('Msg').style.fontSize=this[this.selectedIndex].value+'pt'">
    <option value="8">8</option>
    <option value="9">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12"  selected="selected">12</option>
    <option value="13">13</option>
    <option value="14">14</option>
  </select>
  <input type="image" class="bt_false" title="粗体" onMouseOver="this.className='bt_true'" onMouseOut="if(this.value=='false')this.className='bt_false'" src="/style/room/images/bold.gif" onClick="ck_Font(this,'FontBold')" value="false"/>
  <input type="image" class="bt_false" title="斜体" onMouseOver="this.className='bt_true'" onMouseOut="if(this.value=='false')this.className='bt_false'" src="/style/room/images/Italic.gif" onClick="ck_Font(this,'FontItalic')" value="false"/>
  <input type="image" class="bt_false" title="下划线" onMouseOver="this.className='bt_true'" onMouseOut="if(this.value=='false')this.className='bt_false'" src="/style/room/images/underline.gif" onClick="ck_Font(this,'Fontunderline')" value="false"/>
  <input name="FontColor" type="image" class="bt_false" id="FontColor" title="文字颜色" onMouseOver="this.className='bt_true'" onMouseOut="this.className='bt_false'" src="/style/room/images/color.gif" onClick="ck_Font(this,'ShowColorPicker');" value="false"/>
</div>
<div id='ColorTable' style="display:none; " onblur="BrdBlur('ColorTable');" tabIndex></div>
<div id="Smileys" style="display:none; height:180px;" onblur="BrdBlur('Smileys');" tabIndex></div>
<div id="Send_key_option" style="display:none" onblur="BrdBlur('Send_key_option');" tabIndex>
  <div onMouseOver="this.className='bt_true'" onMouseOut="this.className='bt_false'" style="padding-left:20px; height:20px; line-height:20px;" class="bt_false" onClick="$('Send_key').value='1';$('Send_key_option').style.display='none'">按 Enter 键发送消息</div>
  <div onMouseOver="this.className='bt_true'" onMouseOut="this.className='bt_false'" style="padding-left:20px; height:20px; line-height:20px;" class="bt_false" onClick="$('Send_key').value='2';$('Send_key_option').style.display='none'">按 Ctrl+Enter 键发送消息</div>
</div>

</div>
<div style="position:absolute; left: -100px;" id="MsgSound"></div>
<div id="face" style="position:absolute; display:none"></div>
<div id="caitiao" class="hid ption_a"></div>
<form id="imgUpload" name="imgUpload" action="" method="post" enctype="multipart/form-data" target="e">
<input type="hidden" name="info" id="imgUptag" value="#Msg">
<input id="filedata" contenteditable="false" type="file" style="display:none;" onchange="$('#imgUpload').attr('action','../upload/upload_frame.php?act=InsertImg&' + new Date().getTime() );document.imgUpload.submit();" name="filedata">
</form>
<iframe name="e" id="e" style="display:none"></iframe>
<div id="tip_login_win" style="display:none">
<div>     <div class="ad mt10 tc"><a href="#" target="_blank"><img src="/uploads/day_150601/300.png" alt="" /></a></div></div></div>
</body>
</html>
<script>OnInit();</script>
<div style="display:none">
  </div>