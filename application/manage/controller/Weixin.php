<?php
namespace app\manage\controller;
use wx\Wxbizdatacrypt;
use think\Request;
use think\Cache;
use think\Controller;
    
    class Weixin extends Controller{
        public function index(){
            $str = '远端服务器连接成功！';
            return $str;
        }
        
        public function onlogin(){
            $Request = Request::instance();
            $code = $Request -> get('code');
            $se3 = $Request -> get('se3');
            $rawData = $Request -> get('rawData');
            $appid = 'wxe41c158180274a2c';
            if ( isset($code) && $code!= '' ){
                $AppSecret = '54daf7bc72b36c7fedc1ee529988da36';
                $api = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$AppSecret&js_code=$code&grant_type=authorization_code";
                $jsonData = httpGet($api);
                $arrData=json_decode($jsonData,true);
                $session3rd = randomFromDev(16);
                $arrData['session3rd']= $session3rd;
                cache($session3rd,$arrData['openid'].$arrData['session_key']);
                return $session3rd; 
            }
            if( isset($se3) && $se3 != '' ) {
                if( cache($se3) !== null ){
                    $openid = substr(cache($se3),0,27);
                    $session_key = substr(cache($se3),28);
                    return $session_key;
                }else{
                    return 0;
                }
            }
            if( isset($rawData) && $rawData != '' ){
                $sessionKey = $Request -> get('sessionKey');
                $signature = $Request -> get('signature');
                $encryptedData = $Request -> get('encryptedData');
                $iv = $Request -> get('iv');
                $signature2 = sha1($rawData.$sessionKey);
                if($signature == $signature2){
                   $pc = new Wxbizdatacrypt($appid,$sessionKey);
                   $errcode = $pc ->decryptData($encryptedData, $iv, $data);
                   return $errcode;
                   if( $errcode == 0 ){
                       return $data;
                    }else{
                       return $errcode;
                    }
                }else{
                    return 'signature no same!';
                }    
            } 
            
        }
    }
