<?php
    namespace app\manage\controller;
    use think\Controller;
    
    class Weixin extends Controller{
        public function index(){
            $str = '服务器连接成功！';
            return $str;
        }
    }
 ?>