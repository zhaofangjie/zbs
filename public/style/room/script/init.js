//var e=getEvent();
//var jq = jQuery.noConflict();
//window.onbeforeunload=function(){ws.send("Logout");}

function getId(id)
{
	return document.getElementById(id);
}
function Datetime(tag)
{
	return new Date().toTimeString().split(' ')[tag];
}
function SetChatValue(Variables,value)
{
	ChatValue[Variables]=value;
}
function GetChatValue(Variables)
{
	return ChatValue[Variables];
}

var ChatValue =new Array(10);
function SwfloadCompleted(tag)
{
	if(tag=='Video_main')
	{
		VideoLoaded=true;
		if(RoomInfo.type=='0')
		{
			getId('RoomMV').style.display='none';

		}
		else
		{
			if(RoomInfo.AutoPublicVideo=='1'){
				if(RoomInfo.PVideo==My.chatid)
				thisMovie('pVideo').sConnect(RoomInfo.VServer,My.rid+'·'+My.chatid,'1');
				else
				thisMovie('pVideo').pConnect(RoomInfo.VServer,My.rid+'·'+RoomInfo.PVideo,RoomInfo.PVideoNick);
			}
			if(RoomInfo.AutoSelfVideo=='1'){
				if(RoomInfo.PVideo==My.chatid)
				thisMovie('pVideo').sConnect(RoomInfo.VServer,My.rid+'·'+My.chatid,'1');
				else
				thisMovie('pVideo').sConnect(RoomInfo.VServer,My.rid+'·'+My.chatid,'2');
			}
		}
	}
}
function showLive(){
	if(RoomInfo.OtherVideoAutoPlayer=='0'){location.reload();}
	$('#OnLine_MV').html($('#OnLine_MV').html());
}
var ws;
var page_fire;
function OnSocket(){
	ws=new WebSocket("ws://"+RoomInfo.TServer+":"+RoomInfo.TSPort);
	ws.onopen=onConnect;
	ws.onmessage=function(e){WriteMessage(e.data)};
	ws.onclose=function(){setTimeout('location.reload()',3000);};
	ws.onerror=function(){setTimeout('location.reload()',3000);};
}
function OnInit()
{
	if($.browser.msie&&($.browser.version == "6.0")&&!$.support.style)
	{
		location.href='error.php?msg='+encodeURIComponent('您使用的是不安全IE6.0浏览器,请升级到最新版本或<br>下载安装<a href=http://chrome.360.cn/ target=_blank>360浏览器</a>或<a href=http://www.baidu.com/s?wd=chrome target=_blank>Google浏览器</a>!');
		return false;
	}
	$.ajaxSetup({ contentType: "application/x-www-form-urlencoded; charset=utf-8"});

	$("body").click(function() { MsgCAlert();});
	//auth
	if(check_auth("room_admin"))$('#manage_div').show();
	if(check_auth("rebots_msg"))$('#chat_type').show();
	if(check_auth("def_videosrc"))$('#bt_defvideosrc').show();
	OnSocket();
	//5分钟提示登录
	if(RoomInfo.loginTip=='1'&&My.chatid.indexOf('f')>-1)
	setInterval("loginTip()",1000*60*5);

	$('#Msg').html("连接中...");


	window.moveTo(0,0);
	window.resizeTo(screen.availWidth,screen.availHeight);
	OnResize();

	if(RoomInfo.OtherVideoAutoPlayer!="0"){
		$('#OnLine_MV').html('<iframe height="100%" width="100%" allowTransparency="true" marginwidth="0" marginheight="0"  frameborder="0" scrolling="no" src="/index/play?type=pc"></iframe>');
	}else{
		var vFlash = new SWFObject("images/Video_main.swf?Q="+RoomInfo.VideoQ, "pVideo", "520", "390", "9", "#FFF");
		vFlash.addParam("wmode","transparent");
		vFlash.addParam("allowFullScreen","true");
		vFlash.addParam("allowScriptAccess","always");
		vFlash.write("OnLine_MV");
	}

	interfaceInit();
	POPChat.Init();
	ToUser.set('ALL','大家');
	dragMsgWinx(getId('drag_bar'));
	$('#MsgBox1').niceScroll({cursorcolor:"#FFF",cursorwidth:"2px",cursorborder:"0px;"});
	$('#MsgBox2').niceScroll({cursorcolor:"#FFF",cursorwidth:"2px",cursorborder:"0px;"});
	$('#OnLineUser_FindList').niceScroll({cursorcolor:"#FFF",cursorwidth:"2px",cursorborder:"0px;"});
	$('#OnLineUser_OhterList').niceScroll({cursorcolor:"#FFF",cursorwidth:"2px",cursorborder:"0px;"});
	$('#OnLineUser').niceScroll({cursorcolor:"#FFF",cursorwidth:"2px",cursorborder:"0px;"});
	$('#NoticeList').niceScroll({cursorcolor:"#FFF",cursorwidth:"2px",cursorborder:"0px;"});
	$("#Msg").keydown(function(e){if(e.keyCode==13){ToUser.set($("#ToUser").val(),$("#ToUser").find("option:selected").text());SysSend.msg();HideMenu();MsgCAlert();return false}});
	$("#Send_bt").on("click",function(){ToUser.set($("#ToUser").val(),$("#ToUser").find("option:selected").text());HideMenu();MsgCAlert();SysSend.msg();});
	initFaceColobar();

	openWin(1,false,$("#tip_login_win").html(),800,350);
	//setTimeout("bt_SwitchListTab('Other')",1000)
}
function OnResize(){

	var cw=$(window).width();
	var vw=cw-950;

	if(cw>=1280){
		if(cw>=1280)vw=cw-745;
		if(cw>=1400)vw=cw-820;
		if(cw>=1600)vw=cw-950;
		if(vw>520){
			$('#UI_Central').css("margin-left",242+vw+10);
			$('#UI_Right').css("width",vw);
			$('#OnLine_MV').css("height",vw/16*9);
		}

	}else {
		$('#UI_Central').css("margin-left","772px");
		$('#UI_Right').css("width",520);
		$('#OnLine_MV').css("height",520/16*9);
	}

	var cH=$(window).height()-13;
	$('#UI_MainBox').height(cH);
	$('#MsgBox1').height($('#MsgBox1').height()+cH-$('#UI_Central').height()-$('#UI_Head').height());

	$('#OnLineUser').height($('#OnLineUser').height()+$('#UI_Central').height()-$('#UI_Left2').height());
	$('#UI_Left1').height($('#UI_Left2').height());
	$('#NoticeList').height($('#NoticeList').height()+$('#UI_Central').height()-$('#UI_Right').height());

	$("#OnLineUser_OhterList").height($('#OnLineUser').height());
	$("#OnLineUser_FindList").height($('#OnLineUser').height());
}
function OnUnload(){
	var str="Logout=M=";
	ws.send(str);

}
function tCam(tag)
{
	My.cam=tag;
}
function tCamState(tag)
{
	My.camState=tag;
	//alert(tag);
}
function onConnect()
{
	setInterval("online('<?=$time?>')",10000);
	getId('Msg').innerHTML="";
	var str='Login=M='+My.roomid+'|'+My.chatid+'|'+My.nick+'|'+My.sex+'|'+My.age+'|'+My.qx+'|'+My.ip+'|'+My.vip+'|'+My.color+'|'+My.cam+'|'+My.state+'|'+My.mood;
	ws.send(str);
	if(typeof(UserList)!='undefined'){
		UserList.init();
	}
	//bt_fenping();
}


