<?php
class ControllerMain extends Controller
{
    public function getDate($time){
        return date('d.m.Y h:i:s',$time);
    }
    public function action_index(){
        $view = new View("main");
        $view->useTemplate();
        $view->categories = ModelCategory::instance()->getAll();
        $view->posts = ModelPosts::instance()->getLast(9);
//        $test = ModelPosts::instance()->getLast(10);
//        ModuleDebug::var_dump($test[4]->getCategory()->name);
        $view->is_auth = ModuleAuth::instance()->isAuth();
        if($view->is_auth){
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
        }
        $this->response($view);
    }

    public function action_login(){
        $view = new View("login");
        if(!empty($_SESSION["validate_error"])){
            $view->error = $_SESSION["validate_error"];
            $view->old = $_SESSION["old"];
            unset($_SESSION["validate_error"]);
            unset($_SESSION["old"]);
        }
        $view->useTemplate();
        $view->categories = ModelCategory::instance()->getAll();
        $this->response($view);
    }

    public function action_register(){
        $view = new View("register");
        if(!empty($_SESSION["validate_error"])){
            $view->error = $_SESSION["validate_error"];
            $view->old = $_SESSION["old"];
            unset($_SESSION["validate_error"]);
            unset($_SESSION["old"]);
        }
        $view->useTemplate();
        $view->categories = ModelCategory::instance()->getAll();
        $view->is_auth = ModuleAuth::instance()->isAuth();
        if($view->is_auth){
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
        }
        $this->response($view);
    }
}