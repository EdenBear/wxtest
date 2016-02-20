<?php
// +----------------------------------------------------------------------
// | Date:2016年2月18日
// +----------------------------------------------------------------------
// | Author: EK_熊<1439527494@qq.com>
// +----------------------------------------------------------------------
// | Description: 此文件作用于****
// +----------------------------------------------------------------------
/**
 * 发送curl请求
 * @param unknown $url
 * @return string|unknown
 * date:2016年2月19日
 * author: EK_熊
 */
function https_request($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
    curl_close($curl);
    return $data;
}

/**
 * 日志记录，文本末尾追加
 * @param unknown $label
 * @param string $str
 * @return boolean
 * date:2016年2月19日
 * author: EK_熊
 */
function write_log($label,$str=''){
    $mode='a';//追加方式写
    $file = "log.txt";
    $oldmask = @umask(0);
    $time = time();
    $fp = @fopen($file,$mode);
    @flock($fp, 3);
    if(!$fp)
    {
        Return false;
    }
    else
    {
        @fwrite($fp,"======$label====$time=======\r\n\r\n".$str);
        @fclose($fp);
        @umask($oldmask);
        Return true;
    }
}