<?php

namespace app\index\model;

use think\Model;

class Msg extends Model
{

    protected $autoWriteTimestamp = true;
    protected $createTime = 'mtime';

    //时间格式
    protected function getMtimeAttr($value){
        return date('Y-m-d H:i:s', $value);
    }

}
