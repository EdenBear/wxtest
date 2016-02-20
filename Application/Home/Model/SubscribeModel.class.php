<?php
// +----------------------------------------------------------------------
// | Date:2016年2月19日
// +----------------------------------------------------------------------
// | Author: EK_熊<1439527494@qq.com>
// +----------------------------------------------------------------------
// | Description: 此文件作用于****
// +----------------------------------------------------------------------
namespace Home\Model;
use Think\Model;
class SubscribeModel extends Model{
    public  function addLog($uid, $nickname, $time, $type){
        $add = array(
            'uid' => $uid,
            'nickname'=>$nickname,
            'type' => $type,
            'create'=> $time,
            
        );
        
        $this->add($add);
    }
}