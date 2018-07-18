<?php

class ControllerAdmin extends Controller{
    public function action_index(){
        if(ModuleAuth::instance()->isAuth()){
            if(ModuleAuth::instance()->hasRole("admin")){
                $view = new View("admin/admin");
                $view->useTemplate("admin");
                $view->is_auth = ModuleAuth::instance()->isAuth();
                $this->response($view);
            }
        } else{
            $this->redirect404();
        }
    }
    public function action_test(){
        echo " this is test";
    }
}