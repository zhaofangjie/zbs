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
    protected $noNeedLogin=['index'];
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
        
        //¼àÌý×¢²áµÇÂ¼×¢ÏúµÄÊÂ¼þ
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
    
    public function index(){
        return $this->fetch();
    }
}