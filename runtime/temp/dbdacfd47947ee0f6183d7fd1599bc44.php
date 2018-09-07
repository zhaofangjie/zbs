<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"D:\xampp\htdocs\zbs\public/../application/index\view\index\indexzb.html";i:1536127947;}*/ ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>登录<?php echo $site['name']; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
<link rel="shortcut icon" type="image/x-icon" href="/style/images/favio.ico" />
<meta content="" name="keywords">
<meta content="" name="description">
<link href="/style/images/base.css" rel="stylesheet" type="text/css"  />
<link href="/style/images/login.css" rel="stylesheet" type="text/css"  />
<script src="/style/room/script/jquery.min.js"></script>
<script src="/style/room/script/layer.js"></script>
<script src="/style/room/script/device.min.js"></script>
</head>

<body>
<script>if (device.mobile()){window.location = './room/minilogin.php';}</script>
<div class="mainBg">
<div class="logoBar w1000 m0 cf">
		<div class="logo fl">
			<a href="javascript:;"><img src='/style/images/logo.png' border=0></a>
		</div>
		<p class="fr" style="height:50px;">

            <a href="javascript://"  class="regBtn trans03" style="margin-top:10px;background: #ee6229;color:#fff;" >客服中心</a>

		</p>
	</div>
    <div class="loginBox f14">
		<div class="loginMain cf">

    <div class="loginLeft fl h330">
        <div class="loginTitle">
            <p class="userLogin"></p>
        </div>
        <form action="?act=login" method="post" enctype="application/x-www-form-urlencoded"  name="loginform"  id="login_form" class="loginForm" >
        <div class="loginForm">
            <div class="oneLine cf">
                <span class="itemName">用户名</span>
                <span class="star">&nbsp;</span>
                <span>
                    <input name="username" type="text" value="<?php echo \think\Cookie::get('username'); ?>"></span>
                <span class="tishi" style="display: none"><i class="dui"></i></span>
            </div>
            <div class="oneLine cf">
                <span class="itemName">密码</span>
                <span class="star">&nbsp;</span>
                <span>
                    <input name="password" type="password" ></span>
                <span class="tishi" style="display: none"><i class="cuo">密码错误</i></span>
            </div>


            <div class="oneLine cf">
                <span class="itemName">&nbsp;</span>
                <span class="star">&nbsp;</span>
                <div class="ie7LoginWidth dib cf">
                    <p class="pr">
                        <button id="lnkLogin" class="loginBtn trans03" style="border:0px;" type="submit">登录</button>
                      <a class="tiyan f14" href="index/room">游客体验</a>
                    </p>
                    <p class="pt20 cf w">
                        <span class="fl"><a href="javascript:layer.msg('忘记密码？请联系客服！');" class="forgot">忘记密码？</a></span>
                        <span class="fr"></span>
                    </p>
                </div>
            </div>
        </div>
        </form>

    </div>
    <div class="loginRight fl">
        <div class="loginTitle">
            <p class="toReg"></p>
        </div>
        <a href="register.php" class="regBtn mt40 trans03">立即注册</a>

        <p class="c999 pt30 f12">使用社交账号登录</p>
        <p>
            <a  class="qq_login" href=""></a>
        </p>
        <p>

            <a  class="weibo_login" href=""></a>
        </p>

    </div>


		</div>
		<div class="loginBt"></div>
	</div>
    <div class="footer w" >
    <div class="fLinks cf">
        <div class="w1000 m0">
            <span class="fl">友情链接：</span>
            <ul class="fl">
                <li><a href="http://www.95599.cn/cn/" target="_blank">农业银行</a></li>
                <li><a href="http://www.icbc.com.cn/icbc/" target="_blank">工商银行</a></li>
                <li><a href="http://www.ccb.com/" target="_blank">建设银行</a></li>
                <li><a href="http://www.boc.cn" target="_blank">中国银行</a></li>
            </ul>
        </div>
        <div>


        </div>
    </div>
    <div class="copy">
        <div id="MainContent_footer_divFooterLog" class="w1000 m0 cf">

<div class="fl">
				<p class="cfff">投资有风险，入市须谨慎</p>
				<p><span >财运通达资本管理有限公司  版权所有</span>   </p>
				<p>
                    </p>
			</div>
            <p id="MainContent_footer_pLogo4RJ" class="fr pt10">&nbsp;</p>
        </div>
    </div>
</div>
</div>
</body>
</html>