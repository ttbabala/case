<?php
    namespace app\manage\controller;
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
            $appid = 'wxe41c158180274a2c';
            $AppSecret = '54daf7bc72b36c7fedc1ee529988da36';
            $api = "https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$AppSecret&js_code=$code&grant_type=authorization_code";
            $jsonData = httpGet($api);
            $arrData=json_decode($jsonData,true);
            $session3rd = randomFromDev(16);
            $arrData['session3rd']= $session3rd;
            cache($session3rd,$arrData['openid'].$arrData['session_key']);
            return $session3rd;
        }
    }
 ?>