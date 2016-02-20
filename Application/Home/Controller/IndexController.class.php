<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    
    public function index(){

        $this->responseMsg();  
        
    }
    
    
    /**
     * 接收微信事件信息
     * 
     * date:2016年2月18日
     * author: EK_熊
     */
    public function responseMsg(){
        
        //获取微信服务post过来的xml数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (APP_STATUS == 'db_local') {
            $postStr = "<xml><ToUserName><![CDATA[gh_3188c7eff438]]></ToUserName>
                        <FromUserName><![CDATA[oCmwKv_7UyoFc_Tv_h00kMKKNdF4]]></FromUserName>
                        <CreateTime>1455864889</CreateTime>
                        <MsgType><![CDATA[event]]></MsgType>
                        <Event><![CDATA[subscribe]]></Event>
                        <EventKey><![CDATA[]]></EventKey>
                        </xml>";
        }
        if (!empty($postStr)){
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $openid = $postObj->FromUserName;//user openid
            $developer = $postObj->ToUserName;
            $createTime = $postObj->CreateTime; 
            $event = trim($postObj->Event); // 消息类型；文本、菜单点击等
            $memberModel = D('Member');
            
            /*判断事件类型， 执行对应的事件动作*/
            if ($event == 'subscribe'){//订阅
                
                $userInfo = $this->getUserInfo($openid);//获取用户信息
                $memberModel->memberAdd($userInfo);     //添加数据插入，保存用户信息
                $this->sendNews($developer, $openid);   //发送图文消息

            }else if($event == 'unsubscribe'){//取消订阅
                $memberModel->unSubscibeUpdate($openid);
            }
        }else {
            echo "";
            exit;
        }
    }
    
    /**
     * 获取接口调用凭证，access_token
     * 
     * date:2016年2月18日
     * author: EK_熊
     */
    public function getAccessToken(){
        $actxt = file_get_contents('accesstoken.txt');
        $setInterval = 3000;//token保存时间，单位秒
        if ($actxt){
            $acAry = explode('#',$actxt);
            $create = intval($acAry[1]);
            $interval = time()-$create;
            if ($interval < $setInterval) return $acAry[0];
        }

        if (!$actxt || $interval >= $setInterval){
            $appid = APP_ID;
            $appsecret = APP_SECRET;
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
            $output = https_request($url);
            $jsonInfo = json_decode($output, true);
            $accessToken = $jsonInfo["access_token"];
            if ($accessToken){
                $saveAccessToken = $accessToken.'#'.time();
                file_put_contents('accesstoken.txt',$saveAccessToken);//写入txt，保存access_token,避免过多发送请求
            }
            return $accessToken;
        }
    }
    
    
    /**
     * 获取当个用户的基础信息,输出数组格式
     * @param unknown $openid
     * date:2016年2月19日
     * author: EK_熊
     */
    public function getUserInfo($openid){
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$openid&lang=zh_CN";
        $result = https_request($url);
        $jsonInfo = json_decode($result,ture);  // 默认false，为Object，若是True，为Array
//         dump($jsonInfo);
        return $jsonInfo;
    }
    
    /**
     * 发送图文消息,数量1
     * @param unknown $developer
     * @param unknown $openid
     * date:2016年2月19日
     * author: EK_熊
     */
    public function sendNews($developer,$openid){
        $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                            <item>
                                <Title><![CDATA[%s]]></Title> 
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                            </item>
                        </Articles>
                    </xml>";
        $msgType = "news";
        $title = '功能测试';
        $description = "Welcome to wechat world!";
        $picurl = "http://".$_SERVER['SERVER_NAME'].__ROOT__."/img/1.jpg";
        $time = time();
        $resultStr = sprintf($textTpl, $openid, $developer, $time, $msgType, $title, $description, $picurl,$url='');
        echo $resultStr;
    }
    
    
    /**
     * 发送文本消息
     * @param unknown $developer
     * @param unknown $openid
     * date:2016年2月19日
     * author: EK_熊
     */
    public function sendMsg($developer,$openid){
        $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
        $msgType = "text";
        $contentStr = "Welcome to wechat world!";
        $time = time();
        $resultStr = sprintf($textTpl, $openid, $developer, $time, $msgType, $contentStr);
        echo $resultStr;
        
    }
    

}