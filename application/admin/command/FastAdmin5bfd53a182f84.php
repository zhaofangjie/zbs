<?php

namespace app\admin\command;

use app\common\controller\Backend;

/**
 * 直播间配置
 *
 * @icon fa fa-circle-o
 */
class Config extends Backend
{
    
    /**
     * Config模型对象
     * @var \app\admin\model\room\Config
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\room\Config;
        $this->view->assign("stateList", $this->model->getStateList());
        $this->view->assign("regauditList", $this->model->getRegauditList());
        $this->view->assign("msgblockList", $this->model->getMsgblockList());
        $this->view->assign("msgauditList", $this->model->getMsgauditList());
        $this->view->assign("msglogList", $this->model->getMsglogList());
        $this->view->assign("logintipList", $this->model->getLogintipList());
        $this->view->assign("loginguestList", $this->model->getLoginguestList());
        $this->view->assign("livetypeList", $this->model->getLivetypeList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

}
