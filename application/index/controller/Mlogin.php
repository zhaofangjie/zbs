<?php
namespace app\index\controller;

use app\common\controller\Frontend;
use think\Config;
use think\Cookie;
use think\Hook;
use think\Session;
use think\Validate;

class Mlogin extends Frontend
{
    protected $layout='';
    protected $noNeedLogin=['index','login','register'];
    protected $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();
        $auth = $this->auth;

        if (!Config::get('fastadmin.usercenter')) {
            $this->error(__('User center already closed'));
        }

        $ucenter = get_addon_info('ucenter');
        if ($ucenter && $ucenter['state']) {
            include ADDON_PATH . 'ucenter' . DS . 'uc.php';
        }

        //监听注册于登陆事件
        Hook::add('user_login_successed', function ($user) use ($auth) {
            $expire = input('post.keeplogin') ? 30 * 86400 : 0;
            Cookie::set('uid', $user->id, $expire);
            Cookie::set('token', $auth->getToken(), $expire);
        });
        Hook::add('user_register_successed', function ($user) use ($auth) {
            Cookie::set('uid', $user->id);
            Cookie::set('token', $auth->getToken());
        });
        Hook::add('user_delete_successed', function ($user) use ($auth) {
            Cookie::delete('uid');
            Cookie::delete('token');
         });
       Hook::add('user_logout_successed', function ($user) use ($auth) {
           Cookie::delete('uid');
           Cookie::delete('token');
           Cookie::delete('guest');
       });
    }

    //ajax 登陆 注册 退出
    public function index(){
         return $this->fetch();
    }

    //登陆

    public function login(){
        if ($this->auth->id)
            $this->success(__('You\'ve logged in, do not login again'), $url);
            if ($this->request->isPost()) {
                $account = $this->request->post('username');
                $password = $this->request->post('password');
                //$keeplogin = (int)$this->request->post('keeplogin');
                $token = $this->request->post('__token1__');
                $rule = [
                    'account'   => 'require|length:3,50',
                    'password'  => 'require|length:6,30',
                    '__token1__' => 'token',
                ];

                $msg = [
                    'account.require'  => 'Account can not be empty',
                    'account.length'   => 'Account must be 3 to 50 characters',
                    'password.require' => 'Password can not be empty',
                    'password.length'  => 'Password must be 6 to 30 characters',
                ];
                $data = [
                    'account'   => $account,
                    'password'  => $password,
                    '__token__' => $token,
                ];
                $validate = new Validate($rule, $msg);
                $result = $validate->check($data);
                if (!$result) {
                    echo "<script>top.layer.msg('{$validate->getError()}',{shift: 6});layer.msg('{$validate->getError()}',{shift: 6});</script>";
                    return $this->fetch('index');
                }
                if ($this->auth->login($account, $password)) {
                    $synchtml = '';
                    ////////////////同步到Ucenter////////////////
                    if (defined('UC_STATUS') && UC_STATUS) {
                        $uc = new \addons\ucenter\library\client\Client();
                        $synchtml = $uc->uc_user_synlogin($this->auth->id);
                    }
                    exit("<script>top.location.reload(true);location.href='./';</script>");
                } else {
                    echo "<script>top.layer.msg('{$this->auth->getError()}',{shift: 6});layer.msg('{$this->auth->getError()}',{shift: 6});</script>";
                }
                return $this->fetch('index');
            }
    }


    /**
     * 注册会员
     */

    public function register()
    {
        if ($this->auth->id)
            exit("<script>top.location.reload(true);location.href='./';</script>");
            if ($this->request->isPost()) {
                $username = $this->request->post('username');
                $password = $this->request->post('password');
                $email = $this->request->post('email');
                $mobile = $this->request->post('phone');
                $captcha = $this->request->post('captcha');
                $token = $this->request->post('__token2__');
                $rule = [
                    'username'  => 'require|length:3,30',
                    'password'  => 'require|length:6,30',
                    'email'     => 'require|email',
                    'mobile'    => 'regex:/^1\d{10}$/',
                    'captcha'   => 'require|captcha',
                    '__token2__' => 'token',
                ];

                $msg = [
                    'username.require' => '用户名不能为空',
                    'username.length'  => '用户名必须同时满足大于3个且小于30个字符',
                    'password.require' => '密码不能为空',
                    'password.length'  => '密码最小6个字符最大30个字符',
                    'captcha.require'  => '验证码不能为空',
                    'captcha.captcha'  => '验证码不正确',
                    'email'            => '邮件格式不对',
                    'mobile'           => '电话格式不对',
                ];
                $data = [
                    'username'  => $username,
                    'password'  => $password,
                    'email'     => $email,
                    'mobile'    => $mobile,
                    'captcha'   => $captcha,
                    '__token2__' => $token,
                ];
                $validate = new Validate($rule, $msg);
                $result = $validate->check($data);
                if (!$result) {
                    $json['code'] = '0';
                    $json['msg'] = __($validate->getError());
                    return json($json);
                }
                if ($this->auth->register($username, $password, $email, $mobile)) {
                    $synchtml = '';
                    ////////////////同步到Ucenter////////////////
                    if (defined('UC_STATUS') && UC_STATUS) {
                        $uc = new \addons\ucenter\library\client\Client();
                        $synchtml = $uc->uc_user_synregister($this->auth->id, $password);
                    }
                    $json['code'] = '1';
                    $json['msg'] ='注册成功';
                    return json($json);
                } else {
                    $json['code'] = '0';
                    $json['msg'] = $this->auth->getError();
                    return json($json);
                }

            }
    }

    /**
     * 注销登录
     */
    function logout()
    {
        //注销本站
        $this->auth->logout();
        $synchtml = '';
        ////////////////同步到Ucenter////////////////
        if (defined('UC_STATUS') && UC_STATUS) {
            $uc = new \addons\ucenter\library\client\Client();
            $synchtml = $uc->uc_user_synlogout();
        }
        $this->success(__('退出成功，您现在是游客身份') . $synchtml, url('/index/room'));
    }
}