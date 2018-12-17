
function thisMovie(movieName)
{
	if (navigator.appName.indexOf("Microsoft") != -1)
		return window[movieName];
	else
		return document[movieName];
}
var t=0;
function Auto()
{
	ws.send('SPing:');
}
function XHConn() {
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
  	    try {
  		    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  	    } catch (e) {
  		    try {
  			    xmlhttp = new XMLHttpRequest();
  		    } catch (e) {
  			    xmlhttp = false;
  		    }
  	    }
    }

    return xmlhttp;
}

function interfaceInit()
{
//setInterval('Auto()',3000);
POPChat=(function(){
	var list=[];
	var user=null;
	var win=null;
	return{
		Init:function(){
			var html = '<div class="layim_chatbox" id="layim_chatbox">'
            +'<h6>'
            +'<span class="layim_move"></span>'
            +'    <a href="javascript:void(0)" class="layim_face" target="_blank"><img src="" ></a>'
            +'    <a href="javascript:void(0)" class="layim_names" target="_blank">聊天窗口</a>'
            +'    <a href="javascript:void(0)" class="layim_qq" target="_blank"></a>'
            +'    <span class="layim_rightbtn">'
            +'        <!--<i class="layer_setmin"></i>-->'
            +'        <i class="layim_close"></i>'
            +'    </span>'
            +'</h6>'
            +'<div class="layim_chatmore" id="layim_chatmore">'
            +'    <ul class="layim_chatlist"></ul>'
            +'</div>'
            +'<div class="layim_groups" id="layim_groups"></div>'
            +'<div class="layim_chat">'
            +'    <div class="layim_chatarea" id="layim_chatarea">'
            +'        <ul class="layim_chatview layim_chatthis"  id="layim_area"></ul>'
            +'    </div>'
            +'    <div class="layim_tool">'
            +'        <i class="layim_addface" title="发送表情" onclick="showFacePanel(this,\'#layim_write\');"onclick="showFacePanel(this,\'#layim_write\');"></i>'
            +'        <a href="javascript:;"><i class="layim_addimage" title="发送图片" onclick="bt_insertImg(\'#layim_write\')" ></i></a>'
            +'        <!--<a href="javascript:;"><i class="layim_addfile" title="上传附件"></i></a>-->'
            +'        <!--<a href="" target="_blank" class="layim_seechatlog"><i></i>聊天记录</a>-->'
            +'    </div>'
            +'    <div class="layim_write" id="layim_write" contentEditable="true" ></div>'
            +'    <div class="layim_send">'
            +'        <div class="layim_sendbtn" id="layim_sendbtn">发送<!--<span class="layim_enter" id="layim_enter"><em class="layim_zero"></em></span>--></div>'
            +'        <div class="layim_sendtype" id="layim_sendtype">'
            +'            <span><i>√</i>按Enter键发送</span>'
            +'            <span><i></i>按Ctrl+Enter键发送</span>'
            +'        </div>'
            +'    </div>'
            +'</div>'
            +'</div>';
		layer.open({
    	type: 1,
		shade: false,
		area: ['620px', '493px'],
        move: '.layim_chatbox .layim_move',
        title: false,
		closeBtn: false,
    	content: html,
		success: function(layero){

				win=layero;

                $('.layim_close').on('click', function(){
					  	layero.hide();
        		});
				$('#openPOPChat').on('click', function(){
					  	layero.show();
        		});
				win.find('#layim_chatmore').on('click', 'li em', function(){
						user=null;
						$("#layim_user"+$(this).attr('data-id')).remove();
						$("#layim_area"+$(this).attr('data-id')).remove();
						var find_li=win.find('.layim_chatlist li');

						if(find_li.length>0){
								var li=find_li.first();
								POPChat.showtab({chatid:li.attr('data-id'),nick:li.attr('data-nick')});
						}
						return false;

        		});
				win.find('#layim_chatmore').on('click', 'li', function(){
            			var othis = $(this);
           				POPChat.showtab({chatid:othis.attr('data-id'),nick:othis.attr('data-nick')});
        		});



				win.find("#layim_sendbtn").on('click', POPChat.send);
				win.find("#layim_write").keyup(function(e){
					if(e.keyCode === 13){
						POPChat.send();
						return false;
					}
				});
				layero.hide();
            }

		});

		},
		send:function(){
			if(user==null) return;
			var toUserInfo=UserList.get(user.chatid);
			var msg=encodeURIComponent($("#layim_write").html().str_replace().replace("<br>",""));
			PutMessage(My.rid,My.chatid,user.chatid,My.nick,user.nick,'true','',msg,'');

			if(typeof(toUserInfo)=="undefined" || user.chatid.indexOf('x_r')>-1){
				POPChat.showmsg(My,user,msg);
				win.find("#layim_write").html("");
				MsgCAlert();
				alert('用户离线,消息转存到历史纪录！');
				return;
			}

			var str='SendMsg=M='+user.chatid+'|true|color:#000|'+msg;
			ws.send(str);
			win.find("#layim_write").html("");
			win.find("#layim_write").focus();
			MsgCAlert();
		},
		newtab:function(u){
			var layim_chatmore = win.find('#layim_chatmore');
        	var layim_chatarea = win.find('#layim_chatarea');

			if(win.find('#layim_user'+u.chatid).length<1){
				layim_chatmore.find('ul>li').removeClass('layim_chatnow');
       			layim_chatmore.find('ul').append('<li data-qq="" data-id="'+ u.chatid +'" data-nick="'+u.nick+'" id="layim_user' +u.chatid +'" class="layim_chatnow"><span><b class="layim_msgnum">0</b>'+ u.nick +'</span><em  data-id="'+ u.chatid +'">×</em></li>');
				layim_chatarea.find('.layim_chatview').removeClass('layim_chatthis');
        		layim_chatarea.append('<ul class="layim_chatview layim_chatthis" id="layim_area'+ u.chatid +'"></ul>');

				$.ajax({type:'GET',dataType:'JSON',url:'ajax/mymsgold?tuid='+u.chatid,
				success:function(data){
					$("#layim_area"+ data.tuid).prepend(data.msg);

					$("#layim_user"+data.tuid).attr('data-qq',data.qq);
					win.find('.layim_qq').html("<span><a href='http://wpa.qq.com/msgrd?v=3&uin="+data.qq+"&site=qq&menu=yes' target='_blank'><img src='/style/room/images/icon_qq.png' border=0>"+data.qq+"</a></span>");
					if(data.qq!=null &&data.qq!='0'){
						win.find('.layim_names').css("line-height",'20px');
						win.find('.layim_qq').show();
					}else{
						win.find('.layim_names').css("line-height",'40px');
						win.find('.layim_qq').hide();
					}

					u=UserList.get(u.chatid)
					if(u!=undefined&&data.kfmsg!=""&&data.kfmsg!='null'){
						var str='<li class="layim_chatehe"><div class="layim_chatuser"><img src="ajax/getFaceImg/?t=p1&u='+u.chatid+'"><span class="layim_chatname">'+u.nick+'</span><span class="layim_chattime">'+Datetime(0)+'</span></div><div class="layim_chatsay"><font style="color:#000">'+data.kfmsg+'</font><em class="layim_zero"></em></div></li>';
						$("#layim_area"+ u.chatid).append(str);
					}
					win.find('#layim_area'+ data.tuid).scrollTop(win.find('#layim_area'+ data.tuid)[0].scrollHeight);
				}});


			}


			win.show();
			if(layim_chatmore.find('li').length<2){

				POPChat.showtab(u);
			}
		},
		showtab:function(u){
			user=u;
			var layim_chatmore = win.find('#layim_chatmore');
        	var layim_chatarea = win.find('#layim_chatarea');

			layim_chatmore.find('ul>li').removeClass('layim_chatnow');
			layim_chatarea.find('.layim_chatview').removeClass('layim_chatthis');

			win.find('#layim_user'+u.chatid).addClass('layim_chatnow');
			win.find('#layim_area'+u.chatid).addClass('layim_chatthis');


			win.find('.layim_chatnow .layim_msgnum').text("0");
			win.find('.layim_chatnow .layim_msgnum').hide();

			win.find('.layim_face>img').attr('src', 'ajax/getFaceImg/?t=p1&u='+u.chatid);
    		win.find('.layim_names').text(u.nick);

			win.show();
			win.find('#layim_area'+ u.chatid).scrollTop(win.find('#layim_area'+ u.chatid)[0].scrollHeight);

			win.find('.layim_qq').html("<span><a href='http://wpa.qq.com/msgrd?v=3&uin="+$("#layim_user"+u.chatid).attr('data-qq')+"&site=qq&menu=yes'  target='_blank'><img src='/style/room/images/icon_qq.png' border=0>"+$("#layim_user"+u.chatid).attr('data-qq')+"</a>");

			if($("#layim_user"+u.chatid).attr('data-qq')!=null && $("#layim_user"+u.chatid).attr('data-qq')!='0'){
						win.find('.layim_names').css("line-height",'20px');
						win.find('.layim_qq').show();
					}else{
						win.find('.layim_names').css("line-height",'40px');
						win.find('.layim_qq').hide();
			}
		},
		showmsg:function(u,u1,str){
			if(user.chatid!=u.chatid&&u.chatid!=My.chatid){
				win.find('#layim_user'+u.chatid+' .layim_msgnum').show();
				win.find('#layim_user'+u.chatid+' .layim_msgnum').text(Number(win.find('#layim_user'+u.chatid+' .layim_msgnum').text())+1+"");
			}
			var log = {};
			if(u.chatid==My.chatid)
				log.imarea = win.find('#layim_area'+ u1.chatid);
			else
				log.imarea = win.find('#layim_area'+ u.chatid);
			log.html = function(param, type){
                return '<li class="'+ (type === 'me' ? 'layim_chateme' : ' layim_chatehe') +'">'
                    +'<div class="layim_chatuser">'
                        + function(){
                            if(type === 'me'){
                                return '<span class="layim_chattime">'+ param.time +'</span>'
                                       +'<span class="layim_chatname">'+ param.name +'</span>'
                                       +'<img src="'+ param.face +'" >';
                            } else {
                                return '<img src="'+ param.face +'" >'
                                       +'<span class="layim_chatname">'+ param.name +'</span>'
                                       +'<span class="layim_chattime">'+ param.time +'</span>';
                            }
                        }()
                    +'</div>'
                    +'<div class="layim_chatsay">'+ param.content +'<em class="layim_zero"></em></div>'
                +'</li>';
            };
			log.imarea.append(log.html({
                time: Datetime(0),
                name: u.nick,
                face: 'ajax/getFaceImg/?t=p1&u='+u.chatid,
                content: str
            },u.chatid==My.chatid?'me':""));
			log.imarea.scrollTop(log.imarea[0].scrollHeight);
			//if(win.find('.layim_chatnow').attr('data-id'))
		}
	}
})();

UserList=(function(){
	var list=[];
	var OnLineUser=getId('OnLineUser');
	var OnlineUserNum=getId('OnlineUserNum');
	var OnlineOtherNum=getId('OnlineOtherNum');
	var PInfo=CreateElm(false,'div','','PInfo');
	var hold = 0;
	var show = 0;
	var onlinuser=0;
	var onlinmyuser=0;
	var def_list=$('#OnLineUser').html();
	list['ALL']={sex:2,chatid:'ALL',nick:'大家'}
	return{
		List:function(){return list},
		init:function(){
			list=[];
			//OnLineUser.innerHTML='';
			OnlineUserNum.innerHTML='';
			list['ALL']={sex:2,chatid:'ALL',nick:'大家'}
			//UserList.add(My.chatid,My);
						//获取rebots在线列表
			var request_url='ajax/getrlist/?rid='+My.rid+'&r='+RoomInfo.r+'&'+Math.random() * 10000;
			var xmlhttp=XHConn();
				try{
				xmlhttp.open("GET",request_url,true);
				xmlhttp.send(null);
				xmlhttp.onreadystatechange=function()
					{
						if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
						{
							WriteMessage(xmlhttp.responseText);
							//在线客服
							UserList.getmylist(My.name);

						}
					}
				}
				catch(e){return null;}
			},
		get:function(id){return list[id];},
		getmylist:function(user){

			$.ajax({type: 'get',dataType:'json',url: 'ajax/getmylist?rid='+My.rid+'&user='+encodeURIComponent(My.name),
			success:function(data){
				//alert(data);
				addmyuser=function(u){
					var li=CreateElm1(ref,'li',false,'myuser'+u.chatid,null);
					var vip_ico="<img src='"+grouparr[u.gid].ico+"'  align='top'/ title='"+grouparr[u.gid].title+'-'+grouparr[u.gid].sn+"'>";
					var iscam='<span class="vipico">'+vip_ico+'</span>';

					li.innerHTML='<a href="javascript:void(0)"><font style="color:#FFF">'
					 +iscam
					 +'<cite><img src="ajax/getFaceImg/?t=p1&u='+u.chatid+'" border="0" class="head" /></cite>'
					 +'<dt><strong id="cnick_'+u.chatid+'">'+u.nick+'</strong> &nbsp;<code> </code></dt>'
					 +'<dl> </dl>'
					 +'</font></a>';
					if(u.gid=='3'){
						li.innerHTML=li.innerHTML+'<p class="bts"><input type="button" value="私聊" class="osl"><input type="button" value="QQ对话" class="qsl"></p>';
						li.innerHTML=li.innerHTML+'<p class="sn bg_png1">客服介绍: '+u.mood+'</p>';
					}
					li.oncontextmenu=function(){UserList.menu_kf(u);return false;}
					li.onclick=function(){ToUser.set(u.chatid,u.nick);openWin(2,false,'room/profile/?uid='+u.chatid,460,600);}
					//li.ondblclick=function(){if(u.chatid!=My.chatid||u.chatid.indexOf('x_r')<0){POPChat.newtab(u);POPChat.showtab(u);}}
					$(li).find('.osl').click(function(){if(u.chatid!=My.chatid||u.chatid.indexOf('x_r')<0){POPChat.newtab(u);POPChat.showtab(u);}return false;});
					$(li).find('.qsl').click(function(){window.open('http://wpa.qq.com/msgrd?v=3&uin='+u.qq+'&site=qq&menu=yes');return false;});
					if(UserList.get(u.chatid)==undefined){$(li).addClass('gray');}
				}

				var ref=getId("group_myuser");
				$('#group_myuser').html('');
				onlinmyuser=0;
				if(data.state=='true'){
					for(var key in data.row){

					var u=data.row[key];
					if($("#myuser"+u.chatid)[0]==undefined){
					onlinmyuser++;
					addmyuser(u);
					}
					}
				}

				//未分配随机选一个在线客服 发起私聊
				if($('#group_3').find('li').length>0&&My.color!='3'&&My.fuser==""&&$('#group_myuser').find('li').length<=0){
					var key=parseInt($('#group_3').find('li').length*Math.random());
					var tmp_u=UserList.get($($('#group_3').find('li')[key]).attr('id'));
					if(tmp_u!=undefined){
						$.cookie('tg', $($('#group_3').find('li')[key]).attr('id'), { expires: 36500, path: '/' });
						$.get('/index/ajax/remyfuser');
						POPChat.newtab(tmp_u);
						POPChat.showtab(tmp_u);
						tmp_u.gid=tmp_u.color;
						addmyuser(tmp_u);
						onlinmyuser++;
					}

				}
				//游客主动私聊
				if(onlinmyuser>0&&My.color=="0"&&UserList.get($($('#group_myuser').find('li')[0]).attr("id").replace('myuser',''))!=undefined){
					$($('#group_myuser').find('li')[0]).dblclick();
				}
				OnlineOtherNum.innerHTML=onlinmyuser;
			}

			});
		},
		add:function(id,u){
			if($("#"+id).length >0)return;
			var style="";
			var vip_ico="";
			vip_ico="<img src='"+grouparr[u.color].ico+"'  align='top'/ title='"+grouparr[u.color].title+'-'+grouparr[u.color].sn+"'>";

			onlinuser++;
			OnlineUserNum.innerHTML=onlinuser;
			list[id]=u;
			var ref=getId("group_rebots");
			if(My.chatid==u.chatid){
				ref=getId("group_my");

			}
			else if(u.chatid.indexOf("x_r")>=0)
			{
				ref=getId("group_rebots");
				if($("#group_rebots").find("li").length>100){return;}
			}
			else
			{
				ref=getId("group_"+u.color);
			}




			var li=CreateElm1(ref,'li',false,id,null);


			//list[id]=u;
			//var li=CreateElm(OnLineUser,'li',false,id);
			var iscam='<span class="vipico">'+vip_ico+'</span>';
			//if(u.cam!='0')
			//iscam='<span><img src="images/'+u.cam+'.gif" onclick=\'VideoList.Connect("'+id+'","'+u.nick+'","'+RoomInfo.type+'");return false;\'/></span>';

			li.innerHTML='<a href="javascript:void(0)"><font style="color:#FFF">'
			 +iscam
     		 +'<cite><img src="ajax/getFaceImg/?t=p1&u='+id+'" border="0" class="head" id="head_'+id+'"/><img src="/style/room/images/state'+u.state+'.png" border="0" class="state" id="state'+id+'"/></cite>'
      		 +'<dt><strong id="cnick'+id+'">'+u.nick+'</strong> &nbsp;<code>'+u.mood+'</code></dt>'
			 +'<dl>'+u.mood+'</dl>'
      		 +'</font></a>';
			li.oncontextmenu=function(){UserList.menu(u);return false;}
			li.onclick=function(){if(!check_auth("msg_ptp")){return;}ToUser.set(u.chatid,u.nick);}
			li.ondblclick=function(){if(!check_auth("msg_priv")){layer.msg('没有私聊权限！',{shift: 6});return;}if(u.chatid!=My.chatid&&u.chatid.indexOf('x_r')<0){POPChat.newtab(u);POPChat.showtab(u);}}
			//if(u.vip>=2)
			//ColorNick('cnick'+id,parseInt(Math.random()*9));

			//getId('head_'+id).onmouseover =function(){UserList.info(id)}
			//li.onmouseover=function(){UserList.info(id)}
			//getId('head_'+id).onmouseout =UserList.infoHold;
			//li.onmouseout =UserList.infoHold;

		},
		setstate:function(id,state,automsg){
			list[id].state=state;
			getId(id).title=automsg;
			getId('state'+id).src="/style/room/images/state"+state+".png";
		},
		del:function(id,u){
			if(id==My.chatid)return;
			delete(list[id]);
			onlinuser--;
			OnlineUserNum.innerHTML=onlinuser;
			RemoveElm(OnLineUser,getId(id));
			ToUser.del(id);
		},
		info:function(id){
			show = setTimeout(function(){UserList.showInfo(id)},0);
		},
		showInfo:function(id){
			if(hold)clearTimeout(hold);
			var u=this.get(id);

			var t=getXY(getId(id));
			PInfo.style.top=t[0]-142+'px';
			PInfo.style.left=t[1]+248+'px';
			//PInfo.innerHTML='Login:'+u.roomid+'|'+u.chatid+'|'+u.nick+'|'+u.sex+'|'+u.age+'|'+u.guest+'|'+u.ip+'|'+u.vip+'|'+u.color+'|'+u.cam+'|'+u.headface+'|'+u.state+'|<br><br><br>'+u.automsg;
			var request_url='../ajax.php?act=userinfo&type=json&id='+id+'&'+Math.random() * 10000;
			var xmlhttp=XHConn();
			try{
				xmlhttp.open("GET",request_url,true);
				xmlhttp.send(null);
				xmlhttp.onreadystatechange=function()
				{
					if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
					{
					var UInfo=eval("("+xmlhttp.responseText+")");
					if(My.vip>0)uip=" [ <a href='http://www.ip138.com/ips8.asp?ip="+u.ip+"&action=1' target='_blank' title='点击查询地理位置'>"+u.ip+"</a> ]";
					else uip="";
					PInfo.innerHTML='<div style="width:280px; height:150px; padding:6px;" class="info_m">'
					+'<div style="width:100px; height:150px; float:left; margin-right:6px; margin-bottom:6px" class="info_m_l"><img src="index/ajax/getFaceImg/?t=p2&u='+id+'" style="width:100px; height:150px;"></div>'
					+'<div style="float:left; width:174px; height:150px; overflow:hidden" class="info_m_r">'
					+'<div><a href="../profile.php?uid='+id+'" target="userinfo"  style="cursor:pointer;color:#06C;">'+UInfo.nick+'</a></div><div style="color:#999" class="info_m_m">'+UInfo.sn+'</div><div class="info_m_">'+UInfo.rank+'</div><div><a href="../profile.php?uid='+id+'" target="userinfo"  style="cursor:pointer;">'+UInfo.yx+'</a></div>'
					+'</div></div><div style="width:292px; height:20px; color:#000" >&nbsp;&nbsp;所在地：'+UInfo.city+uip+'</div>';
					PInfo.style.display = '';
					PInfo.style.cursor='default';
					}
				}

			}catch(e){return null;}

		},
		infoOver : function(){
			if(hold)clearTimeout(hold);
		},
		infoHold:function(){
			hold = setTimeout(UserList.infoHidden,500);
			PInfo.onmouseover= UserList.infoOver;
			PInfo.onmouseout = UserList.infoHold;
			if(show)clearTimeout(show);
		},
		infoHidden : function(){
			PInfo.style.display = 'none';
		},
		infos:function(id){
			var u=this.get(id);
			alert(u.nick)
		},
		setVideo:function(u){
				SysSend.command('setVideo',My.rid+'_+_'+u.chatid+'_+_'+u.nick);
				getId('menu').style.display='none';
				var xmlhttp=XHConn();
				var request_url="../ajax.php?act=setvideo&vid="+u.chatid+"&rid="+My.rid
				try{
				xmlhttp.open('GET',request_url,true);
				xmlhttp.send(null);
				}
				catch(e) {return true;}
		},
		menu:function(u)
		{
			this.infoHidden();
			var UserMenu= Menu.init('120px');
			if(My.chatid==u.chatid)
			{
				UserMenu.add('zl.gif','个人资料',function(){getId('menu').style.display='none';openWin(2,false,'room/profile?uid='+u.chatid,460,600);});
				if(RoomInfo.OtherVideoAutoPlayer=='0'&&check_auth("room_admin"))
					UserMenu.add('tv.gif','上麦-房间主持人',function(){UserList.setVideo(u);getId('menu').style.display='none';});
			}
			else
			{
			UserMenu.add('ToMsg.gif','私聊',function(){getId('menu').style.display='none';if(!check_auth("msg_priv")){layer.msg('没有私聊权限！',{shift: 6});return;}if(u.chatid!=My.chatid&&u.chatid.indexOf('x_r')<0){POPChat.newtab(u);POPChat.showtab(u);}} );
			//UserMenu.add('Vlove.gif','视频语音对聊',function(){if(confirm("请求和"+u.nick+"视频密聊"))VideoList.Connect(u.chatid,u.nick,0);getId('menu').style.display='none';});
			//UserMenu.add('lw.gif','赠送礼物',function(){bt_gifts();ToUser.set(u.chatid,u.nick);getId('menu').style.display='none';});
			UserMenu.hr();
			UserMenu.add('zl.gif','查看资料',function(){getId('menu').style.display='none';if(!check_auth("user_info")){layer.msg('没有用户资料查看权限！',{shift: 6});return;}openWin(2,false,'room/profile?uid='+u.chatid,460,600);});
			UserMenu.hr();
			UserMenu.add('pb.gif','屏蔽消息',function(){BList.add(u.chatid,u);getId('menu').style.display='none';});

			if(check_auth("room_admin"))
				{
					UserMenu.hr();
					UserMenu.add('Admin.gif','禁言',function(){ToUser.set(u.chatid,u.nick);SysSend.command('send_Msgblock','');});
					UserMenu.add('Admin.gif','踢出、封IP',function(){getId('menu').style.display='none';if(!check_auth("user_kick")){layer.msg('没有用户踢出权限！',{shift: 6});return;}UKick.ShowMb(u);});
					if(RoomInfo.OtherVideoAutoPlayer=='0')
					UserMenu.add('tv.gif','上麦-房间主持人',function(){UserList.setVideo(u);getId('menu').style.display='none';});
				}
			}
			var e=getEvent();
			UserMenu.display(e.clientX,e.clientY);
		},
		menu_kf:function(u)
		{
			this.infoHidden();
			var UserMenu= Menu.init('120px');
			if(My.chatid==u.chatid)
			{
				UserMenu.add('zl.gif','个人资料',function(){getId('menu').style.display='none';openWin(2,false,'room/profile/?uid='+u.chatid,460,600);});
				if(RoomInfo.OtherVideoAutoPlayer=='0'&&check_auth("room_admin"))
					UserMenu.add('tv.gif','上麦-房间主持人',function(){UserList.setVideo(u);getId('menu').style.display='none';});
			}
			else
			{
			UserMenu.add('ToMsg.gif','私聊',function(){getId('menu').style.display='none';if(u.chatid!=My.chatid&&u.chatid.indexOf('x_r')<0){POPChat.newtab(u);POPChat.showtab(u);}} );
			UserMenu.add('Vlove.gif','视频语音对聊',function(){if(confirm("请求和"+u.nick+"视频密聊"))VideoList.Connect(u.chatid,u.nick,0);getId('menu').style.display='none';});
			UserMenu.add('lw.gif','赠送礼物',function(){bt_gifts();ToUser.set(u.chatid,u.nick);getId('menu').style.display='none';});
			UserMenu.hr();
			UserMenu.add('zl.gif','查看资料',function(){getId('menu').style.display='none';openWin(2,false,'room/profile?uid='+u.chatid,460,600);});
			UserMenu.hr();
			UserMenu.add('pb.gif','屏蔽消息',function(){BList.add(u.chatid,u);getId('menu').style.display='none';});

			if(check_auth("room_admin"))
				{
					UserMenu.hr();
					UserMenu.add('Admin.gif','禁言',function(){ToUser.set(u.chatid,u.nick);SysSend.command('send_Msgblock','');});
					UserMenu.add('Admin.gif','踢出、封IP',function(){getId('menu').style.display='none';if(!check_auth("user_kick")){layer.msg('没有用户踢出权限！',{shift: 6});return;}UKick.ShowMb(u);});
					if(RoomInfo.OtherVideoAutoPlayer=='0')
					UserMenu.add('tv.gif','上麦-房间主持人',function(){UserList.setVideo(u);getId('menu').style.display='none';});
				}
			}
			var e=getEvent();
			UserMenu.display(e.clientX,e.clientY);
		}
	}
})();
PublicVideo=(function(){

})();
UKick=(function(){
	return{
		ShowMb:function(u){
			var loadstr='<div  id="kickmp" onselectstart="return true;">';
			loadstr+='<select name="MCmd" id="MCmd" onchange="if(this.value==\'kick\')getId(\'ktime\').style.display=\'\'"><option value="kick">踢出+封IP</option></select>';
			loadstr+='&nbsp;<select id="ktime" name="ktime">      <option value="0">踢出房间</option>      <option value="1">禁闭01分钟</option>      <option value="10">禁闭10分钟</option>      <option value="30">禁闭30分钟</option>      <option value="60">禁闭60分钟</option>      <option value="720">禁闭12小时</option>      <option value="10080">禁闭1星期</option></select>';
			loadstr+='<br><br><input type="text" name="cause" id="cause" value="原因" size="24" /><br><button class="bt1" onclick="UKick.SendCmd(\''+u.chatid+'\',\''+u.nick+'\')">执行</button>';
			loadstr+='</div>';
				openWin(1,'踢出 '+u.nick,loadstr,290,200);
		},
		SendCmd:function(chatid,nick){
			ToUser.set(chatid,nick);
			SysSend.command('kick',getId('ktime').value+'_+_'+getId('cause').value);
		}


	}
})();
BList=(function(){
	var List=[];
	return{
		init:function(){List=[]},
		add:function(id,u){
			if(BList.isExist(id)){BList.del(id);return;}
			List[id]=u;
			UserList.setstate(id,'00','已经屏蔽消息');
			},
		isExist:function(id){
			var r=false;
			for(key in List){
				if(id==List[key].chatid){return true;}
				}
			return r;
			},
		del:function(id){
			UserList.setstate(id,'0','');
			delete List[id];
			}
		}
})();

ToUser=(function(){
	$('#ToUser').change(function () {
		ToUser.set($(this).val(),$(this).find("option:selected").text());
    });
	return{
		id:null,
		name:null,
		add:function(id,name){
			$('#ToUser option[value='+id+']').remove();
			$("#ToUser").append("<option value='"+id+"'>"+name+"</option>");
			$('#ToUser option[value='+id+']').attr('selected','selected');

		},
		del:function(id){
			$('#ToUser option[value='+id+']').remove();
		},
		set:function(id,name){
			if(id==My.chatid)return;
			this.id=id;
			this.name=name;
			this.add(id,name);
		}
	}
})();
VideoList=(function(){
	var Video_list=getId('Video_List');
	return{
		t:0,
		Connect:function(Uid,Name,type){
			//if(Uid==My.chatid){alert('视频设置...制作中！');return;}
			if(type=='0'){
				if(Uid=='ALL'){alert('请选网友！');return;}
				if(!confirm("确定和'"+Name+"'视频密聊"))return;
				ToUser.set(Uid,Name);
				SysSend.command('requestVideo',My.chatid+'_+_'+My.nick);
				this.PrivateConnect(Uid,Name);return;
			}
			this.t++;
			var vC=Video_list.getElementsByTagName('div');
			if(vC.length>=RoomInfo.MaxVideo)
				RemoveElm(Video_list,vC[0])
			if(getId('V'+My.rid+'·'+Uid)!=null)RemoveElm(Video_list,getId('V'+My.rid+'·'+Uid));
			var div=CreateElm(Video_list,'div','list','V'+My.rid+'·'+Uid);

			var vFlash = new SWFObject('images/Video_list.swf?Server='+RoomInfo.VServer+'&User='+Name+'&Uid='+My.rid+'·'+Uid, "Video_more", "148", "110", "9", "#FFF");
			vFlash.addParam("wmode","transparent");
			vFlash.addParam("allowFullScreen","true");
			vFlash.addParam("allowScriptAccess","sameDomain");
			vFlash.write('V'+My.rid+'·'+Uid);
			return;
		},
		PrivateConnect:function(TUid,TName){
			alert('开发中……');
			/*
			getId('PInfo').style.display='none';

			var L=My.chatid+'-'+TUid;
			var R=TUid+'-'+My.chatid;
			var div='<embed type="application/x-shockwave-flash" src="images/Video.swf?active=true&L='+L+'&R='+R+'&S='+RoomInfo.VServer+'&Q='+RoomInfo.VideoQ+'" width="340" height="360" bgcolor="#FFF" quality="high" allowfullscreen="true" allowscriptaccess="sameDomain">';
			openWin(1,'和 '+TName+'视频聊天',div,340,360);

			var sFlash = new SWFObject("images/Video.swf?active=true&L="+L+"&R="+R+'&S='+RoomInfo.VServer+'&Q='+RoomInfo.VideoQ, "FVideo"+TUid, "340", "360", "9", "#FFF");
				//sFlash.addParam("wmode","transparent");
				sFlash.addParam("allowFullScreen","true");
				sFlash.addParam("allowScriptAccess","sameDomain");
				sFlash.write('Video'+TUid);
			*/
			return;

		},
		Close:function(TUid){
			if(getId('PV'+TUid)!=null)RemoveElm(getId('UI_MainBox'),getId('PV'+TUid));
			getId('PV'+TUid).style.display='none';
			return;
		}
	}
})();
var oldMsg;
var oldTime;
var msgTag=false;
SysSend=(function(){
	return{
		isUser:function()
		{
			if(ToUser.id.indexOf('x_r')>-1){ToUser.set('ALL','大家');}
			var toUserInfo=UserList.get(ToUser.id);
			if(typeof(toUserInfo)=="undefined"){alert('对方已经离线！');ToUser.del(ToUser.id);return false;}
			if(ToUser.id=='ALL'){alert('先选择一个网友！');return false;}
			return true;
		},
		msg:function(){
			if(!check_auth("msg_send")){layer.msg('没有发言权限！',{shift: 6});return false;}
			if($("#chat_type").val()=="he"&&ToUser.id!="ALL"){SysSend.command('rebotmsg',encodeURIComponent(getId('Msg').innerHTML));return true;}

			if(ToUser.id!="ALL")
			if(!this.isUser())return false;
			var toUserInfo=UserList.get(ToUser.id);
			if(toUserInfo.state=='00')alert("注意:"+toUserInfo.nick+' 的消息你已经屏蔽,你将收不到来自ta的消息');
			if(typeof(toUserInfo)=="undefined"){alert('对方已经离线！');ToUser.del(ToUser.id);return false;}
			var Msg=getId('Msg').innerHTML;
			var Style="font-weight:"+getId('Msg').style.fontWeight+";font-style:"+getId('Msg').style.fontStyle+"; text-decoration:"+getId('Msg').style.textDecoration+";color:"+getId('Msg').style.color+"; font-family:"+getId('Msg').style.fontFamily+"; font-size:"+getId('Msg').style.fontSize;
			var Msg=encodeURIComponent(Msg.str_replace());
			if(Msg==oldMsg && My.qx=='0'){alert("请勿刷屏！");getId('Msg').innerHTML='';return;}
			var newTime=new Date().getTime();
			if(newTime-oldTime<500){alert("说话速度过快~歇会儿！");return;}
			if(msgTag){if(newTime-oldTime>5000)msgTag=false;else {alert("说话速度过快~歇会儿！");return;}}
			if(Msg!='')
			{
			var msgid=randStr()+randStr();
			var str='SendMsg=M='+ToUser.id+'|'+getId('Personal').value+'|'+Style+'|'+msgid+'_+_'+Msg;

			ws.send(str);

			oldMsg=Msg;
			oldTime=new Date().getTime();
			getId('Msg').innerHTML='';
			getId('Msg').focus();
			PutMessage(My.rid,My.chatid,ToUser.id,My.nick,toUserInfo.nick,getId('Personal').value,Style,Msg,msgid);
			}
			return true;
		},
		command:function(cmd,value){
			var Msg='';
			var IsPersonal=getId('Personal').value;
			var Style="font-weight:"+getId('Msg').style.fontWeight+";font-style:"+getId('Msg').style.fontStyle+"; text-decoration:"+getId('Msg').style.textDecoration+";color:"+getId('Msg').style.color+"; font-family:"+getId('Msg').style.fontFamily+"; font-size:"+getId('Msg').style.fontSize;
			switch(cmd)
			{
				case 'setVideoSrc':
					Msg+='C0MMAND_+_'+cmd+'_+_'+value;
					IsPersonal='false';
				break;
				case 'send_Msgblock':
					Msg+='C0MMAND_+_'+cmd+'_+_'+value;
					IsPersonal='true';
				break;
				case 'msgBlock':
					Msg+='C0MMAND_+_'+cmd+'_+_'+value;
					IsPersonal='false';
				break;
				case 'kick':
					Msg+='C0MMAND_+_'+cmd+'_+_'+value;
					IsPersonal='true';
				break;
				case 'magicflash':
					if(this.isUser())
					{
					Msg+='C0MMAND_+_'+cmd+'_+_'+value;
					IsPersonal=getId('Personal').value;
					}
				break;
				case 'requestVideo':
					if(this.isUser())
					{
					Msg+='C0MMAND_+_'+cmd+'_+_'+value;
					IsPersonal='true';
					}
				break;
				case 'setstate':
					//ToUser.id='ALL';
					Msg+='C0MMAND_+_'+cmd+'_+_'+My.chatid+'_+_'+value;
					IsPersonal='false';
				break;
				case 'rebotmsg':
					Msg+='C0MMAND_+_'+cmd+'_+_'+ToUser.id+'_+_'+value;

					IsPersonal='false';
				break;
				default:
					Msg+='C0MMAND_+_'+cmd+'_+_'+value;
					IsPersonal='false';
					//ToUser.id='ALL';
				break;
			}
			if(Msg!='')
			{
				var touser=	ToUser.id;
				if(ToUser.id.indexOf('x_r')>-1){
					touser='ALL';
				}
				var str='SendMsg=M='+touser+'|'+IsPersonal+'|'+Style+'|'+Msg;
				ws.send(str);
				getId('Msg').innerHTML='';
				getId('Msg').focus();
			}
		}
	}
})();
Menu=(function(){
	var MObj;
	return{
	init:function(w){
		RemoveElm(false,getId('menu'))
		this.MObj=CreateElm(false,'div',false,'menu');
		this.MObj.tabIndex=1;
		this.MObj.style.width=w;
		this.MObj.style.display='none';
		this.MObj.style.zIndex=1;
		return this;
		},
	add:function(icon,txt,fun){
		var n=CreateElm(this.MObj,'div','out','n');
		n.onmouseout=function(){n.className='out';}
		n.onmouseover=function(){n.className='over';}
		n.innerHTML='<div id="icon" ><img src="/style/room/images/'+icon+'" /></div><span id="txt">'+txt+'</span>';
		n.onclick=fun;
		},
	hr:function(){
		var n=CreateElm(this.MObj,'div','hr','n');
		n.style.height='1px';
		n.style.fontSize='1px';
		},
	display:function(x,y){
		this.MObj.style.display='';
		this.MObj.style.top=y+'px';
		this.MObj.style.left=x+'px';

		this.MObj.focus();
		this.MObj.onblur=function(){BrdBlur('menu');}

		}
	}
})();
}
function WriteMessage(txt){
	//if(txt.indexOf('SendMsg')!=-1)
	//alert(txt);
	var Msg;
	try{
		Msg=eval("("+txt+")");
	}catch(e){return;}
	if(Msg.stat!='OK')
	{
		if(Msg.stat=="MaxOnline"){
			document.body.innerHTML='<div  style="font-size:12px; text-align:center; top:100px; position:absolute;width:100%">O.O 对不起，服务端并发数已满！请您联系管理员对系统扩容升级！<br><br></div>';
			return;
		}
		if(Msg.stat=="OtherLogin"){
			location.href="error.php?msg="+encodeURI('其他地方登录！请注意同一帐号不能两处登录、同一电脑不能两处打开房间！')
		}
		return ;
	}
	Object.keys(Msg).forEach(function(key){

	     console.log(key,Msg[key]);

	});
	switch(Msg.type)
	{
		case "Ulogin":
			var U=Msg.Ulogin;
			var vip_msg="到来";
			var date= Datetime(0);
			var str='<div style="height:22px; line-height:22px;">欢迎：<font class="u" onclick="ToUser.set(\''+U.chatid+'\',\''+U.nick+'\')">'+U.nick+'</font> <font class="date">'+date+'</font></div>';
			if(My.chatid!=U.chatid){
			UserList.add(U.chatid,U);
			}
			var type=0;
			if(U.chatid==My.chatid) type=2;
			MsgShow(str,type);

		break;
		case "UMsg":
			var str=FormatMsg(Msg.UMsg);
			var type=0;
			if(!str)return;
			if(BList.isExist(Msg.UMsg.ChatId))return;
			if(Msg.UMsg.ToChatId==My.chatid) {type=2;MsgAlert(0);if(audioNotify==true)playSound('msg.mp3');}
			if(Msg.UMsg.ChatId==My.chatid) type=2;

			if(Msg.UMsg.ToChatId=='ALL'){if(Msg.UMsg.ChatId==My.chatid)type=2;else type=0;}
			if(Msg.UMsg.IsPersonal!='true'){
				MsgShow(str,type);
			}
			else
			{
				//私聊
				if(Msg.UMsg.ChatId==My.chatid){
					//发送端窗口
					POPChat.newtab(UserList.get(Msg.UMsg.ToChatId));
				}
				else{
					//收消息端窗口
					//此处函数追加历史消息，从数据库中读取，与接下来要执行的showmsg会重复最后一条数据
					POPChat.newtab(UserList.get(Msg.UMsg.ChatId));
				}
				POPChat.showmsg(UserList.get(Msg.UMsg.ChatId),UserList.get(Msg.UMsg.ToChatId),"<font style='"+Msg.UMsg.Style+"'>"+decodeURIComponent(Msg.UMsg.Txt.str_replace())+"</font>");

			}

		break;
		case "UonlineUser":

			var onlineNum=Msg.roomListUser.length;
			for(i=0;i<onlineNum;i++)
			{
			var U=Msg.roomListUser[i];

			UserList.add(U.chatid,U);
			}
		break;
		case "Ulogout":
			var U=Msg.Ulogout;
			var date= Datetime(0);
			var str='<div style="height:22px; line-height:22px;">用户：<font class="u" onclick="ToUser.set(\''+U.chatid+'\',\''+U.nick+'\')">'+U.nick+'</font>   离开！ <font class="date">'+date+'</font></div>';
			MsgShow(str,0);
			UserList.del(U.chatid,U);
		break;
		case "SPing":
			//alert(Msg.SPing.time);
		break;
		case "Sysmsg":
			alert(Msg.Sysmsg.txt);
		break;
	}

}

