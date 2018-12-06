<?php

namespace app\admin\model\room;

use think\Model;

class Config extends Model
{
    // 表名
    protected $name = 'room_config';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [
        'state_text',
        'regaudit_text',
        'msgblock_text',
        'msgaudit_text',
        'msglog_text',
        'logintip_text',
        'loginguest_text',
        'livetype_text'
    ];



    public function getStateList()
    {
        return ['0' => __('State 0'),'1' => __('State 1'),'2' => __('State 2')];
    }

    public function getRegauditList()
    {
        return ['0' => __('Regaudit 0'),'1' => __('Regaudit 1')];
    }

    public function getMsgblockList()
    {
        return ['0' => __('Msgblock 0'),'1' => __('Msgblock 1')];
    }

    public function getMsgauditList()
    {
        return ['0' => __('Msgaudit 0'),'1' => __('Msgaudit 1')];
    }

    public function getMsglogList()
    {
        return ['0' => __('Msglog 0'),'1' => __('Msglog 1')];
    }

    public function getLogintipList()
    {
        return ['0' => __('Logintip 0'),'1' => __('Logintip 1')];
    }

    public function getLoginguestList()
    {
        return ['0' => __('Loginguest 0'),'1' => __('Loginguest 1')];
    }

    public function getLivetypeList()
    {
        return ['0' => __('Livetype 0'),'1' => __('Livetype 1')];
    }


    public function getStateTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['state']) ? $data['state'] : '');
        $list = $this->getStateList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getRegauditTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['regaudit']) ? $data['regaudit'] : '');
        $list = $this->getRegauditList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getMsgblockTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['msgblock']) ? $data['msgblock'] : '');
        $list = $this->getMsgblockList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getMsgauditTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['msgaudit']) ? $data['msgaudit'] : '');
        $list = $this->getMsgauditList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getMsglogTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['msglog']) ? $data['msglog'] : '');
        $list = $this->getMsglogList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getLogintipTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['logintip']) ? $data['logintip'] : '');
        $list = $this->getLogintipList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getLoginguestTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['loginguest']) ? $data['loginguest'] : '');
        $list = $this->getLoginguestList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getLivetypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['livetype']) ? $data['livetype'] : '');
        $list = $this->getLivetypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }
}