function h_l(e)
{
	if(getId('UI_Left').style.display=='')
	{
		getId('UI_Left').style.display='none';
		getId('UI_Central').style.marginLeft='2px';
		e.src="images/h_r.gif";
	}
	else
	{
		getId('UI_Left').style.display='';
		getId('UI_Central').style.marginLeft='157px';
		e.src="images/h_l.gif";
	}
	getId('FontBar').style.display='none';
}
function h_r(e)
{
	if(getId('UI_Right').style.display=='')
	{
		getId('UI_Right').style.display='none';
		getId('UI_Central').style.marginRight='2px';
		e.src="images/h_l.gif";
	}
	else
	{
		getId('UI_Right').style.display='';
		getId('UI_Central').style.marginRight='248px';
		e.src="images/h_r.gif";
	}
	getId('FontBar').style.display='none';
}
function getXY(obj)
{
var a = new Array();
var t = obj.offsetTop;
var l = obj.offsetLeft;
var w = obj.offsetWidth;
var h = obj.offsetHeight;
while(obj=obj.offsetParent)
{ t+=obj.offsetTop; l+=obj.offsetLeft; }
a[0] = t; a[1] = l; a[2] = w; a[3] = h; return a;
}


function CloseColorPicker()
{
	getId('ColorTable').style.display='none'
}