function CommObjectCheck(obj, inObj)
{
	if (obj == inObj)
	{
		return true;
	}
	if(obj.parentNode) {
		return CommObjectCheck(obj.parentNode, inObj);
	}
	return false;
}
function CreateElm(pObj,obj,className,id){
	var elm = null;
	var elm=document.createElement(obj);
	if(!pObj)document.body.insertBefore(elm,null);
	else pObj.insertBefore(elm,null);
	if(id)elm.id = id;
	if(className)elm.className = className;
	return elm
}
function CreateElm1(pObj,obj,className,id,ref){
	var elm = null;
	var elm=document.createElement(obj);
	if(!pObj)document.body.insertBefore(elm,ref);
	else pObj.insertBefore(elm,ref);
	if(id)elm.id = id;
	if(className)elm.className = className;
	return elm
}


function RemoveElm(pObj,id)
{
	$(id).html("");
	$(id).remove()
}


String.prototype.str_replace=function(t){
	var str=this;
	var reg = new RegExp(msg_unallowable, "ig");
	if(reg.test(str)&&!check_auth("room_admin"))return "含敏感关键字，内容被屏蔽";
	str= str.replace(/<\/?(?!br|img|font|p|span|\/font|\/p|\/span)[^>]*>/ig,'').replace(/\r?\n/ig,' ').replace(/(&nbsp;)+/ig," ").replace(/(=M=)+/ig,"").replace(/(|)+/ig,"").replace(/(SendMsg)/ig,'');
	if(!check_auth("room_admin"))str=str.replace(reg,'**')
	return str;
	};
