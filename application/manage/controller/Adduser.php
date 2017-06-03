<?php
    namespace app\manage\controller;
    use think\Controller;
    class AddUser extends Controller{
        public function index(){
            $pageTitle  = '添加用户';
            $this -> assign('title',$pageTitle);
            return $this -> fetch('index');
        }
    }
?>