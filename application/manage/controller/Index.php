<?php
    namespace app\manage\controller;
    vendor('predis.autoload');
    use think\Controller;
    use think\Request;
    use think\cache\driver\Redis;
    class Index extends Controller{
        public function index(){
            return $this -> login();
        }
        
        public function login(){
            $pageTitle = '用户登录';
            $this -> assign('title',$pageTitle);
            return $this -> fetch('login');
        }
        
        public function loginHandle(){
            $Request = Request::instance();
            $username = null;
            $password = null;
            $username = $Request -> post('username');
            $password = $Request -> post('password');
            if(isset($username) && isset($password)){
                $redis = new \Predis\Client();
                try{
                     $userID =  $redis -> hget('username.to.id',$username);
                     if(!$userID){
                         $ResInfo = '用户名不存在！'; 
                     }else{
                         $hashedPassword = $redis -> hget("user:{$userID}",'password');
                         if(!bcryptVerfy($password,$hashedPassword)){
                                $ResInfo = '用户名或密码错误！';
                            }else{
                                $ResInfo = '尊敬的用户'.$username.'，您好！欢迎您登陆本系统！';
                            } 
                     }   
                     $this -> assign('title','用户登陆结果反馈');
                     $this -> assign('ResInfo',$ResInfo);
                     return $this -> fetch('loginHandle');
                } catch (Exception $ex) {
                     echo "Message:  {$ex -> getMessage()}";
                } 
            }       
        }
    }
?>