function LinkMaker( str ) {
	return str.replace( /(https?:\/\/[\w.]+[^ \f\n\r\t\v\"\\\<\>\[\]\u2100-\uFFFF]*)|([a-zA-Z_0-9.-]+@[a-zA-Z_0-9.-]+\.\w+)/ig, function( s, v1, v2 ) {
		if ( v2 )
			return [ '<a href="mailto:', v2, '">', v2, '</a>' ].join( "" );
		else
			return [ '<a href="', s, '">', s, '</a>' ].join( "" );
	} );
}
function SwapLink()
{
	if(!isIE)
	getId('Msg').innerHTML=LinkMaker(getId('Msg').innerHTML);

	var as=getId('Msg').getElementsByTagName('a');
	for ( var i = as.length - 1; i >= 0; i-- ) {
		as[i].target='_blank';
		as[i].className='MsgUrlStyle';
	}
}
function PutMessage(rid,uid,tid,uname,tname,privacy,style,str,msgid){
	var msgtip="";
	if($("#msg_tip").attr("checked")){
		msgtip="msgtip=2&";
		$("#msg_tip").attr("checked",false);
		$("#msg_tip_show").html(decodeURIComponent("<span style='color:#FF0'>"+str+"</span>"));
	}
	else if($("#msg_tip_admin").attr("checked")){
		msgtip="msgtip=3&";
		$("#msg_tip_admin").attr("checked",false);
		$("#msg_tip_admin_show").html(decodeURIComponent("<span style='color:red'>"+str+"</span>"));
	}
	if(RoomInfo.Msglog=='0')return;
	var request_url='/index/ajax/putmsg';
	var postdata=msgtip+'msgid='+msgid+'&uname='+encodeURIComponent(uname)+'&tname='+encodeURIComponent(tname)+'&muid='+uid+'&rid='+rid+'&tid='+tid+'&privacy='+privacy+'&style='+style+'&msg='+str+'&'+Math.random() * 10000;

	$.ajax({type: 'POST',url:request_url,data:postdata});
}
function Mkick(adminid,rid,ktime,cause)
{
	$.ajax({type: 'get',dataType:'json',url: 'ajax/kick?aid='+adminid+'&rid='+rid+'&ktime='+ktime+'&cause='+encodeURIComponent(cause)+'&u='+encodeURIComponent(My.name)+'&'+Math.random() * 10000,
			success:function(data){
				//alert(data);
				if(data.state=="yes"){
				location.href="room/ti?msg="+encodeURI('傻叉，你被踢出！并禁止'+ktime+'分钟内登陆该房间！<br>原因是 '+cause+'');
				}
			}
	});
}

function FormatMsg(Msg)
{
	var User=UserList.get(Msg.ChatId);
	var toUser=UserList.get(Msg.ToChatId);
	var date= Datetime(0);
	var IsPersonal='';
	if(typeof(User)=='undefined'||typeof(toUser)=='undefined')return false;
	if(Msg.IsPersonal=='true' && toUser.chatid!='ALL') IsPersonal='[私]';
	var Txt=decodeURIComponent(Msg.Txt.str_replace());

	if(Txt.indexOf('C0MMAND')!=-1)
	{
		var command=Txt.split('_+_');
		switch(command[1])
		{
			case 'setVideoSrc':
				$('#defvideosrc').html("当前讲师:"+command[3]);
				var date= Datetime(0);
				var str='<div style="height:22px; line-height:22px;">欢迎：<font class="u" ">讲师-'+command[3]+'</font>开讲啦！ <font class="date">'+date+'</font></div>';


			break;
			case 'send_Msgblock':
				if(My.chatid==toUser.chatid){
					remove_auth('msg_send');
					layer.msg('你已被禁言！',{shift: 6});
				}
			break;
			case 'msgAudit':
				$('#'+command[2]).show();
				MsgAutoScroll();
				$.ajax({type: 'get',url: '/index/ajax/msgblock?st=0&msgid='+command[2]});
			break;
			case 'msgBlock':
				$('#'+command[2]).remove();
				MsgAutoScroll();
				$.ajax({type: 'get',url: '/index/ajax/msgblock?st=1&msgid='+command[2]});
			break;
			case 'rebotmsg':
				var msg={};
				msg.ChatId=command[2];
				msg.ToChatId='ALL';
				msg.IsPersonal='false';
				msg.Txt=command[3];
				msg.Style=Msg.Style;
				MsgShow(FormatMsg(msg),0);
			break;

			case 'kick':
				if(My.chatid==toUser.chatid){
					Mkick(Msg.ChatId,My.roomid,command[2],command[3]);
				}
			break;
			case 'requestVideo':
				if(command[2]==My.chatid)return;
				if(!confirm(command[3]+' 请求和你视频，是否接受？'))return;
				ToUser.set(command[2],command[3]);
				VideoList.PrivateConnect(command[2],command[3]);return;
				//SysSend.command('requestVideo',Uid+'_+_'+Name);

			break;
			case 'setstate':
				UserList.setstate(command[2],command[3],command[4]);
			break;
			case 'setVideo':
				toUser=UserList.get(command[3]);
				var str='<div style="height:22px; line-height:22px;">'+IsPersonal+'<font class="u" color="'+aColor[User.sex]+'" onclick="ToUser.set(\''+User.chatid+'\',\''+User.nick+'\')">'+User.nick+'</font> 设置 <font class="u" color="'+aColor[toUser.sex]+'" onclick="ToUser.set(\''+toUser.chatid+'\',\''+toUser.nick+'\')">'+toUser.nick+'</font> 为房间公共视频  <font class="date">'+date+'</font></div>';
				try{

					if(RoomInfo.OtherVideoAutoPlayer!="0"){

						$('#OnLine_MV').html('<iframe height="390" width="100%" allowTransparency="true" marginwidth="0" marginheight="0"  frameborder="0" scrolling="no" src="/index/play?type=pc"></iframe>');

					}

				else if(command[3]==My.chatid)
				{
					thisMovie('pVideo').stopS(RoomInfo.AutoSelfVideo);
					thisMovie('pVideo').sConnect(RoomInfo.VServer,command[2]+'·'+command[3],'1');
					//thisMovie('pVideo').pShow("2",toUser.nick);
				}
				else
				{
					thisMovie('pVideo').stopS(RoomInfo.AutoSelfVideo);
					thisMovie('pVideo').pConnect(RoomInfo.VServer,command[2]+'·'+command[3],toUser.nick);

				}
				}catch(e){}
			break;
			case "sendgift":
				//('sengift','{$uid}_+_{$sid}_+_{$gid}_+_{$num}_+_{$gname}_+_{$msg}')
				var u=UserList.get(command[2]);
				var s=UserList.get(command[3]);
				for(var i=1;i<=command[5];i++){
					if(i>300)break;
					setTimeout('ShowGifteffect("'+command[4]+'")',10*i);
					}
				var str='<p><font class="u" color="'+aColor[u.sex]+'" onclick="ToUser.set(\''+u.chatid+'\',\''+u.nick+'\')">'+u.nick+'</font> 向 <font class="u" color="'+aColor[s.sex]+'" onclick="ToUser.set(\''+s.chatid+'\',\''+s.nick+'\')">'+s.nick+'</font> 送了<img src="../gift/img.php?id='+command[4]+'" height="50" width="50"/> (<span style="color:red"><b>'+command[5]+'</b></span>份) '+command[6]+' <font color="'+aColor[u.sex]+'">赠言： '+command[7]+'</font> <font class="date">'+date+'</font></p>';

			break;
		}
	}
	else
	{
	var msgid="";
	if(Txt.indexOf('_+_')>-1){
		var t=Txt.split('_+_');
		msgid= t[0];
		Txt=t[1];
	}
	var msgBlockBt="";
	if(RoomInfo.msgBlock=="1"){
		if(check_auth('msg_block'))
		msgBlockBt=" <a href='javascript:void(0)' onclick='bt_msgBlock(\""+msgid+"\")'><img src='/style/room/images/11.png' style='border:0px;' title='屏蔽消息'></a>";
	}

	var msgAuditBt="";
	var msgAuditShow='';
	if(RoomInfo.msgAudit=="1"){
		msgAuditShow='style="display:none"';

		if(check_auth('msg_audit')){
			msgAuditBt=" <a href='javascript:void(0)' onclick='bt_msgAudit(\""+msgid+"\",this)'><img src='/style/room/images/22.png' style='border:0px;' title='审核通过'></a>";
			msgAuditShow="";
		}
		if(User.chatid==My.chatid)msgAuditShow="";

	}

	if(toUser.chatid!="ALL"){
		str='<div style="clear:both; height:0px;"></div><div class="msg" id="'+msgid+'" '+msgAuditShow+'><div class="msg_head"><img src="ajax/getFaceImg/?t=p1&u='+User.chatid+'"></div><div class="msg_content"><div>'+IsPersonal+'<font class="u" style="color:'+aColor[User.sex]+'" onclick="ToUser.set(\''+User.chatid+'\',\''+User.nick+'\')">'+User.nick+'</font> <img src="'+grouparr[User.color].ico+'" class="msg_group_ico" title="'+grouparr[User.color].title+"-"+grouparr[User.color].sn+'"> <font class="dui">    对</font> <font class="u" style="color:'+aColor[toUser.sex]+'" onclick="ToUser.set(\''+toUser.chatid+'\',\''+toUser.nick+'\')">'+toUser.nick+'</font> 说 <font class="date">'+date+'</font></div><div class="layim_chatsay" style="margin:5px 0px;"><font  style="'+Msg.Style+';" >'+Txt+msgBlockBt+msgAuditBt+'</font><em class="layim_zero"></em></div></div></div><div style="clear:both; height:0px;"></div>';
	}else{
		str='<div style="clear:both; height:0px;"></div><div class="msg"  id="'+msgid+'" '+msgAuditShow+'><div class="msg_head"><img src="ajax/getFaceImg/?t=p1&u='+User.chatid+'"></div><div class="msg_content"><div>'+IsPersonal+'<font class="u" style="color:'+aColor[User.sex]+'" onclick="ToUser.set(\''+User.chatid+'\',\''+User.nick+'\')">'+User.nick+'</font> <img src="'+grouparr[User.color].ico+'" class="msg_group_ico"  title="'+grouparr[User.color].title+"-"+grouparr[User.color].sn+'">  <font class="date">'+date+'</font></div><div class="layim_chatsay" style="margin:5px 0px;"><font  style="'+Msg.Style+';" >'+Txt+msgBlockBt+msgAuditBt+'</font><em class="layim_zero"></em></div></div></div><div style="clear:both; height:0px;"></div>';
	}
	}
	return str;

}

function ShowGifteffect(gid){
	var Gift_id='G'+Math.round(Math.random())+Math.round(Math.random());
	var div=CreateElm(false,'div',false,Gift_id);
	div.style.position='absolute';
	div.style.display="none";
	div.style.top=Math.round(Math.random()*document.documentElement.clientHeight-50)+'px';
	div.style.left=Math.round(Math.random()*document.documentElement.clientWidth-50)+'px';
	div.innerHTML='<img src="../gift/img.php?id='+gid+'" height="80" width="80" />';
	div.style.display="";
	setTimeout("RemoveElm(false,getId('"+Gift_id+"'))",5000);
}
var msgBlock='';
function MsgShow(str,type){
//0:box1 show 1:box2 show 2:all show
	var say = null;
	if(fenping)type=0;
	switch(type){
		case 0:
			$('#MsgBox1').append(str);
		break;
		case 1:
			$('#MsgBox2').append(str);
		break;
		case 2:
		    $('#MsgBox2').append(str);
		break;
	}
	if($('#MsgBox1').find(".msg").length>100){$('#MsgBox1').find(".msg:first").remove();}
	$(".layim_chatsay img").on("click",function(){if($(this).width()>300||$(this).height()>300)window.open($(this).attr('src'))});
	MsgAutoScroll();
}
function MsgAutoScroll(){
	if(toggleScroll == true){
	//getId('MsgBox1').scrollTop = getId('MsgBox1').scrollHeight;
	$('#MsgBox1').animate({scrollTop:$('#MsgBox1')[0].scrollHeight}, 1000);
	if(!fenping)
	$('#MsgBox2').animate({scrollTop:$('#MsgBox2')[0].scrollHeight}, 1000);
	//getId('MsgBox2').scrollTop = getId('MsgBox2').scrollHeight;
	}
}
var blinkerTimer;
function MsgAlert(tag)
{
	MsgCAlert();

	if(tag==0){document.title='您有新消息！祝你聊得愉快！';blinkerTimer=setTimeout('MsgAlert(1)',1000);}
	if(tag==1){document.title=RoomInfo.defaultTitle;blinkerTimer=setTimeout('MsgAlert(0)',1000);}
}
function MsgCAlert()
{
	if(blinkerTimer)clearTimeout(blinkerTimer);document.title=RoomInfo.defaultTitle;
}
function sendgift(s,g){
	//var loadstr='<font color="red">服务器连接失败</font><br>';
	if(s=="ALL" || s==""){alert("没有选择收礼人！");return false;}
	openWithIframe('赠送礼物','../sendgift.php?froom=froom&gid='+g+'&sid='+s,300,200,null,false);
	dragWinx(getId("massage_box"));
}

function saveCode(obj,filename){
  var winname = window.open("", "", "top=10000,left=10000");
  winname.document.open("text/html", "replace");
  winname.document.writeln(obj);
  winname.document.execCommand("saveas", "", filename + ".html");
  winname.close();
}

function dragWinx(o){

}
function dragMsgWinx(o){
	//o.firstChild.onmousedown=function(){return false;};
	o.onmousedown=function(a){
		var d=document;if(!a)a=window.event;
		var dy=a.clientY-getId('MsgBox1').offsetHeight;
		var x=getId('MsgBox2').offsetHeight+getId('MsgBox1').offsetHeight;
		if(o.setCapture)
			o.setCapture();
		else if(window.captureEvents)
			window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);

		d.onmousemove=function(a){
			if(!a)a=window.event;
			var t=Math.min(Math.max(120,a.clientY-dy),x-40);
			getId('MsgBox1').style.height=t+'px';
			getId('MsgBox2').style.height=x-t+'px';
		};

		d.onmouseup=function(){
			if(o.releaseCapture)
				o.releaseCapture();
			else if(window.captureEvents)
				window.captureEvents(Event.MOUSEMOVE|Event.MOUSEUP);
			d.onmousemove=null;
			d.onmouseup=null;
		};
	};
}
function openWithIframe(tit,url,w,h,str,o){
if(url==null)
{
    $.layer({
        type: 1,
        title: false, //不显示默认标题栏
        shade: [0], //不显示遮罩
        area: ['600px', '360px'],
        page: {html: '<img src="http://static.oschina.net/uploads/space/2014/0516/012728_nAh8_1168184.jpg" alt="layer">'}
    });
}
else
{
	$.layer({
        type: 2,
        title: tit!=""?tit:false,
        maxmin: false,
        shadeClose: true, //开启点击遮罩关闭层
        area : [w+'px' , h+'px'],
        offset : ['100px', ''],
        iframe: {src: url}
    });
}



}

