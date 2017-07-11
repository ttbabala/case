<?php
  namespace app\manage\controller;
  vendor('predis.autoload');
  use wx\Wxbizdatacrypt;
  use think\Request;
  use think\Cache;
  use think\Controller;
  use think\cache\driver\Redis;

    class Weixin extends Controller{
        public function index(){
            $str = '远端服务器连接成功！';
            $redis = new \Predis\Client();
            $g = 'hello';
            $str = '123456';
            $str1 ='789';
            $res = $redis -> set($g,$str.$str1);
            echo $redis -> get($g);
            //return $str;
        }
            
        public function codeGetSession3rd(){
            $Request = Request::instance();
            $code = $Request -> get('code');
            if(isset($code) && $code!= ''){
                $appid = 'wxe41c158180274a2c'; 
                $AppSecret = '54daf7bc72b36c7fedc1ee529988da36';
                $api = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$AppSecret&js_code=$code&grant_type=authorization_code";
                $jsonData = httpGet($api);
                $arrData=json_decode($jsonData,true);
                $session3rd = randomFromDev(16);
                $arrData['session3rd']= $session3rd;
                $redis = new \Predis\Client();
                $redis -> set($session3rd,$arrData['session_key'].$arrData['openid']);
                $redis -> expire($session3rd,3600);
                return $session3rd;   
            }else{
                return 'nothing!';
            }
        }
        
        public function getSe3(){
            $Request = Request::instance();
            $se3 = $Request  -> get('se3');
            if( isset($se3) && $se3 != '' ) {
                $redis = new \Predis\Client();
                $se3 = $redis -> get($se3);
                if( $se3 !== null ){
                    $openid = substr($se3,-28);
                    $session_key = substr($se3,0,strpos($se3,substr($se3,-28)));
                    return $session_key;
                }else{
                    return 0;
                }
            }
        }
        
        public function onLogin(){
            $appid = 'wxe41c158180274a2c'; 
            $Request = Request::instance();
            $rawData = $Request -> get('rawData');
            if ( isset($rawData) && $rawData != '' ){
                $sessionKey = $Request -> get('sessionKey');
                $signature = $Request -> get('signature');
                $encryptedData = $Request -> get('encryptedData');
                $iv = $Request -> get('iv');
                $signature2 = sha1($rawData.$sessionKey);
                if( $signature == $signature2 ){  
                   $pc = new Wxbizdatacrypt($appid,$sessionKey);
                   $errcode = $pc ->decryptData($encryptedData, $iv, $data);
                   if( $errcode == 0 ){
                       return json_decode($data);
                    }else{
                       return $errcode;
                    }
                }else{
                    return 'signature no same!';
                }
            }
        }        
    }
        
  