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

        $post = new Post();
        $posts = $post->allPostsWithCategories();

        $count = count($posts);
        $search = get('search');

        if(!empty($search) ) {

            $posts = $post->allPostsWithCategories($search);
        }
        
        $posts = Pagination::get($posts, 11);
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

    public function read($request) {

        $post = Post::get($request['id']);

        $cssFiles = DB::try()->select('file_name', 'extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $request['id'])->fetch();
        $jsFiles = DB::try()->select('file_name', 'extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $request['id'])->fetch();

        $menusTop = DB::try()->all('menus')->where('position', '=', 'top')->order('ordering')->fetch();
        $menusBottom = DB::try()->all('menus')->where('position', '=', 'bottom')->order('ordering')->fetch();

        $data['menusTop'] = $menusTop;
        $data['menusBottom'] = $menusBottom;
        $data['post'] = $post;
        $data['cssFiles'] = $cssFiles;
        $data['jsFiles'] = $jsFiles;

        return $this->view('/admin/posts/read', $data);
    }

    public function edit($request) {

        $post = Post::get($request['id']);

        $postSlug = explode('/', $post['slug']);
        $postSlug = "/" . $postSlug[array_key_last($postSlug)];

        $ifAlreadyAssingedToCategory = DB::try()->select('page_id')->from('category_page')->where('page_id', '=', $request['id'])->fetch();

        if(empty($ifAlreadyAssingedToCategory)) {

            $categories = DB::try()->select('id, title')->from("categories")->fetch();
            $data['categories'] = $categories;
        } else {

            $category = DB::try()->select('categories.title, categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('category_page.page_id', '=', $request['id'])->first();
            $data['category'] = $category;
        }

        $linkedCssFiles = DB::try()->select('css.id, css.file_name, css.extension')->from('css')->join('css_page')->on('css_page.css_id', '=', 'css.id')->where('css_page.page_id', '=', $request['id'])->fetch();
        
        $linkedCssFileIds = [];

        if(!empty($linkedCssFiles) && $linkedCssFiles !== null) {

            foreach($linkedCssFiles as $linkedCssFile) {

                array_push($linkedCssFileIds, $linkedCssFile['id']);
            }

            $linkedCssFileIdStrings = implode(',', $linkedCssFileIds);

            $notLinkedCssFiles = DB::try()->select('id, file_name, extension')->from('css')->whereNotIn('id', $linkedCssFileIdStrings)->fetch();
        } else {

            $notLinkedCssFiles = DB::try()->select('id, file_name, extension')->from('css')->fetch();
        }

        $linkedJsFiles = DB::try()->select('js.id, js.file_name, js.extension')->from('js')->join('js_page')->on('js_page.js_id', '=', 'js.id')->where('js_page.page_id', '=', $request['id'])->fetch();
        
        $linkedJsFileIds = [];

        if(!empty($linkedJsFiles) && $linkedJsFiles !== null) {

            foreach($linkedJsFiles as $linkedJsFile) {

                array_push($linkedJsFileIds, $linkedJsFile['id']);
            }

            $linkedJsFileIdStrings = implode(',', $linkedJsFileIds);

            $notLinkedJsFiles = DB::try()->select('id, file_name, extension')->from('js')->whereNotIn('id', $linkedJsFileIdStrings)->fetch();
        } else {

            $notLinkedJsFiles = DB::try()->select('id, file_name, extension')->from('js')->fetch();
        }
    
        $data['data'] = $post;
        $data['postSlug'] = $postSlug;
        $data['linkedCssFiles'] = $linkedCssFiles;
        $data['notLinkedCssFiles'] = $notLinkedCssFiles;
        $data['linkedJsFiles'] = $linkedJsFiles;
        $data['notLinkedJsFiles'] = $notLinkedJsFiles;
        $data['rules'] = [];

        return $this->view('admin/posts/edit', $data);
    }

    public function update($request) {

        if(!empty($request['submitCategory']) && $request['submitCategory'] !== null) {

            $this->updateCategory($request);
            exit();
        } else if (!empty($request['removeCategory']) && $request['removeCategory'] !== null) {

            $this->removeCategory($request);
            exit();
        } else if(!empty($request['updateSlug']) && $request['updateSlug'] !== null) {

            $this->updateSlug($request);
            exit();
        } else if(!empty($request['updateMetaData']) && $request['updateMetaData'] !== null) {

            $this->updateMetaData($request);
            exit();
        } else if(!empty($request['removeCss']) && $request['removeCss'] !== null) {

            $this->removeCss($request);
            exit();
        } else if(!empty($request['linkCss']) && $request['linkCss'] !== null) {

            $this->linkCss($request);
            exit();
        } else if(!empty($request['includeJs']) && $request['includeJs'] !== null) {

            $this->includeJs($request);
            exit();
        } else if (!empty($request['removeJs']) && $request['removeJs'] !== null) {

            $this->removeJs($request);
            exit();
        }

        if(submitted("submit") === true && Csrf::validate(Csrf::token('get'), post('token')) === true ) {
                
            $post = new Post();
            $rules = new Rules();

            //if($rules->update_post()->validated($request)) {
                
                $id = $request['id'];

                /*$slug = explode('/', $request['slug']);
                $slug[array_key_last($slug)] = substr($request['postSlug'], 1);
                $slug = implode('/', array_filter($slug));*/

  
                    Post::update(['id' => $id], [

                        'title' => $request["title"],
                        'body' => $request["body"],
                        'date_updated_at' => date("d/m/Y"),
                        'time_updated_at' => date("H:i")
                    ]);

                    Session::set('updated', 'User updated successfully!');
                    redirect("/admin/posts/$id/edit");
                
            //} 
        }
    }

    public function removeJs($request) {

        $id = $request['id'];
        $linkedJsIds = $request['linkedJsFiles'];

        if(!empty($linkedJsIds) && $linkedJsIds !== null) {

            foreach($linkedJsIds as $linkedJsId) {

                DB::try()->delete('js_page')->where('page_id', '=', $id)->and('js_id', '=', $linkedJsId)->run();
            }
        }

        redirect("/admin/posts/$id/edit");
    }

    public function includeJs($request) {

        $id = $request['id'];
        $jsIds = $request['jsFiles'];

        if(!empty($jsIds) && $jsIds !== null) {

            foreach($jsIds as $jsId) {

                DB::try()->insert('js_page', [

                    'js_id' => $jsId,
                    'page_id' => $id

                ])->where('js_page', '=', $id)->and('js_id', '=', $jsId);
            }
        }
        
        redirect("/admin/posts/$id/edit");
    }

    public function linkCss($request) {

        $id = $request['id'];
        $cssIds = $request['cssFiles'];

        if(!empty($cssIds) && $cssIds !== null) {

            foreach($cssIds as $cssId) {

                DB::try()->insert('css_page', [

                    'css_id' => $cssId,
                    'page_id' => $id

                ])->where('css_page', '=', $id)->and('css_id', '=', $cssId);
            }
        }
        redirect("/admin/posts/$id/edit");
    }

    public function removeCss($request) {

        $id = $request['id'];
        $linkedCssIds = $request['linkedCssFiles'];

        if(!empty($linkedCssIds) && $linkedCssIds !== null) {

            foreach($linkedCssIds as $linkedCssId) {

                DB::try()->delete('css_page')->where('page_id', '=', $id)->and('css_id', '=', $linkedCssId)->run();
            }
        }

        redirect("/admin/posts/$id/edit");
    }

    public function updateSlug($request) {

        $id = $request['id'];

        $slug = explode('/', $request['slug']);
        $slug[array_key_last($slug)] = substr($request['postSlug'], 1);
        $slug = implode('/', array_filter($slug));
        
        Post::update(['id' => $id], [

            'slug' => "/" . $slug
        ]);

        redirect("/admin/posts/$id/edit");
    }

    public function updateMetaData($request) {

        $id = $request['id'];

        if(!empty($request['metaTitle']) && $request['metaTitle'] !== null) {

            Post::update(['id' => $id], [

                'metaTitle' => $request['metaTitle']
            ]);
        }
        if(!empty($request['metaDescription']) && $request['metaDescription'] !== null) {

            Post::update(['id' => $id], [

                'metaDescription' => $request['metaDescription']
            ]);
        }
        if(!empty($request['metaKeywords']) && $request['metaKeywords'] !== null) {

            Post::update(['id' => $id], [

                'metaKeywords' => $request['metaKeywords']
            ]);
        }
        redirect("/admin/posts/$id/edit");
    }

    public function updateCategory($request) {

        $categoryId = $request['categories'];
        $pageId = $request['id'];

        CategoryPage::insert([

            'category_id' => $categoryId,
            'page_id'    => $pageId
        ]);

        $currentCategorySlug = DB::try()->select('categories.slug')->from('categories')->join('category_page')->on('category_page.category_id', '=', 'categories.id')->where('categories.id', '=', $categoryId)->first();
        $currentSlugs = DB::try()->select('slug')->from('pages')->where('id', '=', $pageId)->fetch();

        foreach($currentSlugs as $currentSlug) {

            $subCategories = DB::try()->select('categories.slug')->from('categories')->join('category_sub')->on('categories.id', '=', 'category_sub.sub_id')->where('category_sub.category_id', '=', $categoryId)->fetch();

            if(!empty($subCategories) ) {
    
                $subCategoriesArray = [];
    
                foreach($subCategories as $subCategory) {
    
                    array_push($subCategoriesArray, $subCategory['slug']);
                }
    
                $subCategoriesSlug = implode('', $subCategoriesArray);
    
                Post::update(['id' => $pageId], [
    
                    'slug'  => $currentCategorySlug['slug'] . $subCategoriesSlug . $currentSlug['slug']
                ]);
    
            } else {
    
                Post::update(['id' => $pageId], [
    
                    'slug'  => $currentCategorySlug['slug'] . $currentSlug['slug']
                ]);
            }
        }

        redirect('/admin/posts/'. $pageId . '/edit');
    }

    public function removeCategory($request) {

        $pageId = $request['id'];

        $postSlug = DB::try()->select('slug')->from('pages')->where('id', '=', $pageId)->first();
        
        $slugParts = explode('/', $postSlug['slug']);
        $lastPageSlugKey = array_key_last($slugParts);
        $lastPageSlugValue = "/" . $slugParts[$lastPageSlugKey];

        Post::update(['id' => $pageId], [

            'slug'  => $lastPageSlugValue
        ]);

        CategoryPage::delete('page_id', $pageId);

        redirect('/admin/posts/'. $request['id'] . '/edit');
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

    public function delete($request) {

        Post::delete("id", $request['id']);
        redirect("/admin/posts");
    }

}