function closeWithIframe(){
	layer.close();
}

//魔法表情
function online(rst)
{

	var xmlhttp=XHConn();
	var request_url="/index/ajax/online?rst="+rst+"&num="+getId('OnlineUserNum').innerHTML+"&"+Math.random() * 10000;
	try{
		xmlhttp.open('GET',request_url,true);
		xmlhttp.send(null);
		xmlhttp.onreadystatechange=function()
		{
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
			{
				var re = eval("("+xmlhttp.responseText+")");
				if(re.state=="logout")
				{alert('你还没有登陆！');location.href='../index.php'}
				else if(re.state=='ologin')
				{
					var str='<div><img src="images/true.png" width="16" height="16"  style="vertical-align:text-bottom" /> '+Datetime(0)+' <br /> &nbsp;&nbsp;&nbsp;帐号其他地方登录或网络异常！统一帐号不能两个地方（浏览器登录）！ <a href="javascript:location.reload()" style="color:#0033FF;cursor:pointer">点击重新连接</a> </div>';
					MsgShow(str,1);
				}
			}
			return true;
		}
	}
	catch(e) {return true;}
}
function ColorNick(id,i){return;
	if(i>=9)i=0;
	var col = ["white","coral","orange","red","greenyellow","lime","turquoise","coral","blueviolet","violet"];
	document.getElementById(id).style.color=col[i++];
	setTimeout("ColorNick('"+id+"',"+i+")",100);
}
function playSound(file){
	getId('MsgSound').innerHTML='<audio  src="/style/room/sounds/' + file + '" loop="0" autostart="true" hidden="true"></audio>';
}
function openWin(type,title,content,w,h){
	layer.closeAll('iframe');
		layer.open({
		type: type,
		title: title,
		shadeClose: true,
		shade: false,
		area: [w+'px', h+'px'],
		content: content //iframe的url
		});

}
function openApp(obj){
	layer.closeAll('iframe');
	if(obj.target=="NewWin"){
		window.open(obj.url);
	}
	else if(obj.target=="POPWin"){
		layer.open({
		type: 2,
		title: obj.title,
		shadeClose: true,
		shade: false,
		area: [obj.w+'px', obj.h+'px'],
		content: obj.url //iframe的url
		});
	}
	else if(obj.target=="QPWin"){
		layer.open({
    	type: 2,
		shade: false,
		title: false, //不显示标题
		content: obj.url, //捕获的元素
		area: [obj.w+'px', obj.h+'px']
	});
	}

}
function loginTip(){
	//$('#OnLine_MV').html('直播体验结束，请登录！');
	openWin(2,false,'minilogin.php',390,310);
}
function app_sendmsg(msg){
	var msgid=randStr()+randStr();
	var str='SendMsg=M=ALL|false|color:#000|'+encodeURIComponent(msg.str_replace());

	ws.send(str);
	PutMessage(My.rid,My.chatid,'ALL',My.nick,'ALL','false','',msg.str_replace(),msgid)
}
function check_auth(auth){
	var auth_rules = grouparr[My.color].rules;
	if(auth_rules.indexOf(auth)>-1)return true;
	else false;
}
function remove_auth(auth){
	grouparr[My.color].rules=grouparr[My.color].rules.replace(auth,"");
}
function BrdBlur(id) {

		var e=getEvent();
		var act=document.activeElement?document.activeElement:e.explicitOriginalTarget
		var src=e.srcElement ? e.srcElement : e.target
		if (!CommObjectCheck(act, src)) {
			getId(id).style.display='none';
		}
}

