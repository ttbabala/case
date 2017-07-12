<?php
  namespace app\manage\controller;
  vendor('predis.autoload');
  vendor('qiniu.autoload');
  use wx\Wxbizdatacrypt;
  use think\Request;
  use think\Cache;
  use think\Controller;
  use think\cache\driver\Redis;
  use Qiniu\Auth;

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
            if(isset($code) && $code!== ''){
                $appid = 'wxe41c158180274a2c'; 
                $AppSecret = '54daf7bc72b36c7fedc1ee529988da36';
                $api = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$AppSecret&js_code=$code&grant_type=authorization_code";
                $jsonData = httpGet($api);
                $arrData=json_decode($jsonData,true);
                $session3rd = randomFromDev(16);
                $arrData['session3rd']= $session3rd;
                $redis = new \Predis\Client();
                $redis -> set($session3rd,$arrData['session_key'].$arrData['openid']);
                $redis -> expire($session3rd,30);
                return $session3rd;   
            }else{
                return 'nothing get code!';
            }
        }
        
        public function getSe3(){
            $Request = Request::instance();
            $se3 = $Request  -> get('se3');
            if( isset($se3) && $se3 !== '' ) {
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
            if ( isset($rawData) && $rawData !== '' ){
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
                    return 'false';
                }
            }
        } 
        
        public function uploadImg(){  //七牛云接入测试
            $bucket = 'wxuploadphoto';
            $accessKey = 'JGZz1FtUaPP7OX9O8mjnkc1Tu5YO3ofslYF2hM5J';
            $secretKey = 'cEsLVq3-81hpDI-fUVNsTIkG95cma0ajmhQKdh13';
            $auth = new Auth($accessKey, $secretKey);
            $upToken = $auth->uploadToken($bucket, null, 3600);
            $this -> assign('uploadtoken',$upToken);
            return $this -> fetch('uploadImg');
        }
        
    }
        
  