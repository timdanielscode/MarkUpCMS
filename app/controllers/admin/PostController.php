<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use validation\Rules;
use app\models\Post;
use app\models\CategoryPage;
use app\models\Category;
use core\Session;
use database\DB;
use core\Request;
use extensions\Pagination;
use core\Response;

class PostController extends Controller {

    public function index() {

        $posts = Post::all();

        $count = count($posts);
        $search = get('search');

        if(!empty($search) ) {

            $post = new Post();
            $posts = $post->allPostsWithCategories($search);
            
            if(empty($posts) ) {

                $posts = array(["id" => "?","title" => "not found", "author" => "not found", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
            }
        }
        
        $posts = Pagination::get($posts, 10);
        $numberOfPages = Pagination::getPageNumbers();

        $data["posts"] = $posts;
        $data['numberOfPages'] = $numberOfPages;
        $data['count'] = $count;

        return $this->view('admin/posts/index', $data);
    }

    public function create() {
        
        $data['rules'] = [];
        return $this->view('admin/posts/create', $data);
    }

    public function store() {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {

            $rules = new Rules();
            $post = new Post();
    
            if($rules->create_post()->validated()) {
                        
                $slug = "/".post('title');
                $slug = str_replace(" ", "-", $slug);
    
                Post::insert([
    
                    'title' => post('title'),
                    'slug' => $slug,
                    'body' => post('body'),
                    'author' => Session::get('username'),
                    'date_created_at' => date("d/m/Y"),
                    'time_created_at' => date("H:i"),
                    'date_updated_at' => date("d/m/Y"),
                    'time_updated_at' => date("H:i")
                ]);
    
                Session::set('create', 'You have successfully created a new post!');            
                redirect('/admin/posts');
            } 
        }
    }

    public function edit($request) {

        $post = Post::get($request['id']);

        $postSlug = explode('/', $post['slug']);
        $postSlug = "/" . $postSlug[array_key_last($postSlug)];

        $data['data'] = $post;
        $data['postSlug'] = $postSlug;
        $data['rules'] = [];

        return $this->view('admin/posts/edit', $data);
    }

    public function update($request) {

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {
                
            $post = new Post();
            $rules = new Rules();

            //if($rules->update_post()->validated($request)) {
                
                $id = $request['id'];

                $slug = explode('/', $request['slug']);
                $slug[array_key_last($slug)] = substr($request['postSlug'], 1);
                $slug = implode('/', array_filter($slug));

                if(!empty($request['slug']) ) {

                    Post::update(['id' => $id], [

                        'title' => $request["title"],
                        'slug' => $slug,
                        'body' => $request["body"],
                        'date_updated_at' => date("d/m/Y"),
                        'time_updated_at' => date("H:i")
                    ]);

                    Session::set('updated', 'User updated successfully!');
                    redirect("/admin/posts/$id/edit");
                }
            //} 
        }
    }

    /*public function metaData($request) {
            
        $posts = new Post();
        $post = DB::try()->select('*')->from($posts->t)->where($posts->id, '=', $request['id'])->first();
        $data['post'] = $post;
        $data['rules'] = [];

        return $this->view('admin/posts/meta', $data);
    }*/

    /*public function metaDataUpdate($request) {

        if(submitted('meta')) {

            if(CSRF::validate(CSRF::token('get'), post('tokenMeta'))) {
                
                $post = new Post();
                $id = $request['id'];
                $metaTitle = $request["metaTitle"];
                $metaDescription = $request["metaDescription"];

                if(!empty($metaTitle) ) {
                    DB::try()->update($post->t)->set([
                        $post->metaTitle => $metaTitle
                    ])->where($post->id, '=', $id)->run(); 
                }
                if(!empty($metaDescription) ) {
                    DB::try()->update($post->t)->set([
                        $post->metaDescription => $metaDescription
                    ])->where($post->id, '=', $id)->run(); 
                }

                Session::set('updated', 'User updated successfully!');
                redirect("/admin/posts/$id/meta/edit");
            }
        }
    }*/

    public function read($request) {

        $post = Post::get($request['id']);
        $data['post'] = $post;

        return $this->view('/admin/posts/read', $data);
    }

    public function delete($request) {

        Post::delete("id", $request['id']);
        redirect("/admin/posts");
    }

}