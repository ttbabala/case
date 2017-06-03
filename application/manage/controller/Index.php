<?php
    namespace app\manage\controller;
    use think\Controller;
    use think\cache\driver\Redis;
    class Index extends Controller{
        public function index(){
            $this -> login();
        }
        
        public function login(){
            $redis = new Redis();
            try{
                 $this -> assign('full',$redis ->get('full'));
            } catch (Exception $ex) {
                 echo "Message:  {$ex -> getMessage()}";
            } 
            
            return $this -> fetch('login');
        }
    }
?>