<?php

namespace app\controllers\admin;

use app\controllers\Controller;
use core\Csrf;
use validation\Rules;
use app\models\Post;
use parts\Session;
use database\DB;

class PostController extends Controller {

    public function index() {

        $post = new Post();
        $posts = DB::try()->all($post->t)->fetch();
        $data["posts"] = $posts;

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
                    
                    DB::try()->insert($post->t, [

                        $post->title => post('title'),
                        $post->body => post('body'),
                        $post->created_at => date("Y-m-d H:i:s"),
                        $post->updated_at => date("Y-m-d H:i:s")
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

                $id = $request['id'];
                $title = $request["title"];
                $body = $request["body"];

                DB::try()->update($post->t)->set([
                    $post->title => $title,
                    $post->body => $body,
                ])->where($post->id, '=', $id)->run();              

                Session::set('updated', 'User updated successfully!');
                redirect("/admin/posts/$id/edit");

            } else {
                Session::set('csrf', 'Cross site request forgery!');
                redirect("/admin/posts/$id");
            }
        }
    }

    public function read($request) {

        $posts = new Post();
        $post = DB::try()->select('*')->from($posts->t)->where($posts->id, '=', $request['id'])->first();
        $data['post'] = $post;

        return $this->view('admin/posts/read', $data);

    }


}