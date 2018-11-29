<?php

namespace app\admin\model;

use think\Model;

class Msg extends Model
{
    // 表名
    protected $name = 'msg';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'mtime_text',
        'type_text'
    ];
    

    
    public function getTypeList()
    {
        return ['0' => __('Type 0'),'1' => __('Type 1'),'2' => __('Type 2'),'3' => __('Type 3')];
    }     


    public function getMtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['mtime']) ? $data['mtime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : (isset($data['type']) ? $data['type'] : '');
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setMtimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    //状态    
    public function getStateAttr($value){
        $state=array('正常','待审','置顶公告','管理提示');
        return $state[$value];
    }
    
    //私聊 
    public function getPAttr($value){
        if($value==''){
            return '系统';
        }else{
            $p = array('true'=>'是','false'=>'否');
            return $p[$value];
        }
    }
    
    //获取组名 
    public function getUgidAttr($value){
        $group = new \app\admin\model\UserGroup();
        $info = $group->find($value);
        return $info->name;
    }
}