function ck_Font(e,act)
{
	if(e!=null)
	{
	e.value=='true'?e.value='false':e.value='true';
	}
	switch(act)
	{
		case 'FontBold':
			if(e.value=='true')getId('Msg').style.fontWeight='bold';
			else getId('Msg').style.fontWeight='';
		break;
		case "FontItalic":
			if(e.value=='true')getId('Msg').style.fontStyle='italic';
			else getId('Msg').style.fontStyle='';
		break;
		case 'Fontunderline':
			if(e.value=='true')getId('Msg').style.textDecoration='underline';
			else getId('Msg').style.textDecoration='';
		break;
		case 'FontColor':
			getId('Msg').style.color=getId('ColorShow').style.backgroundColor;
		break;
		case 'ShowColorPicker':
			bt_ColorPicker();
		break;
	}
}
function ColorPicker()
{
  	  var baseColor=new Array(6);
      baseColor[0]="00";
      baseColor[1]="33";
      baseColor[2]="66";
      baseColor[3]="99";
      baseColor[4]="CC";
      baseColor[5]="FF";
      var   myColor;
      myColor="";
      var   myHTML="";
      myHTML=myHTML+"<div style='WIDTH:180px;HEIGHT:120px;' onclick='ck_Font(null,\"FontColor\");CloseColorPicker()'>";
      for(i=0;i<6;i++)
      {
              for(j=0;j<3;j++)
                {     for(k=0;k<6;k++)
                      {
                          myColor=baseColor[j]+baseColor[k]+baseColor[i];
                          myHTML=myHTML+"<li data="+myColor+" onmousemove=\"document.getElementById('ColorShow').style.backgroundColor=this.style.backgroundColor\" style='background-color:#"+myColor+"'></li>";
                      }
                    }

      }
      for(i=0;i<6;i++)
      {
              for(j=3;j<6;j++)
                {   for(k=0;k<6;k++)
                      {
                          myColor=baseColor[j]+baseColor[k]+baseColor[i];//get   Color
                          myHTML=myHTML+"<li data="+myColor+" onmousemove=\"document.getElementById('ColorShow').style.backgroundColor=this.style.backgroundColor\" style='background-color:#"+myColor+"'></li>";
                      }
                  }
      }

      myHTML=myHTML+"</div><div style='border:0px; width:180px; height:10px; background:#333333' id='ColorShow'></div>";
      document.getElementById("ColorTable").innerHTML=myHTML;
}
var ColorInit=false;
function bt_ColorPicker()
{
	var t=getXY(getId('FontColor'));
	getId('ColorTable').style.top=t[0]-145+'px';
	getId('ColorTable').style.left=t[1]+1+'px';
	if(!ColorInit)
	{
	ColorPicker();
	ColorInit=true;
	}
	getId('ColorTable').style.display='';
	getId('ColorTable').focus();
	return true;

}

function bt_Personal(e)
{
	if(e.value=='false')
	{
		e.value='true';
		e.src="images/Personal_true.gif";
		e.title='私聊中...[公聊/私聊]';
	}
	else
	{
		e.value='false';
		e.src="images/Personal_false.gif";
		e.title='公聊中...[公聊/私聊]';
	}
}
function bt_FontBar(e)
{
	if(getId('FontBar').style.display=='none')
	{
		var t=getXY(getId('UI_Control'));
		getId('FontBar').style.display='';
		getId('FontBar').style.top=t[0]-29+'px';
		getId('FontBar').style.left=isIE?t[1]+1:t[1]+'px';
		getId('FontBar').style.width=t[2]-8+'px';
	}
	else
	{
		getId('FontBar').style.display='none';
	}
}
function bt_Send_key_option(e)
{
	var t=getXY(e);
	getId('Send_key_option').style.top=t[0]-50+'px';
	getId('Send_key_option').style.left=t[1]+2-165+'px';
	getId('Send_key_option').style.display='';
	getId('Send_key_option').focus();
}



