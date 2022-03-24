<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use validation\Rules;
use app\models\Post;
use parts\Session;
use database\DB;
use core\Request;
use parts\Pagination;
use core\Response;

class PostController extends Controller {

    public function index() {

        $post = new Post();
        $posts = DB::try()->all($post->t)->order('date_created_at')->fetch();

        $count = count($posts);
        $search = get('search');

        if(!empty($search) ) {
            $posts = DB::try()->all($post->t)->where($post->title, 'LIKE', '%'.$search.'%')->or($post->author, 'LIKE', '%'.$search.'%')->or($post->date_created_at, 'LIKE', '%'.$search.'%')->or($post->time_created_at, 'LIKE', '%'.$search.'%')->or($post->date_updated_at, 'LIKE', '%'.$search.'%')->or($post->time_updated_at, 'LIKE', '%'.$search.'%')->fetch();
            if(empty($posts) ) {
                $posts = array(["id" => "?","title" => "not found", "author" => "not found", "date_created_at" => "-", "time_created_at" => "", "date_updated_at" => "-", "time_updated_at" => ""]);
            }
        }
        
        $posts = Pagination::set($posts, 20);
        $numberOfPages = Pagination::getPages();

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

        if(submitted('submit')) {

            if(Csrf::validate(Csrf::token('get'), post('token') ) === true) {

                $rules = new Rules();
                $post = new Post();

                if($rules->create_post()->validated()) {
                    
                    $slug = "/".post('title');
                    $slug = str_replace(" ", "-", $slug);

                    DB::try()->insert($post->t, [

                        $post->title => post('title'),
                        $post->slug => $slug,
                        $post->body => post('body'),
                        $post->author => Session::get('username'),
                        $post->date_created_at => date("d/m/Y"),
                        $post->time_created_at => date("H:i"),
                        $post->date_updated_at => date("d/m/Y"),
                        $post->time_updated_at => date("H:i")
                    ]);

                    Session::set('create', 'You have successfully created a new post!');            
                    redirect('/admin/posts');

                } else {

                    $data['rules'] = $rules->errors;
                    return $this->view('admin/users/create', $data);
                }
            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect('/admin/users/create');
            }
        }
    }

    public function edit($request) {

        $posts = new Post();
        $post = DB::try()->select('*')->from($posts->t)->where($posts->id, '=', $request['id'])->first();
        $data['post'] = $post;
        $data['rules'] = [];

        return $this->view('admin/posts/edit', $data);
    }

    public function update($request) {

        if(submitted('submit')) {

            if(CSRF::validate(CSRF::token('get'), post('token'))) {
                
                $post = new Post();
                $rules = new Rules();
                $id = $request['id'];
                $title = $request["title"];
                $slug = $request["slug"];
                $body = $request["body"];

                if($rules->slug()->validated()) {

                    $slug = str_replace(" ", "-", $slug);

                    if(!empty($slug) ) {

                        DB::try()->update($post->t)->set([
                            $post->title => $title,
                            $post->slug => $slug,
                            $post->body => $body,
                            $post->date_updated_at => date("d/m/Y"),
                            $post->time_updated_at => date("H:i")
                        ])->where($post->id, '=', $id)->run();              

                        Session::set('updated', 'User updated successfully!');
                        redirect("/admin/posts/$id/edit");
                    }
                } else {
                    $data['rules'] = $rules->errors;
                    $data['post'] = DB::try()->select('*')->from($post->t)->where($post->id, '=', $id)->first();
                    return $this->view("/admin/posts/edit", $data);
                }

            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect("/admin/posts/$id");
            }
        }
    }

    public function metaData($request) {
            
        $posts = new Post();
        $post = DB::try()->select('*')->from($posts->t)->where($posts->id, '=', $request['id'])->first();
        $data['post'] = $post;
        $data['rules'] = [];

        return $this->view('admin/posts/meta', $data);
    }

    public function metaDataUpdate($request) {

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
    }

    public function read($request) {

        $posts = new Post();
        $post = DB::try()->select('*')->from($posts->t)->where($posts->id, '=', $request['id'])->first();
        $data['post'] = $post;

        return $this->view('/admin/posts/read', $data);

    }

    public function delete($request) {

        $id = $request['id'];

        $post = new Post();
        $post = DB::try()->delete($post->t)->where($post->id, "=", $id)->run();

        redirect("/admin/posts");
    }

}