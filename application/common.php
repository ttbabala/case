<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//密码加密函数
function bcryptHash($rowPassword,$round =8){ 
    if($round < 4 || $round > 31) $round = 8;
    $salt = '$2a$'.str_pad($round,2,'0',STR_PAD_LEFT).'$';
    $randomValue = openssl_random_pseudo_bytes(16);
    $salt.=substr(strtr(base64_encode($randomValue),'+','.'),0,22);
    return crypt($rowPassword,$salt);
}
//密码解密函数
function bcryptVerfy($rowPassword,$storedHash){
    return crypt($rowPassword,$storedHash) == $storedHash;
}


function httpGet($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT,500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}

function randomFromDev($len) {
    $fp = @fopen('/dev/urandom','rb');
    $result = '';
    if ($fp !== FALSE) {
        $result .= @fread($fp, $len);
        @fclose($fp);
    }
    else{
        trigger_error('Can not open /dev/urandom.');
    }
    // convert from binary to string
    $result = base64_encode($result);
    // remove none url chars
    $result = strtr($result, '+/', '-_');
    return substr($result, 0, $len);
}

