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
                    '__token__' => 'token',
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
                    $this->error(__($validate->getError()), null, ['token' => $this->request->token()]);
                    return FALSE;
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
        $url = $this->request->request('url');
        if ($this->auth->id)
            $this->success(__('You\'ve logged in, do not login again'), $url);
            if ($this->request->isPost()) {
                $username = $this->request->post('username');
                $password = $this->request->post('password');
                $email = $this->request->post('email');
                $mobile = $this->request->post('mobile', '');
                $captcha = $this->request->post('captcha');
                $token = $this->request->post('__token2__');
                $rule = [
                    'username'  => 'require|length:3,30',
                    'password'  => 'require|length:6,30',
                    'email'     => 'require|email',
                    'mobile'    => 'regex:/^1\d{10}$/',
                    'captcha'   => 'require|captcha',
                    '__token__' => 'token',
                ];
                
                $msg = [
                    'username.require' => 'Username can not be empty',
                    'username.length'  => 'Username must be 3 to 30 characters',
                    'password.require' => 'Password can not be empty',
                    'password.length'  => 'Password must be 6 to 30 characters',
                    'captcha.require'  => 'Captcha can not be empty',
                    'captcha.captcha'  => 'Captcha is incorrect',
                    'email'            => 'Email is incorrect',
                    'mobile'           => 'Mobile is incorrect',
                ];
                $data = [
                    'username'  => $username,
                    'password'  => $password,
                    'email'     => $email,
                    'mobile'    => $mobile,
                    'captcha'   => $captcha,
                    '__token__' => $token,
                ];
                $validate = new Validate($rule, $msg);
                $result = $validate->check($data);
                if (!$result) {
                    $this->error(__($validate->getError()), null, ['token' => $this->request->token()]);
                }
                if ($this->auth->register($username, $password, $email, $mobile)) {
                    $synchtml = '';
                    ////////////////同步到Ucenter////////////////
                    if (defined('UC_STATUS') && UC_STATUS) {
                        $uc = new \addons\ucenter\library\client\Client();
                        $synchtml = $uc->uc_user_synregister($this->auth->id, $password);
                    }
                    $this->success(__('Sign up successful') . $synchtml, $url ? $url : url('user/index'));
                } else {
                    $this->error($this->auth->getError(), null, ['token' => $this->request->token()]);
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
        $this->success(__('Logout successful') . $synchtml, url('/index/room'));
    }
}