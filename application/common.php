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
function bcryptHash($rowPassword,$round =8){
    if($round < 4 || $round > 31) $round = 8;
    $salt = '$2a$'.str_pad($round,2,'0',STR_PAD_LEFT).'$';
    $randomValue = openssl_random_pseudo_bytes(16);
    $salt.=substr(strtr(base64_encode($randomValue),'+','.'),0,22);
    return crypt($rowPassword,$salt);
}