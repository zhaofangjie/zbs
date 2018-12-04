<?php

namespace app\index\model;

use think\Model;

class Hd extends Model
{
    // 表名
    protected $name = 'apps_hd';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;

    // 追加属性
    protected $append = [
        'ktime_text',
        'ptime_text',
        'ttime_text',
        'yld'
    ];





    //赢利点
    public function getYldAttr($value,$data)
    {
        if($data['pcj'] !=''){
            if(strpos($data['lx'], '买')){
                return round($data['pcj']-$data['kcj'],2);
            }else{
                return round($data['kcj'] -$data['pcj'],2);
            }
        }

    }

    public function getKtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['ktime']) ? $data['ktime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getPtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['ptime']) ? $data['ptime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getTtimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['ttime']) ? $data['ttime'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setKtimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setPtimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setTtimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


}
