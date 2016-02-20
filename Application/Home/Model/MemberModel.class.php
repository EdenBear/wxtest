<?php
// +----------------------------------------------------------------------
// | Date:2016年2月19日
// +----------------------------------------------------------------------
// | Author: EK_熊<1439527494@qq.com>
// +----------------------------------------------------------------------
// | Description: member表模型文件
// +----------------------------------------------------------------------

namespace Home\Model;
use Think\Model;
class MemberModel extends Model{
    
    const STU_SUBSC = 1;   //状态：订阅
    const STU_UNSUBSC = 2; //状态：取消订阅
    
    /**
     * 数据插入，添加新用户
     * 如果用户不存在，添加数据；
     * 如果用户已经存在，更新用户数据
     * @param unknown $userInfo
     * date:2016年2月19日
     * author: EK_熊
     */
    public function memberAdd($userInfo){
        $isExist = $this->isExist($userInfo['openid']);
        $userData = array(
            'openid'        => $userInfo['openid'],
            'nickname'      => $userInfo['nickname'],
            'sex'           => $userInfo['sex'],
            'city'          => $userInfo['city'],
            'province'      => $userInfo['province'],
            'country'       => $userInfo['country'],
            'headimgurl'    => $userInfo['headimgurl'],
            'subscribe_time'=> time(),
            'unionid'       => $userInfo['unionid'],
            'status'        => self::STU_SUBSC,
        );
        
        if ($isExist) {
            $uid = $isExist['uid'];
            $map['openid'] = $userInfo['openid'];
            $this->where($map)->save($userData);
        }else{
            
            $uid = $this->add($userData);
        }
        /*添加统计*/
        D('Subscribe')->addLog($uid,$userData['nickname'],$userData['subscribe_time'],self::STU_SUBSC);
    }
    /**
     * 取消订阅，更新用户状态，写入时间
     * @param unknown $openid
     * date:2016年2月19日
     * author: EK_熊
     */
    public function unSubscibeUpdate($openid){
        $openid = (string)$openid;
        $isExist = $this->isExist($openid);
        if ($isExist) {
            $save = array(
                'unsubscribe_time' => time(),
                'status'           => self::STU_UNSUBSC,
            );
            $map['openid'] = $openid; //取消订阅时，传进来的是对象类型，需要转换字符串
            $this->where($map)->save($save);
            /*添加统计*/
            D('Subscribe')->addLog($isExist['uid'],$isExist['nickname'],$save['unsubscribe_time'],self::STU_UNSUBSC);
        }else{
            exit();
        }

    }
    
    /**
     * 根据openid查找用户,如果找到，返回用户数据，数组格式
     * @param unknown $openid
     * date:2016年2月19日
     * author: EK_熊
     */
    public function isExist($openid){
        $map['openid']=$openid;
        return $this->where($map)->find();
        
    }
    
    
}