function HideMenu()
{
    var elementTable=["ColorTable","Send_key_option","FontBar"];
    for(var i=0;i<elementTable.length;i++)
      getId(elementTable[i]).style.display='none'
}
//全局事件绑定
//window.onblur =function(){
//    if(!isIE){
//        HideMenu();
//    }
//};
function getEvent() //同时兼容ie和ff event
{
        if(document.all)   return window.event;
        func=getEvent.caller;
        while(func!=null){
            var arg0=func.arguments[0];
            if(arg0)
            {
              if((arg0.constructor==Event || arg0.constructor ==MouseEvent) || (typeof(arg0)=="object" && arg0.preventDefault && arg0.stopPropagation))
              {
              return arg0;
              }
            }
            func=func.caller;
        }
        return null;
}
function MsgKeyDown()
{

}

function randStr(){
	return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
}
//初始化表情包和彩条
function sendCaitiao(tag){
	var ct=[];
	ct['dyg']='<img src="/style/room/face/colorbar/dyg.gif">';
	ct['zyg']='<img src="/style/room/face/colorbar/zyg.gif">';
	ct['gl']='<img src="/style/room/face/pic/s1.gif"><img src="/style/room/face/pic/s6.gif"><img src="/style/room/face/pic/geili_thumb.gif"><img src="/style/room/face/pic/s0.gif">';
	ct['zs']='<img src="/style/room/face/colorbar/zs.gif">';
	ct['xh']='<img src="/style/room/face/colorbar/xh.gif">';
	app_sendmsg(ct[tag]);
}
function showFacePanel(e,toinput){
	var offset = $(e).offset();

	var t = offset.top;
	var l = offset.left;
	$('#face').css( {"top" : t-$('#face').outerHeight(), "left":l});
	$('#face').show();
	$('#face').attr("toinput" , toinput);
}
function  initFaceColobar(){

	$.get("/style/room/face/pic/face.html",function(data){
		$('#face').html(data);
		$('#facenav li').on('click',function(){
			var rel = $(this).attr('rel');
			$('#face dl').hide();
			$('#f_'+rel).show();
			$(this).siblings().removeClass('f_cur');
			$(this).addClass('f_cur');

		});
	}).success(function(){
		$(document).bind('mouseup',function(e){
		if($(e.target).attr('isface')!='1' && $(e.target).attr('isface')!='2')
		{
			$('#face').hide();
			//$(document).unbind('mouseup');
		}
		else if($(e.target).attr('isface')=='1')
		{
			var toinput =$('#face').attr("toinput");
			$(toinput).append('<img src="'+$(e.target).attr('src')+'" onresizestart="return false" contenteditable="false">');
		}
	});


	});

	$.get("/style/room/face/colorbar/colorbar.html",function(data){
		$('#caitiao').html(data);
		//彩条
		$('#bt_caitiao').on('click',function(){
			var offset = $('#bt_caitiao').offset();
			var t = offset.top;
			var l = offset.left;
			$('#caitiao').css( {"top" : t-$('#caitiao').outerHeight(), "left":l});

			$('#caitiao').show();

		});
		$('#caitiaonav li').on('click',function(){

			var rel = $(this).attr('rel');
			$('#caitiao dl').hide();
			$('#c_'+rel).show();
			$(this).siblings().removeClass('f_cur');
			$(this).addClass('f_cur');
		});
		$(document).bind('mouseup',function(e){
				if($(e.target).attr('isnav')!='1')
				{
					$('#caitiao').hide();
				}
			});
	});



}