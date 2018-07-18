<?php

class ControllerCategories extends Controller{
    public function action_index(){
        $view = new View("categories/categories");
        $view->useTemplate();
        $view->categories = ModelCategory::instance()->getAll();
        $view->is_auth = ModuleAuth::instance()->isAuth();
        if($view->is_auth){
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
        }
        $this->response($view);
    }

    public function action_category(){
        $name = $this->getUriParam("name");
        $view = new View("categories/category");
        $view->useTemplate();
        $view->categories = ModelCategory::instance()->getAll();
        $cat = ModelCategory::instance()->getByName($name);
        if($cat->id === null){
            $this->redirect404();
        }else{
            $view->name = ucfirst($name);
            $view->lovername = $name;
            $view->posts = ModelPosts::instance()->getAllByCategory($cat->id);
            $view->category = ModelCategory::instance()->getByName($name);
            $view->selector = (ModelImages::instance()->getById(
                (ModelCategory::instance()->getByName($name))->image_id)
            )->url;
        }
        $view->is_auth = ModuleAuth::instance()->isAuth();
        if($view->is_auth){
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
        }
        $this->response($view);
    }

    public function action_post(){
        $category = $this->getUriParam("name");
        $id = (int)$this->getUriParam("id");
        $view = new View("categories/details");
        $view->useTemplate();
        $view->categories = ModelCategory::instance()->getAll();
        $cat = ModelCategory::instance()->getByName($category);
        if($cat->id === null) $this->redirect404();
        $post = ModelPosts::instance()->getOneFromCategory($cat->id,$id);
        $allPosts = ModelPosts::instance()->getAllByCategory($cat->id);
        $prevId = function (array $allPosts,int $id){
          for($i=0;$i<count($allPosts);$i++){
              if((int)$allPosts[$i]->id === $id){
                  return $allPosts[$i - 1]->id;
              }
          }
        };
        $nextId = function (array $allPosts,int $id){
            for($i=0;$i<count($allPosts);$i++){
                if((int)$allPosts[$i]->id === $id){
                    return $allPosts[$i + 1]->id;
                }
            }
        };
        $getNextId = $nextId($allPosts,$id) ? $nextId($allPosts,$id) : $allPosts[0]->id;
        $getPrevId = $prevId($allPosts,$id) ? $prevId($allPosts,$id) : end($allPosts)->id;
        if($post->name === ""){
            $this->redirect404();
        }else{
            $countViews = ModuleDatabase::instance()->posts->getOne("id=?",[$id]);
            ModuleDatabase::instance()->posts->update($id,[
                "views"=> ($countViews["views"]) + 1
            ]);
            $view->post = $post;
            $view->category = $category;
            $view->next = $getNextId;
            $view->prev = $getPrevId;
        }
        $view->is_auth = ModuleAuth::instance()->isAuth();
        if($view->is_auth){
            $view->user = ModuleAuth::instance()->getUser();
            $view->admin = ModuleAuth::instance()->hasRole("admin");
        }
        $this->response($view);
    }

    public function action_like(){
        $id = (int)$this->getUriParam("id");
//        $post = ModelPosts::instance()->getById($id);
        $isAuth = ModuleAuth::instance()->isAuth();
        if($isAuth){
            try{
                ModuleDatabase::instance()->likes->insert([
                    "user_id"=>ModuleAuth::instance()->getUserId(),
                    "post_id"=>$id
                ]);
            } catch (Exception $e){
            }
        }
        $this->redirect($_SERVER["HTTP_REFERER"]);
    }
}