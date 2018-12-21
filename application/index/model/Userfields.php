<?php

namespace app\index\model;

use think\Model;

class Userfields extends Model
{
    // 表名
    protected $name = 'userfields';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [
        'onlinetime_text'
    ];

    public function getOnlinetimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['onlinetime']) ? $data['onlinetime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setOnlinetimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


}
