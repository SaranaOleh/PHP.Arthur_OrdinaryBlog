<?php

class Controller404 extends Controller{
    public function action_index(){
        header("HTTP/1.1 404 Not Found",true,404);
        $view = new View("404");
        $view->useTemplate();
        $view->categories = ModelCategory::instance()->getAll();
        $view->is_auth = ModuleAuth::instance()->isAuth();
        $this->response($view);
    }
}