function InsertImg(id,src){
	$(id).append('<img src=\"'+src+'\">');
}
function bt_insertImg(id)
{
	$('#imgUptag').val(id);
	$('#filedata').click();
}
function bt_gifts(e){
	openWithIframe('送礼物','../glist_room.php',300,405,'',true)
}
function bt_MsgClear(){
	getId('MsgBox1').innerHTML = '';
	getId('MsgBox2').innerHTML = '';
}
function bt_SendEmote(obj){
	getId('Msg').innerHTML=obj.innerHTML;SysSend.msg();
	getId('Emote').style.display='none';
}
function bt_SwitchListTab(tag){

	if(tag=='User'){
		$("#OnLineUser_OhterList").hide();
		$("#OnLineUser_FindList").hide();
		$("#OnLineUser").show();
		$("#listTab1")[0].className='bg_png2';
		$("#listTab2")[0].className='';
	}
	else if(tag=="Other"){
		$("#OnLineUser_OhterList").show();
		$("#OnLineUser_FindList").hide();
		$("#OnLineUser").hide();
		$("#listTab1")[0].className='';
		$("#listTab2")[0].className='bg_png2';
		UserList.getmylist(My.name);
	}
	return false;
}
function bt_defvideosrc(){
	if(check_auth('def_videosrc')){
		SysSend.command('setVideoSrc',My.chatid+'_+_'+My.nick);
		$.ajax({type: 'get',url: '/index/ajax/setdefvideosrc?vid='+encodeURIComponent(My.chatid)+'&nick='+encodeURIComponent(My.nick)+'&rid='+My.rid});
	}
}
function bt_msgBlock(id){
		SysSend.command('msgBlock',id);
}
function bt_msgAudit(id,a){
		SysSend.command('msgAudit',id);
		$(a).hide();
}
function bt_FindUser(){
	getId("OnLineUser_OhterList").style.display="none";
	var username=getId('finduser').value;
	getId("OnLineUser_FindList").style.display="none";
	getId("OnLineUser").style.display="";
	//alert(username);
	if(username==""){
		getId("OnLineUser_FindList").style.display="none";

		getId("OnLineUser").style.display="";
	}
	else{
		getId("OnLineUser_FindList").style.display="";
		getId("OnLineUser_FindList").innerHTML="";
		getId("OnLineUser_FindList").style.height=getId("OnLineUser").offsetHeight +'px';
		getId("OnLineUser").style.display="none";

		var ulist=UserList.List();
		var li="";
		for(c in ulist){
			if(ulist[c].nick.toLowerCase().indexOf(username.toLowerCase())>=0){
				//alert(ulist[c].nick);
				li=getId(ulist[c].chatid);
				var t_li=CreateElm(getId("OnLineUser_FindList"),'li',false,'fn'+ulist[c].chatid);
				t_li.innerHTML=li.innerHTML;
				t_li.oncontextmenu=li.oncontextmenu;
				t_li.onclick=li.onclick;
				t_li.ondblclick=li.ondblclick;
			}
		}
	}
}
var fenping = true;

function bt_fenping(){
	if(fenping == true) {
      fenping = false;
      //getId('btfp').src = 'images/FP_true.gif';
	  getId('drag_bar').style.display="";
	  getId('MsgBox2').style.display="";
	  getId('MsgBox2').style.height=100+'px';
	  getId('MsgBox1').style.height=getId('MsgBox1').offsetHeight-100-getId('drag_bar').offsetHeight+"px";
   } else {
      fenping = true;
      //getId('btfp').src = 'images/FP_false.gif';
	  getId('MsgBox1').style.height=getId('MsgBox').offsetHeight+"px";
	  getId('drag_bar').style.display="none";
	  getId('MsgBox2').style.display="none";

   }
   MsgAutoScroll();
}
var audioNotify=true;
function bt_toggleAudio() {
   if(audioNotify == true) {
      audioNotify = false;
      getId('toggleaudio').src = 'images/Sc.gif';
   } else {
      audioNotify = true;
      getId('toggleaudio').src = 'images/So.gif';
   }
}
var toggleScroll = true;
function bt_toggleScroll()
{
	if($("#bt_gundong").attr("select")=="true"){
		$("#bt_gundong").attr("select","false");
		toggleScroll = false;
	}
	else {
		$("#bt_gundong").attr("select","true");
		toggleScroll = true;
	}
}
