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
    protected $noNeedLogin=['index','login'];
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

    public function login(){
        if ($this->auth->id)
            $this->success(__('You\'ve logged in, do not login again'), $url);
            if ($this->request->isPost()) {
                $account = $this->request->post('username');
                $password = $this->request->post('password');
                //$keeplogin = (int)$this->request->post('keeplogin');
                $token = $this->request->post('__token__');
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
            }
    }
}