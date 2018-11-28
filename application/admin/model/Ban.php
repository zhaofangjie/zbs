<?php

namespace app\admin\model;

use think\Model;

class Ban extends Model
{
    // 表名
    protected $name = 'ban';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'losttime_text'
    ];
    

    



    public function getLosttimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['losttime']) ? $data['losttime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setLosttimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


}
