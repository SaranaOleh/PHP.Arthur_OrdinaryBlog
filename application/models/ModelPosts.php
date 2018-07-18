<?php

use Entity\Post;

class ModelPosts extends Model{
    private static $instance = null;

    protected function __construct(){
        parent::__construct();
    }

    public static function instance():self {
        return self::$instance ? self::$instance : self::$instance = new self();
    }

    public function getAll():array{
        return Post::fromAssocies($this->db->posts->getAll());
    }

    public function getAllByCategory(int $id):array{
        return Post::fromAssocies($this->db->posts->getAll("category_id=?",[$id]));
    }

    public function getAllByUserId(int $id):array{
        return Post::fromAssocies($this->db->posts->getAll("user_id=?",[$id]));
    }

    public function getById(int $id):Post{
        $img = new Post();
        $img->fromAssoc($this->db->posts->get($id));
        return $img;
    }

    public function getOneFromCategory(int $cat,int $id){
        $post = new Post();
        $post->fromAssoc($this->db->posts->where("category_id",$cat)->andWhere("id",$id)->first());
        return $post;
    }

    public function getLast(int $n){
        return Post::fromAssocies($this->db->posts->desc()->limit($n)->all());
    }

    public function add(Post $post):int{
        return $this->db->posts->insert([
            "name"=>$post->name,
            "text"=>$post->text,
            "time"=>$post->time,
            "user_id"=>(int)$post->user_id,
            "image_id"=>(int)$post->image_id,
            "category_id"=>(int)$post->category_id
        ]);
    }

//    public function view(int $id){
//       $this->db-posts->update($id,[]);
//    }
}