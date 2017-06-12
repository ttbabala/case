<?php
    namespace app\manage\controller;
    vendor('predis.autoload');
    use think\Controller;
    use think\Request;
    use think\cache\driver\Redis;
    
    class Adduser extends Controller{
        public function index(){
            $pageTitle  = '添加用户';
            $this -> assign('title',$pageTitle);
            return $this -> fetch('index');
        }
        public function addUserHandle(){
            $Request = Request::instance();
            $username = null;
            $password = null;
            $username = $Request -> post('username');
            $password = $Request -> post('password');
            if( isset($username) && isset($password) ){
                $hashedPassword = bcryptHash($password);
               // return '用户名为：'.$username.'；密码为：'.$hashedPassword.';长度为：'.strlen($hashedPassword);
                $redis = new \Predis\Client();
                //获取一个用户自增ID
                $userID = $redis -> incr('users:count');
                //存储用户信息
                $setRes = $redis -> hmset("user:{$userID}",array(
                   'username' => $username,
                    'password' => $hashedPassword,
                ));
                //设置用户名与用户ID对应
                $Res = $redis -> hset("username.to.id",$username,$userID);
                if($setRes == 'OK' && $Res == 1){
                    return '用户添加成功！';
                }
            }
        }
   }